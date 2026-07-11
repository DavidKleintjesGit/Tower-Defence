# Toren-upgrades — wat er nog moet gebeuren (frontend)

Dit document beschrijft alleen wat er nog **niet** bestaat. Het backend-gedeelte staat er al:

- `App\Support\TowerUpgrades::tiers(TowerType $tower)` — geeft 3 niveaus terug
  (`level`, `damage`, `range_tiles`, `fire_interval`, `upgrade_cost`), berekend als
  multiplier op de basisstats van de toren. Niveau 1 = huidige stats, `upgrade_cost` = 0.
- `App\Livewire\Game\Play.php` en `Sandbox.php` sturen dit al mee als
  `upgrade_tiers` in elk item van `tower_types` (dus beschikbaar in JS via
  `map.tower_types[i].upgrade_tiers`).

Er is dus al data, maar nog **geen UI en geen gameplay-logica** om een geplaatste
toren daadwerkelijk te upgraden. Dat is wat hieronder beschreven staat.

## 1. State: welk niveau heeft een geplaatste toren

In `resources/js/game/index.js`, in `placeTower()` (rond de `towers.push({...})` call),
staat nu geen niveau-veld. Voeg toe:

```js
towers.push({
    // ...bestaande velden...
    upgradeLevel: 1,
});
```

`damage`, `range` en `fireInterval` worden op dit moment één keer bij plaatsing
vanuit `type` gekopieerd en daarna nooit meer bijgewerkt. Bij een upgrade moeten
die drie waarden herberekend worden uit `type.upgrade_tiers[nieuwLevel - 1]`
(let op: `range` in de toren-state is in **pixels** — `range_tiles * map.tile_size`
— terwijl `upgrade_tiers[].range_tiles` in tegels is, dezelfde omrekening als in
`placeTower()` toepassen).

## 2. UI: de "Upgrades — binnenkort" placeholder vervangen

De haak hiervoor bestaat al: `tower-detail-sidebar` in zowel
`resources/views/livewire/game/play.blade.php` als `sandbox.blade.php` heeft een
blok:

```html
<div class="mt-4 border-t border-slate-800 pt-3 text-center text-[10px] uppercase tracking-widest text-slate-600">
    Upgrades &mdash; binnenkort
</div>
```

Vervang dit door, per niveau boven het huidige:
- Niveau-badge/pips (bv. 3 bolletjes, gevulde bolletjes = huidig niveau).
- Als er een volgend niveau is: een knop "Upgrade naar niveau N — $ kosten" met
  de stat-delta's (bv. "Schade 6 → 8.4").
- Als max niveau bereikt (`level === TowerUpgrades::maxLevel()`, al beschikbaar
  als `3` maar liever via een meegestuurd veld dan hardcoded): "Max niveau"
  i.p.v. een knop.

De sidebar wordt gevuld door `showTowerDetail(type)` in `game/index.js` (rond
regel 740 in de huidige versie) — die functie moet ook de geselecteerde `tower`
(niet alleen `type`) doorkrijgen, want de knop moet weten welke toren te
upgraden en wat zijn huidige niveau is. `selectTower(tower)` roept nu
`showTowerDetail(type)` aan zonder de tower zelf mee te geven — geef 'm door
(`showTowerDetail(type, tower)`).

## 3. Interactie: klikken op "Upgrade"

Nieuwe functie, naast `placeTower()`:

```js
function upgradeTower(tower) {
    const type = towerTypes[tower.typeCode];
    const nextLevel = (tower.upgradeLevel ?? 1) + 1;
    const tier = type?.upgrade_tiers?.[nextLevel - 1];

    if (!tier) {
        return; // al op max niveau, of geen tiers beschikbaar
    }

    if (!SANDBOX) {
        if (gold < tier.upgrade_cost) {
            flashInsufficientGold();
            return;
        }

        gold -= tier.upgrade_cost;
        updateGoldDisplay();
    }

    tower.upgradeLevel = nextLevel;
    tower.damage = tier.damage;
    tower.range = tier.range_tiles * map.tile_size;
    tower.fireInterval = tier.fire_interval;

    showTowerDetail(type, tower); // sidebar verversen met nieuwe stats/niveau
}
```

Sandbox mag, net als bij plaatsen (`if (!SANDBOX) { ... gold check ... }`),
gratis upgraden.

## 4. Visuele indicatie op de toren zelf (optioneel, niet blokkerend)

Nog geen ontwerp voor sprite-varianten per niveau — `TowerUpgrades` levert alleen
cijfers, geen sprite-overrides. Simpelste eerste versie: een kleine gekleurde
ring of niveau-cijfer boven de toren tekenen in `drawTower()` als
`tower.upgradeLevel > 1` (vergelijkbaar met de bestaande selectie-ring). Los van
elkaar te bouwen; hoeft niet in dezelfde PR als punt 1–3.

## Volgorde van bouwen

1. State (`upgradeLevel` bij plaatsing) + herberekening bij upgrade — kleine, geïsoleerde wijziging.
2. `showTowerDetail` laten weten welke `tower` (niet alleen `type`) geselecteerd is.
3. Sidebar-HTML + knop.
4. `upgradeTower()` + click-handler op de nieuwe knop.
5. (Optioneel) visuele niveau-indicator op de toren.

Test in de sandbox (`/sandbox`, gratis) eerst — geen gold-logica in de weg tijdens het bouwen.
