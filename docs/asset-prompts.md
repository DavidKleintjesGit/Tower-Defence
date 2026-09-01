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

---

## Interieur — Area 51 / labcomplex

Zelfde camera/licht-conventie als hierboven, maar de omgeving is **binnen**: kunstmatige
plafondverlichting (koeler, blauwiger wit licht) in plaats van zonlicht, en een steriele
militaire/wetenschappelijke kleurwereld (wit/grijs/staal, met groen/rood accentverlichting op
apparatuur) in plaats van woestijntinten.

### Vloeren (ground-tegels)

**lab-floor-tile** — Prompt: "Top-down 3D pixel art seamless tile of a sterile white
laboratory floor tile, faint grid seams, subtle cool fluorescent-lit sheen, crisp pixel
edges, transparent background, square canvas, seamlessly tileable." Canvas: 512×512.

**lab-floor-grate** — Prompt: "Top-down 3D pixel art seamless tile of a dark metal floor
grating/catwalk panel, perforated grid pattern, industrial steel, crisp pixel edges,
transparent background, square canvas, seamlessly tileable." Canvas: 512×512.

**rubber-floor** — Prompt: "Top-down 3D pixel art seamless tile of dark rubber matting
floor with subtle round anti-slip studs, crisp pixel edges, transparent background, square
canvas, seamlessly tileable." Canvas: 512×512.

**hazard-floor** — Prompt: "Top-down 3D pixel art seamless tile of an industrial floor with
bold yellow-and-black diagonal hazard stripes marking a containment zone, crisp pixel edges,
transparent background, square canvas." Canvas: 512×512.

**blood-floor** — Prompt: "Top-down 3D pixel art seamless tile of a stained concrete
laboratory floor with dark dried blood splatter marks, ominous, crisp pixel edges,
transparent background, square canvas." Canvas: 512×512.

**concrete-floor** — Prompt: "Top-down 3D pixel art seamless tile of plain gray interior
concrete floor, faint expansion-joint seams, crisp pixel edges, transparent background,
square canvas, seamlessly tileable." Canvas: 512×512.

### Wanden (autotiling-skins, volledige set)

**lab-wall** — Prompt: "Top-down 3D pixel art tile of a white sterile laboratory interior
wall panel, clean paneling seams, subtle cool highlight top edge, crisp pixel edges,
seamlessly tileable on all 4 sides, transparent background, square canvas." Canvas: 512×512,
autotile-set (recht, hoek, T-splitsing, kruising, los stuk).

**steel-wall** — Prompt: "Top-down 3D pixel art tile of a gray reinforced steel interior
corridor wall, riveted metal panels, industrial military facility, crisp pixel edges,
seamlessly tileable on all 4 sides, transparent background, square canvas." Canvas: 512×512,
autotile-set.

**glass-wall** — Prompt: "Top-down 3D pixel art tile of a laboratory observation window
wall, dark metal frame with a translucent pale-blue glass pane, crisp pixel edges,
seamlessly tileable on all 4 sides, transparent background, square canvas." Canvas: 512×512,
autotile-set.

### Deuren (losse objecten)

**door-sliding** — Prompt: "3D pixel art of an industrial sliding double door, brushed metal
panels, dark seam down the middle, crisp pixel edges, transparent background, square
canvas." Canvas: 256×256, render_scale 1.8.

**door-blast** — Prompt: "3D pixel art of a heavy reinforced blast door, thick gray metal,
black-and-yellow hazard stripe band, round porthole window, crisp pixel edges, transparent
background, square canvas." Canvas: 256×256, render_scale 1.8.

**door-cell** — Prompt: "3D pixel art of a dark metal prison/containment cell door with
vertical bars, crisp pixel edges, transparent background, square canvas." Canvas: 256×256,
render_scale 1.8.

**door-keycard** — Prompt: "3D pixel art of a secure keycard-access door, light gray metal,
electronic card reader panel with red/green status lights mounted beside it, crisp pixel
edges, transparent background, square canvas." Canvas: 256×256, render_scale 1.8.

### Meubilair & props

**lab-table** — Prompt: "3D pixel art of a stainless steel laboratory workbench table with a
small monitoring device on top, crisp pixel edges, transparent background, square canvas."
Canvas: 256×256, render_scale 1.8.

**computer-terminal** — Prompt: "3D pixel art of a retro-futuristic computer terminal desk
with a glowing blue CRT monitor screen, crisp pixel edges, transparent background, square
canvas." Canvas: 256×256, render_scale 1.6.

**specimen-tank** — Prompt: "3D pixel art of a small glass specimen containment tank on a
metal stand, glowing green liquid with a faint alien silhouette inside, crisp pixel edges,
transparent background, square canvas." Canvas: 256×256, render_scale 1.6.

**filing-cabinet** — Prompt: "3D pixel art of a gray metal office filing cabinet with two
drawers, crisp pixel edges, transparent background, square canvas." Canvas: 256×256,
render_scale 1.6.

**stretcher** — Prompt: "3D pixel art of a medical gurney/stretcher with wheels, white
padded surface, crisp pixel edges, transparent background, square canvas." Canvas: 256×256,
render_scale 1.8.

**ceiling-light** — Prompt: "3D pixel art of a rectangular fluorescent ceiling light
fixture viewed from below, warm glowing panel, crisp pixel edges, transparent background,
square canvas." Canvas: 256×256, render_scale 1.8.

**alarm-light** — Prompt: "3D pixel art of a red emergency alarm beacon light on a wall
mount, glowing red dome, crisp pixel edges, transparent background, square canvas." Canvas:
256×256, render_scale 1.6.

**vent-grate** — Prompt: "3D pixel art of a metal wall ventilation grate/vent cover, dark
horizontal slats, crisp pixel edges, transparent background, square canvas." Canvas:
256×256, render_scale 1.8.

**pipes** — Prompt: "3D pixel art of wall-mounted industrial pipes and conduits, one gray
metal pipe and one brass/copper pipe running horizontally with bracket supports, crisp pixel
edges, transparent background, square canvas." Canvas: 256×256, render_scale 1.8.

**cell-bars** — Prompt: "3D pixel art of a standalone metal prison cell bars section, crisp
pixel edges, transparent background, square canvas." Canvas: 256×256, render_scale 1.8.

**whiteboard** — Prompt: "3D pixel art of a wall-mounted whiteboard with scribbled diagrams
and a red circled note, crisp pixel edges, transparent background, square canvas." Canvas:
256×256, render_scale 1.8.

**office-chair** — Prompt: "3D pixel art of a black office desk chair on wheels, crisp pixel
edges, transparent background, square canvas." Canvas: 256×256, render_scale 1.4.

---

## Exterieur — aanvullende tegels & objecten

### Grondtegels

**parking-lines** — Prompt: "Top-down 3D pixel art seamless tile of dark asphalt with white
painted parking bay lines, crisp pixel edges, transparent background, square canvas."
Canvas: 512×512.

**oil-stain** — Prompt: "Top-down 3D pixel art tile of a dark oil spill stain decal on
asphalt, organic irregular shape, crisp pixel edges, transparent background, square canvas."
Canvas: 512×512.

**salt-flat** — Prompt: "Top-down 3D pixel art seamless tile of a cracked white desert salt
flat, fine hairline cracks, crisp pixel edges, transparent background, square canvas."
Canvas: 512×512.

**canyon-rock** — Prompt: "Top-down 3D pixel art seamless tile of reddish-brown canyon
rock terrain, layered sediment texture, crisp pixel edges, transparent background, square
canvas." Canvas: 512×512.

**scorched-ground** — Prompt: "Top-down 3D pixel art seamless tile of burnt, charred desert
ground with glowing ember specks, crisp pixel edges, transparent background, square canvas."
Canvas: 512×512.

### Objecten

**plane-wreck** — Prompt: "3D pixel art of a crashed small military aircraft wreck, broken
fuselage, bent wing, scorch marks, crisp pixel edges, transparent background, square canvas,
slightly elevated 3/4 top-down angle." Canvas: 512×512, footprint 2×2.

**antenna-array** — Prompt: "3D pixel art of a large multi-dish radio antenna array on a
tall mast, several angled cross-braces, red aircraft warning lights, crisp pixel edges,
transparent background, square canvas." Canvas: 512×512, footprint 2×2.

**tent-camp** — Prompt: "3D pixel art of a military field camp tent, olive-green canvas,
dark entrance flap, crisp pixel edges, transparent background, square canvas." Canvas:
256×256, render_scale 3.0.

**barrel-stack** — Prompt: "3D pixel art of a stacked pyramid of three toxic/nuclear waste
barrels, yellow-green with radiation markings, crisp pixel edges, transparent background,
square canvas." Canvas: 256×256, render_scale 2.2.
