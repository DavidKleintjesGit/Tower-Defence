<?php

namespace Database\Seeders;

use App\Models\TileType;
use Illuminate\Database\Seeder;

class TileTypeSeeder extends Seeder
{
    public function run(): void
    {
        $tileTypes = [
            ['code' => 'sand', 'category' => 'ground', 'label' => 'Woestijnzand', 'color' => '#d9b56c', 'is_buildable' => true, 'sprite' => $this->sandSprite('#d9b56c', '#c49a52')],
            ['code' => 'sand-dusty', 'category' => 'ground', 'label' => 'Stoffig zand', 'color' => '#c9a860', 'is_buildable' => true, 'sprite' => $this->sandSprite('#c9a860', '#b28f4a')],
            ['code' => 'sand-pebbles', 'category' => 'ground', 'label' => 'Zand met kiezels', 'color' => '#d3ab6c', 'is_buildable' => true, 'sprite' => $this->pebbleSandSprite()],
            ['code' => 'ground-cracked', 'category' => 'ground', 'label' => 'Gebarsten grond', 'color' => '#c2985c', 'is_buildable' => true, 'sprite' => $this->crackedGroundSprite()],
            ['code' => 'rock', 'category' => 'ground', 'label' => 'Rotsgrond', 'color' => '#8a8272', 'is_buildable' => true, 'sprite' => $this->rockSprite()],
            ['code' => 'water', 'category' => 'ground', 'label' => 'Water', 'color' => '#3b7ea1', 'is_buildable' => false, 'sprite' => $this->waterSprite()],
            ['code' => 'road', 'category' => 'road', 'label' => 'Weg', 'color' => '#3a3a40', 'is_buildable' => false, 'sprite' => $this->roadSprite()],
            ['code' => 'crate', 'category' => 'decoration', 'label' => 'Kisten', 'color' => '#8a5a2f', 'is_buildable' => false, 'sprite' => $this->crateSprite(), 'render_scale' => 2.2],
            ['code' => 'barrier', 'category' => 'decoration', 'label' => 'Betonblok', 'color' => '#9a9a9a', 'is_buildable' => false, 'sprite' => $this->barrierSprite(), 'render_scale' => 2.0],
            ['code' => 'plant', 'category' => 'decoration', 'label' => 'Cactus', 'color' => '#2f8f4e', 'is_buildable' => false, 'sprite' => $this->plantSprite(), 'render_scale' => 1.3],
            ['code' => 'barrel', 'category' => 'decoration', 'label' => 'Nucleaire vaten', 'color' => '#c9d94a', 'is_buildable' => false, 'sprite' => $this->barrelSprite(), 'render_scale' => 2.0],
            ['code' => 'fence', 'category' => 'fence', 'label' => 'Hek', 'color' => '#6b6f73', 'is_buildable' => false, 'sprite' => $this->fenceSprite(), 'render_scale' => 1.6],
            ['code' => 'tree', 'category' => 'decoration', 'label' => 'Boom', 'color' => '#166534', 'is_buildable' => false, 'sprite' => $this->treeSprite(), 'footprint_width' => 2, 'footprint_height' => 2],
            ['code' => 'dune', 'category' => 'decoration', 'label' => 'Duin', 'color' => '#c9ad6e', 'is_buildable' => false, 'sprite' => $this->duneSprite(), 'footprint_width' => 2, 'footprint_height' => 2],
            ['code' => 'boulder', 'category' => 'decoration', 'label' => 'Rotsblok', 'color' => '#8a8272', 'is_buildable' => false, 'sprite' => $this->boulderSprite(), 'render_scale' => 1.4],
            ['code' => 'tent', 'category' => 'decoration', 'label' => 'Tent', 'color' => '#5a6b4d', 'is_buildable' => false, 'sprite' => $this->tentSprite(), 'render_scale' => 3.2],
            ['code' => 'lamp', 'category' => 'decoration', 'label' => 'Lantaarnpaal', 'color' => '#3f3f3f', 'is_buildable' => false, 'sprite' => $this->lampSprite(), 'render_scale' => 2.4],
            ['code' => 'cone', 'category' => 'decoration', 'label' => 'Pion', 'color' => '#ea580c', 'is_buildable' => false, 'sprite' => $this->coneSprite(), 'render_scale' => 1.6],
            ['code' => 'watertank', 'category' => 'decoration', 'label' => 'Watertank', 'color' => '#6b7280', 'is_buildable' => false, 'sprite' => $this->watertankSprite(), 'render_scale' => 2.2],
            ['code' => 'sign', 'category' => 'decoration', 'label' => 'Waarschuwingsbord', 'color' => '#dc2626', 'is_buildable' => false, 'sprite' => $this->signSprite(), 'render_scale' => 1.8],
            ['code' => 'crystal', 'category' => 'decoration', 'label' => 'Alien Kristal', 'color' => '#34d399', 'is_buildable' => false, 'sprite' => $this->crystalSprite(), 'render_scale' => 1.6],
            ['code' => 'fence-corner-post', 'category' => 'decoration', 'label' => 'Hekhoek', 'color' => '#6b6f73', 'is_buildable' => false, 'sprite' => $this->fenceCornerPostSprite(), 'render_scale' => 2.0],
            ['code' => 'satellite-dish', 'category' => 'decoration', 'label' => 'Satellietschotel', 'color' => '#9ca3af', 'is_buildable' => false, 'sprite' => $this->satelliteDishSprite(), 'render_scale' => 2.2],
            ['code' => 'turret-cannon', 'category' => 'decoration', 'label' => 'Kanonopstelling', 'color' => '#475569', 'is_buildable' => false, 'sprite' => $this->turretCannonSprite(), 'render_scale' => 2.2],
            ['code' => 'reactor-core', 'category' => 'decoration', 'label' => 'Alien reactorkern', 'color' => '#7f1d1d', 'is_buildable' => false, 'sprite' => $this->reactorCoreSprite(), 'render_scale' => 2.2],
            ['code' => 'watchtower', 'category' => 'decoration', 'label' => 'Uitkijktoren', 'color' => '#6b4a2f', 'is_buildable' => false, 'sprite' => $this->watchtowerSprite(), 'footprint_width' => 2, 'footprint_height' => 2],
            ['code' => 'ufo-wreck', 'category' => 'decoration', 'label' => 'UFO-wrak', 'color' => '#6b7280', 'is_buildable' => false, 'sprite' => $this->ufoWreckSprite(), 'footprint_width' => 3, 'footprint_height' => 3],
            ['code' => 'antenna', 'category' => 'decoration', 'label' => 'Radioantenne', 'color' => '#374151', 'is_buildable' => false, 'sprite' => $this->antennaSprite(), 'render_scale' => 2.4],
            ['code' => 'campfire', 'category' => 'decoration', 'label' => 'Kampvuur', 'color' => '#ea580c', 'is_buildable' => false, 'sprite' => $this->campfireSprite(), 'render_scale' => 1.5],
            ['code' => 'bones', 'category' => 'decoration', 'label' => 'Botten', 'color' => '#e5e0cf', 'is_buildable' => false, 'sprite' => $this->bonesSprite(), 'render_scale' => 1.2],
            ['code' => 'rubble', 'category' => 'decoration', 'label' => 'Puin', 'color' => '#6b6f73', 'is_buildable' => false, 'sprite' => $this->rubbleSprite(), 'render_scale' => 1.6],
            ['code' => 'bunker', 'category' => 'decoration', 'label' => 'Bunker', 'color' => '#4b5563', 'is_buildable' => false, 'sprite' => $this->bunkerSprite(), 'footprint_width' => 2, 'footprint_height' => 2],

            // Wegen-skins (volledige autotile-set via RoadArt)
            ['code' => 'road-cracked', 'category' => 'road', 'label' => 'Gebarsten weg', 'color' => '#33312c', 'is_buildable' => false, 'sprite' => $this->roadCrackedSprite()],
            ['code' => 'road-wide', 'category' => 'road', 'label' => 'Brede weg', 'color' => '#3a3a40', 'is_buildable' => false, 'sprite' => $this->roadWideSprite()],
            ['code' => 'road-dirt', 'category' => 'road', 'label' => 'Zandweg', 'color' => '#b8925a', 'is_buildable' => false, 'sprite' => $this->roadDirtSprite()],
            ['code' => 'corridor-path', 'category' => 'road', 'label' => 'Looppad (binnen)', 'color' => '#71717a', 'is_buildable' => false, 'sprite' => $this->corridorPathSprite()],

            // Muren-skins (volledige autotile-set via RoadArt)
            ['code' => 'concrete-wall', 'category' => 'fence', 'label' => 'Betonmuur', 'color' => '#9a9a9a', 'is_buildable' => false, 'sprite' => $this->concreteWallSprite(), 'render_scale' => 1.8],
            ['code' => 'fence-barbed', 'category' => 'fence', 'label' => 'Prikkeldraadhek', 'color' => '#6b6f73', 'is_buildable' => false, 'sprite' => $this->fenceBarbedSprite(), 'render_scale' => 1.7],

            // Losse muur-/hekdecoraties (niet auto-tiled)
            ['code' => 'fence-broken', 'category' => 'decoration', 'label' => 'Kapot hek', 'color' => '#6b6f73', 'is_buildable' => false, 'sprite' => $this->fenceBrokenSprite(), 'render_scale' => 1.6],
            ['code' => 'checkpoint-gate', 'category' => 'decoration', 'label' => 'Slagboom', 'color' => '#dc2626', 'is_buildable' => false, 'sprite' => $this->checkpointGateSprite(), 'render_scale' => 2.0],

            // Grond
            ['code' => 'tarmac', 'category' => 'ground', 'label' => 'Landingsbaan-asfalt', 'color' => '#4b4b52', 'is_buildable' => true, 'sprite' => $this->tarmacSprite()],
            ['code' => 'runway-stripe', 'category' => 'ground', 'label' => 'Rijbaanmarkering', 'color' => '#4b4b52', 'is_buildable' => true, 'sprite' => $this->runwayStripeSprite()],
            ['code' => 'helipad', 'category' => 'ground', 'label' => 'Helihaven', 'color' => '#3f3f45', 'is_buildable' => true, 'sprite' => $this->helipadSprite()],
            ['code' => 'concrete-pad', 'category' => 'ground', 'label' => 'Betonplaat', 'color' => '#9a9a9a', 'is_buildable' => true, 'sprite' => $this->concretePadSprite()],
            ['code' => 'ground-irradiated', 'category' => 'ground', 'label' => 'Besmette grond', 'color' => '#4a5a2f', 'is_buildable' => true, 'sprite' => $this->irradiatedGroundSprite()],

            // Obstakels/decoraties
            ['code' => 'sandbags', 'category' => 'decoration', 'label' => 'Zandzakken', 'color' => '#c2a469', 'is_buildable' => false, 'sprite' => $this->sandbagsSprite(), 'render_scale' => 1.8],
            ['code' => 'barbed-wire-coil', 'category' => 'decoration', 'label' => 'Prikkeldraadrol', 'color' => '#8a8272', 'is_buildable' => false, 'sprite' => $this->barbedWireCoilSprite(), 'render_scale' => 1.4],
            ['code' => 'dragons-teeth', 'category' => 'decoration', 'label' => 'Tankversperring', 'color' => '#9a9a9a', 'is_buildable' => false, 'sprite' => $this->dragonsTeethSprite(), 'render_scale' => 1.6],
            ['code' => 'crater', 'category' => 'decoration', 'label' => 'Inslagkrater', 'color' => '#4d4739', 'is_buildable' => false, 'sprite' => $this->craterSprite(), 'render_scale' => 1.8],
            ['code' => 'wrecked-jeep', 'category' => 'decoration', 'label' => 'Wrak jeep', 'color' => '#4b5563', 'is_buildable' => false, 'sprite' => $this->wreckedJeepSprite(), 'render_scale' => 2.2],
            ['code' => 'flagpole', 'category' => 'decoration', 'label' => 'Vlaggenmast', 'color' => '#9ca3af', 'is_buildable' => false, 'sprite' => $this->flagpoleSprite(), 'render_scale' => 2.6],
            ['code' => 'searchlight', 'category' => 'decoration', 'label' => 'Schijnwerper', 'color' => '#4b5563', 'is_buildable' => false, 'sprite' => $this->searchlightSprite(), 'render_scale' => 2.4],
            ['code' => 'sign-biohazard', 'category' => 'decoration', 'label' => 'Biohazard-bord', 'color' => '#f2c227', 'is_buildable' => false, 'sprite' => $this->signBiohazardSprite(), 'render_scale' => 1.8],
            ['code' => 'server-rack', 'category' => 'decoration', 'label' => 'Serverkast', 'color' => '#374151', 'is_buildable' => false, 'sprite' => $this->serverRackSprite(), 'render_scale' => 2.0],
            ['code' => 'containment-pod', 'category' => 'decoration', 'label' => 'Containmentbuis', 'color' => '#34d399', 'is_buildable' => false, 'sprite' => $this->containmentPodSprite(), 'render_scale' => 2.4],
            ['code' => 'guard-soldier', 'category' => 'decoration', 'label' => 'Bewaker', 'color' => '#4b5563', 'is_buildable' => false, 'sprite' => $this->guardSoldierSprite(), 'render_scale' => 1.8],
            ['code' => 'ufo-flying', 'category' => 'decoration', 'label' => 'Zwevende UFO', 'color' => '#7c3aed', 'is_buildable' => false, 'sprite' => $this->ufoFlyingSprite(), 'footprint_width' => 2, 'footprint_height' => 2],

            // Gebouwen
            ['code' => 'hangar', 'category' => 'decoration', 'label' => 'Hangar', 'color' => '#6b7280', 'is_buildable' => false, 'sprite' => $this->hangarSprite(), 'footprint_width' => 3, 'footprint_height' => 3],
            ['code' => 'lab-building', 'category' => 'decoration', 'label' => 'Laboratorium', 'color' => '#9ca3af', 'is_buildable' => false, 'sprite' => $this->labBuildingSprite(), 'footprint_width' => 2, 'footprint_height' => 2],
            ['code' => 'checkpoint-house', 'category' => 'decoration', 'label' => 'Wachthuisje', 'color' => '#6b4a2f', 'is_buildable' => false, 'sprite' => $this->checkpointHouseSprite(), 'footprint_width' => 2, 'footprint_height' => 2],
            ['code' => 'radar-tower', 'category' => 'decoration', 'label' => 'Radartoren', 'color' => '#9ca3af', 'is_buildable' => false, 'sprite' => $this->radarTowerSprite(), 'footprint_width' => 2, 'footprint_height' => 2],
            ['code' => 'missile-silo', 'category' => 'decoration', 'label' => 'Raketsilo', 'color' => '#4b5563', 'is_buildable' => false, 'sprite' => $this->missileSiloSprite(), 'footprint_width' => 2, 'footprint_height' => 2],

            // Nieuwe objecten, ontworpen met een zichtbaar top-, front- en zijvlak
            ['code' => 'cargo-container', 'category' => 'decoration', 'label' => 'Vrachtcontainer', 'color' => '#556b2f', 'is_buildable' => false, 'sprite' => $this->cargoContainerSprite(), 'render_scale' => 2.0],
            ['code' => 'fuel-tank', 'category' => 'decoration', 'label' => 'Brandstoftank', 'color' => '#9ca3af', 'is_buildable' => false, 'sprite' => $this->fuelTankSprite(), 'render_scale' => 1.8],
            ['code' => 'solar-panel', 'category' => 'decoration', 'label' => 'Zonnepaneel', 'color' => '#1f2937', 'is_buildable' => false, 'sprite' => $this->solarPanelSprite(), 'render_scale' => 1.6],
            ['code' => 'security-camera', 'category' => 'decoration', 'label' => 'Bewakingscamera', 'color' => '#4b5563', 'is_buildable' => false, 'sprite' => $this->securityCameraSprite(), 'render_scale' => 1.4],

            // Interieur-vloeren
            ['code' => 'lab-floor-tile', 'category' => 'ground', 'label' => 'Labtegel', 'color' => '#e5e7eb', 'is_buildable' => true, 'sprite' => $this->labFloorTileSprite()],
            ['code' => 'lab-floor-grate', 'category' => 'ground', 'label' => 'Roostervloer', 'color' => '#374151', 'is_buildable' => true, 'sprite' => $this->labFloorGrateSprite()],
            ['code' => 'rubber-floor', 'category' => 'ground', 'label' => 'Rubberen vloer', 'color' => '#27272a', 'is_buildable' => true, 'sprite' => $this->rubberFloorSprite()],
            ['code' => 'hazard-floor', 'category' => 'ground', 'label' => 'Gevarenvloer', 'color' => '#f2c227', 'is_buildable' => true, 'sprite' => $this->hazardFloorSprite()],
            ['code' => 'blood-floor', 'category' => 'ground', 'label' => 'Bebloede vloer', 'color' => '#52504a', 'is_buildable' => true, 'sprite' => $this->bloodFloorSprite()],
            ['code' => 'concrete-floor', 'category' => 'ground', 'label' => 'Betonvloer', 'color' => '#71717a', 'is_buildable' => true, 'sprite' => $this->concreteFloorSprite()],

            // Interieur-wanden (volledige autotile-set via RoadArt)
            ['code' => 'lab-wall', 'category' => 'fence', 'label' => 'Labwand', 'color' => '#e5e7eb', 'is_buildable' => false, 'sprite' => $this->labWallSprite(), 'render_scale' => 1.8],
            ['code' => 'steel-wall', 'category' => 'fence', 'label' => 'Stalen wand', 'color' => '#6b7280', 'is_buildable' => false, 'sprite' => $this->steelWallSprite(), 'render_scale' => 1.8],
            ['code' => 'glass-wall', 'category' => 'fence', 'label' => 'Observatieruit', 'color' => '#7dd3fc', 'is_buildable' => false, 'sprite' => $this->glassWallSprite(), 'render_scale' => 1.8],

            // Interieur-deuren (losse objecten, niet auto-tiled)
            ['code' => 'door-sliding', 'category' => 'decoration', 'label' => 'Schuifdeur', 'color' => '#6b7280', 'is_buildable' => false, 'sprite' => $this->doorSlidingSprite(), 'render_scale' => 1.8],
            ['code' => 'door-blast', 'category' => 'decoration', 'label' => 'Blastdeur', 'color' => '#4b5563', 'is_buildable' => false, 'sprite' => $this->doorBlastSprite(), 'render_scale' => 1.8],
            ['code' => 'door-cell', 'category' => 'decoration', 'label' => 'Celdeur', 'color' => '#27272a', 'is_buildable' => false, 'sprite' => $this->doorCellSprite(), 'render_scale' => 1.8],
            ['code' => 'door-keycard', 'category' => 'decoration', 'label' => 'Keycard-deur', 'color' => '#9ca3af', 'is_buildable' => false, 'sprite' => $this->doorKeycardSprite(), 'render_scale' => 1.8],

            // Interieur-meubilair & props
            ['code' => 'lab-table', 'category' => 'decoration', 'label' => 'Labtafel', 'color' => '#cbd5e1', 'is_buildable' => false, 'sprite' => $this->labTableSprite(), 'render_scale' => 1.8],
            ['code' => 'computer-terminal', 'category' => 'decoration', 'label' => 'Computerterminal', 'color' => '#1f2937', 'is_buildable' => false, 'sprite' => $this->computerTerminalSprite(), 'render_scale' => 1.6],
            ['code' => 'specimen-tank', 'category' => 'decoration', 'label' => 'Specimentank', 'color' => '#34d399', 'is_buildable' => false, 'sprite' => $this->specimenTankSprite(), 'render_scale' => 1.6],
            ['code' => 'filing-cabinet', 'category' => 'decoration', 'label' => 'Archiefkast', 'color' => '#6b7280', 'is_buildable' => false, 'sprite' => $this->filingCabinetSprite(), 'render_scale' => 1.6],
            ['code' => 'stretcher', 'category' => 'decoration', 'label' => 'Brancard', 'color' => '#e5e7eb', 'is_buildable' => false, 'sprite' => $this->stretcherSprite(), 'render_scale' => 1.8],
            ['code' => 'ceiling-light', 'category' => 'decoration', 'label' => 'Plafondlamp', 'color' => '#fef3c7', 'is_buildable' => false, 'sprite' => $this->ceilingLightSprite(), 'render_scale' => 1.8],
            ['code' => 'alarm-light', 'category' => 'decoration', 'label' => 'Alarmlicht', 'color' => '#ef4444', 'is_buildable' => false, 'sprite' => $this->alarmLightSprite(), 'render_scale' => 1.6],
            ['code' => 'vent-grate', 'category' => 'decoration', 'label' => 'Ventilatierooster', 'color' => '#4b5563', 'is_buildable' => false, 'sprite' => $this->ventGrateSprite(), 'render_scale' => 1.8],
            ['code' => 'pipes', 'category' => 'decoration', 'label' => 'Leidingwerk', 'color' => '#6b7280', 'is_buildable' => false, 'sprite' => $this->pipesSprite(), 'render_scale' => 1.8],
            ['code' => 'cell-bars', 'category' => 'decoration', 'label' => 'Celtralies', 'color' => '#3f3f46', 'is_buildable' => false, 'sprite' => $this->cellBarsSprite(), 'render_scale' => 1.8],
            ['code' => 'whiteboard', 'category' => 'decoration', 'label' => 'Whiteboard', 'color' => '#f8fafc', 'is_buildable' => false, 'sprite' => $this->whiteboardSprite(), 'render_scale' => 1.8],
            ['code' => 'office-chair', 'category' => 'decoration', 'label' => 'Bureaustoel', 'color' => '#374151', 'is_buildable' => false, 'sprite' => $this->officeChairSprite(), 'render_scale' => 1.4],

            // Exterieur: extra grondtegels
            ['code' => 'parking-lines', 'category' => 'ground', 'label' => 'Parkeervak', 'color' => '#4b4b52', 'is_buildable' => true, 'sprite' => $this->parkingLinesSprite()],
            ['code' => 'oil-stain', 'category' => 'ground', 'label' => 'Olievlek', 'color' => '#4b4b52', 'is_buildable' => true, 'sprite' => $this->oilStainSprite()],
            ['code' => 'salt-flat', 'category' => 'ground', 'label' => 'Zoutvlakte', 'color' => '#e5e1d3', 'is_buildable' => true, 'sprite' => $this->saltFlatSprite()],
            ['code' => 'canyon-rock', 'category' => 'ground', 'label' => 'Ravijnrots', 'color' => '#9a5a3f', 'is_buildable' => true, 'sprite' => $this->canyonRockSprite()],
            ['code' => 'scorched-ground', 'category' => 'ground', 'label' => 'Verschroeide grond', 'color' => '#2b2620', 'is_buildable' => true, 'sprite' => $this->scorchedGroundSprite()],

            // Exterieur: extra objecten
            ['code' => 'plane-wreck', 'category' => 'decoration', 'label' => 'Vliegtuigwrak', 'color' => '#6b7280', 'is_buildable' => false, 'sprite' => $this->planeWreckSprite(), 'footprint_width' => 2, 'footprint_height' => 2],
            ['code' => 'antenna-array', 'category' => 'decoration', 'label' => 'Antennepark', 'color' => '#4b5563', 'is_buildable' => false, 'sprite' => $this->antennaArraySprite(), 'footprint_width' => 2, 'footprint_height' => 2],
            ['code' => 'tent-camp', 'category' => 'decoration', 'label' => 'Legertent', 'color' => '#4a5d3a', 'is_buildable' => false, 'sprite' => $this->tentCampSprite(), 'render_scale' => 3.0],
            ['code' => 'barrel-stack', 'category' => 'decoration', 'label' => 'Vatenstapel', 'color' => '#c9d94a', 'is_buildable' => false, 'sprite' => $this->barrelStackSprite(), 'render_scale' => 2.2],
        ];

        foreach ($tileTypes as $tileType) {
            $tileType['image_path'] = null;
            TileType::updateOrCreate(['code' => $tileType['code']], $tileType);
        }
    }

    private function svg(string $inner, int $size = 32): string
    {
        return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 '.$size.' '.$size.'" shape-rendering="crispEdges">'.$inner.'</svg>';
    }

    private function sandSprite(string $base, string $shadow): string
    {
        return $this->svg(
            '<rect width="32" height="32" fill="'.$base.'"/>'
            .'<rect x="0" y="0" width="32" height="4" fill="'.$this->lighten($base).'"/>'
            .'<rect x="0" y="24" width="32" height="8" fill="'.$shadow.'"/>'
            .'<rect x="4" y="4" width="4" height="4" fill="'.$shadow.'"/>'
            .'<rect x="20" y="8" width="4" height="4" fill="'.$shadow.'"/>'
            .'<rect x="12" y="16" width="4" height="4" fill="'.$this->lighten($base).'"/>'
            .'<rect x="24" y="20" width="4" height="4" fill="'.$shadow.'"/>'
        );
    }

    private function lighten(string $hex): string
    {
        $r = min(255, hexdec(substr($hex, 1, 2)) + 24);
        $g = min(255, hexdec(substr($hex, 3, 2)) + 24);
        $b = min(255, hexdec(substr($hex, 5, 2)) + 24);

        return sprintf('#%02x%02x%02x', $r, $g, $b);
    }

    private function pebbleSandSprite(): string
    {
        return $this->svg(<<<'SVG'
            <rect width="32" height="32" fill="#d3ab6c"/>
            <rect x="0" y="24" width="32" height="8" fill="#b8924f"/>
            <rect x="4" y="6" width="4" height="4" fill="#8a8272"/>
            <rect x="4" y="6" width="2" height="2" fill="#a39a82"/>
            <rect x="20" y="14" width="4" height="4" fill="#8a8272"/>
            <rect x="20" y="14" width="2" height="2" fill="#a39a82"/>
            <rect x="12" y="22" width="4" height="4" fill="#8a8272"/>
            <rect x="12" y="22" width="2" height="2" fill="#a39a82"/>
            <rect x="24" y="4" width="3" height="3" fill="#8a8272"/>
            SVG);
    }

    private function crackedGroundSprite(): string
    {
        return $this->svg(<<<'SVG'
            <rect width="32" height="32" fill="#c2985c"/>
            <rect x="0" y="24" width="32" height="8" fill="#a87d47"/>
            <path d="M0 10 L8 12 L14 8 L20 14 L32 12" stroke="#8a6a3a" stroke-width="1" fill="none"/>
            <path d="M6 12 L8 24" stroke="#8a6a3a" stroke-width="1" fill="none"/>
            <path d="M20 14 L18 28" stroke="#8a6a3a" stroke-width="1" fill="none"/>
            <path d="M0 22 L12 24 L22 20 L32 24" stroke="#8a6a3a" stroke-width="1" fill="none"/>
            SVG);
    }

    private function rockSprite(): string
    {
        return $this->svg(<<<'SVG'
            <rect width="32" height="32" fill="#8a8272"/>
            <rect x="0" y="0" width="32" height="4" fill="#9c9482"/>
            <rect x="8" y="0" width="2" height="14" fill="#4d4739"/>
            <rect x="8" y="12" width="10" height="2" fill="#4d4739"/>
            <rect x="20" y="18" width="2" height="14" fill="#4d4739"/>
            <rect x="12" y="18" width="10" height="2" fill="#4d4739"/>
            <rect x="0" y="20" width="2" height="12" fill="#4d4739"/>
            <rect x="4" y="4" width="6" height="6" fill="#a39a82"/>
            <rect x="22" y="6" width="6" height="6" fill="#a39a82"/>
            <rect x="16" y="24" width="6" height="6" fill="#a39a82"/>
            <rect x="24" y="24" width="4" height="4" fill="#5f5847"/>
            SVG);
    }

    private function waterSprite(): string
    {
        return $this->svg(<<<'SVG'
            <rect width="32" height="32" fill="#3b7ea1"/>
            <rect x="0" y="0" width="32" height="4" fill="#356f8e"/>
            <rect x="0" y="6" width="8" height="2" fill="#5a9ec2"/>
            <rect x="12" y="6" width="8" height="2" fill="#5a9ec2"/>
            <rect x="24" y="6" width="8" height="2" fill="#5a9ec2"/>
            <rect x="4" y="16" width="8" height="2" fill="#5a9ec2"/>
            <rect x="16" y="16" width="8" height="2" fill="#5a9ec2"/>
            <rect x="0" y="26" width="6" height="2" fill="#5a9ec2"/>
            <rect x="10" y="26" width="8" height="2" fill="#5a9ec2"/>
            <rect x="22" y="26" width="8" height="2" fill="#5a9ec2"/>
            SVG);
    }

    private function roadSprite(): string
    {
        return $this->svg(<<<'SVG'
            <rect width="32" height="32" fill="#37373d"/>
            <rect x="0" y="0" width="4" height="32" fill="#232327"/>
            <rect x="28" y="0" width="4" height="32" fill="#232327"/>
            <rect x="14" y="0" width="4" height="8" fill="#e8c547"/>
            <rect x="14" y="12" width="4" height="8" fill="#e8c547"/>
            <rect x="14" y="24" width="4" height="8" fill="#e8c547"/>
            SVG);
    }

    private function fenceSprite(): string
    {
        return $this->svg(<<<'SVG'
            <rect x="2" y="12" width="3" height="17" fill="#5b5f63"/>
            <rect x="27" y="12" width="3" height="17" fill="#5b5f63"/>
            <rect x="2" y="14" width="28" height="3" fill="#6b6f73"/>
            <rect x="2" y="23" width="28" height="3" fill="#6b6f73"/>
            <rect x="8" y="14" width="2" height="12" fill="#3f4246"/>
            <rect x="15" y="14" width="2" height="12" fill="#3f4246"/>
            <rect x="22" y="14" width="2" height="12" fill="#3f4246"/>
            SVG);
    }

    private function fenceCornerPostSprite(): string
    {
        return $this->svg(<<<'SVG'
            <ellipse cx="16" cy="28" rx="8" ry="2" fill="#000000" opacity="0.25"/>
            <rect x="14" y="6" width="4" height="23" fill="#5b5f63"/>
            <rect x="15" y="6" width="1" height="23" fill="#787c80"/>
            <rect x="4" y="14" width="10" height="3" fill="#6b6f73"/>
            <rect x="18" y="14" width="10" height="3" fill="#6b6f73"/>
            <rect x="4" y="22" width="10" height="3" fill="#6b6f73"/>
            <rect x="18" y="22" width="10" height="3" fill="#6b6f73"/>
            <rect x="13" y="4" width="6" height="3" fill="#3f4246"/>
            SVG);
    }

    private function crateSprite(): string
    {
        return $this->svg(<<<'SVG'
            <ellipse cx="16" cy="28" rx="11" ry="2" fill="#000000" opacity="0.3"/>
            <rect x="7" y="10" width="18" height="16" fill="#8a5a2f"/>
            <rect x="7" y="10" width="18" height="4" fill="#a06e3d"/>
            <rect x="7" y="22" width="18" height="4" fill="#5a3a1c"/>
            <rect x="14" y="10" width="1" height="16" fill="#5a3a1c"/>
            <rect x="7" y="10" width="4" height="4" fill="#5a3a1c"/>
            <rect x="21" y="10" width="4" height="4" fill="#5a3a1c"/>
            <rect x="7" y="22" width="4" height="4" fill="#5a3a1c"/>
            <rect x="21" y="22" width="4" height="4" fill="#5a3a1c"/>
            <rect x="13" y="14" width="6" height="6" fill="#c99a5f"/>
            SVG);
    }

    private function barrierSprite(): string
    {
        return $this->svg(<<<'SVG'
            <ellipse cx="16" cy="28" rx="12" ry="2" fill="#000000" opacity="0.25"/>
            <rect x="4" y="14" width="24" height="12" fill="#9a9a9a"/>
            <rect x="4" y="14" width="24" height="4" fill="#b8b8b8"/>
            <rect x="4" y="22" width="24" height="4" fill="#6f6f6f"/>
            <rect x="4" y="10" width="24" height="4" fill="#b5b5b5"/>
            <rect x="6" y="10" width="3" height="4" fill="#f2c227"/>
            <rect x="12" y="10" width="3" height="4" fill="#141414"/>
            <rect x="18" y="10" width="3" height="4" fill="#f2c227"/>
            <rect x="24" y="10" width="3" height="4" fill="#141414"/>
            SVG);
    }

    private function plantSprite(): string
    {
        return $this->svg(<<<'SVG'
            <ellipse cx="16" cy="27" rx="7" ry="2" fill="#000000" opacity="0.25"/>
            <rect x="14" y="8" width="4" height="18" fill="#2f8f4e"/>
            <rect x="15" y="8" width="1" height="18" fill="#4ade80"/>
            <rect x="7" y="10" width="5" height="3" fill="#2f8f4e"/>
            <rect x="6" y="13" width="4" height="9" fill="#2f8f4e"/>
            <rect x="7" y="13" width="1" height="7" fill="#4ade80"/>
            <rect x="20" y="6" width="5" height="3" fill="#2f8f4e"/>
            <rect x="21" y="9" width="4" height="9" fill="#2f8f4e"/>
            <rect x="22" y="9" width="1" height="7" fill="#4ade80"/>
            SVG);
    }

    private function barrelSprite(): string
    {
        return $this->svg(<<<'SVG'
            <ellipse cx="16" cy="29" rx="9" ry="2" fill="#000000" opacity="0.3"/>
            <rect x="9" y="8" width="14" height="20" fill="#c9d94a"/>
            <rect x="9" y="8" width="14" height="4" fill="#8a9a3a"/>
            <rect x="9" y="24" width="14" height="4" fill="#6f7d2f"/>
            <rect x="9" y="12" width="14" height="3" fill="#181c0f"/>
            <rect x="9" y="21" width="14" height="3" fill="#181c0f"/>
            <circle cx="16" cy="17" r="4" fill="#181c0f"/>
            <path d="M16 14 L18 17 L16 20 L14 17 Z" fill="#f2c227"/>
            SVG);
    }

    private function treeSprite(): string
    {
        return $this->svg(<<<'SVG'
            <ellipse cx="32" cy="58" rx="22" ry="4" fill="#000000" opacity="0.25"/>
            <rect x="28" y="34" width="8" height="24" fill="#6b4a2f"/>
            <rect x="28" y="34" width="3" height="24" fill="#7d5a3c"/>
            <rect x="8" y="8" width="24" height="24" fill="#166534"/>
            <rect x="32" y="14" width="20" height="18" fill="#166534"/>
            <rect x="16" y="28" width="24" height="14" fill="#166534"/>
            <rect x="10" y="10" width="8" height="8" fill="#22a35e"/>
            <rect x="36" y="16" width="8" height="8" fill="#22a35e"/>
            <rect x="42" y="10" width="4" height="4" fill="#0f4a26"/>
            <rect x="14" y="26" width="4" height="4" fill="#0f4a26"/>
            SVG, 64);
    }

    private function duneSprite(): string
    {
        return $this->svg(<<<'SVG'
            <rect x="4" y="44" width="56" height="12" fill="#000000" opacity="0.15"/>
            <path d="M2 50 Q16 22 32 34 Q48 20 62 46 L62 58 L2 58 Z" fill="#c9ad6e"/>
            <path d="M2 50 Q16 22 32 34 Q22 40 14 52 Z" fill="#d9c48a"/>
            <path d="M32 34 Q48 20 62 46 Q50 40 40 50 Z" fill="#b89d5e"/>
            SVG, 64);
    }

    private function boulderSprite(): string
    {
        return $this->svg(<<<'SVG'
            <ellipse cx="16" cy="27" rx="11" ry="3" fill="#000000" opacity="0.3"/>
            <path d="M5 24c-1-6 2-12 8-15 5-3 12-2 15 3 3 4 3 10-1 14-4 4-11 5-16 3-3-1-5-3-6-5z" fill="#8a8272"/>
            <path d="M8 15c3-3 8-4 12-2-2 1-3 3-3 5-3-1-6-1-9-3z" fill="#a39a82"/>
            <path d="M22 12c3 2 4 6 3 9-2-1-3-3-5-3 1-2 1-4 2-6z" fill="#5f5847"/>
            SVG);
    }

    private function tentSprite(): string
    {
        return $this->svg(<<<'SVG'
            <ellipse cx="16" cy="28" rx="12" ry="2" fill="#000000" opacity="0.25"/>
            <path d="M16 6L28 26H4z" fill="#5a6b4d"/>
            <path d="M16 6L22 26h-6z" fill="#43523a"/>
            <path d="M16 6L10 26H4z" fill="#6b7d5c"/>
            <rect x="14" y="20" width="4" height="6" fill="#241f16"/>
            <rect x="3" y="25" width="26" height="2" fill="#3f4a36"/>
            SVG);
    }

    private function lampSprite(): string
    {
        return $this->svg(<<<'SVG'
            <ellipse cx="16" cy="30" rx="6" ry="1.5" fill="#000000" opacity="0.25"/>
            <circle cx="16" cy="9" r="9" fill="#fde68a" opacity="0.18"/>
            <rect x="15" y="10" width="2" height="20" fill="#3f3f3f"/>
            <rect x="11" y="28" width="10" height="2" fill="#2c2c2c"/>
            <path d="M16 4c-3 0-5 2-5 5s2 4 5 4 5-1 5-4-2-5-5-5z" fill="#fde68a"/>
            <path d="M16 4c-3 0-5 2-5 5s2 4 5 4z" fill="#fef3c7" opacity="0.6"/>
            <rect x="14" y="12" width="4" height="2" fill="#3f3f3f"/>
            SVG);
    }

    private function coneSprite(): string
    {
        return $this->svg(<<<'SVG'
            <ellipse cx="16" cy="28" rx="8" ry="2" fill="#000000" opacity="0.25"/>
            <path d="M16 6l7 22H9z" fill="#ea580c"/>
            <path d="M11.5 20h9l1.5 5H10z" fill="#f5f3ff"/>
            <path d="M13.6 13h4.8l1 4h-6.8z" fill="#f5f3ff"/>
            <rect x="6" y="26" width="20" height="3" fill="#c2410c"/>
            SVG);
    }

    private function watertankSprite(): string
    {
        return $this->svg(<<<'SVG'
            <ellipse cx="16" cy="29" rx="10" ry="2" fill="#000000" opacity="0.25"/>
            <rect x="7" y="18" width="4" height="10" fill="#4b5563"/>
            <rect x="21" y="18" width="4" height="10" fill="#4b5563"/>
            <rect x="14" y="18" width="4" height="10" fill="#4b5563"/>
            <circle cx="16" cy="12" r="10" fill="#6b7280"/>
            <rect x="6" y="12" width="20" height="6" fill="#6b7280"/>
            <rect x="6" y="12" width="20" height="2" fill="#9ca3af"/>
            <rect x="14" y="4" width="4" height="4" fill="#4b5563"/>
            SVG);
    }

    private function signSprite(): string
    {
        return $this->svg(<<<'SVG'
            <rect x="14" y="16" width="3" height="14" fill="#4b4b4b"/>
            <rect x="5" y="4" width="22" height="14" fill="#f5f3ff"/>
            <rect x="5" y="4" width="22" height="14" fill="none" stroke="#dc2626" stroke-width="2"/>
            <path d="M16 7l4 7h-8z" fill="#111111"/>
            SVG);
    }

    private function crystalSprite(): string
    {
        return $this->svg(<<<'SVG'
            <ellipse cx="16" cy="28" rx="7" ry="2" fill="#000000" opacity="0.25"/>
            <circle cx="16" cy="17" r="11" fill="#34d399" opacity="0.12"/>
            <path d="M16 4l6 12-6 12-6-12z" fill="#34d399"/>
            <path d="M16 4l3 12-3 12-3-12z" fill="#6ee7b7"/>
            SVG);
    }

    private function satelliteDishSprite(): string
    {
        return $this->svg(<<<'SVG'
            <ellipse cx="16" cy="29" rx="9" ry="2" fill="#000000" opacity="0.25"/>
            <rect x="14" y="18" width="4" height="11" fill="#6b7280"/>
            <rect x="8" y="26" width="16" height="3" fill="#4b5563"/>
            <path d="M6 16c0-6 5-11 11-11 4 0 8 2 10 6L8 20c-1-1-2-3-2-4z" fill="#9ca3af"/>
            <path d="M6 16c0-6 5-11 11-11" stroke="#e5e7eb" stroke-width="1" fill="none"/>
            <rect x="21" y="6" width="2" height="8" fill="#4b5563"/>
            <circle cx="22" cy="6" r="1.6" fill="#f59e0b"/>
            SVG);
    }

    private function turretCannonSprite(): string
    {
        return $this->svg(<<<'SVG'
            <ellipse cx="16" cy="29" rx="11" ry="2" fill="#000000" opacity="0.3"/>
            <path d="M5 22c0-4 5-7 11-7s11 3 11 7-5 5-11 5-11-1-11-5z" fill="#9ca3af"/>
            <path d="M5 22c0-4 5-7 11-7s11 3 11 7" fill="none" stroke="#4b5563" stroke-width="1"/>
            <rect x="10" y="15" width="12" height="7" fill="#475569"/>
            <rect x="10" y="15" width="12" height="2" fill="#64748b"/>
            <path d="M8 15h8l3 4H8z" fill="#334155"/>
            <rect x="2" y="14" width="8" height="3" fill="#1f2937"/>
            <circle cx="16" cy="18" r="2" fill="#f59e0b"/>
            SVG);
    }

    private function reactorCoreSprite(): string
    {
        return $this->svg(<<<'SVG'
            <ellipse cx="16" cy="29" rx="11" ry="2" fill="#000000" opacity="0.3"/>
            <path d="M4 24c0-3 5-5 12-5s12 2 12 5-5 5-12 5-12-2-12-5z" fill="#9a8c7a"/>
            <path d="M6 21c0-6 4-11 10-11s10 5 10 11" fill="#334155"/>
            <path d="M6 21c0-6 4-11 10-11s10 5 10 11" fill="none" stroke="#1e293b" stroke-width="1"/>
            <circle cx="16" cy="14" r="3.4" fill="#ef4444"/>
            <circle cx="16" cy="14" r="1.6" fill="#fecaca"/>
            <circle cx="9" cy="19" r="2" fill="#ef4444"/>
            <circle cx="23" cy="19" r="2" fill="#ef4444"/>
            SVG);
    }

    private function watchtowerSprite(): string
    {
        return $this->svg(<<<'SVG'
            <ellipse cx="32" cy="58" rx="20" ry="4" fill="#000000" opacity="0.25"/>
            <path d="M14 55L18 20h28l4 35z" fill="#3a2a1a" opacity="0.4"/>
            <rect x="16" y="30" width="4" height="26" fill="#6b4a2f"/>
            <rect x="44" y="30" width="4" height="26" fill="#6b4a2f"/>
            <rect x="16" y="30" width="1" height="26" fill="#8a6540"/>
            <path d="M16 44 L48 44" stroke="#4a3420" stroke-width="2"/>
            <path d="M16 56 L28 48" stroke="#4a3420" stroke-width="2"/>
            <path d="M48 56 L36 48" stroke="#4a3420" stroke-width="2"/>
            <rect x="12" y="16" width="40" height="16" fill="#7d5a3c"/>
            <rect x="12" y="16" width="40" height="4" fill="#8a6540"/>
            <path d="M8 4L56 4L48 16H16Z" fill="#4b5563"/>
            <path d="M8 4L56 4L52 10H12Z" fill="#6b7280"/>
            <rect x="30" y="38" width="2" height="16" fill="#3a2a1a"/>
            <rect x="27" y="40" width="8" height="1.5" fill="#3a2a1a"/>
            <rect x="27" y="45" width="8" height="1.5" fill="#3a2a1a"/>
            <rect x="27" y="50" width="8" height="1.5" fill="#3a2a1a"/>
            SVG, 64);
    }

    private function ufoWreckSprite(): string
    {
        return $this->svg(<<<'SVG'
            <ellipse cx="48" cy="86" rx="46" ry="9" fill="#000000" opacity="0.3"/>
            <ellipse cx="48" cy="86" rx="34" ry="6" fill="#2b2416" opacity="0.5"/>
            <path d="M6 60c8-3 20-5 30-5l4 8-8 4c-10 2-20 0-28-3z" fill="#3a3a3a" opacity="0.4"/>
            <path d="M90 60c-8-3-20-5-30-5l-4 8 8 4c10 2 20 0 26-3z" fill="#3a3a3a" opacity="0.4"/>

            <path d="M4 58c0-9 20-15 44-15s44 6 44 15c0 6-8 10-18 12l-2 4c-8 2-16 3-24 3s-16-1-24-3l-2-4c-10-2-18-6-18-12z" fill="#6b7280"/>
            <path d="M4 58c0-9 20-15 44-15s44 6 44 15" fill="none" stroke="#374151" stroke-width="2"/>
            <path d="M4 58c8 5 24 8 44 8s36-3 44-8" fill="none" stroke="#4b5563" stroke-width="1.5"/>
            <rect x="10" y="58" width="5" height="4" fill="#374151"/>
            <rect x="22" y="62" width="5" height="4" fill="#374151"/>
            <rect x="68" y="62" width="5" height="4" fill="#374151"/>
            <rect x="80" y="58" width="5" height="4" fill="#374151"/>
            <rect x="44" y="66" width="8" height="4" fill="#2b3038"/>

            <path d="M26 46c5-11 13-18 22-18s17 7 22 18c-7-5-14-7-22-7s-15 2-22 7z" fill="#9ca3af"/>
            <path d="M26 46c5-11 13-18 22-18" fill="none" stroke="#d1d5db" stroke-width="1"/>
            <path d="M48 28c9 0 17 7 22 18-4-2-8-4-12-5l-2-13z" fill="#7d8794"/>
            <path d="M40 30 L44 42 L36 44 Z" fill="#4b5563" opacity="0.6"/>

            <circle cx="47" cy="39" r="8" fill="#a7f3d0" opacity="0.9"/>
            <circle cx="47" cy="39" r="12" fill="#4ade80" opacity="0.22"/>
            <path d="M44 32 L50 46" stroke="#065f46" stroke-width="1.4" opacity="0.7"/>
            <circle cx="44" cy="36" r="2" fill="#ecfdf5" opacity="0.9"/>

            <path d="M18 68c-2 8-3 13-9 18-2 2 1 4 3 3 6-4 8-10 11-19z" fill="#4ade80" opacity="0.9"/>
            <path d="M19 69c-2 6-3 10-7 15" stroke="#16a34a" stroke-width="1" opacity="0.6"/>
            <path d="M78 68c2 8 4 12 9 17 2 2-1 4-3 3-6-3-8-9-11-18z" fill="#4ade80" opacity="0.85"/>
            <ellipse cx="14" cy="88" rx="7" ry="2.4" fill="#4ade80" opacity="0.45"/>
            <ellipse cx="83" cy="87" rx="6" ry="2.2" fill="#4ade80" opacity="0.4"/>
            <rect x="30" y="60" width="3" height="10" fill="#4ade80" opacity="0.7"/>
            <rect x="58" y="61" width="3" height="9" fill="#4ade80" opacity="0.6"/>

            <path d="M12 46c3-4 4-10 2-16" stroke="#9ca3af" stroke-width="1.4" fill="none" opacity="0.55"/>
            <path d="M6 40c2-2 2-6 0-9" stroke="#cbd5e1" stroke-width="1" fill="none" opacity="0.4"/>
            <circle cx="10" cy="56" r="1.6" fill="#fbbf24"/>
            <circle cx="84" cy="56" r="1.6" fill="#fbbf24"/>
            <circle cx="48" cy="15" r="1.2" fill="#fde68a" opacity="0.8"/>
            SVG, 96);
    }

    private function antennaSprite(): string
    {
        return $this->svg(<<<'SVG'
            <ellipse cx="16" cy="29" rx="7" ry="1.6" fill="#000000" opacity="0.25"/>
            <rect x="15" y="6" width="2" height="23" fill="#374151"/>
            <path d="M8 12 L15 14 M24 12 L17 14" stroke="#4b5563" stroke-width="1.4"/>
            <path d="M10 8 L15 10 M22 8 L17 10" stroke="#4b5563" stroke-width="1.2"/>
            <rect x="11" y="26" width="10" height="3" fill="#1f2937"/>
            <circle cx="16" cy="5" r="2" fill="#ef4444"/>
            <circle cx="16" cy="5" r="4" fill="#ef4444" opacity="0.3"/>
            SVG);
    }

    private function campfireSprite(): string
    {
        return $this->svg(<<<'SVG'
            <ellipse cx="16" cy="28" rx="8" ry="2" fill="#000000" opacity="0.3"/>
            <path d="M6 26 L14 20 L18 26z" fill="#4a3420"/>
            <path d="M26 26 L18 20 L14 26z" fill="#5a4028"/>
            <path d="M16 24c-2-6 1-10 0-15 3 3 3 7 2 9 2-2 3-5 2-8 3 4 3 9 0 13-1-2-2-2-4 1z" fill="#f59e0b"/>
            <path d="M16 24c-1-4 1-7 0-10 2 2 2 5 1 7 1-1 2-3 1-5 2 3 2 6 0 9-1-1-1-1-2-1z" fill="#fde68a"/>
            SVG);
    }

    private function bonesSprite(): string
    {
        return $this->svg(<<<'SVG'
            <ellipse cx="16" cy="27" rx="8" ry="2" fill="#000000" opacity="0.2"/>
            <path d="M8 20c-1-1-1-3 0-4s3-1 4 0c1-1 3-1 4 0l2 2 2-2c1-1 3-1 4 0s1 3 0 4l-8 6z" fill="#e5e0cf"/>
            <circle cx="11" cy="18" r="1.4" fill="#3f3f3f"/>
            <circle cx="15" cy="18" r="1.4" fill="#3f3f3f"/>
            <rect x="6" y="24" width="10" height="2.4" fill="#e5e0cf"/>
            <rect x="5" y="23.4" width="2.4" height="3.6" fill="#e5e0cf"/>
            <rect x="14.6" y="23.4" width="2.4" height="3.6" fill="#e5e0cf"/>
            SVG);
    }

    private function rubbleSprite(): string
    {
        return $this->svg(<<<'SVG'
            <ellipse cx="16" cy="27" rx="10" ry="2.4" fill="#000000" opacity="0.28"/>
            <path d="M6 24 L10 14 L18 16 L16 24z" fill="#8a8272"/>
            <path d="M16 24 L18 16 L24 20 L26 24z" fill="#6b6459"/>
            <path d="M8 24 L12 20 L16 24z" fill="#a39a82"/>
            <rect x="12" y="18" width="3" height="3" fill="#5f5847"/>
            <rect x="19" y="19" width="2" height="2" fill="#5f5847"/>
            <path d="M10 16 L18 16" stroke="#4d4739" stroke-width="0.8"/>
            SVG);
    }

    private function bunkerSprite(): string
    {
        return $this->svg(<<<'SVG'
            <ellipse cx="32" cy="58" rx="26" ry="5" fill="#000000" opacity="0.28"/>
            <path d="M6 40c0-8 12-14 26-14s26 6 26 14v10c0 3-3 5-6 5H12c-3 0-6-2-6-5z" fill="#6b7280"/>
            <path d="M6 40c0-8 12-14 26-14s26 6 26 14" fill="none" stroke="#374151" stroke-width="2"/>
            <rect x="6" y="46" width="52" height="9" fill="#4b5563"/>
            <rect x="6" y="46" width="52" height="2" fill="#374151"/>
            <path d="M26 30h12v10H26z" fill="#1f2937"/>
            <path d="M28 32h8v6h-8z" fill="#0f172a"/>
            <rect x="10" y="48" width="4" height="4" fill="#1f2937"/>
            <rect x="50" y="48" width="4" height="4" fill="#1f2937"/>
            <rect x="4" y="53" width="56" height="4" fill="#374151"/>
            SVG, 64);
    }

    private function roadCrackedSprite(): string
    {
        return $this->svg(<<<'SVG'
            <rect width="32" height="32" fill="#33312c"/>
            <rect x="0" y="0" width="4" height="32" fill="#1c1a17"/>
            <rect x="28" y="0" width="4" height="32" fill="#1c1a17"/>
            <rect x="14" y="0" width="4" height="8" fill="#8a7a5c"/>
            <rect x="14" y="12" width="4" height="8" fill="#8a7a5c"/>
            <rect x="14" y="24" width="4" height="8" fill="#8a7a5c"/>
            <path d="M4 6 L12 12 L8 20 L18 16 L24 26" stroke="#1c1a17" stroke-width="1" fill="none" opacity="0.7"/>
            <path d="M20 4 L16 12" stroke="#1c1a17" stroke-width="1" fill="none" opacity="0.5"/>
            SVG);
    }

    private function roadWideSprite(): string
    {
        return $this->svg(<<<'SVG'
            <rect width="32" height="32" fill="#3a3a40"/>
            <rect x="0" y="0" width="2" height="32" fill="#232327"/>
            <rect x="30" y="0" width="2" height="32" fill="#232327"/>
            <rect x="13" y="2" width="6" height="8" fill="#f2c94c"/>
            <rect x="13" y="13" width="6" height="6" fill="#f2c94c"/>
            <rect x="13" y="24" width="6" height="6" fill="#f2c94c"/>
            SVG);
    }

    private function roadDirtSprite(): string
    {
        return $this->svg(<<<'SVG'
            <rect width="32" height="32" fill="#b8925a"/>
            <rect x="0" y="0" width="32" height="3" fill="#c9a86c"/>
            <rect x="0" y="29" width="32" height="3" fill="#8f6d3f"/>
            <rect x="12" y="0" width="2" height="32" fill="#8f6d3f"/>
            <rect x="18" y="0" width="2" height="32" fill="#8f6d3f"/>
            <rect x="6" y="8" width="3" height="3" fill="#a17f4a" opacity="0.6"/>
            <rect x="22" y="18" width="3" height="3" fill="#a17f4a" opacity="0.6"/>
            SVG);
    }

    private function corridorPathSprite(): string
    {
        // Same base as concreteFloorSprite() — a painted walking lane on the
        // lab floor, not a separate road material.
        return $this->svg(<<<'SVG'
            <rect width="32" height="32" fill="#71717a"/>
            <rect x="0" y="0" width="32" height="2" fill="#a1a1aa"/>
            <rect x="14" y="0" width="4" height="8" fill="#f2c94c"/>
            <rect x="14" y="12" width="4" height="8" fill="#f2c94c"/>
            <rect x="14" y="24" width="4" height="8" fill="#f2c94c"/>
            SVG);
    }

    private function concreteWallSprite(): string
    {
        return $this->svg(<<<'SVG'
            <rect x="2" y="8" width="28" height="20" fill="#9a9a9a"/>
            <rect x="2" y="8" width="28" height="4" fill="#b8b8b8"/>
            <rect x="2" y="24" width="28" height="4" fill="#6f6f6f"/>
            <rect x="10" y="8" width="1" height="20" fill="#7f7f7f"/>
            <rect x="21" y="8" width="1" height="20" fill="#7f7f7f"/>
            SVG);
    }

    private function fenceBarbedSprite(): string
    {
        return $this->svg(<<<'SVG'
            <rect x="2" y="14" width="3" height="15" fill="#5b5f63"/>
            <rect x="27" y="14" width="3" height="15" fill="#5b5f63"/>
            <rect x="2" y="16" width="28" height="3" fill="#6b6f73"/>
            <rect x="2" y="25" width="28" height="3" fill="#6b6f73"/>
            <rect x="8" y="16" width="2" height="12" fill="#3f4246"/>
            <rect x="15" y="16" width="2" height="12" fill="#3f4246"/>
            <rect x="22" y="16" width="2" height="12" fill="#3f4246"/>
            <path d="M2 12 L30 12" stroke="#b45309" stroke-width="1"/>
            <path d="M4 10 L8 14 M8 10 L4 14 M14 10 L18 14 M18 10 L14 14 M24 10 L28 14 M28 10 L24 14" stroke="#b45309" stroke-width="1"/>
            SVG);
    }

    private function fenceBrokenSprite(): string
    {
        return $this->svg(<<<'SVG'
            <ellipse cx="16" cy="28" rx="12" ry="2" fill="#000000" opacity="0.25"/>
            <rect x="2" y="16" width="3" height="11" fill="#5b5f63"/>
            <rect x="27" y="18" width="3" height="9" fill="#5b5f63" transform="rotate(8 28 22)"/>
            <path d="M5 17 L14 15 L13 22 L5 24Z" fill="#6b6f73"/>
            <path d="M20 18 L29 20 L28 26 L21 25Z" fill="#6b6f73"/>
            <rect x="9" y="17" width="2" height="6" fill="#3f4246"/>
            <path d="M13 22 L20 18" stroke="#3f4246" stroke-width="1.5"/>
            <rect x="12" y="25" width="6" height="3" fill="#8a8272"/>
            <rect x="16" y="26" width="4" height="2" fill="#5f5847"/>
            SVG);
    }

    private function checkpointGateSprite(): string
    {
        return $this->svg(<<<'SVG'
            <ellipse cx="16" cy="29" rx="10" ry="2" fill="#000000" opacity="0.3"/>
            <rect x="4" y="10" width="4" height="19" fill="#4b5563"/>
            <rect x="4" y="10" width="4" height="4" fill="#6b7280"/>
            <rect x="8" y="10" width="20" height="4" fill="#f5f3ff"/>
            <rect x="8" y="10" width="4" height="4" fill="#dc2626"/>
            <rect x="16" y="10" width="4" height="4" fill="#dc2626"/>
            <rect x="24" y="10" width="4" height="4" fill="#dc2626"/>
            <circle cx="6" cy="17" r="1.6" fill="#f59e0b"/>
            SVG);
    }

    private function tarmacSprite(): string
    {
        return $this->svg(<<<'SVG'
            <rect width="32" height="32" fill="#4b4b52"/>
            <rect x="0" y="0" width="32" height="3" fill="#5a5a62"/>
            <rect x="0" y="29" width="32" height="3" fill="#38383e"/>
            <rect x="0" y="10" width="32" height="1" fill="#3a3a40" opacity="0.6"/>
            <rect x="0" y="21" width="32" height="1" fill="#3a3a40" opacity="0.6"/>
            <rect x="4" y="4" width="3" height="3" fill="#5a5a62" opacity="0.5"/>
            <rect x="24" y="16" width="3" height="3" fill="#3a3a40" opacity="0.5"/>
            SVG);
    }

    private function runwayStripeSprite(): string
    {
        return $this->svg(<<<'SVG'
            <rect width="32" height="32" fill="#4b4b52"/>
            <rect x="0" y="0" width="32" height="3" fill="#5a5a62"/>
            <rect x="0" y="29" width="32" height="3" fill="#38383e"/>
            <rect x="13" y="0" width="6" height="10" fill="#e5e7eb"/>
            <rect x="13" y="14" width="6" height="4" fill="#e5e7eb"/>
            <rect x="13" y="22" width="6" height="10" fill="#e5e7eb"/>
            SVG);
    }

    private function helipadSprite(): string
    {
        // Same tarmac base as tarmacSprite()/runwayStripeSprite() — this is
        // "tarmac with an H-pad marking", not a separate ground material, so
        // it has to tile flush next to plain tarmac.
        return $this->svg(<<<'SVG'
            <rect width="32" height="32" fill="#4b4b52"/>
            <rect x="0" y="0" width="32" height="3" fill="#5a5a62"/>
            <rect x="0" y="29" width="32" height="3" fill="#38383e"/>
            <circle cx="16" cy="16" r="13" fill="none" stroke="#f2c94c" stroke-width="2"/>
            <rect x="10" y="9" width="3" height="14" fill="#f2c94c"/>
            <rect x="19" y="9" width="3" height="14" fill="#f2c94c"/>
            <rect x="10" y="14" width="12" height="3" fill="#f2c94c"/>
            SVG);
    }

    private function concretePadSprite(): string
    {
        return $this->svg(<<<'SVG'
            <rect width="32" height="32" fill="#9a9a9a"/>
            <rect x="0" y="0" width="32" height="4" fill="#b8b8b8"/>
            <rect x="0" y="28" width="32" height="4" fill="#6f6f6f"/>
            <rect x="0" y="10" width="32" height="1" fill="#7f7f7f"/>
            <rect x="0" y="20" width="32" height="1" fill="#7f7f7f"/>
            <rect x="10" y="0" width="1" height="32" fill="#7f7f7f"/>
            <rect x="21" y="0" width="1" height="32" fill="#7f7f7f"/>
            SVG);
    }

    private function irradiatedGroundSprite(): string
    {
        // Same cracked-desert base as crackedGroundSprite() — this is that
        // ground contaminated by alien energy, not a different biome, so it
        // has to read as a continuation of the surrounding sand/cracked
        // ground rather than a patch of alien swamp.
        return $this->svg(<<<'SVG'
            <rect width="32" height="32" fill="#c2985c"/>
            <rect x="0" y="24" width="32" height="8" fill="#a87d47"/>
            <path d="M0 10 L8 12 L14 8 L20 14 L32 12" stroke="#8a6a3a" stroke-width="1" fill="none"/>
            <path d="M6 12 L8 24" stroke="#8a6a3a" stroke-width="1" fill="none"/>
            <path d="M20 14 L18 28" stroke="#8a6a3a" stroke-width="1" fill="none"/>
            <circle cx="16" cy="16" r="10" fill="#4ade80" opacity="0.22"/>
            <path d="M4 10 L10 14 L8 22 L18 18 L26 26" stroke="#4ade80" stroke-width="1.4" fill="none" opacity="0.85"/>
            <path d="M20 6 L16 14" stroke="#4ade80" stroke-width="1.2" fill="none" opacity="0.65"/>
            <circle cx="10" cy="14" r="1.4" fill="#a7f3d0"/>
            <circle cx="18" cy="18" r="1.4" fill="#a7f3d0"/>
            SVG);
    }

    private function sandbagsSprite(): string
    {
        return $this->svg(<<<'SVG'
            <ellipse cx="16" cy="28" rx="13" ry="2" fill="#000000" opacity="0.3"/>
            <rect x="3" y="18" width="8" height="8" rx="3" fill="#c2a469"/>
            <rect x="12" y="18" width="8" height="8" rx="3" fill="#b0925a"/>
            <rect x="21" y="18" width="8" height="8" rx="3" fill="#c2a469"/>
            <rect x="7" y="11" width="8" height="8" rx="3" fill="#b0925a"/>
            <rect x="16" y="11" width="8" height="8" rx="3" fill="#c2a469"/>
            <path d="M3 21 h8 M12 21 h8 M21 21 h8 M7 14 h8 M16 14 h8" stroke="#8a6f42" stroke-width="1" opacity="0.6"/>
            SVG);
    }

    private function barbedWireCoilSprite(): string
    {
        return $this->svg(<<<'SVG'
            <ellipse cx="16" cy="27" rx="10" ry="2" fill="#000000" opacity="0.25"/>
            <ellipse cx="16" cy="18" rx="11" ry="7" fill="none" stroke="#8a8272" stroke-width="2"/>
            <ellipse cx="16" cy="15" rx="9" ry="6" fill="none" stroke="#9c9482" stroke-width="2"/>
            <ellipse cx="16" cy="12" rx="7" ry="5" fill="none" stroke="#a39a82" stroke-width="2"/>
            <path d="M6 18 L4 15 M8 21 L5 19 M26 18 L29 15 M24 21 L28 19 M12 9 L10 6 M20 9 L22 6" stroke="#b45309" stroke-width="1"/>
            SVG);
    }

    private function dragonsTeethSprite(): string
    {
        return $this->svg(<<<'SVG'
            <ellipse cx="16" cy="28" rx="13" ry="2" fill="#000000" opacity="0.3"/>
            <path d="M4 27 L9 15 L14 27Z" fill="#9a9a9a"/>
            <path d="M4 27 L9 15 L9 27Z" fill="#b8b8b8"/>
            <path d="M12 27 L18 12 L24 27Z" fill="#8f8f8f"/>
            <path d="M12 27 L18 12 L18 27Z" fill="#a8a8a8"/>
            <path d="M20 27 L25 16 L30 27Z" fill="#9a9a9a"/>
            <path d="M20 27 L25 16 L25 27Z" fill="#b8b8b8"/>
            SVG);
    }

    private function craterSprite(): string
    {
        return $this->svg(<<<'SVG'
            <ellipse cx="16" cy="18" rx="14" ry="9" fill="#1c1a17" opacity="0.5"/>
            <ellipse cx="16" cy="17" rx="11" ry="7" fill="#33312c"/>
            <ellipse cx="16" cy="16" rx="7" ry="4.4" fill="#211f1b"/>
            <path d="M6 12 L2 8 M26 12 L30 8 M8 24 L4 28 M24 24 L28 28" stroke="#1c1a17" stroke-width="1.4" opacity="0.6"/>
            <ellipse cx="14" cy="15" rx="2" ry="1.2" fill="#4d4739" opacity="0.6"/>
            SVG);
    }

    private function wreckedJeepSprite(): string
    {
        return $this->svg(<<<'SVG'
            <ellipse cx="16" cy="28" rx="14" ry="2.4" fill="#000000" opacity="0.3"/>
            <rect x="4" y="16" width="24" height="8" fill="#4b5563"/>
            <rect x="4" y="16" width="24" height="2" fill="#6b7280"/>
            <path d="M8 16 L11 9 L23 9 L25 16Z" fill="#5b6472"/>
            <path d="M12 9 L11 16 M20 9 L21 16" stroke="#1f2937" stroke-width="1"/>
            <circle cx="10" cy="24" r="3.4" fill="#1f2937"/>
            <circle cx="22" cy="24" r="3.4" fill="#1f2937"/>
            <circle cx="10" cy="24" r="1.4" fill="#4b5563"/>
            <circle cx="22" cy="24" r="1.4" fill="#4b5563"/>
            <path d="M6 18 L10 12 L9 20Z" fill="#3f4650" opacity="0.8"/>
            <rect x="14" y="18" width="4" height="2" fill="#ea580c" opacity="0.8"/>
            SVG);
    }

    private function flagpoleSprite(): string
    {
        return $this->svg(<<<'SVG'
            <ellipse cx="16" cy="30" rx="5" ry="1.4" fill="#000000" opacity="0.25"/>
            <rect x="15" y="4" width="2" height="26" fill="#9ca3af"/>
            <circle cx="16" cy="3" r="1.6" fill="#e5e7eb"/>
            <path d="M17 6 L28 8 L24 12 L28 16 L17 18Z" fill="#dc2626"/>
            <path d="M17 6 L28 8 L24 12 L28 16 L17 18" fill="none" stroke="#991b1b" stroke-width="0.6"/>
            SVG);
    }

    private function searchlightSprite(): string
    {
        return $this->svg(<<<'SVG'
            <ellipse cx="16" cy="29" rx="7" ry="1.6" fill="#000000" opacity="0.25"/>
            <rect x="14" y="12" width="4" height="17" fill="#4b5563"/>
            <rect x="10" y="27" width="12" height="2.4" fill="#374151"/>
            <path d="M16 6c-4 0-7 3-7 6s3 4 7 4 7-1 7-4-3-6-7-6z" fill="#e5e7eb"/>
            <path d="M16 6c-4 0-7 3-7 6" fill="none" stroke="#9ca3af" stroke-width="1"/>
            <path d="M11 12 L2 2 M21 12 L30 2 M16 10 L16 0" fill="none" stroke="#fde68a" stroke-width="1.2" opacity="0.55"/>
            <circle cx="16" cy="11" r="2.4" fill="#fde68a"/>
            SVG);
    }

    private function signBiohazardSprite(): string
    {
        return $this->svg(<<<'SVG'
            <rect x="14" y="16" width="3" height="14" fill="#4b4b4b"/>
            <rect x="4" y="3" width="24" height="16" fill="#f2c227"/>
            <rect x="4" y="3" width="24" height="16" fill="none" stroke="#111111" stroke-width="2"/>
            <circle cx="16" cy="11" r="2" fill="#111111"/>
            <circle cx="10" cy="8" r="2" fill="none" stroke="#111111" stroke-width="1.4"/>
            <circle cx="22" cy="8" r="2" fill="none" stroke="#111111" stroke-width="1.4"/>
            <circle cx="10" cy="14" r="2" fill="none" stroke="#111111" stroke-width="1.4"/>
            <circle cx="22" cy="14" r="2" fill="none" stroke="#111111" stroke-width="1.4"/>
            SVG);
    }

    private function serverRackSprite(): string
    {
        return $this->svg(<<<'SVG'
            <ellipse cx="16" cy="28" rx="10" ry="2" fill="#000000" opacity="0.3"/>
            <rect x="7" y="6" width="18" height="22" fill="#374151"/>
            <rect x="7" y="6" width="18" height="3" fill="#4b5563"/>
            <rect x="7" y="25" width="18" height="3" fill="#1f2937"/>
            <rect x="9" y="11" width="14" height="2" fill="#1f2937"/>
            <rect x="9" y="15" width="14" height="2" fill="#1f2937"/>
            <rect x="9" y="19" width="14" height="2" fill="#1f2937"/>
            <rect x="9" y="23" width="14" height="2" fill="#1f2937"/>
            <circle cx="21" cy="12" r="1" fill="#4ade80"/>
            <circle cx="21" cy="16" r="1" fill="#ef4444"/>
            <circle cx="21" cy="20" r="1" fill="#4ade80"/>
            <circle cx="21" cy="24" r="1" fill="#f59e0b"/>
            SVG);
    }

    private function containmentPodSprite(): string
    {
        return $this->svg(<<<'SVG'
            <ellipse cx="16" cy="28" rx="9" ry="2" fill="#000000" opacity="0.3"/>
            <rect x="8" y="6" width="16" height="22" rx="6" fill="#374151"/>
            <rect x="8" y="6" width="16" height="4" rx="2" fill="#4b5563"/>
            <rect x="8" y="24" width="16" height="4" fill="#1f2937"/>
            <rect x="10" y="8" width="12" height="18" rx="4" fill="#34d399" opacity="0.35"/>
            <rect x="10" y="8" width="12" height="18" rx="4" fill="none" stroke="#6ee7b7" stroke-width="1"/>
            <path d="M16 12c-2 0-3 2-3 4s1 5 3 5 3-3 3-5-1-4-3-4z" fill="#0f172a" opacity="0.7"/>
            <circle cx="16" cy="10" r="1.2" fill="#a7f3d0"/>
            SVG);
    }

    private function guardSoldierSprite(): string
    {
        return $this->svg(<<<'SVG'
            <ellipse cx="16" cy="29" rx="6" ry="1.6" fill="#000000" opacity="0.3"/>
            <rect x="13" y="21" width="3" height="8" fill="#374151"/>
            <rect x="16" y="21" width="3" height="8" fill="#1f2937"/>
            <rect x="11" y="12" width="10" height="10" fill="#4b5563"/>
            <rect x="9" y="13" width="3" height="7" fill="#4b5563"/>
            <rect x="20" y="13" width="3" height="7" fill="#4b5563"/>
            <circle cx="16" cy="8" r="4" fill="#c9a06a"/>
            <path d="M11 6c0-3 2-5 5-5s5 2 5 5H11z" fill="#374151"/>
            <rect x="21" y="14" width="6" height="1.6" fill="#1f2937"/>
            SVG);
    }

    private function ufoFlyingSprite(): string
    {
        return $this->svg(<<<'SVG'
            <ellipse cx="32" cy="56" rx="16" ry="4" fill="#000000" opacity="0.25"/>
            <path d="M8 34c6-14 16-20 24-20s18 6 24 20c-8 6-16 8-24 8s-16-2-24-8z" fill="#7c3aed"/>
            <path d="M8 34c6-14 16-20 24-20s18 6 24 20" fill="none" stroke="#a855f7" stroke-width="2"/>
            <ellipse cx="32" cy="34" rx="26" ry="7" fill="#9333ea"/>
            <ellipse cx="32" cy="34" rx="26" ry="7" fill="none" stroke="#c084fc" stroke-width="1.5"/>
            <circle cx="32" cy="22" r="9" fill="#a7f3d0" opacity="0.9"/>
            <circle cx="32" cy="22" r="13" fill="#4ade80" opacity="0.2"/>
            <circle cx="14" cy="34" r="2" fill="#fde68a"/>
            <circle cx="50" cy="34" r="2" fill="#fde68a"/>
            <circle cx="32" cy="38" r="2" fill="#fde68a"/>
            <path d="M20 41 L10 60 L54 60 L44 41Z" fill="#c4b5fd" opacity="0.22"/>
            SVG, 64);
    }

    // Buildings below use the same "flat silhouette + light-top/dark-bottom
    // banding + drop shadow" language as the rest of the catalog (crate,
    // watchtower, bunker) instead of a skewed 3/4-corner polygon. The game's
    // tilt is a pure rotateX (no yaw), so a true side wall isn't camera-
    // accurate from any direction; a fake corner made them read as flat
    // cutouts pasted on top of the scene rather than sitting on the ground.
    // Roofs are drawn as symmetric, front-facing shapes (arc/trapezoid),
    // never skewed to one side.
    private function hangarSprite(): string
    {
        return $this->svg(<<<'SVG'
            <ellipse cx="48" cy="88" rx="40" ry="6" fill="#000000" opacity="0.3"/>
            <path d="M8 44c0-22 18-38 40-38s40 16 40 38z" fill="#9ca3af"/>
            <path d="M8 44c0-18 14-32 32-35" fill="none" stroke="#d1d5db" stroke-width="2" opacity="0.6"/>
            <path d="M8 44c0-22 18-38 40-38s40 16 40 38" fill="none" stroke="#6b7280" stroke-width="2"/>
            <rect x="8" y="44" width="80" height="44" fill="#6b7280"/>
            <rect x="8" y="44" width="80" height="4" fill="#8f97a3"/>
            <rect x="8" y="76" width="80" height="8" fill="#374151"/>
            <rect x="8" y="84" width="80" height="4" fill="#1f2937"/>
            <rect x="8" y="76" width="12" height="8" fill="#f2c227"/>
            <rect x="32" y="76" width="12" height="8" fill="#111111"/>
            <rect x="52" y="76" width="12" height="8" fill="#f2c227"/>
            <rect x="76" y="76" width="12" height="8" fill="#111111"/>
            <rect x="22" y="48" width="1.5" height="28" fill="#4b5563"/>
            <rect x="34" y="48" width="1.5" height="28" fill="#4b5563"/>
            <rect x="46" y="48" width="1.5" height="28" fill="#4b5563"/>
            <rect x="58" y="48" width="1.5" height="28" fill="#4b5563"/>
            <rect x="70" y="48" width="1.5" height="28" fill="#4b5563"/>
            <rect x="44" y="10" width="8" height="6" fill="#374151"/>
            <circle cx="48" cy="8" r="1.8" fill="#ef4444"/>
            SVG, 96);
    }

    private function labBuildingSprite(): string
    {
        return $this->svg(<<<'SVG'
            <ellipse cx="32" cy="54" rx="26" ry="5" fill="#000000" opacity="0.28"/>
            <path d="M6 24L58 24L48 10H16Z" fill="#d1d5db"/>
            <path d="M6 24L58 24L52 18H12Z" fill="#9ca3af"/>
            <rect x="8" y="24" width="48" height="30" fill="#9ca3af"/>
            <rect x="8" y="24" width="48" height="4" fill="#d1d5db"/>
            <rect x="8" y="46" width="48" height="4" fill="#f2c227"/>
            <rect x="8" y="46" width="6" height="4" fill="#111111"/>
            <rect x="20" y="46" width="6" height="4" fill="#111111"/>
            <rect x="32" y="46" width="6" height="4" fill="#111111"/>
            <rect x="44" y="46" width="6" height="4" fill="#111111"/>
            <rect x="12" y="30" width="10" height="10" fill="#1f2937"/>
            <rect x="14" y="32" width="6" height="6" fill="#34d399" opacity="0.6"/>
            <rect x="26" y="30" width="10" height="10" fill="#1f2937"/>
            <rect x="28" y="32" width="6" height="6" fill="#ef4444" opacity="0.6"/>
            <rect x="40" y="30" width="10" height="10" fill="#1f2937"/>
            <rect x="42" y="32" width="6" height="6" fill="#34d399" opacity="0.6"/>
            <rect x="26" y="42" width="12" height="12" fill="#374151"/>
            <rect x="34" y="4" width="6" height="10" fill="#6b7280"/>
            <circle cx="37" cy="4" r="3" fill="#9ca3af"/>
            SVG, 64);
    }

    private function checkpointHouseSprite(): string
    {
        return $this->svg(<<<'SVG'
            <ellipse cx="32" cy="54" rx="22" ry="4.4" fill="#000000" opacity="0.28"/>
            <path d="M8 30L56 30L48 14H16Z" fill="#4b5563"/>
            <path d="M8 30L56 30L52 22H12Z" fill="#6b7280"/>
            <rect x="10" y="30" width="44" height="24" fill="#6b4a2f"/>
            <rect x="10" y="30" width="44" height="4" fill="#8a6540"/>
            <rect x="14" y="36" width="9" height="9" fill="#1f2937"/>
            <rect x="16" y="38" width="5" height="5" fill="#93c5fd" opacity="0.7"/>
            <rect x="41" y="36" width="9" height="9" fill="#1f2937"/>
            <rect x="43" y="38" width="5" height="5" fill="#93c5fd" opacity="0.7"/>
            <rect x="27" y="42" width="10" height="12" fill="#241f16"/>
            <rect x="6" y="12" width="4" height="4" fill="#ef4444"/>
            SVG, 64);
    }

    private function radarTowerSprite(): string
    {
        return $this->svg(<<<'SVG'
            <ellipse cx="27" cy="58" rx="16" ry="4" fill="#000000" opacity="0.28"/>
            <rect x="17" y="44" width="24" height="14" fill="#4b5563"/>
            <rect x="17" y="44" width="24" height="3" fill="#6b7280"/>
            <rect x="17" y="54" width="24" height="4" fill="#1f2937"/>
            <rect x="21" y="48" width="5" height="5" fill="#1f2937"/>
            <rect x="22.5" y="49.5" width="2" height="2" fill="#f59e0b" opacity="0.8"/>
            <rect x="27" y="20" width="4" height="24" fill="#4b5563"/>
            <rect x="27" y="20" width="1.5" height="24" fill="#6b7280"/>
            <path d="M6 20c0-11 9-19 20-19 8 0 15 4 19 11L10 30c-2-3-4-6-4-10z" fill="#9ca3af"/>
            <path d="M6 20c0-11 9-19 20-19" stroke="#e5e7eb" stroke-width="1.2" fill="none"/>
            <rect x="34" y="6" width="2" height="14" fill="#4b5563"/>
            <circle cx="35" cy="6" r="1.8" fill="#ef4444"/>
            <circle cx="35" cy="6" r="3.6" fill="#ef4444" opacity="0.3"/>
            SVG, 64);
    }

    private function missileSiloSprite(): string
    {
        return $this->svg(<<<'SVG'
            <ellipse cx="32" cy="56" rx="22" ry="4" fill="#000000" opacity="0.3"/>
            <rect x="10" y="24" width="44" height="32" fill="#4b5563"/>
            <rect x="10" y="24" width="44" height="4" fill="#6b7280"/>
            <rect x="10" y="50" width="44" height="6" fill="#f2c227"/>
            <rect x="10" y="50" width="8" height="6" fill="#111111"/>
            <rect x="26" y="50" width="8" height="6" fill="#111111"/>
            <rect x="42" y="50" width="8" height="6" fill="#111111"/>
            <ellipse cx="32" cy="18" rx="18" ry="7" fill="#6b7280"/>
            <ellipse cx="32" cy="18" rx="18" ry="7" fill="none" stroke="#9ca3af" stroke-width="1"/>
            <ellipse cx="32" cy="18" rx="11" ry="4.4" fill="#374151"/>
            <ellipse cx="32" cy="18" rx="7" ry="2.8" fill="#1f2937"/>
            <circle cx="32" cy="38" r="3" fill="#ef4444"/>
            SVG, 64);
    }

    private function cargoContainerSprite(): string
    {
        return $this->svg(<<<'SVG'
            <ellipse cx="16" cy="27" rx="13" ry="2.2" fill="#000000" opacity="0.3"/>
            <rect x="3" y="8" width="26" height="19" fill="#556b2f"/>
            <rect x="3" y="8" width="26" height="3" fill="#6b8043"/>
            <rect x="3" y="22" width="26" height="5" fill="#3d4d26"/>
            <rect x="3" y="8" width="3" height="19" fill="#3d4d26" opacity="0.5"/>
            <rect x="26" y="8" width="3" height="19" fill="#3d4d26" opacity="0.5"/>
            <rect x="9" y="11" width="1" height="16" fill="#3d4d26" opacity="0.6"/>
            <rect x="13" y="11" width="1" height="16" fill="#3d4d26" opacity="0.6"/>
            <rect x="17" y="11" width="1" height="16" fill="#3d4d26" opacity="0.6"/>
            <rect x="21" y="11" width="1" height="16" fill="#3d4d26" opacity="0.6"/>
            <rect x="12" y="14" width="9" height="5" fill="#dcd4a8" opacity="0.8"/>
            SVG);
    }

    private function fuelTankSprite(): string
    {
        return $this->svg(<<<'SVG'
            <ellipse cx="16" cy="27" rx="12" ry="2.2" fill="#000000" opacity="0.3"/>
            <rect x="6" y="20" width="4" height="6" fill="#4b5563"/>
            <rect x="22" y="20" width="4" height="6" fill="#4b5563"/>
            <rect x="4" y="10" width="24" height="12" rx="6" fill="#9ca3af"/>
            <rect x="4" y="10" width="24" height="4" rx="2" fill="#d1d5db"/>
            <rect x="4" y="18" width="24" height="4" fill="#6b7280"/>
            <circle cx="6" cy="16" r="5" fill="#8b95a1"/>
            <circle cx="4" cy="14" r="1.4" fill="#374151"/>
            <rect x="14" y="6" width="4" height="5" fill="#4b5563"/>
            <circle cx="16" cy="5" r="1.6" fill="#f59e0b"/>
            SVG);
    }

    private function solarPanelSprite(): string
    {
        return $this->svg(<<<'SVG'
            <ellipse cx="16" cy="28" rx="12" ry="2" fill="#000000" opacity="0.28"/>
            <polygon points="4,24 4,14 16,6 28,10 28,20 16,26" fill="#1f2937"/>
            <polygon points="4,14 16,6 28,10 16,17" fill="#1e3a8a"/>
            <path d="M8 12 L20 15.5 M12 10 L24 13.5" stroke="#60a5fa" stroke-width="0.8" opacity="0.7"/>
            <rect x="14" y="17" width="4" height="9" fill="#4b5563"/>
            <rect x="6" y="24" width="4" height="4" fill="#374151"/>
            <rect x="22" y="24" width="4" height="4" fill="#374151"/>
            SVG);
    }

    private function securityCameraSprite(): string
    {
        return $this->svg(<<<'SVG'
            <ellipse cx="16" cy="29" rx="5" ry="1.2" fill="#000000" opacity="0.25"/>
            <rect x="15" y="12" width="2" height="17" fill="#374151"/>
            <rect x="8" y="8" width="12" height="8" fill="#4b5563"/>
            <rect x="8" y="8" width="12" height="2" fill="#6b7280"/>
            <rect x="8" y="14" width="12" height="2" fill="#1f2937"/>
            <circle cx="10" cy="12" r="2.6" fill="#111111"/>
            <circle cx="9.4" cy="11.4" r="0.8" fill="#60a5fa" opacity="0.8"/>
            <circle cx="18" cy="9.4" r="0.9" fill="#ef4444"/>
            SVG);
    }

    // --- Interieur-vloeren -------------------------------------------------

    private function labFloorTileSprite(): string
    {
        return $this->svg(<<<'SVG'
            <rect width="32" height="32" fill="#e5e7eb"/>
            <rect x="0" y="0" width="32" height="2" fill="#f9fafb"/>
            <rect x="0" y="15" width="32" height="1" fill="#cbd5e1"/>
            <rect x="15" y="0" width="1" height="32" fill="#cbd5e1"/>
            <rect x="4" y="4" width="3" height="3" fill="#d1d5db" opacity="0.6"/>
            <rect x="24" y="20" width="3" height="3" fill="#d1d5db" opacity="0.6"/>
            SVG);
    }

    private function labFloorGrateSprite(): string
    {
        return $this->svg(<<<'SVG'
            <rect width="32" height="32" fill="#374151"/>
            <rect x="0" y="0" width="32" height="2" fill="#4b5563"/>
            <rect x="2" y="4" width="28" height="2" fill="#1f2937"/>
            <rect x="2" y="10" width="28" height="2" fill="#1f2937"/>
            <rect x="2" y="16" width="28" height="2" fill="#1f2937"/>
            <rect x="2" y="22" width="28" height="2" fill="#1f2937"/>
            <rect x="2" y="28" width="28" height="2" fill="#1f2937"/>
            <rect x="4" y="2" width="2" height="28" fill="#1f2937"/>
            <rect x="12" y="2" width="2" height="28" fill="#1f2937"/>
            <rect x="20" y="2" width="2" height="28" fill="#1f2937"/>
            <rect x="28" y="2" width="2" height="28" fill="#1f2937"/>
            SVG);
    }

    private function rubberFloorSprite(): string
    {
        return $this->svg(<<<'SVG'
            <rect width="32" height="32" fill="#27272a"/>
            <rect x="0" y="0" width="32" height="2" fill="#3f3f46"/>
            <circle cx="8" cy="8" r="1.4" fill="#3f3f46" opacity="0.6"/>
            <circle cx="24" cy="8" r="1.4" fill="#3f3f46" opacity="0.6"/>
            <circle cx="8" cy="24" r="1.4" fill="#3f3f46" opacity="0.6"/>
            <circle cx="24" cy="24" r="1.4" fill="#3f3f46" opacity="0.6"/>
            <circle cx="16" cy="16" r="1.4" fill="#3f3f46" opacity="0.6"/>
            SVG);
    }

    // hazard-floor and blood-floor are both "concreteFloorSprite() with
    // something painted/spilled on top" — same base+edge fill as
    // concreteFloorSprite() below, so they tile flush next to a plain
    // concrete floor instead of reading as an unrelated material.
    private function hazardFloorSprite(): string
    {
        return $this->svg(<<<'SVG'
            <rect width="32" height="32" fill="#71717a"/>
            <rect x="0" y="0" width="32" height="2" fill="#a1a1aa"/>
            <rect x="0" y="6" width="32" height="6" fill="#f2c227"/>
            <rect x="0" y="6" width="6" height="6" fill="#111111"/>
            <rect x="12" y="6" width="6" height="6" fill="#111111"/>
            <rect x="24" y="6" width="8" height="6" fill="#111111"/>
            <rect x="0" y="20" width="32" height="6" fill="#f2c227"/>
            <rect x="6" y="20" width="6" height="6" fill="#111111"/>
            <rect x="18" y="20" width="6" height="6" fill="#111111"/>
            SVG);
    }

    private function bloodFloorSprite(): string
    {
        return $this->svg(<<<'SVG'
            <rect width="32" height="32" fill="#71717a"/>
            <rect x="0" y="0" width="32" height="2" fill="#a1a1aa"/>
            <rect x="0" y="15" width="32" height="1" fill="#52525b" opacity="0.6"/>
            <rect x="15" y="0" width="1" height="32" fill="#52525b" opacity="0.6"/>
            <path d="M10 8c4 2 6 6 5 11-1 4-5 6-8 4-3-2-3-7-1-11 1-2 2-3 4-4z" fill="#7f1d1d" opacity="0.8"/>
            <path d="M20 18c2 1 3 4 2 6-1 2-3 2-4 1-2-1-2-4-1-6 1-1 2-1 3-1z" fill="#7f1d1d" opacity="0.7"/>
            <circle cx="9" cy="12" r="1.4" fill="#450a0a" opacity="0.8"/>
            SVG);
    }

    private function concreteFloorSprite(): string
    {
        return $this->svg(<<<'SVG'
            <rect width="32" height="32" fill="#71717a"/>
            <rect x="0" y="0" width="32" height="2" fill="#a1a1aa"/>
            <rect x="0" y="15" width="32" height="1" fill="#52525b" opacity="0.6"/>
            <rect x="15" y="0" width="1" height="32" fill="#52525b" opacity="0.6"/>
            SVG);
    }

    // --- Interieur-wanden (previewicoon; volledige set komt uit RoadArt) --

    private function labWallSprite(): string
    {
        return $this->svg(<<<'SVG'
            <rect x="2" y="8" width="28" height="20" fill="#e5e7eb"/>
            <rect x="2" y="8" width="28" height="4" fill="#f9fafb"/>
            <rect x="2" y="24" width="28" height="4" fill="#9ca3af"/>
            <rect x="10" y="8" width="1" height="20" fill="#cbd5e1"/>
            <rect x="21" y="8" width="1" height="20" fill="#cbd5e1"/>
            SVG);
    }

    private function steelWallSprite(): string
    {
        return $this->svg(<<<'SVG'
            <rect x="2" y="8" width="28" height="20" fill="#6b7280"/>
            <rect x="2" y="8" width="28" height="4" fill="#9ca3af"/>
            <rect x="2" y="24" width="28" height="4" fill="#374151"/>
            <rect x="10" y="8" width="1" height="20" fill="#4b5563"/>
            <rect x="21" y="8" width="1" height="20" fill="#4b5563"/>
            SVG);
    }

    private function glassWallSprite(): string
    {
        return $this->svg(<<<'SVG'
            <rect x="2" y="8" width="28" height="20" fill="#374151"/>
            <rect x="4" y="10" width="24" height="16" fill="#7dd3fc" opacity="0.45"/>
            <rect x="15" y="10" width="2" height="16" fill="#374151"/>
            SVG);
    }

    // --- Interieur-deuren ---------------------------------------------------

    private function doorSlidingSprite(): string
    {
        return $this->svg(<<<'SVG'
            <ellipse cx="16" cy="29" rx="10" ry="1.6" fill="#000000" opacity="0.25"/>
            <rect x="4" y="6" width="24" height="23" fill="#374151"/>
            <rect x="4" y="6" width="24" height="3" fill="#4b5563"/>
            <rect x="5" y="10" width="10" height="17" fill="#6b7280"/>
            <rect x="17" y="10" width="10" height="17" fill="#9ca3af"/>
            <rect x="15" y="10" width="2" height="17" fill="#1f2937"/>
            <circle cx="14" cy="18" r="1" fill="#1f2937"/>
            <circle cx="18" cy="18" r="1" fill="#1f2937"/>
            SVG);
    }

    private function doorBlastSprite(): string
    {
        return $this->svg(<<<'SVG'
            <ellipse cx="16" cy="29" rx="10" ry="1.8" fill="#000000" opacity="0.3"/>
            <rect x="4" y="4" width="24" height="25" fill="#4b5563"/>
            <rect x="4" y="4" width="24" height="4" fill="#6b7280"/>
            <rect x="4" y="12" width="24" height="3" fill="#f2c227"/>
            <rect x="4" y="12" width="4" height="3" fill="#111111"/>
            <rect x="12" y="12" width="4" height="3" fill="#111111"/>
            <rect x="20" y="12" width="4" height="3" fill="#111111"/>
            <circle cx="16" cy="20" r="3" fill="#1f2937"/>
            <circle cx="16" cy="20" r="1.4" fill="#6b7280"/>
            <rect x="6" y="24" width="20" height="3" fill="#374151"/>
            SVG);
    }

    private function doorCellSprite(): string
    {
        return $this->svg(<<<'SVG'
            <ellipse cx="16" cy="29" rx="10" ry="1.6" fill="#000000" opacity="0.25"/>
            <rect x="5" y="6" width="22" height="23" fill="#27272a"/>
            <rect x="8" y="9" width="2" height="20" fill="#71717a"/>
            <rect x="13" y="9" width="2" height="20" fill="#71717a"/>
            <rect x="18" y="9" width="2" height="20" fill="#71717a"/>
            <rect x="23" y="9" width="2" height="20" fill="#71717a"/>
            <rect x="5" y="9" width="22" height="2" fill="#71717a"/>
            <rect x="5" y="26" width="22" height="2" fill="#71717a"/>
            SVG);
    }

    private function doorKeycardSprite(): string
    {
        return $this->svg(<<<'SVG'
            <ellipse cx="16" cy="29" rx="10" ry="1.6" fill="#000000" opacity="0.25"/>
            <rect x="4" y="6" width="24" height="23" fill="#9ca3af"/>
            <rect x="4" y="6" width="24" height="3" fill="#d1d5db"/>
            <rect x="6" y="11" width="20" height="16" fill="#6b7280"/>
            <rect x="22" y="13" width="5" height="7" fill="#1f2937"/>
            <circle cx="24.5" cy="15" r="0.8" fill="#ef4444"/>
            <circle cx="24.5" cy="17.5" r="0.8" fill="#4ade80"/>
            <rect x="8" y="17" width="2" height="6" fill="#374151"/>
            SVG);
    }

    // --- Interieur-meubilair & props ----------------------------------------

    private function labTableSprite(): string
    {
        return $this->svg(<<<'SVG'
            <ellipse cx="16" cy="27" rx="12" ry="2" fill="#000000" opacity="0.25"/>
            <rect x="4" y="12" width="24" height="4" fill="#cbd5e1"/>
            <rect x="4" y="12" width="24" height="1.5" fill="#f1f5f9"/>
            <rect x="6" y="16" width="2" height="11" fill="#94a3b8"/>
            <rect x="24" y="16" width="2" height="11" fill="#94a3b8"/>
            <rect x="9" y="6" width="6" height="5" fill="#374151"/>
            <circle cx="12" cy="8.5" r="1.6" fill="#4ade80" opacity="0.8"/>
            SVG);
    }

    private function computerTerminalSprite(): string
    {
        return $this->svg(<<<'SVG'
            <ellipse cx="16" cy="27" rx="9" ry="1.8" fill="#000000" opacity="0.25"/>
            <rect x="6" y="18" width="20" height="8" fill="#9ca3af"/>
            <rect x="6" y="18" width="20" height="2" fill="#d1d5db"/>
            <rect x="10" y="6" width="12" height="10" fill="#1f2937"/>
            <rect x="11" y="7" width="10" height="7" fill="#38bdf8" opacity="0.5"/>
            <rect x="14" y="16" width="4" height="3" fill="#374151"/>
            <rect x="9" y="21" width="14" height="1.4" fill="#4b5563"/>
            SVG);
    }

    private function specimenTankSprite(): string
    {
        return $this->svg(<<<'SVG'
            <ellipse cx="16" cy="27" rx="8" ry="1.8" fill="#000000" opacity="0.3"/>
            <rect x="7" y="20" width="18" height="7" fill="#374151"/>
            <rect x="9" y="6" width="14" height="18" rx="3" fill="#34d399" opacity="0.3"/>
            <rect x="9" y="6" width="14" height="18" rx="3" fill="none" stroke="#6ee7b7" stroke-width="1"/>
            <path d="M16 12c-1.6 0-2.4 1.6-2.4 3.2s0.8 4 2.4 4 2.4-2.4 2.4-4-0.8-3.2-2.4-3.2z" fill="#0f172a" opacity="0.7"/>
            <circle cx="16" cy="10" r="1" fill="#a7f3d0"/>
            SVG);
    }

    private function filingCabinetSprite(): string
    {
        return $this->svg(<<<'SVG'
            <ellipse cx="16" cy="29" rx="8" ry="1.6" fill="#000000" opacity="0.3"/>
            <rect x="8" y="6" width="16" height="23" fill="#6b7280"/>
            <rect x="8" y="6" width="16" height="3" fill="#9ca3af"/>
            <rect x="9" y="11" width="14" height="6" fill="#4b5563"/>
            <rect x="9" y="19" width="14" height="6" fill="#4b5563"/>
            <rect x="15" y="13" width="2" height="2" fill="#1f2937"/>
            <rect x="15" y="21" width="2" height="2" fill="#1f2937"/>
            SVG);
    }

    private function stretcherSprite(): string
    {
        return $this->svg(<<<'SVG'
            <ellipse cx="16" cy="26" rx="12" ry="1.6" fill="#000000" opacity="0.25"/>
            <rect x="4" y="14" width="24" height="8" rx="2" fill="#e5e7eb"/>
            <rect x="4" y="14" width="24" height="2" fill="#f9fafb"/>
            <rect x="4" y="20" width="2" height="6" fill="#6b7280"/>
            <rect x="26" y="20" width="2" height="6" fill="#6b7280"/>
            <rect x="4" y="20" width="24" height="1.4" fill="#9ca3af"/>
            <rect x="9" y="16" width="14" height="3" fill="#93c5fd" opacity="0.4"/>
            SVG);
    }

    private function ceilingLightSprite(): string
    {
        return $this->svg(<<<'SVG'
            <rect x="4" y="4" width="24" height="6" fill="#374151"/>
            <rect x="6" y="10" width="20" height="4" fill="#fef3c7"/>
            <rect x="6" y="10" width="20" height="4" fill="#fde68a" opacity="0.6"/>
            <ellipse cx="16" cy="24" rx="14" ry="8" fill="#fde68a" opacity="0.12"/>
            SVG);
    }

    private function alarmLightSprite(): string
    {
        return $this->svg(<<<'SVG'
            <ellipse cx="16" cy="29" rx="4" ry="1.2" fill="#000000" opacity="0.25"/>
            <rect x="13" y="14" width="6" height="15" fill="#374151"/>
            <path d="M11 8a5 5 0 0 1 10 0v6H11z" fill="#ef4444"/>
            <path d="M11 8a5 5 0 0 1 10 0" fill="none" stroke="#fca5a5" stroke-width="1"/>
            <circle cx="16" cy="8" r="8" fill="#ef4444" opacity="0.25"/>
            SVG);
    }

    private function ventGrateSprite(): string
    {
        return $this->svg(<<<'SVG'
            <rect x="4" y="6" width="24" height="20" fill="#4b5563"/>
            <rect x="4" y="6" width="24" height="3" fill="#6b7280"/>
            <rect x="7" y="10" width="18" height="2" fill="#1f2937"/>
            <rect x="7" y="14" width="18" height="2" fill="#1f2937"/>
            <rect x="7" y="18" width="18" height="2" fill="#1f2937"/>
            <rect x="7" y="22" width="18" height="2" fill="#1f2937"/>
            SVG);
    }

    private function pipesSprite(): string
    {
        return $this->svg(<<<'SVG'
            <rect x="4" y="8" width="24" height="4" rx="2" fill="#6b7280"/>
            <rect x="4" y="8" width="24" height="1.4" fill="#9ca3af"/>
            <rect x="4" y="17" width="24" height="4" rx="2" fill="#8a6f2a" opacity="0.85"/>
            <rect x="4" y="17" width="24" height="1.4" fill="#d1a94a"/>
            <rect x="8" y="12" width="3" height="5" fill="#4b5563"/>
            <rect x="20" y="12" width="3" height="5" fill="#4b5563"/>
            SVG);
    }

    private function cellBarsSprite(): string
    {
        return $this->svg(<<<'SVG'
            <ellipse cx="16" cy="29" rx="11" ry="1.8" fill="#000000" opacity="0.25"/>
            <rect x="4" y="24" width="24" height="3" fill="#3f3f46"/>
            <rect x="4" y="4" width="24" height="3" fill="#3f3f46"/>
            <rect x="6" y="4" width="2" height="23" fill="#71717a"/>
            <rect x="12" y="4" width="2" height="23" fill="#71717a"/>
            <rect x="18" y="4" width="2" height="23" fill="#71717a"/>
            <rect x="24" y="4" width="2" height="23" fill="#71717a"/>
            SVG);
    }

    private function whiteboardSprite(): string
    {
        return $this->svg(<<<'SVG'
            <rect x="4" y="6" width="24" height="18" fill="#f8fafc"/>
            <rect x="4" y="6" width="24" height="18" fill="none" stroke="#94a3b8" stroke-width="1.4"/>
            <path d="M8 12 L18 10 M8 16 L20 15 M8 20 L15 19" stroke="#334155" stroke-width="1" opacity="0.7"/>
            <path d="M20 18 L24 12" stroke="#dc2626" stroke-width="1" opacity="0.6"/>
            <rect x="4" y="24" width="24" height="2" fill="#94a3b8"/>
            SVG);
    }

    private function officeChairSprite(): string
    {
        return $this->svg(<<<'SVG'
            <ellipse cx="16" cy="28" rx="7" ry="1.6" fill="#000000" opacity="0.28"/>
            <rect x="12" y="10" width="8" height="10" rx="2" fill="#374151"/>
            <rect x="13" y="20" width="6" height="3" fill="#1f2937"/>
            <rect x="15" y="23" width="2" height="4" fill="#4b5563"/>
            <path d="M10 27 L22 27 M11 25 L16 27 M21 25 L16 27" stroke="#4b5563" stroke-width="1.4"/>
            SVG);
    }

    // --- Exterieur: extra grondtegels ---------------------------------------

    private function parkingLinesSprite(): string
    {
        return $this->svg(<<<'SVG'
            <rect width="32" height="32" fill="#4b4b52"/>
            <rect x="0" y="0" width="32" height="3" fill="#5a5a62"/>
            <rect x="2" y="4" width="2" height="24" fill="#e5e7eb"/>
            <rect x="28" y="4" width="2" height="24" fill="#e5e7eb"/>
            SVG);
    }

    private function oilStainSprite(): string
    {
        return $this->svg(<<<'SVG'
            <rect width="32" height="32" fill="#4b4b52"/>
            <path d="M16 8c5 2 8 7 6 12-2 5-8 6-12 4-4-2-6-7-4-12 1-2 2-3 4-4z" fill="#0f0f12" opacity="0.7"/>
            <path d="M14 12c3 1 4 4 3 6-1 2-4 3-6 2-2-1-3-4-2-6 1-1 3-2 5-2z" fill="#1c1c22" opacity="0.5"/>
            SVG);
    }

    private function saltFlatSprite(): string
    {
        return $this->svg(<<<'SVG'
            <rect width="32" height="32" fill="#e5e1d3"/>
            <rect x="0" y="0" width="32" height="3" fill="#f2efe4"/>
            <path d="M0 10 L10 12 L8 20 L18 18 L16 30" stroke="#c9c3ac" stroke-width="1" fill="none"/>
            <path d="M20 4 L18 14 L28 16" stroke="#c9c3ac" stroke-width="1" fill="none"/>
            SVG);
    }

    private function canyonRockSprite(): string
    {
        return $this->svg(<<<'SVG'
            <rect width="32" height="32" fill="#9a5a3f"/>
            <rect x="0" y="0" width="32" height="4" fill="#b06f4f"/>
            <rect x="0" y="24" width="32" height="8" fill="#7a4530"/>
            <rect x="6" y="8" width="8" height="4" fill="#7a4530"/>
            <rect x="20" y="14" width="6" height="4" fill="#b06f4f"/>
            SVG);
    }

    private function scorchedGroundSprite(): string
    {
        // Same cracked-desert base as crackedGroundSprite(), with a charred
        // patch burned into it — reads as "this spot of ground got
        // scorched", not an unrelated black material next to the sand.
        return $this->svg(<<<'SVG'
            <rect width="32" height="32" fill="#c2985c"/>
            <rect x="0" y="24" width="32" height="8" fill="#a87d47"/>
            <path d="M0 10 L8 12 L14 8 L20 14 L32 12" stroke="#8a6a3a" stroke-width="1" fill="none"/>
            <path d="M6 12 L8 24" stroke="#8a6a3a" stroke-width="1" fill="none"/>
            <path d="M20 14 L18 28" stroke="#8a6a3a" stroke-width="1" fill="none"/>
            <ellipse cx="16" cy="16" rx="13" ry="10" fill="#1a1713" opacity="0.75"/>
            <path d="M4 8 L10 12 L6 18 L16 16 L22 24" stroke="#0f0d0a" stroke-width="1" fill="none" opacity="0.7"/>
            <circle cx="20" cy="10" r="1.4" fill="#ea580c" opacity="0.5"/>
            <circle cx="8" cy="20" r="1" fill="#ea580c" opacity="0.4"/>
            SVG);
    }

    // --- Exterieur: extra objecten -------------------------------------------

    private function planeWreckSprite(): string
    {
        return $this->svg(<<<'SVG'
            <ellipse cx="32" cy="54" rx="28" ry="5" fill="#000000" opacity="0.3"/>
            <path d="M8 40 L48 34 L56 40 L48 46 L8 44Z" fill="#6b7280"/>
            <path d="M8 40 L48 34 L56 40 L48 46 L8 44Z" fill="none" stroke="#374151" stroke-width="1.4"/>
            <path d="M24 34 L30 14 L34 34Z" fill="#4b5563" opacity="0.8"/>
            <path d="M28 46 L24 58 L32 50Z" fill="#4b5563" opacity="0.8"/>
            <circle cx="14" cy="42" r="4" fill="#1f2937"/>
            <path d="M10 42 L4 38 M10 44 L4 48" stroke="#1f2937" stroke-width="1.4"/>
            <rect x="36" y="38" width="6" height="4" fill="#ea580c" opacity="0.7"/>
            SVG, 64);
    }

    private function antennaArraySprite(): string
    {
        return $this->svg(<<<'SVG'
            <ellipse cx="32" cy="58" rx="16" ry="3.6" fill="#000000" opacity="0.28"/>
            <rect x="30" y="20" width="4" height="38" fill="#4b5563"/>
            <path d="M18 30 L30 34 M46 30 L34 34" stroke="#6b7280" stroke-width="1.4"/>
            <path d="M20 20 L30 26 M44 20 L34 26" stroke="#6b7280" stroke-width="1.4"/>
            <rect x="16" y="18" width="4" height="4" fill="#374151"/>
            <rect x="44" y="14" width="4" height="4" fill="#374151"/>
            <circle cx="18" cy="16" r="1.6" fill="#ef4444"/>
            <circle cx="46" cy="12" r="1.6" fill="#ef4444"/>
            <circle cx="32" cy="18" r="1.6" fill="#ef4444"/>
            SVG, 64);
    }

    private function tentCampSprite(): string
    {
        return $this->svg(<<<'SVG'
            <ellipse cx="16" cy="28" rx="12" ry="2" fill="#000000" opacity="0.25"/>
            <path d="M16 5L29 26H3z" fill="#4a5d3a"/>
            <path d="M16 5L23 26h-7z" fill="#39492c"/>
            <path d="M16 5L9 26H3z" fill="#5a6f48"/>
            <rect x="14" y="19" width="4" height="7" fill="#1f1a12"/>
            <rect x="2" y="25" width="27" height="2" fill="#2f3a24"/>
            SVG);
    }

    private function barrelStackSprite(): string
    {
        return $this->svg(<<<'SVG'
            <ellipse cx="16" cy="29" rx="11" ry="2" fill="#000000" opacity="0.3"/>
            <rect x="4" y="16" width="10" height="13" fill="#c9d94a"/>
            <rect x="4" y="16" width="10" height="3" fill="#8a9a3a"/>
            <circle cx="9" cy="22" r="2.6" fill="#181c0f"/>
            <rect x="18" y="14" width="10" height="15" fill="#a8b83a"/>
            <rect x="18" y="14" width="10" height="3" fill="#7a8a2a"/>
            <circle cx="23" cy="21" r="2.6" fill="#181c0f"/>
            <rect x="10" y="4" width="10" height="13" fill="#c9d94a"/>
            <rect x="10" y="4" width="10" height="3" fill="#8a9a3a"/>
            <circle cx="15" cy="10" r="2.4" fill="#181c0f"/>
            SVG);
    }
}
