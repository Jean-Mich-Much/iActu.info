# 🪧 iactu.info — Version 18  
**Date de publication : 26 mars 2026**

Code source complet de la **version 18** du site **iactu.info**.

## 🚀 Technologies utilisées
- **PHP 8.3.6**
- **HTML5**
- **CSS3**
- **Ubuntu 24.04 LTS**
- **Serveur Caddy**

## 📚 Documentation
Une documentation complète, détaillée et normalisée (UTF‑8 LF) est disponible dans :
Fondation/doc/


Elle couvre :
- l’architecture générale du projet  
- les scripts CLI et leur logique interne  
- les flux RSS et le fonctionnement de `fusion_rss.php`  
- les conventions de nommage et de structure  
- les procédures de mise à jour  
- les dépendances et prérequis système  
- les bonnes pratiques Linux appliquées au projet  

## 🧩 Structure générale du projet
- `index.php` — point d’entrée du site  
- `jeux.php` — gestion des modules et logique interne  
- `maj_cli.php` — mises à jour automatisées et tâches programmées  
- `fusion_rss.php` — moteur de fusion et normalisation des flux RSS  
- `assets/` — ressources statiques (CSS, images, etc.)  
- `Fondation/` — cœur technique, librairies internes, documentation  

## 🔧 Environnement recommandé
- Serveur Linux (Ubuntu 24.04 ou équivalent)  
- PHP 8.3.x avec extensions standard 
- Serveur web **Caddy** configuré en HTTPS automatique  
- Cron ou systemd timers pour les tâches CLI  

## 📝 Notes importantes
- Le projet respecte une logique stricte
- Les scripts CLI sont conçus pour fonctionner en environnement Linux uniquement  
- Le code est organisé pour être lisible, stable et facilement maintenable  

## 🧑‍💻 Auteur & Collaboration
Projet développé avec passion, rigueur et humour par **Jean‑Michel G - Bordeaux - France.**, artisan du code propre et cohérent.

📁 Arborescence du projet
/
├── Page_technologie.php
├── Page_Apple.php
├── Page_jeux.php
├── Page_sciences.php
├── Page_actualités.php
│
└── Fondation/
    ├── cache/
    │   ├── rss/
    │   └── rss/fusion/
    │
    ├── css/
    │   ├── base.css
    │   ├── mid.css
    │   ├── menu.css
    │   ├── messages.css
    │   └── fonts.css
    │
    ├── doc/
    │   ├── index.html
    │   ├── lit_rss.html
    │   ├── fusion_rss.html
    │   ├── job.html
    │   ├── maj_cli.html
    │   ├── maj.html
    │   ├── affiche_html.html
    │   └── pages.html
    │
    ├── fonts/
    │   ├── inter_01.woff2
    │   ├── inter_02.woff2
    │   └── Twemoji.woff2
    │
    ├── logs/
    │   ├── job.log
    │   ├── fusion.log
    │   └── affiche_html.log
    │
    ├── php/
    │   ├── job.php
    │   ├── fusion_rss.php
    │   ├── parser/
    │   │   └── lit_rss.php
    │   ├── affiche_html.php
    │   ├── menu.php
    │   ├── messages_top.php
    │   └── messages_bot.php
    │
    └── sites/
        ├── technologie.php
        ├── apple.php
        ├── jeux.php
        ├── sciences.php
        └── actualités.php


Contributions bienvenues via issues ou pull requests.

---


