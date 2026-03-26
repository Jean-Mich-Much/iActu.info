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
Une documentation complète, détaillée et normalisée est disponible dans :
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
- `Page_*.php` — pages des news, gestion des modules et logique interne  
- `maj_cli.php` — mises à jour automatisées et tâches programmées  
- `lit_rss.php` — parser des flux RSS/Atom  
- `fusion_rss.php` — moteur de fusion et normalisation des flux RSS  
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
Projet développé avec passion, rigueur et humour par **Jean‑Michel G - Bordeaux - France.**, artisan du code 😊

Contributions bienvenues via issues ou pull requests.

---


