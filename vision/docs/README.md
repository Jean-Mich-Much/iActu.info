<div align="center">

### 🇫🇷 Documentation française - [🇬🇧 English version below](#english-version)

**📄 Une documentation complète est disponible ici : https://iactu.info/vision/docs/vision.html**  

</div>

# 🚀 Vision - Natural Data Engine (NDE)

Vision est un **moteur de données naturel (NDE)**.  
Un NDE est une évolution des bases de données traditionnelles : il stocke, lit et relie des données comme une base classique, mais **sans SQL**, **sans schéma**, **sans tables**, et **sans structure imposée**.

### ⚡ Activation Immédiate (Zéro Config)
Vision est conçu pour être "Plug & Play". Il n'y a aucune installation complexe.

```php
// Une seule ligne active tout le moteur (BD, Cache, Verrous)
include "vision/vision.php";
```

Vision repose sur deux fondations :

- **UniCell** - chaque donnée est une cellule autonome stockée dans un fichier `.vr`
- **Wave** - un état global minimal qui coordonne le moteur

Vision ne prédit rien, ne cascade rien automatiquement et ne restructure jamais vos données.  
Il vous laisse **le contrôle total**, tout en restant **stable**, **simple** et **prévisible**.

---

# 1. Introduction

Vision est un moteur de données artisanal pour PHP 8.3+, UTF‑8 LF, sans dépendances.  
Il reconstruit naturellement des structures complexes :

- forums  
- CMS  
- knowledge graphs  
- systèmes de notes  
- présentations  
- données scientifiques  

### UniCell - niveaux de relation

- **0** : latéral  
- **1** : interne directe  
- **2** : interne profonde  
- **3+** : cas rares

### Wave - état global minimal

Chaque module lit Wave et réagit localement.  
Aucune prédiction. Aucune cascade automatique.

---

# 2. Architecture des fichiers

/vision/
 - vision.php              → point d’entrée
 - Vision_UniCell.php      → stockage UniCell (.vr) + sharding
 - Vision_API.php          → API Vision complète
 - vision_wave.php         → état global Wave
 - vision_lock.php         → lock global non bloquant
 - vision_worker.php       → multitâche (Fibers)
 - vision_autoclean.php    → maintenance autonome

/data/
YYYYMMDD/
id_unique.vr    → 1 fichier = 1 cellule

---

# 3. Format UniCell (.vr)

VISION1
⟪¦<base64(json)>¦⟫
⟪Σ¦<crc32_checksum>¦⟫
⟪⇒¦⇐⟫

Exemple JSON interne :

json
{
  "id": "20260416_ab12cd34ef56",
  "data": { ... },
  "relations": [
    {"id": "xxx", "niveau": 1},
    {"id": "yyy", "niveau": 0}
  ],
  "createdAt": 1713200000,
  "updatedAt": 1713200000
}

Propriétés
écriture atomique (.tmp → fsync → rename())
vérification d'intégrité par CRC externe (ultra-rapide)
gestion intelligente de la RAM (Auto-purge à 75%)
format déterministe
UTF‑8 strict

# 4. Sharding
Chaque cellule est stockée dans :


/vision/data/YYYYMMDD/id.vr
Le préfixe de l’ID détermine automatiquement le shard.

# 5. Wave - état global
json
{
  "charge": 0.12,
  "priorite": "read",
  "pression": "low",
  "lock": "free",
  "ram": "stable",
  "disk": "idle",
  "op": "none"
}
Wave est lu par tous les modules.
Aucune orchestration lourde.

# 6. Modules internes
 - Module	 - Rôle
 - vision.php	 - orchestrateur, logs silencieux
 - Vision_UniCell.php	 - stockage .vr, sharding
 - Vision_API.php	 - API publique complète
 - vision_wave.php	 - état global
 - vision_lock.php	 - lock non bloquant
 - vision_worker.php	 - multitâche (Fibers)
 - vision_autoclean.php	 - maintenance autonome

# 7. API Vision (complète)
 - Fonction	 - Description	 - Signature
	 - create	 - créer une cellule	 - create(array $data, array $relations=[]): string
	 - read	 - lire une cellule	 - read(string $id): ?array
	 - update	 - mettre à jour	 - update(string $id, array $data, array $relations=[]): bool
	 - delete	 - supprimer	 - delete(string $id): bool
	 - exists	 - vérifier existence	 - exists(string $id): bool
	 - relations	 - lire relations	 - relations(string $id): array
	 - link	 - créer relation	 - link(string $id, string $targetId, int $niveau): bool
	 - unlink	 - supprimer relation	 - unlink(string $id, string $targetId): bool
	 - graph	 - graphe autour d’une cellule	 - graph(string $id): array
	 - find	 - recherche personnalisée	 - find(callable $filter): \Generator
	 - count	 - nombre total	 - count(): int
	 - stats	 - statistiques	 - stats(): array
	 - clean	 - maintenance	 - clean(): void

# 8. Exemples
 - Forum : 
	 - $topic = Vision_API::create(['title' => 'Bonjour']); 
	 - $post  = Vision_API::create(['text' => 'Salut'], [['id'=>$topic,'niveau'=>1]]); 
	 - $reply = Vision_API::create(['text' => 'Merci'], [['id'=>$post,'niveau'=>1]]); 

 - CMS :
	 - $page = Vision_API::create(['title' => 'Accueil']); 
	 - $section = Vision_API::create(['title'=>'Intro'], [['id'=>$page,'niveau'=>1]]); 

# 9. Sécurité & robustesse
 - Ecriture atomique
 - aucune corruption possible
 - crash‑safe
 - lock non bloquant
 - aucune cascade automatique
 - aucune prédiction
 - logs silencieux

# 10. Limites explicites
 - Vision ne fera jamais :
	 - transactions multi‑cellules
	 - triggers
	 - prédictions
	 - réorganisation globale
	 - analyse du contenu
	 - index secondaires automatiques
	 - logique métier

# 11. Pourquoi Vision ?
 - Comparé à SQL :
	 - pas de tables
	 - pas de schéma
	 - pas de migrations
	 - pas de jointures complexes

 - Comparé à un document store :
	 - cellules autonomes
	 - relations natives
	 - sharding naturel

 - Comparé à une base graphe :
	 - relations simples
	 - navigation multi‑shards
	 - résistance aux cycles

 - Ce que Vision apporte :
	 - simplicité
	 - robustesse
	 - prévisibilité
	 - lisibilité humaine
	 - zéro dépendance

<a name="english-version"><div align="center">🇬🇧 English Version</div></a>

**📄 A complete English documentation is available here: https://iactu.info/vision/docs/vision_english.html**

🚀 Vision - Natural Data Engine (NDE)
Vision is a Natural Data Engine (NDE).
An NDE is an evolution of traditional databases: it stores, reads and links data like a classic DB, but without SQL, without schema, without tables, and without imposed structure.

### ⚡ Immediate Activation (Zero Config)
Vision is designed to be "Plug & Play". There is no complex installation.

```php
// A single line activates the entire engine (DB, Cache, Locks)
include "vision/vision.php";
```

Vision is built on two foundations:
UniCell - each piece of data is an autonomous cell stored in a .vr file
Wave - a minimal global state coordinating the engine

Vision does not predict, cascade or restructure anything automatically.
It gives developers full control, while remaining stable, simple and predictable.

# 1. Introduction
Vision is a lightweight data engine for PHP 8.3+, UTF‑8 LF, with zero dependencies.
It naturally reconstructs complex structures:
forums
CMS
knowledge graphs
note systems
presentations
scientific datasets

UniCell - relation levels
0: lateral
1: direct internal
2: deep internal
3+: rare cases

Wave - minimal global state
Modules read Wave and react locally.
No prediction. No automatic cascade.

# 2. File Architecture

/vision/
  -  vision.php
   - Vision_UniCell.php
   - Vision_API.php
   - vision_wave.php
   - vision_lock.php
   - vision_worker.php
   - vision_autoclean.php

    /data/
        YYYYMMDD/
            id_unique.vr

# 3. UniCell - .vr Format

VISION1
⟪¦<base64(json)>¦⟫
⟪Σ¦<crc32_checksum>¦⟫
⟪⇒¦⇐⟫

Example JSON:

{
  "id": "20260416_ab12cd34ef56",
  "data": { ... },
  "relations": [
    {"id": "xxx", "niveau": 1},
    {"id": "yyy", "niveau": 0}
  ],
  "createdAt": 1713200000,
  "updatedAt": 1713200000
}

# 4. Sharding

/vision/data/YYYYMMDD/id.vr

# 5. Wave - Global State
json
{
  "charge": 0.12,
  "priority": "read",
  "pressure": "low",
  "lock": "free",
  "ram": "stable",
  "disk": "idle",
  "op": "none"
}

# 6. Internal Modules
 - Module	 - Role
 - vision.php	 - entry point
 - Vision_UniCell.php	 - .vr storage
 - Vision_API.php	 - full API
 - vision_wave.php	 - global state
 - vision_lock.php	 - non‑blocking lock
 - vision_worker.php	 - async tasks
 - vision_autoclean.php	 - maintenance

# 7. Vision API (Complete)
 - Function	 - Description	 - Signature
	 - create	 - create a cell	 - create(array $data, array $relations=[]): string
	 - read	 - read a cell	 - read(string $id): ?array
	 - update	 - update a cell	 - update(string $id, array $data, array $relations=[]): bool
	 - delete	 - delete a cell	 - delete(string $id): bool
	 - exists	 - check existence	 - exists(string $id): bool
	 - relations	 - get relations	 - relations(string $id): array
	 - link	 - create relation	 - link(string $id, string $targetId, int $niveau): bool
	 - unlink	 - remove relation	 - unlink(string $id, string $targetId): bool
	 - graph	 - build graph	 - graph(string $id): array
	 - find	 - custom search	 - find(callable $filter): \Generator
	 - count	 - total cells	 - count(): int
	 - stats	 - global stats	 - stats(): array
	 - clean	 - maintenance	 - clean(): void

# 8. Usage Examples
 - Forum : 
	 - $topic = Vision_API::create(['title' => 'Hello']); 
	 - $post  = Vision_API::create(['text' => 'Hi'], [['id'=>$topic,'niveau'=>1]]); 
	 - $reply = Vision_API::create(['text' => 'Thanks'], [['id'=>$post,'niveau'=>1]]); 

 - CMS : 
	 - $page = Vision_API::create(['title' => 'Home']); 
	 - $section = Vision_API::create(['title'=>'Intro'], [['id'=>$page,'niveau'=>1]]); 

 - Knowledge Graph : 
	 - $paris = Vision_API::create(['name'=>'Paris']); 
	 - $france = Vision_API::create(['name'=>'France']); 
	 - Vision_API::link($paris, $france, 1); 

# 9. Security & Robustness
 - atomic writes
 - external CRC integrity check
 - smart memory protection (75% threshold)
 - crash‑safe (fsync + rename)
 - non‑blocking lock
 - no automatic cascade
 - no prediction
 - silent logs

# 10. Explicit Limitations
 - Vision will never implement :
	 - multi‑cell transactions
	 - triggers
	 - prediction
	 - global reorganization
	 - content analysis
	 - automatic secondary indexes
	 - business logic

# 11. Why Vision?
 - Compared to SQL :
	 - no tables
	 - no schema
	 - no migrations
	 - no joins

 - Compared to document stores :
	 - autonomous cells
	 - native relations
	 - natural sharding

 - Compared to graph databases :
	 - simple relation levels
	 - multi‑shard navigation
	 - cycle‑resistant

 - What Vision provides :
	 - simplicity
	 - robustness
	 - predictability
	 - human‑readable structure
	 - zero dependencies
