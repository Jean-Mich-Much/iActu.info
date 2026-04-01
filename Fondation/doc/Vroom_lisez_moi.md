# 🚀 Vroom : la base de données PHP réinventée

Vroom est un moteur de stockage artisanal en PHP, conçu pour être **simple**, **rapide**, **lisible** et **incassable**.  
Il repose sur un principe clair :

> **1 enregistrement = 1 fichier**, avec un moteur RAM‑centré, multitâche, et une écriture disque 100% atomique.

Vroom est idéal pour les projets qui veulent éviter la complexité d’une base SQL tout en conservant :
- des performances élevées  
- une robustesse maximale  
- un déploiement ultra‑léger  
- une architecture simple à comprendre et à modifier  

---

## 📘 Documentation complète

La documentation HTML complète se trouve ici :
Fondation/doc/Vroom.html


Elle contient :
- l’architecture interne détaillée  
- le rôle de chaque fichier  
- la description de chaque fonction interne  
- l’API complète  
- des exemples concrets  
- les mécanismes RAM ↔ disque  
- la gestion des locks, transactions, workers, relations, etc.  

---

## 🧩 Fonctionnement général

Vroom combine deux couches :

### **1. Vroom BD**
- stockage physique en fichiers `.vrec`  
- header ASCII solide  
- données encodées en Base64  
- marqueur final pour vérifier l’intégrité  
- répartition automatique dans des sous‑dossiers  

### **2. Vroom Moteur**
- buffers RAM  
- transactions  
- écriture atomique  
- locks physiques  
- workers parallèles  
- file d’attente  
- cache RAM avec éviction LRU  

---

## ⚙️ API rapide

```php
$id = create('post', ['title' => 'Hello']);
$post = get('post', $id);
update('post', $id, ['title' => 'Modifié']);
delete('post', $id);

begin();
create('log', ['msg' => 'A']);
commit();
```

## 📂 Structure du projet

```text
Vroom.php
Fondation/
 └── vroom/
     ├── data/
     ├── logs/
     └── php/
         ├── Vroom_state.php
         ├── Vroom_id.php
         ├── Vroom_lock.php
         ├── Vroom_disk_read.php
         ├── Vroom_disk_do.php
         ├── Vroom_ram.php
         ├── Vroom_exec.php
         ├── Vroom_job.php
         └── Vroom_relations.php
```

## 🛡️ Sécurité & robustesse

- UTF‑8 strict  
- LF uniquement  
- Base64 pour les données  
- header ASCII  
- écriture atomique  
- locks physiques simples  
- nettoyage automatique  
- marqueur final

## 📜 Objectif

Vroom ne cherche pas à remplacer une base SQL complète.  
Il propose une alternative **ultra‑simple**, **ultra‑fiable** et **ultra‑performante** pour les projets qui veulent aller vite, rester légers, et garder le contrôle total sur leurs données.

## 👤 Auteur

Jean‑Michel G — Bordeaux, France  
iactu.info — depuis 2003
