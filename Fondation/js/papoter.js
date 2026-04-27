const container = document.getElementById("messages-container"),
 input = document.getElementById("chat-input"),
 sendBtn = document.getElementById("send-btn"),
 pseudoInput = document.getElementById("pseudo-input"),
 updatePseudoBtn = document.getElementById("update-pseudo"),
 charCounter = document.getElementById("char-counter"),
 cancelEditBtn = document.getElementById("cancel-edit-btn"),
 startTime = Math.floor(Date.now() / 1e3);
let lastMessageIds = new Set(),
 deletedIds = new Set(),
 isFetching = !1,
 lastSyncToken = "",
 editingMsgId = null,
 currentSubject = "",
 currentSearch = "",
 isTabActive = true,
 lastStableValue = pseudoInput.value,
 replyingToId = null,
 replyingToPseudo = null,
 historyStack = [input.innerHTML],
 redoStack = [];
const MAX_CHARS = 8192,
 PAGE_SIZE = 1024;
const subjects = [
 { id: "Papoter", emoji: "🗨️ ", label: "Papoter" },
 { id: "Technologies", emoji: "🤖 ", label: "Technologies" },
 { id: "Apple", emoji: "🍏 ", label: "Apple" },
 { id: "Jeux", emoji: "🕹️ ", label: "Jeux" },
 { id: "Sciences", emoji: "🧪 ", label: "Sciences" },
 { id: "Actualités", emoji: "🗞️ ", label: "Actualités" },
];
function saveState() {
 let h = input.innerHTML;
 if (historyStack[historyStack.length - 1] !== h) {
  historyStack.push(h);
  if (historyStack.length > 50) historyStack.shift();
  redoStack = [];
 }
}
async function fetchMessages(force = false) {
 if (isFetching && !force) return;
 isFetching = true;
 try {
  const syncParam = force ? "" : lastSyncToken;
  let e = await fetch(
    `Papoter.php?action=get_messages&subject=${encodeURIComponent(currentSubject)}&search=${encodeURIComponent(currentSearch)}&sync=${syncParam}${force ? "&force=1" : ""}`,
   ),
   t = await e.json(),
   sync = t.sync || "";
  if (t.no_change && !force) return;
  const myFp = document.getElementById("fp-ref").value;
  lastSyncToken = sync;
  const a = t.messages || [],
   s = t.user,
   conn = t.connected || [];
  if (s.isAdmin !== IS_ADMIN || s.isModo !== IS_MODO) {
   location.reload();
   return;
  }
  if (s.isBanned)
   ((input.innerHTML = ""),
    (input.contentEditable = "false"),
    input.setAttribute("data-placeholder", s.banMsg),
    (sendBtn.style.opacity = "0.5"),
    (sendBtn.style.pointerEvents = "none"));
  else {
   input.contentEditable = "true";
   let n = input.getAttribute("data-placeholder") || "";
   ((!n || n.startsWith("Vous \xeates banni")) &&
    input.setAttribute(
     "data-placeholder",
     "\xc9crire un message... \uD83D\uDCAC",
    ),
    (sendBtn.style.opacity = "1"),
    (sendBtn.style.pointerEvents = "auto"));
  }
  updateIdUI(s.shortId, s.nextId);
  const serverIds = new Set(a.map((e) => e.id));
  lastMessageIds.forEach((e) => {
   const isDeleted = deletedIds.has(e),
    notOnServer = !serverIds.has(e);
   if (notOnServer || isDeleted) {
    document.querySelector(`.message-bubble[data-id="${e}"]`)?.remove();
    if (notOnServer) {
     lastMessageIds.delete(e);
     deletedIds.delete(e);
    }
   }
  });
  let domIndex = 0;
  a.forEach((e) => {
   if (deletedIds.has(e.id)) return;
   let msgEl = document.querySelector(`.message-bubble[data-id="${e.id}"]`);
   if (!msgEl) {
    msgEl = createMessageElement(e, s.shortId);
    lastMessageIds.add(e.id);
   } else {
    const hasChanged =
     msgEl.dataset.text !== (e.data.text || "") ||
     msgEl.dataset.subject !== (e.data.subject || "Papoter") ||
     msgEl.dataset.pinned !== String(!!e.data.is_pinned) ||
     msgEl.dataset.pseudo !== (e.data.pseudo || "") ||
     msgEl.dataset.emoji !== (e.data.emoji || "") ||
     msgEl.dataset.updatedAt !== String(e.updatedAt || e.createdAt);
    if (editingMsgId !== e.id && hasChanged) {
     const updated = createMessageElement(e, s.shortId);
     updated.classList.add("just-updated");
     msgEl.replaceWith(updated);
     msgEl = updated;
    }
   }
   if (container.children[domIndex] !== msgEl)
    container.insertBefore(msgEl, container.children[domIndex]);
   domIndex++;
  });
  updateConnectedList(conn);
 } catch (r) {
 } finally {
  isFetching = false;
 }
}
function updateIdUI(shortId, nextId) {
 const zone = document.getElementById("id-display-zone");
 const pseudoInput = document.getElementById("pseudo-input");
 const hint = document.getElementById("pseudo-hint");
 if (!zone) return;
 if (shortId) {
  zone.innerHTML = `<span class="my-id-tag">ID: ${shortId}</span>`;
  if (document.activeElement !== pseudoInput) {
   pseudoInput.value = pseudoInput.value.split("#")[0].trim();
   hint.textContent = shortId;
   hint.classList.add("active-id");
   hint.style.display = "block";
   hint.style.opacity = "1";
  }
 } else {
  const hasCopied = localStorage.getItem("papoter_id_copied");
  zone.innerHTML = `
   <div class="copy-wrapper">
    <button type="button" class="copy-id-btn" onclick="reserveAndCopyId('${nextId}')">Copier l'identifiant ${nextId}</button>
    ${
     !hasCopied
      ? `
    <div class="id-tooltip-postit">
     📌 <b>C'est votre clé !</b><br>
     Copiez cet identifiant pour retrouver votre pseudo et vos messages sur un autre appareil ou après avoir vidé votre cache.
    </div>`
      : ""
    }
   </div>`;
  if (document.activeElement !== pseudoInput) {
   hint.textContent = "Coller ID ou changer pseudo ou emoji";
   hint.classList.remove("active-id");
   hint.style.display = "block";
   hint.style.opacity = "0.5";
  }
 }
}
async function reserveAndCopyId(id) {
 let r = await fetch("Papoter.php?action=reserve_id", { method: "POST" });
 let d = await r.json();
 if (d.status === "ok") {
  navigator.clipboard.writeText(d.shortId);
  localStorage.setItem("papoter_id_copied", "1");
  alert(
   "Identifiant " +
    d.shortId +
    " copié et réservé ! Gardez-le précieusement pour retrouver votre compte.",
  );
  fetchMessages(true);
 }
}
function updateSubjectListUI() {
 const list = document.getElementById("subjects-list");
 if (!list) return;
 list.innerHTML = "";

 const all = document.createElement("div");
 all.className = "subject-item" + (currentSubject === "" ? " active" : "");
 all.textContent = "🌍 Tous";
 all.onclick = () => selectSubject("");
 list.appendChild(all);
 subjects.forEach((s) => {
  const d = document.createElement("div");
  d.className = "subject-item" + (currentSubject === s.id ? " active" : "");
  d.textContent = s.emoji + " " + s.label;
  d.onclick = () => selectSubject(s.id);
  list.appendChild(d);
 });

 const searchWrap = document.createElement("div");
 searchWrap.className = "search-wrapper";
 const searchInp = document.createElement("input");
 searchInp.type = "text";
 searchInp.placeholder = "🔍 Chercher...";
 searchInp.value = currentSearch;
 searchInp.oninput = (e) => {
  currentSearch = e.target.value;
  fetchMessages(true);
 };
 searchWrap.appendChild(searchInp);
 list.appendChild(searchWrap);
}
function selectSubject(s) {
 if (currentSubject === s) return;
 currentSubject = s;
 lastMessageIds.clear();
 container.innerHTML = "";
 updateSubjectListUI();
 fetchMessages(true);
}
function updateConnectedList(users) {
 const list = document.getElementById("connected-list");
 if (!list) return;
 list.innerHTML = "";
 users.forEach((u) => {
  const div = document.createElement("div");
  div.className = "connected-user";
  if (u.isAdmin || u.short_id === "#1") div.classList.add("iActu-gold");
  else if (u.isModo) div.classList.add("iActu-silver");
  const displayPseudo = (u.pseudo || "Anonyme").split("#")[0].trim();
  div.textContent = (u.emoji || "😊") + " " + displayPseudo;
  list.appendChild(div);
 });
}
function createMessageElement(e, myShortId) {
 let fp = document.getElementById("fp-ref").value;
 let isOwned =
  (myShortId && e.data.short_id === myShortId) ||
  (!myShortId && e.data.user_hash === fp);
 let t = document.createElement("div");
 t.className = "message-bubble";
 t.dataset.id = e.id;

 t.dataset.text = e.data.text || "";
 t.dataset.subject = e.data.subject || "Papoter";
 t.dataset.pinned = String(!!e.data.is_pinned);
 t.dataset.pseudo = e.data.pseudo || "";
 t.dataset.emoji = e.data.emoji || "";
 t.dataset.updatedAt = String(e.updatedAt || e.createdAt);

 if (e.data.is_pinned) {
  t.classList.add("is-pinned");
  let pinIco = document.createElement("span");
  pinIco.className = "pin-indicator";
  pinIco.innerHTML = "📌";
  t.appendChild(pinIco);
 }

 if (isOwned) {
  t.classList.add("is-me");
 }
 if (!e.data.emoji) e.data.emoji = "😊";
 if (!e.data.subject) e.data.subject = "Papoter";
 let header = document.createElement("div");
 header.className = "message-header";
 let a = document.createElement("span");
 const displayPseudo = (e.data.pseudo || "Anonyme").split("#")[0].trim();
 ((a.className = "author"),
  (e.data.short_id === "#1" || displayPseudo === "iActu") &&
   a.classList.add("iActu-gold"),
  (a.textContent = (e.data.emoji || "\uD83D\uDE0A") + " " + displayPseudo));
 header.appendChild(a);

 if (e.data.parent_id || e.data.parent_pseudo) {
  let parentPseudo = e.data.parent_pseudo || "quelqu'un";
  let replyRef = document.createElement("div");
  replyRef.className = "reply-reference";
  replyRef.innerHTML = `↳ @<b>${parentPseudo}</b>`;
  replyRef.onclick = () => {
   const el = document.querySelector(
    `.message-bubble[data-id="${e.data.parent_id}"]`,
   );
   if (el) el.scrollIntoView({ behavior: "smooth", block: "center" });
  };
  t.appendChild(replyRef);
 }
 let controlsWrap = document.createElement("div");
 controlsWrap.className = "controls-wrapper";
 let toggle = document.createElement("span");
 toggle.className = "msg-options-toggle";
 toggle.textContent = "«";
 let controls = document.createElement("div");
 controls.className = "message-controls hidden";

 let replyBtn = document.createElement("span");
 replyBtn.innerHTML = "💬";
 replyBtn.title = "Répondre à ce message";
 replyBtn.onclick = (event) => {
  event.stopPropagation();
  setReplyMode(e.id, displayPseudo);
 };
 controls.appendChild(replyBtn);
 const isTargetAdmin = e.data.short_id === "#1";
 if (IS_ADMIN || isOwned || (IS_MODO && !isTargetAdmin)) {
  let sel = document.createElement("select");
  sel.className = "msg-subject-select";
  let defaultOpt = document.createElement("option");
  defaultOpt.textContent = "📚 Catégories";
  sel.appendChild(defaultOpt);
  subjects.forEach((s) => {
   let opt = document.createElement("option");
   opt.value = s.id;
   opt.textContent = s.emoji + " " + s.label;
   if ((e.data.subject || "Papoter") === s.id) opt.selected = true;
   sel.appendChild(opt);
  });
  sel.onchange = async () => {
   let fd = new FormData();
   fd.append("msg_id", e.id);
   fd.append("subject", sel.value);
   await fetch("Papoter.php?action=update_msg_subject", {
    method: "POST",
    body: fd,
   });
   fetchMessages(true);
  };
  controls.appendChild(sel);

  let editBtn = document.createElement("span");
  editBtn.className = "edit-msg-btn";
  editBtn.innerHTML = "✏️";
  editBtn.title = "Modifier ce message";
  editBtn.onclick = (event) => {
   event.stopPropagation();
   startEdit(e.id, e.data.text);
  };
  controls.appendChild(editBtn);

  let n = document.createElement("span");
  n.className = "delete-msg";
  n.innerHTML = "&times;";
  n.title = "Supprimer le message";
  n.onclick = (ev) => {
   ev.stopPropagation();
   deleteMessage(e.id);
  };
  controls.appendChild(n);

  if (IS_ADMIN || (IS_MODO && !isTargetAdmin)) {
   let s = document.createElement("span");
   s.className = "ban-user-btn";
   s.innerHTML = "🚫";
   s.title = `Bannir ${e.data.pseudo} (24h)`;
   s.onclick = (ev) => {
    ev.stopPropagation();
    banUser(e.id, e.data.pseudo);
   };
   controls.appendChild(s);

   let p = document.createElement("span");
   p.className = "pin-msg-btn";
   p.innerHTML = e.data.is_pinned ? "📍" : "📌";
   p.title = e.data.is_pinned ? "Désépingler" : "Épingler en haut";
   p.onclick = async (ev) => {
    ev.stopPropagation();
    await fetch(`Papoter.php?action=toggle_pin&msg_id=${e.id}`);
    fetchMessages(true);
   };
   controls.appendChild(p);
  }
 }
 let closeTimer;
 const closeMenu = () => {
  controls.classList.add("hidden");
  toggle.textContent = "«";
 };
 toggle.onclick = (ev) => {
  ev.stopPropagation();
  const isHidden = controls.classList.toggle("hidden");
  toggle.textContent = isHidden ? "«" : "»";
  if (!isHidden) {
   clearTimeout(closeTimer);
   closeTimer = setTimeout(closeMenu, 5000);
  }
 };
 controlsWrap.onmouseenter = () => clearTimeout(closeTimer);
 controlsWrap.onmouseleave = () => {
  if (!controls.classList.contains("hidden")) {
   closeTimer = setTimeout(closeMenu, 5000);
  }
 };
 controlsWrap.appendChild(toggle);
 controlsWrap.appendChild(controls);
 header.appendChild(controlsWrap);
 t.appendChild(header);
 let l = e.data.text || "",
  r = [],
  o = l;
 for (; o.length > PAGE_SIZE; ) {
  let i = PAGE_SIZE,
   d = o.lastIndexOf(" ", PAGE_SIZE),
   c = o.lastIndexOf(">", PAGE_SIZE),
   p = Math.max(d, c);
  (p > 0 && (i = p + 1), r.push(o.substring(0, i)), (o = o.substring(i)));
 }
 if (
  (o.length > 0 && r.push(o),
  r.forEach((e, a) => {
   let s = document.createElement("div");
   ((s.className = `msg-content-page ${0 === a ? "active" : ""}`),
    (s.dataset.page = a),
    (s.innerHTML = e),
    t.appendChild(s));
  }),
  r.length > 1)
 ) {
  let u = document.createElement("div");
  ((u.className = "msg-pagination"),
   r.forEach((e, a) => {
    let s = document.createElement("span");
    ((s.className = `page-btn ${0 === a ? "active" : ""}`),
     (s.textContent = a + 1),
     (s.onclick = () => {
      (t
       .querySelectorAll(".msg-content-page")
       .forEach((e) => e.classList.remove("active")),
       t
        .querySelectorAll(".page-btn")
        .forEach((e) => e.classList.remove("active")),
       t
        .querySelector(`.msg-content-page[data-page="${a}"]`)
        .classList.add("active"),
       s.classList.add("active"));
     }),
     u.appendChild(s));
   }),
   t.appendChild(u));
 }

 const formatDate = (ts) => {
  const months = ["jan.", "fév.", "mars", "avril", "mai", "juin", "jul.", "août", "sep.", "oct.", "nov.", "déc."];
  const d = new Date(ts * 1000);
  return d.getDate() + " " + months[d.getMonth()] + " " + d.getFullYear() + " à " + 
         String(d.getHours()).padStart(2, '0') + ":" + String(d.getMinutes()).padStart(2, '0');
 };

 let dateDiv = document.createElement("div");
 dateDiv.className = "message-date";
 let dateStr = "⏰ Publié le " + formatDate(e.createdAt);
 if (e.updatedAt && e.updatedAt > e.createdAt + 1) {
  dateStr += " - Modifié le " + formatDate(e.updatedAt);
 }
 dateDiv.textContent = dateStr;
 t.appendChild(dateDiv);

 return t;
}
function startEdit(id, text) {
 editingMsgId = id;
 input.innerHTML = text;
 input.focus();
 sendBtn.classList.add("edit-mode");
 cancelEditBtn.style.display = "inline-block";
 let range = document.createRange();
 let sel = window.getSelection();
 range.selectNodeContents(input);
 range.collapse(false);
 sel.removeAllRanges();
 sel.addRange(range);
 input.scrollIntoView({ behavior: "smooth", block: "center" });
}
function setReplyMode(id, pseudo) {
 replyingToId = id;
 replyingToPseudo = pseudo;
 const info = document.getElementById("reply-info");
 info.innerHTML = `En réponse à <b>${pseudo}</b> <span onclick="cancelReply()" style="cursor:pointer">✖</span>`;
 info.style.display = "block";
 input.focus();
}
function cancelReply() {
 replyingToId = null;
 replyingToPseudo = null;
 document.getElementById("reply-info").style.display = "none";
}
function cancelEdit() {
 editingMsgId = null;
 input.innerHTML = "";
 sendBtn.classList.remove("edit-mode");
 cancelEditBtn.style.display = "none";
 charCounter.textContent = "8192 caractères restants";
}
async function sendMessage() {
 let e = input.innerHTML.trim();
 if (
  (("<br>" === e || "<div><br></div>" === e || "<p><br></p>" === e) && (e = ""),
  !e || 0 === e.length)
 )
  return;
 if (editingMsgId) {
  let m = document.querySelector(`.message-bubble[data-id="${editingMsgId}"]`);
  if (m) {
   m.dataset.text = e;
   let c = m.querySelector(".msg-content-page.active");
   if (c) c.innerHTML = e;
  }
 }

 let t = document.getElementById("fp-ref").value,
  a = document.querySelector('input[name="valid_email_check"]').value,
  s = btoa(t).split("").reverse().join(""),
  n = new FormData();

 (n.append("text", e),
  n.append("valid_email_check", a),
  n.append("ts_load", startTime),
  n.append("pow", s),
  n.append("subject", currentSubject));

 if (editingMsgId) n.append("msg_id", editingMsgId);
 if (replyingToId) n.append("parent_id", replyingToId);
 if (replyingToPseudo) n.append("parent_pseudo", replyingToPseudo);

 const oldHTML = input.innerHTML;
 const action = editingMsgId ? "edit_msg" : "send";

 ((input.innerHTML = ""),
  (charCounter.textContent = "8192 caract\xe8res restants"),
  (charCounter.style.color = ""),
  (sendBtn.disabled = !0),
  (sendBtn.style.opacity = "0.5"));
 try {
  let l = await fetch("Papoter.php?action=" + action, {
   method: "POST",
   body: n,
  });
  if (l.ok) {
   let r = await l.json();
   if ("error" === r.status) {
    alert(r.msg);
    input.innerHTML = oldHTML;
    input.dispatchEvent(new Event("input"));
   } else {
    input.focus();
    if (editingMsgId) cancelEdit();
    if (replyingToId) cancelReply();
    historyStack = [input.innerHTML];
    redoStack = [];
   }
   ("admin_promoted" === r.status || "admin_revoked" === r.status) &&
    (r.msg && alert(r.msg), location.reload());
  } else {
   console.error("Erreur serveur : " + l.status);
   input.innerHTML = oldHTML;
   input.dispatchEvent(new Event("input"));
  }
 } catch (o) {
  console.error("R\xe9ponse invalide du serveur ou timeout");
  input.innerHTML = oldHTML;
  input.dispatchEvent(new Event("input"));
 } finally {
  ((sendBtn.disabled = !1), (sendBtn.style.opacity = "1"));
 }
 fetchMessages(true);
}
async function deleteMessage(e) {
 confirm("Supprimer ce message ?") &&
  (deletedIds.add(String(e)),
  document.querySelector(`.message-bubble[data-id="${e}"]`)?.remove(),
  await fetch(`Papoter.php?action=delete_msg&msg_id=${e}`),
  fetchMessages(true));
}
async function banUser(e, t) {
 if (!confirm(`Bannir ${t} pendant 24h ?`)) return;
 let a = await fetch(`Papoter.php?action=ban_user&msg_id=${e}`);
 try {
  let s = await a.json();
  "ok" === s.status
   ? (alert(`${t} a été banni.`), fetchMessages(true))
   : alert("Erreur : " + (s.msg || "Inconnue"));
 } catch (n) {
  alert("Le serveur n'a pas pu traiter le bannissement.");
 }
}
async function chatLoop() {
 try {
  await fetchMessages(false);
 } catch (e) {
 } finally {
  const delay = isTabActive ? 100 : 2000;
  setTimeout(chatLoop, delay);
 }
}
("undefined" != typeof BAN_MSG &&
 BAN_MSG &&
 ((input.contentEditable = "false"),
 input.setAttribute("data-placeholder", BAN_MSG),
 (sendBtn.style.opacity = "0.5"),
 (sendBtn.style.pointerEvents = "none")),
 (window.format = function (e, t = null) {
  saveState();
  let a = window.getSelection();
  if (!a.rangeCount) return;
  input.focus();
  let s = a.getRangeAt(0);
  if ("removeFormat" === e) {
   let f = s.extractContents();
   let div = document.createElement("div");
   div.appendChild(f);
   s.insertNode(document.createTextNode(div.textContent));
  } else if (e.startsWith("justify")) {
   let d = { justifyLeft: "gauche", justifyCenter: "centre", justifyRight: "droite" }[e] || "gauche";
   let node = a.anchorNode;
   if (node) {
    if (node.nodeType === 3) node = node.parentNode;
    let block = node.closest("#chat-input > div, #chat-input > p");
    if (block && block.parentNode === input) {
     block.className = "alignement-" + d;
    } else {
     block = document.createElement("div");
     block.className = "alignement-" + d;
     if (s.collapsed) {
      let child = a.anchorNode;
      while (child && child.parentNode !== input) child = child.parentNode;
      if (child) {
       input.insertBefore(block, child);
       block.appendChild(child);
      } else {
       block.innerHTML = "<br>";
       input.appendChild(block);
      }
      s.selectNodeContents(block);
      s.collapse(false);
      a.removeAllRanges();
      a.addRange(s);
     } else {
      block.appendChild(s.extractContents());
      s.insertNode(block);
     }
    }
   }
  } else if ("normalText" === e) {
   let f = s.extractContents();
   s.insertNode(document.createTextNode(f.textContent));
  } else {
   let tagMap = {
    bold: "span",
    italic: "i",
    underline: "u",
    strikeThrough: "s",
    foreColor: "span",
    fontSize: "span",
   };
   let wrapper = document.createElement(tagMap[e] || "span");
   if ("bold" === e) wrapper.className = "style-gras";
   if ("foreColor" === e) wrapper.style.color = t;
   if ("fontSize" === e)
    wrapper.className =
     "font-size-" + ("3" === t ? "16px" : "5" === t ? "20px" : "24px");
   wrapper.appendChild(s.extractContents());
   s.insertNode(wrapper);
   let z = document.createTextNode("\u200B");
   wrapper.after(z);
   s.setStartAfter(z);
   s.collapse(true);
   a.removeAllRanges();
   a.addRange(s);
  }
  saveState();
 }),
 input.addEventListener("keydown", (e) => {
  if (e.ctrlKey && e.key === "z") {
   e.preventDefault();
   if (historyStack.length > 1) {
    redoStack.push(historyStack.pop());
    input.innerHTML = historyStack[historyStack.length - 1];
   }
   return;
  }
  if (e.ctrlKey && e.key === "y") {
   e.preventDefault();
   if (redoStack.length > 0) {
    let s = redoStack.pop();
    historyStack.push(s);
    input.innerHTML = s;
   }
   return;
  }
  if (e.key === "Enter" && !e.ctrlKey) {
   e.preventDefault();
   saveState();
   const s = window.getSelection(),
    r = s.getRangeAt(0),
    n = document.createElement("div");
   n.innerHTML = "<br>";
   let b = s.anchorNode;
   if (b.nodeType === 3) b = b.parentNode;
   let c = b.closest("#chat-input > div, #chat-input > p");
   if (c && c.parentNode === input) {
    c.after(n);
   } else {
    r.insertNode(n);
   }
   r.setStart(n, 0);
   r.collapse(true);
   s.removeAllRanges();
   s.addRange(r);
   saveState();
  } else if (e.key === "Enter" && e.ctrlKey) {
   e.preventDefault();
   sendMessage();
  }
 }),
 input.addEventListener("input", () => {
  let e = input.textContent || "",
   t = 8192 - e.length;
  ((charCounter.textContent = t + " caract\xe8res restants"),
   (charCounter.style.color = t < 100 ? "#ff4444" : ""));
 }),
 input.addEventListener("paste", (e) => {
  e.preventDefault();
  let t = (e.originalEvent || e).clipboardData.getData("text/plain");
  t = t.replace(/http:\/\//gi, "https://");
  const sel = window.getSelection();
  if (!sel.rangeCount) return;
  const range = sel.getRangeAt(0);
  range.deleteContents();
  const frag = document.createDocumentFragment(), lines = t.split(/\r?\n/);
  lines.forEach((l, i) => {
   frag.appendChild(document.createTextNode(l));
   if (i < lines.length - 1) frag.appendChild(document.createElement("br"));
  });
  const last = frag.lastChild;
  range.insertNode(frag);
  if (last) {
   range.setStartAfter(last);
   range.collapse(true);
   sel.removeAllRanges();
   sel.addRange(range);
  }
 }),
 (window.selectEmoji = function (e) {
  ((document.getElementById("selected-emoji").textContent = e),
   (document.getElementById("emoji-value").value = e));
  let t = document.getElementById("selected-emoji");
  ((t.style.transform = "scale(1.2)"),
   setTimeout(() => (t.style.transform = "scale(1)"), 200));
 }),
 (window.toggleExtraEmojis = function (e) {
  e.stopPropagation();
  let t = document.getElementById("extra-emojis-grid");
  (t.classList.toggle("show"),
   (e.target.textContent = t.classList.contains("show")
    ? "R\xe9duire"
    : "+ d'\xe9mojis"));
 }),
 updatePseudoBtn.addEventListener("click", async () => {
  let e = pseudoInput.value.trim(),
   t = document.getElementById("emoji-value").value,
   a = new FormData();
  (a.append("pseudo", e), a.append("emoji", t));
  let s = await fetch("Papoter.php?action=update_pseudo", {
    method: "POST",
    body: a,
   }),
   n = await s.json();
  if (n.status === "ok") location.reload();
  else alert(n.msg);
 }),
 pseudoInput.addEventListener("focus", () => {
  lastStableValue = pseudoInput.value;
  pseudoInput.value = "";
  const h = document.getElementById("pseudo-hint");
  if (h) {
   h.style.opacity = "0";
   setTimeout(() => {
    if (document.activeElement === pseudoInput) h.style.display = "none";
   }, 200);
  }
 }),
 pseudoInput.addEventListener("blur", () => {
  if (pseudoInput.value.trim() === "") {
   pseudoInput.value = lastStableValue;
  }
  fetchMessages(true);
 }),
 sendBtn.addEventListener("click", sendMessage),
 cancelEditBtn.addEventListener("click", cancelEdit),
 document.addEventListener("visibilitychange", () => {
  isTabActive = !document.hidden;
  if (isTabActive) {
   fetchMessages(true);
  }
 }),
 window.addEventListener("pagehide", () => {
  navigator.sendBeacon("Papoter.php?action=disconnect");
 }),
 (function initSidebar() {
  const btn = document.getElementById("toggle-sidebar");
  const body = document.querySelector(".chat-body");
  if (!btn || !body) return;

  const updateBtn = () => {
   const isHidden = body.classList.contains("sidebar-hidden");
   btn.textContent = isHidden ? "«" : "»";
   btn.title = isHidden ? "Afficher les panneaux" : "Masquer les panneaux";
  };

  btn.onclick = () => {
   body.classList.toggle("sidebar-hidden");
   updateBtn();
  };

  const checkResolution = () => {
   if (window.innerWidth <= 1152) {
    if (!body.classList.contains("sidebar-hidden")) {
     body.classList.add("sidebar-hidden");
     updateBtn();
    }
   } else if (body.classList.contains("sidebar-hidden")) {
    body.classList.remove("sidebar-hidden");
    updateBtn();
   }
  };

  window.addEventListener("resize", checkResolution);
  checkResolution();
 })(),
 updateSubjectListUI(),
 chatLoop());
