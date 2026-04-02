# 🚀 Vroom: the reinvented PHP database

Vroom is a handcrafted PHP storage engine designed to be simple, fast, readable and unbreakable.  
It is built on a clear principle:

> 1 record = 1 file, with a RAM‑centered, multitasking engine and 100% atomic disk writes.

Vroom is ideal for projects that want to avoid the complexity of an SQL database while keeping:
- high performance  
- maximum robustness  
- ultra‑light deployment  
- an architecture that is easy to understand and modify  

---

## 📘 Full documentation

The complete HTML documentation is available here:  
Fondation/doc/Vroom.html

It contains:
- detailed internal architecture  
- the role of each file  
- the description of every internal function  
- the full API  
- concrete examples  
- RAM ↔ disk mechanisms  
- lock, transaction, worker and relation management  

---

## 🧩 General operation

Vroom combines two layers:

### 1. Vroom DB
- physical storage in `.vrec` files  
- solid ASCII header  
- data encoded in Base64  
- final marker for integrity checks  
- automatic distribution into subfolders  

### 2. Vroom Engine
- RAM buffers  
- transactions  
- atomic write  
- physical locks  
- parallel workers  
- job queue  
- RAM cache with LRU eviction  

---

## ⚙️ Quick API

```php
$id = create('post', ['title' => 'Hello']);
$post = get('post', $id);
update('post', $id, ['title' => 'Updated']);
delete('post', $id);

begin();
create('log', ['msg' => 'A']);
commit();
```

## 📂 Project structure

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

## 🛡️ Security and robustness

- strict UTF‑8  
- LF only  
- Base64 for data  
- ASCII header  
- atomic write  
- simple physical locks  
- automatic cleanup  
- final marker

## 📜 Purpose

Vroom does not aim to replace a full SQL database.  
It offers an ultra‑simple, ultra‑reliable and ultra‑fast alternative for projects that want to move quickly, stay lightweight and keep full control over their data.

## 👤 Author

Jean‑Michel G — Bordeaux, France  
iactu.info — since 2003
