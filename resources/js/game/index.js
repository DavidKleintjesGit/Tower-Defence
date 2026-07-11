function initGame() {
    const canvas = document.getElementById('game-canvas');
    const viewport = document.getElementById('game-viewport');

    if (!canvas || !viewport || canvas.dataset.initialized === 'true') {
        return;
    }

    canvas.dataset.initialized = 'true';

    const dataScript = document.getElementById('map-data');
    const map = JSON.parse(dataScript.textContent);
    const SANDBOX = Boolean(map.sandbox);
    const tileColors = map.tile_colors ?? {};
    const tileSprites = map.tile_sprites ?? {};
    const tileScales = map.tile_scales ?? {};

    function scaleOf(code) {
        const value = parseFloat(tileScales[code]);
        return Number.isFinite(value) && value > 0 ? value : 1;
    }

    const spriteImages = {};

    Object.keys(tileSprites).forEach((code) => {
        const img = new Image();
        img.src = tileSprites[code];
        spriteImages[code] = img;
    });

    const enemyTypes = {};
    const ANIM_FRAME_DURATION = 0.12; // seconds per walk-cycle frame
    const TOWER_HEAD_SQUASH = 0.55; // vertical squash applied after rotate, fakes an elevated 3D camera on a flat sprite
    const TOWER_PIVOT_FRACTION = { x: 16 / 32, y: 20 / 32 }; // where base/head art hinges, matches the seeded SVG's dome center

    // Each tower type carries its own render_scale (e.g. the raketwerper is
    // set much bigger than the machine gun) instead of one fixed size for
    // every tower.
    function towerRenderSize(type) {
        const scale = Number(type?.render_scale);
        return map.tile_size * (Number.isFinite(scale) && scale > 0 ? scale : 1.05);
    }

    (map.enemy_types ?? []).forEach((type) => {
        const img = new Image();
        img.src = type.sprite;

        const frames = (type.walk_frames ?? []).map((src) => {
            const frameImg = new Image();
            frameImg.src = src;
            return frameImg;
        });

        enemyTypes[type.code] = { ...type, image: img, frames };
    });

    const towerTypes = {};

    (map.tower_types ?? []).forEach((type) => {
        const img = new Image();
        img.src = type.sprite;

        const baseImage = new Image();
        if (type.base_sprite) {
            baseImage.src = type.base_sprite;
        }

        const headImage = new Image();
        if (type.head_sprite) {
            headImage.src = type.head_sprite;
        }

        const muzzleFlashImage = new Image();
        if (type.muzzle_flash_sprite) {
            muzzleFlashImage.src = type.muzzle_flash_sprite;
        }

        const projectileImage = new Image();
        if (type.projectile_sprite) {
            projectileImage.src = type.projectile_sprite;
        }

        towerTypes[type.code] = { ...type, image: img, baseImage, headImage, muzzleFlashImage, projectileImage };
    });

    // Nested per-code asset maps: { [tileCode]: { [udlrMask]: dataUri } }.
    function loadAssetImages(nestedUrls) {
        const images = {};

        Object.keys(nestedUrls ?? {}).forEach((code) => {
            images[code] = {};

            Object.keys(nestedUrls[code] ?? {}).forEach((mask) => {
                const img = new Image();
                img.src = nestedUrls[code][mask];
                images[code][mask] = img;
            });
        });

        return images;
    }

    const roadAssetImages = loadAssetImages(map.road_assets);
    const fenceAssetImages = loadAssetImages(map.fence_assets);

    const enemyTypeCodes = Object.keys(enemyTypes);

    const BORDER = Math.max(map.tile_size * 0.5, 28);

    const ctx = canvas.getContext('2d');
    canvas.width = map.width * map.tile_size + BORDER * 2;
    canvas.height = map.height * map.tile_size + BORDER * 2;

    // Viewport fit/zoom/pan: zooming out is capped at the scale where the
    // whole map fits the viewport ("fitScale"); zooming in goes above it.
    // Panning is damped (lerped toward a target each frame) instead of
    // following the cursor 1:1, per the requested "eased" game-screen feel.
    const TILT_DEGREES = Number.isFinite(Number(map.tilt_angle)) ? Number(map.tilt_angle) : 18;
    const MAX_ZOOM_MULTIPLIER = 3.2;
    let fitScale = 1;
    let zoomLevel = 1;
    let panX = 0;
    let panY = 0;
    let panTargetX = 0;
    let panTargetY = 0;

    function computeFitScale() {
        const vw = viewport.clientWidth;
        const vh = viewport.clientHeight;
        const tiltShrink = Math.cos((TILT_DEGREES * Math.PI) / 180);
        const effectiveHeight = canvas.height * tiltShrink;

        fitScale = Math.min(vw / canvas.width, vh / effectiveHeight) * 0.94;

        if (!Number.isFinite(fitScale) || fitScale <= 0) {
            fitScale = 1;
        }

        zoomLevel = Math.min(Math.max(zoomLevel, fitScale), fitScale * MAX_ZOOM_MULTIPLIER);
        clampPanTarget();
    }

    function clampPanTarget() {
        const maxPanX = Math.max(0, (canvas.width * zoomLevel - viewport.clientWidth) / 2);
        const maxPanY = Math.max(0, (canvas.height * zoomLevel - viewport.clientHeight) / 2);

        panTargetX = Math.min(maxPanX, Math.max(-maxPanX, panTargetX));
        panTargetY = Math.min(maxPanY, Math.max(-maxPanY, panTargetY));
    }

    function applyViewTransform() {
        canvas.style.transform = `translate(${panX}px, ${panY}px) scale(${zoomLevel}) rotateX(${TILT_DEGREES}deg)`;
    }

    function updatePan(deltaSeconds) {
        const lerpFactor = Math.min(1, deltaSeconds * 6);
        panX += (panTargetX - panX) * lerpFactor;
        panY += (panTargetY - panY) * lerpFactor;
    }

    let isPanning = false;
    let panMoved = false;
    let activePointerId = null;
    let dragStartClientX = 0;
    let dragStartClientY = 0;
    let dragStartPanX = 0;
    let dragStartPanY = 0;

    viewport.addEventListener('pointerdown', (event) => {
        if (event.button !== 0) {
            return;
        }

        isPanning = true;
        panMoved = false;
        activePointerId = event.pointerId;
        dragStartClientX = event.clientX;
        dragStartClientY = event.clientY;
        dragStartPanX = panTargetX;
        dragStartPanY = panTargetY;

        // Pointer capture is deferred until real drag movement is detected
        // (see pointermove below). Capturing immediately on every mousedown
        // retargets the resulting 'click' event (and its offsetX/offsetY) to
        // the capturing element per the Pointer Events spec — since the
        // click handler lives on the canvas, that silently broke every
        // plain click (tower/enemy selection, click-to-place) even though
        // no actual dragging ever happened.
    });

    viewport.addEventListener('pointermove', (event) => {
        if (!isPanning) {
            return;
        }

        const dx = event.clientX - dragStartClientX;
        const dy = event.clientY - dragStartClientY;

        if (!panMoved && Math.hypot(dx, dy) > 10) {
            panMoved = true;
            viewport.setPointerCapture(activePointerId);
        }

        if (!panMoved) {
            return;
        }

        panTargetX = dragStartPanX + dx;
        panTargetY = dragStartPanY + dy;
        clampPanTarget();
    });

    function endPan(event) {
        if (!isPanning) {
            return;
        }

        isPanning = false;

        if (event?.pointerId !== undefined && viewport.hasPointerCapture?.(event.pointerId)) {
            viewport.releasePointerCapture(event.pointerId);
        }
    }

    viewport.addEventListener('pointerup', endPan);
    viewport.addEventListener('pointercancel', endPan);

    viewport.addEventListener('wheel', (event) => {
        event.preventDefault();
        const factor = event.deltaY < 0 ? 1.12 : 0.89;
        zoomLevel = Math.min(fitScale * MAX_ZOOM_MULTIPLIER, Math.max(fitScale, zoomLevel * factor));
        clampPanTarget();
    }, { passive: false });

    window.addEventListener('resize', computeFitScale);
    computeFitScale();
    zoomLevel = fitScale;
    applyViewTransform();

    function drawTile(code, x, y, width, height = width) {
        const img = spriteImages[code];

        if (img && img.complete && img.naturalWidth > 0) {
            ctx.drawImage(img, x, y, width, height);
            return;
        }

        ctx.fillStyle = tileColors[code] ?? '#cccccc';
        ctx.fillRect(x, y, width, height);
    }

    function hasRoad(x, y) {
        if (x < 0 || y < 0 || x >= map.width || y >= map.height) {
            return false;
        }

        return Boolean(map.path_grid?.[y]?.[x]);
    }

    function hasFence(x, y) {
        if (x < 0 || y < 0 || x >= map.width || y >= map.height) {
            return false;
        }

        return Boolean(map.fence_grid?.[y]?.[x]);
    }

    function drawFenceConnectorFallback(x, y, code) {
        const size = map.tile_size;
        const scale = scaleOf(code);
        const span = Math.min(0.92, 0.44 * scale);
        const inset = (1 - span) / 2;
        const topOverflow = Math.max(0, (scale - 1) * 0.2);
        const topInset = inset - topOverflow;

        const px = x * size;
        const py = y * size;

        ctx.fillStyle = tileColors[code] ?? '#94a3b8';
        ctx.fillRect(px + inset * size, py + topInset * size, span * size, size * (1 - topInset - inset));

        if (hasFence(x, y - 1)) {
            ctx.fillRect(px + inset * size, py + Math.min(0, topInset) * size, span * size, 0.36 * size);
        }

        if (hasFence(x, y + 1)) {
            ctx.fillRect(px + inset * size, py + (1 - 0.36) * size, span * size, 0.36 * size);
        }

        if (hasFence(x - 1, y)) {
            ctx.fillRect(px, py + inset * size, 0.36 * size, span * size);
        }

        if (hasFence(x + 1, y)) {
            ctx.fillRect(px + (1 - 0.36) * size, py + inset * size, 0.36 * size, span * size);
        }
    }

    // Mask = "u d l r" (1/0 per direction) — matches the 16 sprites RoadArt
    // pre-generates per skin code (mirrors resources/js/map-builder/index.js).
    function neighborMask(n) {
        return `${n.up ? '1' : '0'}${n.down ? '1' : '0'}${n.left ? '1' : '0'}${n.right ? '1' : '0'}`;
    }

    function drawFenceConnector(x, y) {
        const code = map.fence_grid?.[y]?.[x];

        if (!code) {
            return;
        }

        const n = {
            up: map.fence_grid?.[y - 1]?.[x] === code,
            down: map.fence_grid?.[y + 1]?.[x] === code,
            left: map.fence_grid?.[y]?.[x - 1] === code,
            right: map.fence_grid?.[y]?.[x + 1] === code,
        };

        const img = fenceAssetImages[code]?.[neighborMask(n)];

        if (!img || !img.complete || !img.naturalWidth) {
            drawFenceConnectorFallback(x, y, code);
            return;
        }

        const size = map.tile_size;
        const px = x * size;
        const py = y * size;
        const scale = scaleOf(code);
        const drawSize = size * scale;

        ctx.save();
        ctx.translate(px + size / 2, py + size / 2);
        ctx.drawImage(img, -drawSize / 2, -drawSize / 2, drawSize, drawSize);
        ctx.restore();
    }

    function drawRoadConnector(x, y) {
        const size = map.tile_size;
        const px = x * size;
        const py = y * size;
        const code = map.path_grid?.[y]?.[x];

        const n = {
            up: map.path_grid?.[y - 1]?.[x] === code,
            down: map.path_grid?.[y + 1]?.[x] === code,
            left: map.path_grid?.[y]?.[x - 1] === code,
            right: map.path_grid?.[y]?.[x + 1] === code,
        };

        const img = roadAssetImages[code]?.[neighborMask(n)];

        if (img && img.complete && img.naturalWidth > 0) {
            ctx.drawImage(img, px, py, size, size);
            return;
        }

        ctx.fillStyle = tileColors[code] ?? tileColors.road ?? '#3f3f3f';
        ctx.fillRect(px, py, size, size);
    }

    function drawMap() {
        // Each tile has a 3x3 sub-grid so several small props can share one
        // cell; legacy saves stored plain code strings with no position
        // (treated as sub-grid slot 0,0) — normalize those here.
        const subSize = map.tile_size / 3;

        for (let y = 0; y < map.height; y++) {
            for (let x = 0; x < map.width; x++) {
                const groundCode = map.ground_grid?.[y]?.[x];
                drawTile(groundCode, x * map.tile_size, y * map.tile_size, map.tile_size);

                if (map.path_grid?.[y]?.[x]) {
                    drawRoadConnector(x, y);
                }

                if (map.fence_grid?.[y]?.[x]) {
                    drawFenceConnector(x, y);
                }

                const objects = map.object_grid?.[y]?.[x] ?? [];

                objects.forEach((entry) => {
                    const code = typeof entry === 'string' ? entry : entry.code;
                    const sx = typeof entry === 'string' ? 0 : (entry.sx ?? 0);
                    const sy = typeof entry === 'string' ? 0 : (entry.sy ?? 0);
                    const scale = scaleOf(code);
                    const footprint = scale <= 1.5 ? 1 : (scale <= 2.1 ? 2 : 3);

                    drawTile(
                        code,
                        x * map.tile_size + sx * subSize,
                        y * map.tile_size + sy * subSize,
                        footprint * subSize
                    );
                });
            }
        }
    }

    // Depth (world-space Y of the object's "front"/base edge) used to sort
    // large decorations against towers below, so an object whose base sits
    // further down the map always draws on top of — and correctly occludes —
    // one whose base sits further up, instead of towers always winning.
    function largeObjectDepth(object) {
        const footprint = (map.tile_footprints ?? {})[object.tile_code] ?? { width: 1, height: 1 };

        return (object.y + footprint.height) * map.tile_size;
    }

    function drawLargeObject(object) {
        const footprints = map.tile_footprints ?? {};
        const footprint = footprints[object.tile_code] ?? { width: 1, height: 1 };
        const scale = scaleOf(object.tile_code);
        const baseWidth = footprint.width * map.tile_size;
        const baseHeight = footprint.height * map.tile_size;
        const scaledWidth = baseWidth * scale;
        const scaledHeight = baseHeight * scale;

        drawTile(
            object.tile_code,
            object.x * map.tile_size - (scaledWidth - baseWidth) / 2,
            object.y * map.tile_size - (scaledHeight - baseHeight) / 2,
            scaledWidth,
            scaledHeight
        );
    }

    // Large decorations (trees, buildings, ...) and towers both live on the
    // map's ground plane, so whichever one's base is further down the screen
    // has to draw on top to look correctly in front. Plain fixed draw-order
    // (decorations, then always towers) let every tower win regardless of
    // position — this merges both into one painter's-algorithm pass instead.
    function drawGroundLevelEntities() {
        const entries = [];

        (map.objects ?? []).forEach((object) => {
            entries.push({ depth: largeObjectDepth(object), draw: () => drawLargeObject(object) });
        });

        towers.forEach((tower) => {
            entries.push({ depth: tower.y, draw: () => drawTower(tower) });
        });

        entries.sort((a, b) => a.depth - b.depth);
        entries.forEach((entry) => entry.draw());
    }

    function drawBorderPanelStripes(x, y, w, h) {
        ctx.save();
        ctx.beginPath();
        ctx.rect(x, y, w, h);
        ctx.clip();

        ctx.strokeStyle = 'rgba(255, 255, 255, 0.045)';
        ctx.lineWidth = 4;

        const step = 14;
        const span = w + h;

        for (let i = -h; i < span; i += step) {
            ctx.beginPath();
            ctx.moveTo(x + i, y);
            ctx.lineTo(x + i - h, y + h);
            ctx.stroke();
        }

        ctx.restore();
    }

    function drawRivet(x, y) {
        ctx.beginPath();
        ctx.arc(x, y, 2.4, 0, Math.PI * 2);
        ctx.fillStyle = '#4b5a72';
        ctx.fill();
        ctx.strokeStyle = 'rgba(0, 0, 0, 0.5)';
        ctx.lineWidth = 0.6;
        ctx.stroke();
    }

    function drawBorder() {
        const thickness = BORDER;

        // Base metal-panel band, two-tone so it reads as a physical frame
        // rather than a flat rectangle.
        const gradient = ctx.createLinearGradient(0, 0, thickness, thickness);
        gradient.addColorStop(0, '#1e293b');
        gradient.addColorStop(0.5, '#111827');
        gradient.addColorStop(1, '#1e293b');
        ctx.fillStyle = gradient;
        ctx.fillRect(0, 0, canvas.width, thickness);
        ctx.fillRect(0, canvas.height - thickness, canvas.width, thickness);
        ctx.fillRect(0, 0, thickness, canvas.height);
        ctx.fillRect(canvas.width - thickness, 0, thickness, canvas.height);

        // Subtle diagonal hazard-panel texture on each side of the band.
        drawBorderPanelStripes(0, 0, canvas.width, thickness);
        drawBorderPanelStripes(0, canvas.height - thickness, canvas.width, thickness);
        drawBorderPanelStripes(0, thickness, thickness, canvas.height - thickness * 2);
        drawBorderPanelStripes(canvas.width - thickness, thickness, thickness, canvas.height - thickness * 2);

        // Rivets running along all four sides, not just the corners.
        const rivetSpacing = 30;
        const rivetInset = thickness * 0.24;

        for (let x = thickness; x < canvas.width - thickness; x += rivetSpacing) {
            drawRivet(x, rivetInset);
            drawRivet(x, canvas.height - rivetInset);
        }

        for (let y = thickness; y < canvas.height - thickness; y += rivetSpacing) {
            drawRivet(rivetInset, y);
            drawRivet(canvas.width - rivetInset, y);
        }

        // Outer dark seam + inner emerald accent line.
        ctx.strokeStyle = 'rgba(0, 0, 0, 0.6)';
        ctx.lineWidth = 2;
        ctx.strokeRect(1, 1, canvas.width - 2, canvas.height - 2);

        ctx.strokeStyle = '#22c55e';
        ctx.lineWidth = 2;
        ctx.strokeRect(thickness / 2, thickness / 2, canvas.width - thickness, canvas.height - thickness);

        ctx.fillStyle = '#22c55e';
        [
            [thickness / 2, thickness / 2],
            [canvas.width - thickness / 2, thickness / 2],
            [thickness / 2, canvas.height - thickness / 2],
            [canvas.width - thickness / 2, canvas.height - thickness / 2],
        ].forEach(([px, py]) => {
            ctx.beginPath();
            ctx.arc(px, py, 5, 0, Math.PI * 2);
            ctx.fill();
        });
    }

    const waypoints = (map.waypoints ?? []).map((waypoint) => ({
        x: waypoint.x * map.tile_size + map.tile_size / 2,
        y: waypoint.y * map.tile_size + map.tile_size / 2,
    }));

    const buildSpots = (map.build_spots ?? []).map((spot) => ({
        id: spot.id,
        x: spot.x * map.tile_size + map.tile_size / 2,
        y: spot.y * map.tile_size + map.tile_size / 2,
        hasTower: false,
    }));

    // Boss-coded enemies are excluded from the progressive early-wave pool —
    // a boss shouldn't be able to randomly spawn as regular wave-1 fodder —
    // and only added into the final wave's mix.
    const bossEnemyCodes = enemyTypeCodes.filter((code) => code.includes('boss'));
    const regularEnemyCodes = enemyTypeCodes.filter((code) => !code.includes('boss'));

    const WAVES = [
        { count: 5, spawnInterval: 0.8, types: regularEnemyCodes.slice(0, 1) },
        { count: 8, spawnInterval: 0.7, types: regularEnemyCodes.slice(0, 2) },
        { count: 12, spawnInterval: 0.6, types: regularEnemyCodes.concat(bossEnemyCodes) },
    ];

    const speed = map.tile_size * 1.5; // pixels per second
    let enemies = [];
    let towers = [];
    let projectiles = [];
    let lives = 10;
    let gold = Number(map.starting_gold) || 150;
    let gameOver = false;
    let victory = false;

    let currentWaveIndex = -1;
    let waveActive = false;
    let spawnQueueRemaining = 0;
    let spawnTimer = 0;

    const livesEl = document.getElementById('game-lives');
    const goldEl = document.getElementById('game-gold');
    const waveInfoEl = document.getElementById('game-wave-info');
    const waveButton = document.getElementById('game-wave-btn');
    const overlay = document.getElementById('game-over-overlay');
    const victoryOverlay = document.getElementById('game-victory-overlay');

    function updateGoldDisplay() {
        if (goldEl) {
            goldEl.textContent = String(gold);
        }
    }

    function flashInsufficientGold() {
        if (!goldEl) {
            return;
        }

        goldEl.classList.add('text-red-500');
        setTimeout(() => goldEl.classList.remove('text-red-500'), 300);
    }

    updateGoldDisplay();

    function updateWaveInfo() {
        if (!waveInfoEl) {
            return;
        }

        const waveNumber = Math.max(currentWaveIndex + 1, 1);
        waveInfoEl.textContent = `${waveNumber} / ${WAVES.length}`;
    }

    function startWave() {
        if (waveActive || gameOver || victory) {
            return;
        }

        currentWaveIndex += 1;

        if (currentWaveIndex >= WAVES.length) {
            return;
        }

        const wave = WAVES[currentWaveIndex];
        waveActive = true;
        spawnQueueRemaining = wave.count;
        spawnTimer = 0;
        updateWaveInfo();
        waveButton.disabled = true;
        waveButton.textContent = `Wave ${currentWaveIndex + 1} bezig...`;
    }

    function updateWaveSpawning(deltaSeconds) {
        if (!waveActive) {
            return;
        }

        if (spawnQueueRemaining > 0) {
            spawnTimer -= deltaSeconds;

            if (spawnTimer <= 0) {
                const types = WAVES[currentWaveIndex].types;
                const typeCode = types[Math.floor(Math.random() * types.length)];
                spawnEnemy(typeCode);
                spawnQueueRemaining -= 1;
                spawnTimer = WAVES[currentWaveIndex].spawnInterval;
            }

            return;
        }

        if (enemies.length === 0) {
            waveActive = false;

            if (currentWaveIndex >= WAVES.length - 1) {
                victory = true;
                victoryOverlay.classList.remove('hidden');
                victoryOverlay.classList.add('flex');
                waveButton.classList.add('hidden');
                return;
            }

            waveButton.disabled = false;
            waveButton.textContent = `Start wave ${currentWaveIndex + 2}`;
        }
    }

    function spawnEnemy(typeCode) {
        if (gameOver || waypoints.length < 2) {
            return;
        }

        const type = enemyTypes[typeCode] ?? Object.values(enemyTypes)[0];

        if (!type) {
            return;
        }

        enemies.push({
            x: waypoints[0].x,
            y: waypoints[0].y,
            targetIndex: 1,
            hp: type.hp,
            maxHp: type.hp,
            speedMultiplier: type.speed_multiplier,
            typeCode: type.code,
            animTimer: 0,
        });
    }

    function findBuildSpotNear(x, y) {
        return buildSpots.find(
            (s) => !s.hasTower && Math.hypot(s.x - x, s.y - y) < map.tile_size * 0.6
        );
    }

    function findTowerNear(x, y) {
        return towers.find((t) => Math.hypot(t.x - x, t.y - y) < map.tile_size * 0.45);
    }

    function findEnemyNear(x, y) {
        return enemies.find((e) => Math.hypot(e.x - x, e.y - y) < map.tile_size * 0.35);
    }

    let selectedTower = null;
    let selectedEnemy = null;

    const infoPopup = document.getElementById('info-popup');
    const infoPopupName = document.getElementById('info-popup-name');
    const infoPopupImage = document.getElementById('info-popup-image');
    const infoStatLabels = [
        document.getElementById('info-stat-label-1'),
        document.getElementById('info-stat-label-2'),
        document.getElementById('info-stat-label-3'),
    ];
    const infoStatValues = [
        document.getElementById('info-stat-value-1'),
        document.getElementById('info-stat-value-2'),
        document.getElementById('info-stat-value-3'),
    ];

    function setInfoPopup(name, stats, spriteUrl) {
        if (!infoPopup) {
            return;
        }

        infoPopupName.textContent = name;

        if (infoPopupImage) {
            infoPopupImage.src = spriteUrl ?? '';
            infoPopupImage.classList.toggle('hidden', !spriteUrl);
        }

        stats.forEach((stat, index) => {
            if (infoStatLabels[index]) {
                infoStatLabels[index].textContent = stat.label;
            }

            if (infoStatValues[index]) {
                infoStatValues[index].textContent = stat.value;
            }
        });

        infoPopup.classList.remove('hidden');
    }

    // Placed towers get a dedicated sidebar (bigger image, upgrade controls);
    // enemies still just get the small top-right popup.
    const towerDetailSidebar = document.getElementById('tower-detail-sidebar');
    const towerDetailName = document.getElementById('tower-detail-name');
    const towerDetailImage = document.getElementById('tower-detail-image');
    const towerDetailDamage = document.getElementById('tower-detail-damage');
    const towerDetailRange = document.getElementById('tower-detail-range');
    const towerDetailRate = document.getElementById('tower-detail-rate');
    const towerDetailCost = document.getElementById('tower-detail-cost');
    const towerUpgradeLabel = document.getElementById('tower-upgrade-label');
    const towerUpgradeBtn = document.getElementById('tower-upgrade-btn');
    const towerUpgradeCost = document.getElementById('tower-upgrade-cost');

    function showTowerDetail(type, tower) {
        if (!towerDetailSidebar) {
            return;
        }

        if (towerDetailName) {
            towerDetailName.textContent = `${type.name} — Niv. ${tower.level}`;
        }

        if (towerDetailImage) {
            towerDetailImage.src = type.sprite ?? '';
        }

        if (towerDetailDamage) {
            towerDetailDamage.textContent = tower.damage;
        }

        if (towerDetailRange) {
            towerDetailRange.textContent = `${(tower.range / map.tile_size).toFixed(1)} tegels`;
        }

        if (towerDetailRate) {
            towerDetailRate.textContent = `${(1 / tower.fireInterval).toFixed(1)}/sec`;
        }

        if (towerDetailCost) {
            towerDetailCost.textContent = `$ ${type.cost}`;
        }

        const tiers = type.upgrade_tiers ?? [];
        const nextTier = tiers.find((tier) => tier.level === tower.level + 1);

        if (towerUpgradeLabel && towerUpgradeBtn && towerUpgradeCost) {
            if (nextTier) {
                towerUpgradeLabel.textContent = `Upgrade naar niveau ${nextTier.level}`;
                towerUpgradeCost.textContent = SANDBOX ? 'Gratis' : `$ ${nextTier.upgrade_cost}`;
                towerUpgradeBtn.classList.remove('hidden');
                towerUpgradeBtn.disabled = false;
            } else {
                towerUpgradeLabel.textContent = 'Maximaal niveau bereikt';
                towerUpgradeBtn.classList.add('hidden');
            }
        }

        towerDetailSidebar.classList.remove('hidden');
        towerDetailSidebar.classList.add('flex');
    }

    function hideTowerDetail() {
        towerDetailSidebar?.classList.add('hidden');
        towerDetailSidebar?.classList.remove('flex');
    }

    document.getElementById('tower-detail-close')?.addEventListener('click', deselectTower);

    towerUpgradeBtn?.addEventListener('click', () => {
        if (!selectedTower) {
            return;
        }

        const type = towerTypes[selectedTower.typeCode];
        const tiers = type?.upgrade_tiers ?? [];
        const nextTier = tiers.find((tier) => tier.level === selectedTower.level + 1);

        if (!nextTier) {
            return;
        }

        if (!SANDBOX) {
            if (gold < nextTier.upgrade_cost) {
                flashInsufficientGold();
                return;
            }

            gold -= nextTier.upgrade_cost;
            updateGoldDisplay();
        }

        selectedTower.level = nextTier.level;
        selectedTower.damage = nextTier.damage;
        selectedTower.range = nextTier.range_tiles * map.tile_size;
        selectedTower.fireInterval = nextTier.fire_interval;

        showTowerDetail(type, selectedTower);
    });

    function selectTower(tower) {
        const type = towerTypes[tower.typeCode];

        if (!type) {
            return;
        }

        selectedEnemy = null;
        selectedTower = tower;
        infoPopup?.classList.add('hidden');
        showTowerDetail(type, tower);
    }

    function selectEnemy(enemy) {
        const type = enemyTypes[enemy.typeCode];

        if (!type) {
            return;
        }

        // Selecting an enemy does NOT clear the tower selection — the
        // tower-detail sidebar and the enemy popup can be open at once.
        selectedEnemy = enemy;
        setInfoPopup(type.name, [
            { label: 'Levenspunten', value: `${Math.max(0, Math.round(enemy.hp))} / ${enemy.maxHp}` },
            { label: 'Snelheid', value: `${(enemy.speedMultiplier ?? 1).toFixed(2)}x` },
            { label: 'Beloning', value: `$ ${type.bounty ?? 0}` },
        ], type.sprite);
    }

    function refreshSelectionPopup() {
        if (selectedEnemy) {
            if (selectedEnemy.hp <= 0 || !enemies.includes(selectedEnemy)) {
                deselectTower();
                return;
            }

            if (infoStatValues[0]) {
                infoStatValues[0].textContent = `${Math.max(0, Math.round(selectedEnemy.hp))} / ${selectedEnemy.maxHp}`;
            }
        }
    }

    function deselectTower() {
        selectedTower = null;
        selectedEnemy = null;
        infoPopup?.classList.add('hidden');
        hideTowerDetail();
    }

    document.getElementById('info-popup-close')?.addEventListener('click', deselectTower);

    function placeTower(spot, towerCode) {
        const type = towerTypes[towerCode];

        if (!spot || spot.hasTower || !type) {
            return;
        }

        if (!SANDBOX) {
            if (gold < type.cost) {
                flashInsufficientGold();
                return;
            }

            gold -= type.cost;
            updateGoldDisplay();
        }

        spot.hasTower = true;
        towers.push({
            x: spot.x,
            y: spot.y,
            range: type.range_tiles * map.tile_size,
            damage: type.damage,
            fireInterval: type.fire_interval,
            typeCode: type.code,
            cooldown: 0,
            angle: 0,
            idleSweep: Math.random() * Math.PI * 2,
            fireTtl: 0,
            level: 1,
        });
    }

    // Weapon placement is click-to-arm, click-to-place (no dragging): pick a
    // weapon from the sidebar, its card lights up and build spots appear,
    // hover shows a ghost preview over the nearest spot, and clicking one
    // places the tower and disarms the selection again (so a stray click
    // afterwards doesn't drop a second one). Esc or clicking the same/another
    // weapon also (re)arms/disarms it.
    let selectedTowerCode = null;
    let hoverPoint = null;

    function setSelectedTowerCode(code) {
        selectedTowerCode = code;

        document.querySelectorAll('.weapon-palette-item').forEach((item) => {
            const isSelected = item.dataset.towerCode === selectedTowerCode;

            // Toggle the dim base border off and the bright one on (rather than
            // just adding border-emerald-400 on top of the existing
            // border-emerald-500/30) so the selection can't lose a CSS
            // specificity tie-break with the base class — plus a ring, which
            // is a wholly separate box-shadow property, for an unmistakable
            // "this stays selected" indicator.
            item.classList.toggle('border-emerald-500/30', !isSelected);
            item.classList.toggle('border-emerald-400', isSelected);
            item.classList.toggle('bg-emerald-500/10', isSelected);
            item.classList.toggle('ring-2', isSelected);
            item.classList.toggle('ring-emerald-400', isSelected);
        });
    }

    document.querySelectorAll('.weapon-palette-item').forEach((item) => {
        item.addEventListener('click', () => {
            const code = item.dataset.towerCode;
            setSelectedTowerCode(selectedTowerCode === code ? null : code);
        });
    });

    document.querySelectorAll('.monster-palette-item').forEach((item) => {
        item.addEventListener('click', () => {
            spawnEnemy(item.dataset.enemyCode);
        });
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            setSelectedTowerCode(null);
            deselectTower();
        }
    });

    document.getElementById('sandbox-clear-btn')?.addEventListener('click', () => {
        enemies = [];
        towers = [];
        projectiles = [];
        deselectTower();
        setSelectedTowerCode(null);
        buildSpots.forEach((spot) => {
            spot.hasTower = false;
        });
    });

    function canvasPointFromEvent(event) {
        // offsetX/offsetY are reported in the canvas's own untransformed layout
        // space (the browser inverts any CSS 3D transform during hit-testing),
        // so this stays correct even with the tilted [transform:rotateX(...)] look.
        const scaleX = canvas.width / canvas.offsetWidth;
        const scaleY = canvas.height / canvas.offsetHeight;

        return {
            x: event.offsetX * scaleX - BORDER,
            y: event.offsetY * scaleY - BORDER,
        };
    }

    canvas.addEventListener('mousemove', (event) => {
        hoverPoint = canvasPointFromEvent(event);
    });

    canvas.addEventListener('mouseleave', () => {
        hoverPoint = null;
    });

    canvas.addEventListener('click', (event) => {
        if (panMoved) {
            panMoved = false;
            return;
        }

        const { x, y } = canvasPointFromEvent(event);
        const existingTower = findTowerNear(x, y);

        if (existingTower) {
            selectTower(existingTower);
            return;
        }

        const existingEnemy = findEnemyNear(x, y);

        if (existingEnemy) {
            selectEnemy(existingEnemy);
            return;
        }

        const spot = findBuildSpotNear(x, y);

        if (spot && selectedTowerCode) {
            placeTower(spot, selectedTowerCode);
            setSelectedTowerCode(null);
            return;
        }

        deselectTower();
    });

    function loseLife() {
        if (SANDBOX) {
            return;
        }

        lives = Math.max(lives - 1, 0);

        if (livesEl) {
            livesEl.textContent = String(lives);
        }

        if (lives === 0 && !gameOver) {
            gameOver = true;
            overlay?.classList.remove('hidden');
            overlay?.classList.add('flex');
        }
    }

    function updateEnemies(deltaSeconds) {
        enemies = enemies.filter((enemy) => {
            if (enemy.hp <= 0) {
                gold += enemyTypes[enemy.typeCode]?.bounty ?? 0;
                updateGoldDisplay();
                return false;
            }

            enemy.animTimer = (enemy.animTimer ?? 0) + deltaSeconds * (enemy.speedMultiplier ?? 1);

            const target = waypoints[enemy.targetIndex];
            const dx = target.x - enemy.x;
            const dy = target.y - enemy.y;
            const distance = Math.hypot(dx, dy);
            const step = speed * (enemy.speedMultiplier ?? 1) * deltaSeconds;

            if (distance <= step) {
                enemy.x = target.x;
                enemy.y = target.y;
                enemy.targetIndex += 1;

                if (enemy.targetIndex >= waypoints.length) {
                    loseLife();
                    return false;
                }
            } else {
                enemy.x += (dx / distance) * step;
                enemy.y += (dy / distance) * step;
            }

            return true;
        });
    }

    function towerHeadPivot(tower, size) {
        // Screen-space point the head/flash art rotates around; matches the
        // dome center baked into the base/head SVGs (16, 20 of a 32x32 canvas).
        return {
            x: tower.x + size * (TOWER_PIVOT_FRACTION.x - 0.5),
            y: tower.y + size * (TOWER_PIVOT_FRACTION.y - 0.5),
        };
    }

    function spawnProjectile(tower, target) {
        const size = towerRenderSize(towerTypes[tower.typeCode]);
        const scale = size / 32;
        const pivot = towerHeadPivot(tower, size);
        const relX = 27 - 32 * TOWER_PIVOT_FRACTION.x;
        const relY = 20 - 32 * TOWER_PIVOT_FRACTION.y;
        const cos = Math.cos(tower.angle);
        const sin = Math.sin(tower.angle);
        const rotatedX = relX * cos - relY * sin;
        const rotatedY = relX * sin + relY * cos;
        const tipX = pivot.x + rotatedX * scale;
        const tipY = pivot.y + rotatedY * TOWER_HEAD_SQUASH * scale;

        projectiles.push({
            x: tipX,
            y: tipY,
            startX: tipX,
            startY: tipY,
            targetX: target.x,
            targetY: target.y,
            t: 0,
            duration: 0.14,
            typeCode: tower.typeCode,
        });
    }

    function updateTowers(deltaSeconds) {
        towers.forEach((tower) => {
            tower.cooldown -= deltaSeconds;

            if (tower.fireTtl > 0) {
                tower.fireTtl -= deltaSeconds;
            }

            let target = null;
            let closestDistance = Infinity;

            enemies.forEach((enemy) => {
                const distance = Math.hypot(enemy.x - tower.x, enemy.y - tower.y);

                if (distance <= tower.range && distance < closestDistance) {
                    closestDistance = distance;
                    target = enemy;
                }
            });

            if (target) {
                tower.angle = Math.atan2(target.y - tower.y, target.x - tower.x);
            } else {
                // No target in range: keep the turret alive with a slow idle
                // sweep instead of freezing pointed at the last enemy.
                tower.idleSweep += deltaSeconds;
                tower.angle = Math.sin(tower.idleSweep * 0.6) * 0.5;
            }

            if (target && tower.cooldown <= 0) {
                target.hp -= tower.damage;
                tower.cooldown = tower.fireInterval;
                tower.fireTtl = 0.12;
                spawnProjectile(tower, target);
            }
        });
    }

    function updateProjectiles(deltaSeconds) {
        projectiles = projectiles.filter((projectile) => {
            projectile.t += deltaSeconds;
            const progress = Math.min(1, projectile.t / projectile.duration);
            projectile.x = projectile.startX + (projectile.targetX - projectile.startX) * progress;
            projectile.y = projectile.startY + (projectile.targetY - projectile.startY) * progress;
            return projectile.t < projectile.duration;
        });
    }

    function drawBuildSpots() {
        if (!selectedTowerCode) {
            return;
        }

        buildSpots.forEach((spot) => {
            if (spot.hasTower) {
                return;
            }

            ctx.save();
            ctx.setLineDash([4, 4]);
            ctx.strokeStyle = '#38bdf8';
            ctx.lineWidth = 2;
            ctx.strokeRect(
                spot.x - map.tile_size * 0.35,
                spot.y - map.tile_size * 0.35,
                map.tile_size * 0.7,
                map.tile_size * 0.7
            );
            ctx.restore();
        });
    }

    function drawSprite(image, x, y, size) {
        if (image && image.complete && image.naturalWidth > 0) {
            ctx.drawImage(image, x - size / 2, y - size / 2, size, size);
            return true;
        }

        return false;
    }

    function drawTowerPlacementPreview() {
        if (!selectedTowerCode || !hoverPoint) {
            return;
        }

        const spot = findBuildSpotNear(hoverPoint.x, hoverPoint.y);

        if (!spot || spot.hasTower) {
            return;
        }

        const type = towerTypes[selectedTowerCode];
        const size = towerRenderSize(type);

        ctx.save();
        ctx.globalAlpha = 0.55;

        if (!drawSprite(type?.image, spot.x, spot.y, size)) {
            ctx.fillStyle = '#4b5563';
            ctx.beginPath();
            ctx.arc(spot.x, spot.y, map.tile_size * 0.28, 0, Math.PI * 2);
            ctx.fill();
        }

        ctx.restore();
    }

    function drawTowerHead(tower, type, size) {
        const base = type.baseImage;
        const head = type.headImage;

        if (!base?.complete || !base.naturalWidth || !head?.complete || !head.naturalWidth) {
            return false;
        }

        drawSprite(base, tower.x, tower.y, size);

        const scale = size / 32;
        const pivot = towerHeadPivot(tower, size);
        const recoil = tower.fireTtl > 0 ? -3 : 0;

        ctx.save();
        ctx.translate(pivot.x, pivot.y);
        ctx.scale(1, TOWER_HEAD_SQUASH);
        ctx.rotate(tower.angle);
        ctx.translate(recoil, 0);
        ctx.drawImage(head, -32 * TOWER_PIVOT_FRACTION.x * scale, -32 * TOWER_PIVOT_FRACTION.y * scale, size, size);

        const flash = type.muzzleFlashImage;

        if (tower.fireTtl > 0 && flash?.complete && flash.naturalWidth) {
            ctx.drawImage(flash, -32 * TOWER_PIVOT_FRACTION.x * scale, -32 * TOWER_PIVOT_FRACTION.y * scale, size, size);
        }

        ctx.restore();

        return true;
    }

    function drawTower(tower) {
        if (tower === selectedTower) {
            ctx.save();
            ctx.strokeStyle = 'rgba(255, 255, 255, 0.6)';
            ctx.lineWidth = 1.5;
            ctx.beginPath();
            ctx.arc(tower.x, tower.y, tower.range, 0, Math.PI * 2);
            ctx.stroke();
            ctx.restore();
        }

        const type = towerTypes[tower.typeCode];
        const size = towerRenderSize(type);

        if (!drawTowerHead(tower, type ?? {}, size) && !drawSprite(type?.image, tower.x, tower.y, size)) {
            ctx.fillStyle = '#4b5563';
            ctx.beginPath();
            ctx.arc(tower.x, tower.y, map.tile_size * 0.28, 0, Math.PI * 2);
            ctx.fill();
        }
    }

    function drawEnemies() {
        enemies.forEach((enemy) => {
            const type = enemyTypes[enemy.typeCode];
            const enemyScale = Number(type?.render_scale);
            const radius = map.tile_size * 0.25 * (Number.isFinite(enemyScale) && enemyScale > 0 ? enemyScale : 1);
            let sprite = type?.image;

            if (enemy === selectedEnemy) {
                ctx.save();
                ctx.strokeStyle = 'rgba(255, 255, 255, 0.6)';
                ctx.lineWidth = 1.5;
                ctx.beginPath();
                ctx.arc(enemy.x, enemy.y, radius * 1.3, 0, Math.PI * 2);
                ctx.stroke();
                ctx.restore();
            }

            if (type?.frames?.length) {
                const frameIndex = Math.floor((enemy.animTimer ?? 0) / ANIM_FRAME_DURATION) % type.frames.length;
                sprite = type.frames[frameIndex];
            }

            if (!drawSprite(sprite, enemy.x, enemy.y, radius * 2.2)) {
                ctx.beginPath();
                ctx.arc(enemy.x, enemy.y, radius, 0, Math.PI * 2);
                ctx.fillStyle = '#f87171';
                ctx.fill();
            }

            const barWidth = radius * 2;
            const barHeight = 4;
            const barX = enemy.x - radius;
            const barY = enemy.y - radius - 10;
            const hpRatio = Math.max(enemy.hp / enemy.maxHp, 0);

            ctx.fillStyle = '#3f3f3f';
            ctx.fillRect(barX, barY, barWidth, barHeight);
            ctx.fillStyle = hpRatio > 0.5 ? '#4ade80' : hpRatio > 0.25 ? '#facc15' : '#ef4444';
            ctx.fillRect(barX, barY, barWidth * hpRatio, barHeight);
        });
    }

    // Jagged tower-to-target line, re-jittered fresh on every call — used for
    // the tesla's instant "bolt" projectiles instead of a travelling sprite.
    // Regenerating the offsets each frame (rather than caching one shape per
    // shot) is what gives it a crackling, flickering look instead of a fixed
    // static zap.
    function generateBoltPoints(x1, y1, x2, y2, jaggedness) {
        const segments = 5;
        const dx = (x2 - x1) / segments;
        const dy = (y2 - y1) / segments;
        const len = Math.hypot(dx, dy) || 1;
        const perpX = -dy / len;
        const perpY = dx / len;
        const points = [{ x: x1, y: y1 }];

        for (let i = 1; i < segments; i++) {
            const offset = (Math.random() - 0.5) * jaggedness;
            points.push({
                x: x1 + dx * i + perpX * offset,
                y: y1 + dy * i + perpY * offset,
            });
        }

        points.push({ x: x2, y: y2 });
        return points;
    }

    function strokeBoltPath(points, color, width, opacity) {
        ctx.save();
        ctx.globalAlpha = opacity;
        ctx.strokeStyle = color;
        ctx.lineWidth = width;
        ctx.lineJoin = 'round';
        ctx.beginPath();
        points.forEach((point, index) => {
            if (index === 0) {
                ctx.moveTo(point.x, point.y);
            } else {
                ctx.lineTo(point.x, point.y);
            }
        });
        ctx.stroke();
        ctx.restore();
    }

    function drawProjectiles() {
        projectiles.forEach((projectile) => {
            const type = towerTypes[projectile.typeCode];

            if (type?.projectile_style === 'bolt') {
                strokeBoltPath(
                    generateBoltPoints(projectile.startX, projectile.startY, projectile.targetX, projectile.targetY, 7),
                    '#c4b5fd',
                    2.4,
                    0.6
                );
                strokeBoltPath(
                    generateBoltPoints(projectile.startX, projectile.startY, projectile.targetX, projectile.targetY, 10),
                    '#f5f3ff',
                    1,
                    0.95
                );
                return;
            }

            const image = type?.projectileImage;
            const travelAngle = Math.atan2(
                projectile.targetY - projectile.startY,
                projectile.targetX - projectile.startX
            );

            if (image?.complete && image.naturalWidth) {
                const width = map.tile_size * 0.45;
                const height = width * (image.naturalHeight / image.naturalWidth);

                ctx.save();
                ctx.translate(projectile.x, projectile.y);
                ctx.rotate(travelAngle);
                ctx.drawImage(image, -width / 2, -height / 2, width, height);
                ctx.restore();
                return;
            }

            ctx.fillStyle = '#fff3b0';
            ctx.beginPath();
            ctx.arc(projectile.x, projectile.y, 3, 0, Math.PI * 2);
            ctx.fill();
        });
    }

    let lastTime = null;

    function loop(time) {
        if (lastTime === null) {
            lastTime = time;
        }

        const deltaSeconds = (time - lastTime) / 1000;
        lastTime = time;

        updatePan(deltaSeconds);
        applyViewTransform();

        ctx.clearRect(0, 0, canvas.width, canvas.height);
        drawBorder();

        ctx.save();
        ctx.translate(BORDER, BORDER);

        drawMap();
        drawBuildSpots();
        drawTowerPlacementPreview();

        if (!gameOver && !victory) {
            updateWaveSpawning(deltaSeconds);
            updateEnemies(deltaSeconds);
            updateTowers(deltaSeconds);
            updateProjectiles(deltaSeconds);
        }

        refreshSelectionPopup();

        drawGroundLevelEntities();
        drawEnemies();
        drawProjectiles();

        ctx.restore();

        requestAnimationFrame(loop);
    }

    requestAnimationFrame(loop);

    function resetGame() {
        lives = 10;
        gold = Number(map.starting_gold) || 150;
        enemies = [];
        towers = [];
        projectiles = [];
        deselectTower();
        buildSpots.forEach((spot) => {
            spot.hasTower = false;
        });
        gameOver = false;
        victory = false;
        currentWaveIndex = -1;
        waveActive = false;
        spawnQueueRemaining = 0;
        spawnTimer = 0;

        livesEl.textContent = String(lives);
        updateGoldDisplay();
        waveInfoEl.textContent = '-';
        overlay.classList.add('hidden');
        overlay.classList.remove('flex');
        victoryOverlay.classList.add('hidden');
        victoryOverlay.classList.remove('flex');
        waveButton.classList.remove('hidden');
        waveButton.disabled = false;
        waveButton.textContent = 'Start wave 1';
    }

    waveButton?.addEventListener('click', startWave);
    document.getElementById('game-restart-btn')?.addEventListener('click', resetGame);
    document.getElementById('game-victory-restart-btn')?.addEventListener('click', resetGame);
}

document.addEventListener('livewire:navigated', initGame);
initGame();
