let currentMouseUpHandler = null;

// Each cell has a 3x3 "sub-grid" so multiple small props can share one tile.
// Legacy saves stored object_grid entries as plain code strings (no position);
// normalize those to {code, sx: 0, sy: 0} so old maps keep loading correctly.
const SUBGRID_SIZE = 3;

function getCellObjects(cell) {
    try {
        const raw = JSON.parse(cell.dataset.objects || '[]');
        return raw.map((entry) => (typeof entry === 'string' ? { code: entry, sx: 0, sy: 0 } : entry));
    } catch (error) {
        return [];
    }
}

function csrfToken() {
    return document.querySelector('meta[name="csrf-token"]').content;
}

const statusStyles = {
    published: 'bg-emerald-500/10 text-emerald-400 ring-emerald-500/30',
    valid: 'bg-sky-500/10 text-sky-400 ring-sky-500/30',
    invalid: 'bg-red-500/10 text-red-400 ring-red-500/30',
    draft: 'bg-slate-700/40 text-slate-300 ring-slate-500/30',
};

function updateStatusBadge(status) {
    const badge = document.getElementById('map-status-badge');

    if (!badge) {
        return;
    }

    badge.textContent = status;
    badge.className = `rounded-full px-2 py-1 text-xs font-medium ring-1 ring-inset ${statusStyles[status] ?? statusStyles.draft}`;
}

// Green toast for success, red for error — auto-dismisses after a few
// seconds but always has a close (x) button too.
function showToast(message, type = 'success') {
    const container = document.getElementById('toast-container');

    if (!container || !message) {
        return;
    }

    const isSuccess = type === 'success';
    const toast = document.createElement('div');
    toast.className = `pointer-events-auto flex items-start gap-2 rounded-md border px-3 py-2 text-xs shadow-lg shadow-black/40 backdrop-blur-sm ${
        isSuccess
            ? 'border-emerald-500/50 bg-emerald-950/90 text-emerald-200'
            : 'border-red-500/50 bg-red-950/90 text-red-200'
    }`;

    const text = document.createElement('span');
    text.className = 'flex-1';
    text.textContent = message;

    const closeBtn = document.createElement('button');
    closeBtn.type = 'button';
    closeBtn.className = 'shrink-0 text-current opacity-70 hover:opacity-100';
    closeBtn.textContent = '×';
    closeBtn.addEventListener('click', () => toast.remove());

    toast.appendChild(text);
    toast.appendChild(closeBtn);
    container.appendChild(toast);

    setTimeout(() => toast.remove(), 4000);
}

function showRouteErrors(errors) {
    const messages = (errors ?? []).filter(Boolean);

    if (messages.length) {
        showToast(messages.join(' '), 'error');
    }
}

function initMapGrid() {
    const grid = document.getElementById('map-grid');

    if (!grid || grid.dataset.initialized === 'true') {
        return;
    }

    grid.dataset.initialized = 'true';

    let activeCode = null;
    let activeCategory = null;
    let painting = false;
    let currentWaypoints = [];
    let deleteMode = false;
    let panMode = false;
    let routeDragging = false;

    let roadAssets = {};

    try {
        roadAssets = JSON.parse(grid.dataset.roadAssets || '{}');
    } catch (error) {
        roadAssets = {};
    }

    let fenceAssets = {};

    try {
        fenceAssets = JSON.parse(grid.dataset.fenceAssets || '{}');
    } catch (error) {
        fenceAssets = {};
    }

    let tileScales = {};

    try {
        tileScales = JSON.parse(grid.dataset.tileScales || '{}');
    } catch (error) {
        tileScales = {};
    }

    function scaleOf(code) {
        const value = parseFloat(tileScales[code]);
        return Number.isFinite(value) && value > 0 ? value : 1;
    }

    function footprintFor(code) {
        const scale = scaleOf(code);

        if (scale <= 1.5) return 1;
        if (scale <= 2.1) return 2;
        return SUBGRID_SIZE;
    }

    function slotFree(existing, sx, sy, footprint) {
        return existing.every((entry) => {
            const otherFootprint = footprintFor(entry.code);
            const overlapsX = sx < entry.sx + otherFootprint && sx + footprint > entry.sx;
            const overlapsY = sy < entry.sy + otherFootprint && sy + footprint > entry.sy;
            return !(overlapsX && overlapsY);
        });
    }

    function findOpenSlot(existing, footprint) {
        for (let sy = 0; sy <= SUBGRID_SIZE - footprint; sy++) {
            for (let sx = 0; sx <= SUBGRID_SIZE - footprint; sx++) {
                if (slotFree(existing, sx, sy, footprint)) {
                    return { sx, sy };
                }
            }
        }

        return null;
    }

    const paletteButtons = document.querySelectorAll('.tile-palette-btn');
    const paletteSprites = {};
    const paletteColors = {};
    const SELECTED_CLASSES = ['ring-4', 'ring-emerald-400', 'ring-offset-2', 'ring-offset-slate-900', 'z-10'];

    const deleteToolButton = document.getElementById('delete-tool-btn');
    const panToolButton = document.getElementById('pan-tool-btn');

    function clearToolSelection() {
        paletteButtons.forEach((b) => b.classList.remove(...SELECTED_CLASSES));
        deleteToolButton?.classList.remove(...SELECTED_CLASSES);
        panToolButton?.classList.remove(...SELECTED_CLASSES);
    }

    function setDeleteMode(on) {
        deleteMode = on;
        panMode = false;
        activeCode = null;
        activeCategory = null;
        clearToolSelection();
        panToolButton?.classList.remove('bg-sky-600');

        if (on) {
            deleteToolButton?.classList.add(...SELECTED_CLASSES);
            grid.style.cursor = 'crosshair';
        } else {
            grid.style.cursor = '';
        }
    }

    function setPanMode(on) {
        panMode = on;
        deleteMode = false;
        activeCode = null;
        activeCategory = null;
        clearToolSelection();
        deleteToolButton?.classList.remove('bg-red-600');

        const viewport = document.getElementById('map-viewport');

        if (on) {
            panToolButton?.classList.add(...SELECTED_CLASSES);
            grid.style.cursor = 'grab';
            if (viewport) viewport.style.cursor = 'grab';
        } else {
            grid.style.cursor = '';
            if (viewport) viewport.style.cursor = '';
        }
    }

    deleteToolButton?.addEventListener('click', () => setDeleteMode(!deleteMode));
    panToolButton?.addEventListener('click', () => setPanMode(!panMode));

    function deselectActiveTool() {
        setDeleteMode(false);
        setPanMode(false);
        hideGhost();
    }

    document.getElementById('cancel-tool-btn')?.addEventListener('click', deselectActiveTool);

    paletteButtons.forEach((button) => {
        if (button.dataset.tileSprite) {
            paletteSprites[button.dataset.tileCode] = button.dataset.tileSprite;
        }

        if (button.dataset.tileColor) {
            paletteColors[button.dataset.tileCode] = button.dataset.tileColor;
        }

        button.addEventListener('click', () => {
            deleteMode = false;
            panMode = false;
            grid.style.cursor = '';
            clearToolSelection();
            button.classList.add(...SELECTED_CLASSES);
            activeCode = button.dataset.tileCode;
            activeCategory = button.dataset.tileCategory;
        });
    });

    function renderCellObjects(cell) {
        const container = cell.querySelector('.cell-objects');

        if (!container) {
            return;
        }

        container.innerHTML = '';

        getCellObjects(cell).forEach(({ code, sx, sy }) => {
            const footprint = footprintFor(code);
            const subPercent = 100 / SUBGRID_SIZE;

            const icon = document.createElement('span');
            icon.className = 'absolute bg-contain bg-center bg-no-repeat';
            icon.style.left = `${sx * subPercent}%`;
            icon.style.top = `${sy * subPercent}%`;
            icon.style.width = `${footprint * subPercent}%`;
            icon.style.height = `${footprint * subPercent}%`;
            icon.style.backgroundImage = paletteSprites[code] ? `url('${paletteSprites[code]}')` : '';
            icon.title = code;
            container.appendChild(icon);
        });
    }

    function setCellObjects(cell, objects) {
        cell.dataset.objects = JSON.stringify(objects);
        renderCellObjects(cell);
    }

    function neighborsOf(cell) {
        const x = parseInt(cell.dataset.x, 10);
        const y = parseInt(cell.dataset.y, 10);

        return {
            up: grid.querySelector(`.map-cell[data-x="${x}"][data-y="${y - 1}"]`),
            down: grid.querySelector(`.map-cell[data-x="${x}"][data-y="${y + 1}"]`),
            left: grid.querySelector(`.map-cell[data-x="${x - 1}"][data-y="${y}"]`),
            right: grid.querySelector(`.map-cell[data-x="${x + 1}"][data-y="${y}"]`),
        };
    }

    // Mask = "u d l r" (1/0 per direction) — matches the 16 sprites RoadArt
    // pre-generates per skin code, so every straight/corner/T/cross/end/
    // isolated shape is a direct lookup, no runtime mirroring needed.
    function neighborMask(n) {
        return `${n.up ? '1' : '0'}${n.down ? '1' : '0'}${n.left ? '1' : '0'}${n.right ? '1' : '0'}`;
    }

    function renderRoadConnector(cell) {
        const overlay = cell.querySelector('.cell-road');

        if (!overlay) {
            return;
        }

        const code = cell.dataset.path;

        if (!code) {
            overlay.style.backgroundImage = 'none';
            overlay.style.transform = 'none';
            return;
        }

        const neighbors = neighborsOf(cell);
        const present = {
            up: !!(neighbors.up && neighbors.up.dataset.path === code),
            down: !!(neighbors.down && neighbors.down.dataset.path === code),
            left: !!(neighbors.left && neighbors.left.dataset.path === code),
            right: !!(neighbors.right && neighbors.right.dataset.path === code),
        };

        const url = roadAssets[code]?.[neighborMask(present)];

        overlay.style.backgroundImage = url ? `url('${url}')` : 'none';
        overlay.style.backgroundSize = '100% 100%';
        overlay.style.backgroundRepeat = 'no-repeat';
        overlay.style.transform = 'none';
    }

    function renderFenceConnectorFallback(cell, overlay, code) {
        const color = paletteColors[code] ?? '#94a3b8';
        const neighbors = neighborsOf(cell);

        const scale = scaleOf(code);
        const span = Math.min(92, 44 * scale);
        const inset = (100 - span) / 2;
        const topOverflow = Math.max(0, (scale - 1) * 20);
        const topInset = inset - topOverflow;

        overlay.style.overflow = 'visible';

        const hub = document.createElement('div');
        hub.className = 'absolute rounded-sm';
        hub.style.cssText = `left:${inset}%;right:${inset}%;top:${topInset}%;bottom:${inset}%;background-color:${color};z-index:1;`;
        overlay.appendChild(hub);

        const directions = ['up', 'down', 'left', 'right'];

        directions.forEach((direction) => {
            const neighborCell = neighbors[direction];

            if (!neighborCell || !neighborCell.dataset.fence) {
                return;
            }

            const stub = document.createElement('div');
            stub.className = 'absolute';

            const positions = {
                up: `left:${inset}%;right:${inset}%;top:${Math.min(0, topInset)}%;height:36%;`,
                down: `left:${inset}%;right:${inset}%;bottom:0;height:36%;`,
                left: `top:${inset}%;bottom:${inset}%;left:0;width:36%;`,
                right: `top:${inset}%;bottom:${inset}%;right:0;width:36%;`,
            };

            stub.style.cssText = `${positions[direction]}background-color:${color};z-index:1;`;
            overlay.appendChild(stub);
        });
    }

    function renderFenceConnector(cell) {
        const overlay = cell.querySelector('.cell-fence');

        if (!overlay) {
            return;
        }

        overlay.innerHTML = '';
        overlay.style.backgroundImage = 'none';
        overlay.style.transform = 'none';

        const code = cell.dataset.fence;

        if (!code) {
            return;
        }

        const neighbors = neighborsOf(cell);
        const present = {
            up: !!(neighbors.up && neighbors.up.dataset.fence === code),
            down: !!(neighbors.down && neighbors.down.dataset.fence === code),
            left: !!(neighbors.left && neighbors.left.dataset.fence === code),
            right: !!(neighbors.right && neighbors.right.dataset.fence === code),
        };

        const asset = fenceAssets[code]?.[neighborMask(present)];

        if (!asset) {
            renderFenceConnectorFallback(cell, overlay, code);
            return;
        }

        const scale = scaleOf(code);

        overlay.style.overflow = 'visible';
        overlay.style.backgroundImage = `url('${asset}')`;
        overlay.style.backgroundSize = '100% 100%';
        overlay.style.backgroundRepeat = 'no-repeat';
        overlay.style.transform = `scale(${scale})`;
    }

    function renderConnector(cell, kind) {
        if (kind === 'road') {
            renderRoadConnector(cell);
        } else {
            renderFenceConnector(cell);
        }
    }

    function refreshConnector(cell, kind) {
        renderConnector(cell, kind);

        Object.values(neighborsOf(cell)).forEach((neighborCell) => {
            if (neighborCell) {
                renderConnector(neighborCell, kind);
            }
        });
    }

    function setCellPath(cell, code) {
        cell.dataset.path = code ?? '';
        refreshConnector(cell, 'road');
    }

    function setCellFence(cell, code) {
        cell.dataset.fence = code ?? '';
        refreshConnector(cell, 'fence');
    }

    function renderWaypoints(waypoints) {
        currentWaypoints = waypoints;

        grid.querySelectorAll('.cell-waypoint').forEach((el) => {
            el.innerHTML = '';
        });

        const routeColors = { entrance: '#16a34a', path: '#2563eb', exit: '#7c3aed' };

        waypoints.forEach((waypoint, index) => {
            const cell = grid.querySelector(`.map-cell[data-x="${waypoint.x}"][data-y="${waypoint.y}"]`);
            const container = cell?.querySelector('.cell-waypoint');

            if (!container) {
                return;
            }

            const badge = document.createElement('span');
            badge.className = 'flex h-5 w-5 items-center justify-center rounded-full border-2 border-white text-[10px] font-bold text-white shadow';
            badge.style.backgroundColor = routeColors[waypoint.type] ?? '#2563eb';
            badge.textContent = String(index + 1);
            container.appendChild(badge);
        });
    }

    function renderBuildSpots(spots) {
        grid.querySelectorAll('.cell-buildspot').forEach((el) => {
            el.classList.add('hidden');
        });

        spots.forEach((spot) => {
            const cell = grid.querySelector(`.map-cell[data-x="${spot.x}"][data-y="${spot.y}"]`);
            cell?.querySelector('.cell-buildspot')?.classList.remove('hidden');
        });
    }

    function showBuildSpotError(message) {
        if (message) {
            showToast(message, 'error');
        }
    }

    async function handleBuildSpotClick(x, y) {
        try {
            const response = await fetch(`/admin/maps/${grid.dataset.mapId}/build-spots`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': csrfToken(),
                },
                body: JSON.stringify({ x, y }),
            });

            const json = await response.json();

            if (!response.ok) {
                showBuildSpotError(json.message ?? 'Actie mislukt.');
                return;
            }

            showBuildSpotError('');
            renderBuildSpots(json.build_spots);
        } catch (error) {
            showBuildSpotError('Actie mislukt.');
        }
    }

    function paint(cell) {
        if (!activeCode) {
            return;
        }

        if (activeCategory === 'ground') {
            cell.dataset.code = activeCode;
            cell.style.backgroundImage = paletteSprites[activeCode] ? `url('${paletteSprites[activeCode]}')` : '';
            return;
        }

        // Always additive/idempotent, like 'ground' above — a drag that
        // starts on an already-painted tile must not flip into an erase
        // gesture (that previously wiped out every same-code tile the drag
        // passed over, including ones further along the line). Use the
        // Delete tool to remove existing road/fence tiles.
        if (activeCategory === 'road') {
            if (cell.dataset.path !== activeCode) {
                setCellPath(cell, activeCode);
            }
            return;
        }

        if (activeCategory === 'fence') {
            if (cell.dataset.fence !== activeCode) {
                setCellFence(cell, activeCode);
            }
            return;
        }

        if (activeCategory === 'decoration') {
            addDecoration(cell, activeCode);
        }
    }

    // Left click/drag always adds another instance of the selected prop (up to
    // however many fit in the cell's 3x3 sub-grid) — multiple of the *same*
    // small prop can share one tile, e.g. several campfires. Right click
    // removes the most-recently-placed instance of whatever prop is active.
    function addDecoration(cell, code) {
        const objects = getCellObjects(cell);
        const footprint = footprintFor(code);
        const slot = findOpenSlot(objects, footprint);

        if (!slot) {
            showLargeObjectError('Geen ruimte meer in dit vakje voor dit object.');
            return;
        }

        objects.push({ code, sx: slot.sx, sy: slot.sy });
        setCellObjects(cell, objects);
    }

    function removeDecoration(cell, code) {
        const objects = getCellObjects(cell);
        const index = objects.map((o) => o.code).lastIndexOf(code);

        if (index === -1) {
            return;
        }

        objects.splice(index, 1);
        setCellObjects(cell, objects);
    }

    function renderLargeObjects(objects) {
        const container = document.getElementById('map-large-objects');
        const tileSize = parseInt(grid.dataset.tileSize, 10);
        const types = JSON.parse(grid.dataset.largeObjectTypes || '{}');

        if (!container) {
            return;
        }

        container.innerHTML = '';

        objects.forEach((object) => {
            const type = types[object.tile_code];

            if (!type) {
                return;
            }

            const scale = type.scale && type.scale > 0 ? type.scale : 1;
            const baseWidth = type.width * tileSize;
            const baseHeight = type.height * tileSize;
            const scaledWidth = baseWidth * scale;
            const scaledHeight = baseHeight * scale;

            const div = document.createElement('div');
            div.className = 'map-large-object absolute bg-contain bg-center bg-no-repeat';
            div.dataset.tileCode = object.tile_code;
            div.dataset.originX = object.x;
            div.dataset.originY = object.y;
            div.style.left = `${object.x * tileSize - (scaledWidth - baseWidth) / 2}px`;
            div.style.top = `${object.y * tileSize - (scaledHeight - baseHeight) / 2}px`;
            div.style.width = `${scaledWidth}px`;
            div.style.height = `${scaledHeight}px`;
            div.style.backgroundImage = `url('${type.sprite}')`;
            container.appendChild(div);
        });
    }

    function showLargeObjectError(message) {
        if (message) {
            showToast(message, 'error');
        }
    }

    async function handleLargeObjectClick(x, y, tileCodeOverride) {
        const tileCode = tileCodeOverride ?? activeCode;

        if (!tileCode) {
            return;
        }

        try {
            const response = await fetch(`/admin/maps/${grid.dataset.mapId}/objects`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': csrfToken(),
                },
                body: JSON.stringify({ tile_code: tileCode, x, y }),
            });

            const json = await response.json();

            if (!response.ok) {
                showLargeObjectError(json.message ?? 'Actie mislukt.');
                return;
            }

            showLargeObjectError('');
            renderLargeObjects(json.objects);
        } catch (error) {
            showLargeObjectError('Actie mislukt.');
        }
    }

    async function handleRouteClick(cell) {
        if (!activeCode) {
            return;
        }

        try {
            const response = await fetch(`/admin/maps/${grid.dataset.mapId}/route`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': csrfToken(),
                },
                body: JSON.stringify({
                    type: activeCode,
                    x: parseInt(cell.dataset.x, 10),
                    y: parseInt(cell.dataset.y, 10),
                }),
            });

            const json = await response.json();

            if (!response.ok) {
                showRouteErrors([json.message ?? 'Actie mislukt.']);
                return;
            }

            renderWaypoints(json.waypoints);
            updateStatusBadge(json.status);
            showRouteErrors(json.errors);
        } catch (error) {
            showRouteErrors(['Actie mislukt.']);
        }
    }

    let undoStack = [];
    let redoStack = [];
    const MAX_HISTORY = 50;

    function snapshotExtras() {
        const largeObjects = [...grid.querySelectorAll('.map-large-object')].map((el) => ({
            tile_code: el.dataset.tileCode,
            x: parseInt(el.dataset.originX, 10),
            y: parseInt(el.dataset.originY, 10),
        }));

        const buildSpots = [...grid.querySelectorAll('.map-cell')]
            .filter((cell) => {
                const el = cell.querySelector('.cell-buildspot');
                return el && !el.classList.contains('hidden');
            })
            .map((cell) => ({
                x: parseInt(cell.dataset.x, 10),
                y: parseInt(cell.dataset.y, 10),
            }));

        return { largeObjects, buildSpots };
    }

    function snapshotGrid() {
        return { ...collectGridData(), ...snapshotExtras() };
    }

    async function reconcileExtras(target) {
        const same = (a, b) => a.x === b.x && a.y === b.y;
        const sameObject = (a, b) => same(a, b) && a.tile_code === b.tile_code;

        let current = snapshotExtras();

        const spotsToRemove = current.buildSpots.filter((cur) => !target.buildSpots.some((t) => same(t, cur)));
        const spotsToAdd = target.buildSpots.filter((t) => !current.buildSpots.some((cur) => same(cur, t)));

        for (const spot of spotsToRemove) {
            await handleBuildSpotClick(spot.x, spot.y);
        }

        for (const spot of spotsToAdd) {
            await handleBuildSpotClick(spot.x, spot.y);
        }

        current = snapshotExtras();

        const objectsToRemove = current.largeObjects.filter((cur) => !target.largeObjects.some((t) => sameObject(t, cur)));
        const objectsToAdd = target.largeObjects.filter((t) => !current.largeObjects.some((cur) => sameObject(cur, t)));

        for (const object of objectsToRemove) {
            await handleLargeObjectClick(object.x, object.y, object.tile_code);
        }

        for (const object of objectsToAdd) {
            await handleLargeObjectClick(object.x, object.y, object.tile_code);
        }
    }

    async function applyGridSnapshot(snapshot) {
        const { groundGrid, pathGrid, fenceGrid, objectGrid } = snapshot;

        grid.querySelectorAll('.map-cell').forEach((cell) => {
            const x = parseInt(cell.dataset.x, 10);
            const y = parseInt(cell.dataset.y, 10);

            const groundCode = groundGrid[y]?.[x];

            if (groundCode) {
                cell.dataset.code = groundCode;
                cell.style.backgroundImage = paletteSprites[groundCode] ? `url('${paletteSprites[groundCode]}')` : '';
            }

            cell.dataset.path = pathGrid[y]?.[x] || '';
            cell.dataset.fence = fenceGrid[y]?.[x] || '';
            setCellObjects(cell, objectGrid[y]?.[x] || []);
        });

        grid.querySelectorAll('.map-cell').forEach((cell) => {
            renderConnector(cell, 'road');
            renderConnector(cell, 'fence');
        });

        await reconcileExtras(snapshot);
    }

    function updateUndoRedoButtons() {
        const undoButton = document.getElementById('undo-btn');
        const redoButton = document.getElementById('redo-btn');

        if (undoButton) undoButton.disabled = undoStack.length === 0;
        if (redoButton) redoButton.disabled = redoStack.length === 0;
    }

    function pushUndoSnapshot() {
        undoStack.push(snapshotGrid());

        if (undoStack.length > MAX_HISTORY) {
            undoStack.shift();
        }

        redoStack = [];
        updateUndoRedoButtons();
    }

    async function undo() {
        if (!undoStack.length) {
            return;
        }

        redoStack.push(snapshotGrid());
        await applyGridSnapshot(undoStack.pop());
        updateUndoRedoButtons();
    }

    async function redo() {
        if (!redoStack.length) {
            return;
        }

        undoStack.push(snapshotGrid());
        await applyGridSnapshot(redoStack.pop());
        updateUndoRedoButtons();
    }

    document.getElementById('undo-btn')?.addEventListener('click', undo);
    document.getElementById('redo-btn')?.addEventListener('click', redo);
    updateUndoRedoButtons();

    window.addEventListener('keydown', (event) => {
        const tag = document.activeElement?.tagName;

        if (tag === 'INPUT' || tag === 'TEXTAREA') {
            return;
        }

        if (event.key === 'Escape') {
            event.preventDefault();
            deselectActiveTool();
            return;
        }

        if (!(event.ctrlKey || event.metaKey)) {
            return;
        }

        if (event.key === 'z' || event.key === 'Z') {
            event.preventDefault();
            undo();
        } else if (event.key === 'y' || event.key === 'Y') {
            event.preventDefault();
            redo();
        }
    });

    let largeObjectTypesCache = {};

    try {
        largeObjectTypesCache = JSON.parse(grid.dataset.largeObjectTypes || '{}');
    } catch (error) {
        largeObjectTypesCache = {};
    }

    async function handleDeleteClick(cell) {
        if (getCellObjects(cell).length) {
            setCellObjects(cell, []);
        }

        if (cell.dataset.fence) {
            setCellFence(cell, '');
        }

        if (cell.dataset.path) {
            setCellPath(cell, '');
        }

        const x = parseInt(cell.dataset.x, 10);
        const y = parseInt(cell.dataset.y, 10);

        const buildSpotEl = cell.querySelector('.cell-buildspot');

        if (buildSpotEl && !buildSpotEl.classList.contains('hidden')) {
            await handleBuildSpotClick(x, y);
        }

        const hitObject = [...grid.querySelectorAll('.map-large-object')].find((el) => {
            const ox = parseInt(el.dataset.originX, 10);
            const oy = parseInt(el.dataset.originY, 10);
            const type = largeObjectTypesCache[el.dataset.tileCode];
            const w = type?.width ?? 1;
            const h = type?.height ?? 1;

            return x >= ox && x < ox + w && y >= oy && y < oy + h;
        });

        if (hitObject) {
            await handleLargeObjectClick(x, y, hitObject.dataset.tileCode);
        }
    }

    const ghost = document.createElement('div');
    ghost.id = 'map-ghost';
    ghost.className = 'pointer-events-none absolute opacity-60';
    ghost.style.display = 'none';
    ghost.style.zIndex = '20';
    grid.appendChild(ghost);

    function hideGhost() {
        ghost.style.display = 'none';
    }

    function updateGhost(cell) {
        if (panMode || deleteMode || !activeCode || !activeCategory) {
            hideGhost();
            return;
        }

        const tileSize = parseInt(grid.dataset.tileSize, 10);
        const x = parseInt(cell.dataset.x, 10);
        const y = parseInt(cell.dataset.y, 10);

        ghost.style.border = 'none';
        ghost.style.borderRadius = '0';
        ghost.style.backgroundColor = '';
        ghost.style.backgroundImage = 'none';
        ghost.style.transform = 'none';

        if (activeCategory === 'ground' || activeCategory === 'road' || activeCategory === 'fence') {
            ghost.style.left = `${x * tileSize}px`;
            ghost.style.top = `${y * tileSize}px`;
            ghost.style.width = `${tileSize}px`;
            ghost.style.height = `${tileSize}px`;
            ghost.style.backgroundImage = paletteSprites[activeCode] ? `url('${paletteSprites[activeCode]}')` : 'none';
            ghost.style.backgroundSize = 'cover';
            ghost.style.display = 'block';
            return;
        }

        if (activeCategory === 'decoration') {
            const footprint = footprintFor(activeCode);
            const existing = getCellObjects(cell);
            const slot = findOpenSlot(existing, footprint) ?? { sx: 0, sy: 0 };
            const subPercent = tileSize / SUBGRID_SIZE;

            ghost.style.left = `${x * tileSize + slot.sx * subPercent}px`;
            ghost.style.top = `${y * tileSize + slot.sy * subPercent}px`;
            ghost.style.width = `${footprint * subPercent}px`;
            ghost.style.height = `${footprint * subPercent}px`;
            ghost.style.backgroundImage = paletteSprites[activeCode] ? `url('${paletteSprites[activeCode]}')` : 'none';
            ghost.style.backgroundSize = 'contain';
            ghost.style.display = 'block';
            return;
        }

        if (activeCategory === 'largeobject') {
            const type = largeObjectTypesCache[activeCode];

            if (!type) {
                hideGhost();
                return;
            }

            const scale = type.scale && type.scale > 0 ? type.scale : 1;
            const baseWidth = type.width * tileSize;
            const baseHeight = type.height * tileSize;
            const scaledWidth = baseWidth * scale;
            const scaledHeight = baseHeight * scale;

            ghost.style.left = `${x * tileSize - (scaledWidth - baseWidth) / 2}px`;
            ghost.style.top = `${y * tileSize - (scaledHeight - baseHeight) / 2}px`;
            ghost.style.width = `${scaledWidth}px`;
            ghost.style.height = `${scaledHeight}px`;
            ghost.style.backgroundImage = `url('${type.sprite}')`;
            ghost.style.backgroundSize = 'contain';
            ghost.style.display = 'block';
            return;
        }

        if (activeCategory === 'buildspot') {
            ghost.style.left = `${x * tileSize + tileSize * 0.1}px`;
            ghost.style.top = `${y * tileSize + tileSize * 0.1}px`;
            ghost.style.width = `${tileSize * 0.8}px`;
            ghost.style.height = `${tileSize * 0.8}px`;
            ghost.style.border = '2px dashed #38bdf8';
            ghost.style.borderRadius = '6px';
            ghost.style.display = 'block';
            return;
        }

        if (activeCategory === 'route') {
            const color = { entrance: '#16a34a', path: '#2563eb', exit: '#7c3aed' }[activeCode] ?? '#2563eb';
            const size = tileSize * 0.6;
            ghost.style.left = `${x * tileSize + (tileSize - size) / 2}px`;
            ghost.style.top = `${y * tileSize + (tileSize - size) / 2}px`;
            ghost.style.width = `${size}px`;
            ghost.style.height = `${size}px`;
            ghost.style.borderRadius = '9999px';
            ghost.style.backgroundColor = color;
            ghost.style.display = 'block';
            return;
        }

        hideGhost();
    }

    grid.addEventListener('mouseleave', hideGhost);
    grid.addEventListener('mouseleave', hideCoordLabel);

    grid.querySelectorAll('.map-cell').forEach((cell) => {
        cell.addEventListener('mousedown', (event) => {
            if (panMode) {
                return;
            }

            event.preventDefault();

            if (deleteMode) {
                pushUndoSnapshot();
                painting = true;
                handleDeleteClick(cell);
                return;
            }

            if (activeCategory === 'route') {
                routeDragging = true;
                handleRouteClick(cell);
                return;
            }

            if (activeCategory === 'buildspot') {
                pushUndoSnapshot();
                handleBuildSpotClick(parseInt(cell.dataset.x, 10), parseInt(cell.dataset.y, 10));
                return;
            }

            if (activeCategory === 'largeobject') {
                pushUndoSnapshot();
                handleLargeObjectClick(parseInt(cell.dataset.x, 10), parseInt(cell.dataset.y, 10));
                return;
            }

            if (activeCategory) {
                pushUndoSnapshot();
            }

            painting = true;

            paint(cell);
        });

        cell.addEventListener('mouseenter', () => {
            updateGhost(cell);
            updateCoordLabel(cell);

            if (panMode) {
                return;
            }

            if (deleteMode && painting) {
                handleDeleteClick(cell);
                return;
            }

            if (routeDragging && activeCategory === 'route') {
                handleRouteClick(cell);
                return;
            }

            if (painting) {
                paint(cell);
            }
        });

        cell.addEventListener('contextmenu', (event) => {
            if (panMode || deleteMode || activeCategory !== 'decoration' || !activeCode) {
                return;
            }

            event.preventDefault();
            pushUndoSnapshot();
            removeDecoration(cell, activeCode);
        });
    });

    grid.querySelectorAll('.map-cell').forEach((cell) => {
        if (cell.dataset.path) {
            renderConnector(cell, 'road');
        }

        if (cell.dataset.fence) {
            renderConnector(cell, 'fence');
        }
    });

    if (currentMouseUpHandler) {
        window.removeEventListener('mouseup', currentMouseUpHandler);
    }

    currentMouseUpHandler = () => {
        painting = false;
        routeDragging = false;
    };

    window.addEventListener('mouseup', currentMouseUpHandler);

    const clearRouteButton = document.getElementById('route-clear-btn');

    clearRouteButton?.addEventListener('click', async () => {
        try {
            const response = await fetch(`/admin/maps/${grid.dataset.mapId}/route`, {
                method: 'DELETE',
                headers: {
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': csrfToken(),
                },
            });

            const json = await response.json();
            renderWaypoints(json.waypoints);
            updateStatusBadge(json.status);
            showRouteErrors(json.errors);
        } catch (error) {
            showRouteErrors(['Route wissen mislukt.']);
        }
    });

    try {
        renderWaypoints(JSON.parse(grid.dataset.waypoints || '[]'));
    } catch (error) {
        // no initial waypoints to render
    }

    try {
        renderBuildSpots(JSON.parse(grid.dataset.buildSpots || '[]'));
    } catch (error) {
        // no initial build spots to render
    }

    try {
        renderLargeObjects(JSON.parse(grid.dataset.mapObjects || '[]'));
    } catch (error) {
        // no initial large objects to render
    }

    function collectGridData() {
        const width = parseInt(grid.dataset.width, 10);
        const height = parseInt(grid.dataset.height, 10);
        const groundGrid = [];
        const pathGrid = [];
        const fenceGrid = [];
        const objectGrid = [];

        for (let y = 0; y < height; y++) {
            const groundRow = [];
            const pathRow = [];
            const fenceRow = [];
            const objectRow = [];

            for (let x = 0; x < width; x++) {
                const cell = grid.querySelector(`.map-cell[data-x="${x}"][data-y="${y}"]`);
                groundRow.push(cell.dataset.code);
                pathRow.push(cell.dataset.path ? cell.dataset.path : null);
                fenceRow.push(cell.dataset.fence ? cell.dataset.fence : null);
                objectRow.push(getCellObjects(cell));
            }

            groundGrid.push(groundRow);
            pathGrid.push(pathRow);
            fenceGrid.push(fenceRow);
            objectGrid.push(objectRow);
        }

        return { groundGrid, pathGrid, fenceGrid, objectGrid };
    }

    const saveButton = document.getElementById('map-save-btn');

    saveButton.addEventListener('click', async () => {
        const { groundGrid, pathGrid, fenceGrid, objectGrid } = collectGridData();

        try {
            const response = await fetch(`/admin/maps/${grid.dataset.mapId}/tiles`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': csrfToken(),
                },
                body: JSON.stringify({
                    ground_grid: groundGrid,
                    path_grid: pathGrid,
                    fence_grid: fenceGrid,
                    object_grid: objectGrid,
                    tilt_angle: TILT_DEGREES,
                }),
            });

            showToast(response.ok ? 'Opgeslagen.' : 'Opslaan mislukt.', response.ok ? 'success' : 'error');
        } catch (error) {
            showToast('Opslaan mislukt.', 'error');
        }
    });

    const gridToggleButton = document.getElementById('grid-toggle-btn');
    let gridLinesVisible = true;

    gridToggleButton?.addEventListener('click', () => {
        gridLinesVisible = !gridLinesVisible;

        grid.querySelectorAll('.map-cell').forEach((cell) => {
            cell.style.borderColor = gridLinesVisible ? '' : 'transparent';
        });

        gridToggleButton.classList.toggle('bg-gray-100', !gridLinesVisible);
    });

    const darkBgImage = 'radial-gradient(circle at 1px 1px, rgba(148,163,184,0.08) 1px, transparent 0)';
    const bgModes = {
        dark: { color: '', image: darkBgImage },
        darker: { color: '#0b1220', image: 'none' },
        light: { color: '#e2e8f0', image: 'none' },
    };

    document.querySelectorAll('.map-bg-option').forEach((button) => {
        button.addEventListener('click', () => {
            const mode = bgModes[button.dataset.bgMode] ?? bgModes.dark;
            const viewport = document.getElementById('map-viewport');

            if (!viewport) {
                return;
            }

            viewport.style.backgroundColor = mode.color;
            viewport.style.backgroundImage = mode.image;

            document.querySelectorAll('.map-bg-option').forEach((b) => b.classList.remove('border-emerald-400', 'text-white'));
            button.classList.add('border-emerald-400', 'text-white');
        });
    });

    const showCoordsCheckbox = document.getElementById('show-coords-checkbox');
    let showCoordinates = false;

    showCoordsCheckbox?.addEventListener('change', (event) => {
        showCoordinates = event.target.checked;

        if (!showCoordinates) {
            hideCoordLabel();
        }
    });

    const coordLabel = document.createElement('div');
    coordLabel.id = 'map-coord-label';
    coordLabel.className = 'pointer-events-none absolute rounded bg-black/80 px-1.5 py-0.5 text-[10px] font-medium text-emerald-300';
    coordLabel.style.display = 'none';
    coordLabel.style.zIndex = '25';
    grid.appendChild(coordLabel);

    function hideCoordLabel() {
        coordLabel.style.display = 'none';
    }

    function updateCoordLabel(cell) {
        if (!showCoordinates) {
            return;
        }

        const tileSize = parseInt(grid.dataset.tileSize, 10);
        const x = parseInt(cell.dataset.x, 10);
        const y = parseInt(cell.dataset.y, 10);

        coordLabel.textContent = `${x}, ${y}`;
        coordLabel.style.left = `${x * tileSize}px`;
        coordLabel.style.top = `${y * tileSize - 16}px`;
        coordLabel.style.display = 'block';
    }

    const mapViewport = document.getElementById('map-viewport');
    let TILT_DEGREES = 22;

    const tiltRange = document.getElementById('tilt-angle-range');
    const tiltValueLabel = document.getElementById('tilt-angle-value');

    tiltRange?.addEventListener('input', (event) => {
        TILT_DEGREES = parseInt(event.target.value, 10);

        if (tiltValueLabel) {
            tiltValueLabel.textContent = `${TILT_DEGREES}°`;
        }

        updateGridTransform();
    });
    let zoomLevel = 1;
    let panX = 0;
    let panY = 0;
    let panning = false;
    let panStartX = 0;
    let panStartY = 0;
    let panOriginX = 0;
    let panOriginY = 0;

    function updateGridTransform() {
        grid.style.transform = `translate(${panX}px, ${panY}px) scale(${zoomLevel}) rotateX(${TILT_DEGREES}deg)`;
    }

    updateGridTransform();

    mapViewport?.addEventListener('mousedown', (event) => {
        // Middle-click, or Ctrl+left-click (trackpad/no-middle-button
        // friendly), always pans regardless of the active tool. Using the
        // capture phase + stopPropagation here means this runs before (and
        // suppresses) the per-cell mousedown handler, so it never also
        // paints/deletes/etc. Plain left-click only pans while panMode is on.
        const isCtrlClick = event.button === 0 && event.ctrlKey;

        if (event.button !== 1 && !panMode && !isCtrlClick) {
            return;
        }

        event.preventDefault();
        event.stopPropagation();

        panning = true;
        panStartX = event.clientX;
        panStartY = event.clientY;
        panOriginX = panX;
        panOriginY = panY;
        mapViewport.style.cursor = 'grabbing';
        grid.style.cursor = 'grabbing';
    }, true);

    mapViewport?.addEventListener('auxclick', (event) => {
        if (event.button === 1) {
            event.preventDefault();
        }
    });

    window.addEventListener('mousemove', (event) => {
        if (!panning) {
            return;
        }

        panX = panOriginX + (event.clientX - panStartX);
        panY = panOriginY + (event.clientY - panStartY);
        updateGridTransform();
    });

    window.addEventListener('mouseup', () => {
        if (panning) {
            panning = false;

            if (mapViewport) {
                mapViewport.style.cursor = panMode ? 'grab' : '';
            }

            grid.style.cursor = panMode ? 'grab' : '';
        }
    });

    mapViewport?.addEventListener('wheel', (event) => {
        event.preventDefault();

        const delta = event.deltaY > 0 ? -0.1 : 0.1;
        zoomLevel = Math.min(2.5, Math.max(0.4, zoomLevel + delta));
        updateGridTransform();
    }, { passive: false });

    const testWaveButton = document.getElementById('test-wave-btn');

    async function runTestWave() {
        if (!currentWaypoints || currentWaypoints.length < 2) {
            showRouteErrors(['Stel eerst een volledige route in om een testwave te draaien.']);
            return;
        }

        if (testWaveButton.dataset.running === 'true') {
            return;
        }

        testWaveButton.dataset.running = 'true';
        testWaveButton.disabled = true;
        testWaveButton.classList.add('opacity-50');

        const tileSize = parseInt(grid.dataset.tileSize, 10);
        const points = currentWaypoints.map((waypoint) => ({
            x: waypoint.x * tileSize + tileSize / 2,
            y: waypoint.y * tileSize + tileSize / 2,
        }));

        const enemySprite = grid.dataset.enemySprite;
        const dot = document.createElement('div');
        dot.className = 'pointer-events-none absolute z-10 -translate-x-1/2 -translate-y-1/2 rounded-full border-2 border-white shadow';
        dot.style.width = `${tileSize * 0.7}px`;
        dot.style.height = `${tileSize * 0.7}px`;

        if (enemySprite) {
            dot.style.backgroundImage = `url('${enemySprite}')`;
            dot.style.backgroundSize = 'contain';
            dot.style.backgroundRepeat = 'no-repeat';
            dot.style.backgroundPosition = 'center';
        } else {
            dot.style.backgroundColor = '#dc2626';
        }

        dot.style.left = `${points[0].x}px`;
        dot.style.top = `${points[0].y}px`;
        grid.appendChild(dot);

        const pxPerMs = tileSize / 220;

        await new Promise((resolve) => {
            let segmentIndex = 0;
            let segmentProgress = 0;
            let lastTime = null;

            function step(time) {
                if (lastTime === null) {
                    lastTime = time;
                }

                const dt = time - lastTime;
                lastTime = time;

                if (segmentIndex >= points.length - 1) {
                    resolve();
                    return;
                }

                const from = points[segmentIndex];
                const to = points[segmentIndex + 1];
                const dx = to.x - from.x;
                const dy = to.y - from.y;
                const segmentLength = Math.hypot(dx, dy) || 1;

                segmentProgress += pxPerMs * dt;
                const t = Math.min(1, segmentProgress / segmentLength);

                dot.style.left = `${from.x + dx * t}px`;
                dot.style.top = `${from.y + dy * t}px`;

                if (t >= 1) {
                    segmentIndex += 1;
                    segmentProgress = 0;
                }

                requestAnimationFrame(step);
            }

            requestAnimationFrame(step);
        });

        dot.remove();
        testWaveButton.disabled = false;
        testWaveButton.classList.remove('opacity-50');
        testWaveButton.dataset.running = 'false';
    }

    testWaveButton?.addEventListener('click', runTestWave);
}

document.addEventListener('livewire:navigated', initMapGrid);
initMapGrid();
