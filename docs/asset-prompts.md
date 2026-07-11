# Area 51 Mapbuilder — AI Image Prompt Pack

Referentiebestand, niet door de app ingeladen. Gebruik dit alleen als je een van de nieuwe
tegels/objecten later wilt upgraden van de ingebouwde SVG-sprite naar een echt gegenereerde
pixelart-PNG. Genereer de afbeelding via een externe tool (Midjourney, DALL·E, Stable
Diffusion, etc.), snijd bij tot het genoemde canvasformaat met transparante achtergrond, en
upload het resultaat via **Adminpaneel → Tegeltypes → (tegel) → afbeelding uploaden**
(overschrijft de SVG automatisch, geen codewijziging nodig).

## Gedeelde stijlrichtlijnen (in elk prompt te gebruiken)

- **Stijl:** 3D pixel art, crisp pixel edges, geen anti-aliasing/blur, low-poly-voxel gevoel
- **Camera:** vlak top-down grondvlak met een lichte 3/4-neiging naar de kijker (alsof het
  hele bord ~20° achterover kantelt) — vergelijkbaar met klassieke tactische/tower-defense
  tile-art, NIET volledig isometrisch
- **Lichtrichting:** licht komt van linksboven; duidelijke slagschaduw rechtsonder aan de
  basis van het object (ellipsvormige contactschaduw)
- **Thema:** Area 51 woestijnbasis — militair/industrieel grijs, verweerd zandkleurig terrein,
  aliens/sci-fi-tech in groen/paars accent, waarschuwingsgeel/-rood voor gevaar
- **Achtergrond:** volledig transparant (PNG met alpha-kanaal)
- **Canvasformaat:** vierkant, per asset hieronder vermeld (32×32-conventie: 1× tile = 256×256
  of 512×512 px workingsize, daarna neerschalen)

---

## Wegen & muren (autotiling-skins)

### road-cracked — Gebarsten weg
Prompt: "Top-down 3D pixel art tile of cracked, worn desert asphalt road segment, dark
weathered gray surface with faded amber lane-marking dashes, hairline cracks, crisp pixel
edges, seamlessly tileable on all 4 sides, transparent background, square canvas, muted color
palette (#33312c base, #8a7a5c stripe)."
Canvas: 512×512 (1×1 tile), moet naadloos aansluiten op alle 4 zijden (autotile-set).

### concrete-wall — Betonmuur
Prompt: "Top-down 3D pixel art tile of a thick precast concrete perimeter wall segment, light
gray weathered concrete panels with visible seams, subtle top highlight edge, crisp pixel
edges, seamlessly tileable on all 4 sides, transparent background, square canvas, palette
#9a9a9a/#b8b8b8/#6f6f6f."
Canvas: 512×512 (1×1 tile), autotile-set (recht, hoek, T-splitsing, kruising, los stuk).

### fence-barbed — Prikkeldraadhek
Prompt: "Top-down 3D pixel art tile of a military chain-link fence segment topped with rusty
barbed wire coils, gray metal mesh, dark posts, warning rust-orange barbed wire accents, crisp
pixel edges, seamlessly tileable on all 4 sides, transparent background, square canvas."
Canvas: 512×512 (1×1 tile), autotile-set.

---

## Losse muur-/hekdecoraties

### fence-broken — Kapot hek
Prompt: "3D pixel art of a broken, breached chain-link fence section, torn mesh, bent posts,
rubble at the base, dust cloud hint, crisp pixel edges, transparent background, square canvas,
desert military color palette."
Canvas: 256×256, render_scale 1.6.

### checkpoint-gate — Slagboom
Prompt: "3D pixel art of a military checkpoint boom barrier / gate arm, red-and-white striped
barrier bar raised on a gray post, small warning light, crisp pixel edges, transparent
background, square canvas."
Canvas: 256×256, render_scale 2.0.

---

## Grondtegels

### tarmac — Landingsbaan-asfalt
Prompt: "Top-down 3D pixel art seamless tile of dark airfield tarmac/asphalt, subtle panel
seams, slight sheen highlight top edge, crisp pixel edges, transparent background, square
canvas, seamlessly tileable."
Canvas: 512×512.

### runway-stripe — Rijbaanmarkering
Prompt: "Top-down 3D pixel art seamless tile of dark airfield tarmac with a bold white dashed
runway centerline stripe running through the middle, crisp pixel edges, transparent
background, square canvas, seamlessly tileable."
Canvas: 512×512.

### helipad — Helihaven
Prompt: "Top-down 3D pixel art seamless tile of a helicopter landing pad, dark tarmac with a
large yellow painted 'H' inside a yellow ring, crisp pixel edges, transparent background,
square canvas."
Canvas: 512×512.

### concrete-pad — Betonplaat
Prompt: "Top-down 3D pixel art seamless tile of a light gray concrete building foundation
slab, visible panel seams, subtle weathering, crisp pixel edges, transparent background,
square canvas, seamlessly tileable."
Canvas: 512×512.

### ground-irradiated — Besmette grond
Prompt: "Top-down 3D pixel art seamless tile of alien-contaminated desert ground, dark
olive-green cracked dirt with glowing toxic green energy veins seeping through cracks, crisp
pixel edges, transparent background, square canvas, seamlessly tileable."
Canvas: 512×512.

---

## Obstakels & decoraties

### sandbags — Zandzakken
Prompt: "3D pixel art of a stacked military sandbag wall/bunker, tan canvas bags stacked in a
brick pattern, visible seams and folds, crisp pixel edges, transparent background, square
canvas."
Canvas: 256×256, render_scale 1.8.

### barbed-wire-coil — Prikkeldraadrol
Prompt: "3D pixel art of a coiled roll of rusty barbed wire (concertina wire) lying on the
ground, spiral coil shape, rust-orange barbs, crisp pixel edges, transparent background,
square canvas."
Canvas: 256×256, render_scale 1.4.

### dragons-teeth — Tankversperring
Prompt: "3D pixel art of a row of concrete anti-tank obstacles (dragon's teeth), pyramid-shaped
weathered concrete blocks in a staggered row, crisp pixel edges, transparent background,
square canvas."
Canvas: 256×256, render_scale 1.6.

### crater — Inslagkrater
Prompt: "Top-down 3D pixel art of a bomb/impact crater decal on desert ground, dark scorched
rim, blast marks radiating outward, crisp pixel edges, transparent background, square
canvas."
Canvas: 256×256, render_scale 1.8, ground-decal (geen contactschaduw nodig).

### wrecked-jeep — Wrak jeep
Prompt: "3D pixel art of a destroyed military jeep wreck, three-quarter view, burnt gray-green
chassis, missing windshield, flat tires, small fire/scorch accents, crisp pixel edges,
transparent background, square canvas."
Canvas: 256×256, render_scale 2.2.

### flagpole — Vlaggenmast
Prompt: "3D pixel art of a tall metal flagpole with a small red flag waving, gray pole, gold
ball finial on top, crisp pixel edges, transparent background, square canvas."
Canvas: 256×256, render_scale 2.6.

### searchlight — Schijnwerper
Prompt: "3D pixel art of a military searchlight/spotlight tower, gray tripod base, large round
light housing angled upward, warm glowing beam cone, crisp pixel edges, transparent
background, square canvas."
Canvas: 256×256, render_scale 2.4.

### sign-biohazard — Biohazard-bord
Prompt: "3D pixel art of a yellow warning sign on a metal post displaying a black biohazard
symbol, black border, crisp pixel edges, transparent background, square canvas."
Canvas: 256×256, render_scale 1.8.

### server-rack — Serverkast
Prompt: "3D pixel art of a military/lab server rack cabinet, dark gray metal casing, rows of
blinking status lights (green/red/amber), crisp pixel edges, transparent background, square
canvas."
Canvas: 256×256, render_scale 2.0.

### containment-pod — Containmentbuis
Prompt: "3D pixel art of a glowing alien containment stasis pod, dark metal frame, glass tube
with green glowing liquid and a faint alien silhouette inside, crisp pixel edges, transparent
background, square canvas."
Canvas: 256×256, render_scale 2.4.

### guard-soldier — Bewaker
Prompt: "3D pixel art of a standing military base guard figure, gray/olive uniform, helmet,
rifle slung on back, idle pose, facing camera at a slight 3/4 angle, crisp pixel edges,
transparent background, square canvas."
Canvas: 256×256, render_scale 1.8.

### ufo-flying — Zwevende UFO
Prompt: "3D pixel art of an intact hovering flying saucer UFO, metallic purple-gray disc
shape, glowing green energy core underside, small light ring, faint downward tractor-beam
glow, crisp pixel edges, transparent background, square canvas, viewed from a slightly
elevated 3/4 top-down angle."
Canvas: 512×512, footprint 2×2.

---

## Gebouwen

### hangar — Hangar (3×3)
Prompt: "3D pixel art of a large military aircraft hangar building, curved corrugated metal
roof, big closed hangar doors on the front face, small warning light on roof ridge, crisp
pixel edges, transparent background, square canvas, slightly elevated 3/4 top-down angle."
Canvas: 768×768, footprint 3×3.

### lab-building — Laboratorium (2×2)
Prompt: "3D pixel art of a sci-fi military research lab building, light gray industrial
facade, black-and-yellow hazard stripe band, glowing green/red tech windows, roof-mounted
vents and a small satellite dish, crisp pixel edges, transparent background, square canvas."
Canvas: 512×512, footprint 2×2.

### checkpoint-house — Wachthuisje (2×2)
Prompt: "3D pixel art of a small military checkpoint guard house, wooden/brown paneled walls,
angled gray roof, two glowing blue windows, small red warning light, crisp pixel edges,
transparent background, square canvas."
Canvas: 512×512, footprint 2×2.

### radar-tower — Radartoren (2×2)
Prompt: "3D pixel art of a tall radar/control tower, gray metal support tower, large
parabolic radar dish angled upward, blinking red aircraft warning light on top, crisp pixel
edges, transparent background, square canvas."
Canvas: 512×512, footprint 2×2.

### missile-silo — Raketsilo (2×2)
Prompt: "3D pixel art of a military missile/containment silo hatch, dark gray reinforced
circular hatch cover set in a raised concrete housing, black-and-yellow hazard stripe band,
small red warning light, crisp pixel edges, transparent background, square canvas, top-down
3/4 angle."
Canvas: 512×512, footprint 2×2.
