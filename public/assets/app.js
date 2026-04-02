/* ============================================================
   app.js — единый JS для всех страниц PRICE_CRUNCHER
   ============================================================ */

document.addEventListener("DOMContentLoaded", () => {
  initProfileDropdown();
  initCrtGlitch();
  initSettingsNav();
  initSettingsTelegram();
  initAddPage();
  initDeleteConfirm();
  initUrlAutofocus();
});

/* ============================================================
     ОБЩИЕ МОДУЛИ
     ============================================================ */

/* ── Дропдаун профиля (header) ─────────────────────────────── */
function initProfileDropdown() {
  const profile = document.getElementById("profileTrigger");
  const menu = document.getElementById("profileMenu");
  if (!profile || !menu) return;

  profile.addEventListener("click", (e) => {
    e.stopPropagation();
    const open = menu.classList.contains("open");
    menu.classList.toggle("open", !open);
    profile.classList.toggle("active", !open);
  });

  document.addEventListener("click", () => {
    menu.classList.remove("open");
    profile.classList.remove("active");
  });

  menu.addEventListener("click", (e) => e.stopPropagation());
}

/* ── CRT scanline glitch ───────────────────────────────────── */
function initCrtGlitch() {
  const tick = () => {
    const el = document.querySelector(".scanlines");
    if (el) {
      el.style.opacity = "0.3";
      setTimeout(() => {
        el.style.opacity = "1";
      }, 55);
      setTimeout(() => {
        el.style.opacity = "0.6";
      }, 90);
      setTimeout(() => {
        el.style.opacity = "1";
      }, 130);
    }
    setTimeout(tick, 6000 + Math.random() * 8000);
  };
  setTimeout(tick, 4000);
}

/* ── Автофокус поля URL на дашборде ───────────────────────── */
function initUrlAutofocus() {
  const el = document.querySelector('.cmd-form input[type="url"]');
  if (el) el.focus();
}

/* ── Подтверждение удаления карточки ──────────────────────── */
function initDeleteConfirm() {
  document.querySelectorAll(".action-btn.danger").forEach((btn) => {
    btn.addEventListener("click", (e) => {
      if (
        !confirm("CONFIRM_DELETE? This node will be removed from monitoring.")
      ) {
        e.preventDefault();
        e.stopPropagation();
      }
    });
  });
}

/* ============================================================
     SETTINGS PAGE
     ============================================================ */

/* ── Навигация по вкладкам + анимация стрелки ─────────────── */
function initSettingsNav() {
  const navItems = document.querySelectorAll(".settings-nav-item");
  const panels = document.querySelectorAll(".settings-panel");
  if (!navItems.length) return;

  navItems.forEach((item) => {
    item.addEventListener("click", () => {
      const target = item.dataset.panel;

      // снимаем активность со всех
      navItems.forEach((n) => n.classList.remove("active"));
      panels.forEach((p) => {
        p.classList.remove("active");
        // сброс анимации для повторного воспроизведения
        p.style.animation = "none";
        p.offsetHeight; // reflow
        p.style.animation = "";
      });

      // ставим активную вкладку
      item.classList.add("active");
      const panel = document.getElementById("panel-" + target);
      if (panel) panel.classList.add("active");
    });
  });
}

/* ── Telegram: инициализация статуса при загрузке ─────────── */
function initSettingsTelegram() {
  const dot = document.getElementById("tgDot");
  const statusText = document.getElementById("tgStatusText");
  if (!dot || !statusText) return;

  // PHP рендерит data-атрибут на элементе tgStatusText: data-connected="1" или "0"
  const isConnected = statusText.dataset.connected === "1";
  setTelegramState(isConnected);
}

/* ── Установить визуальное состояние TG ────────────────────── */
function setTelegramState(connected) {
  const dot = document.getElementById("tgDot");
  const statusText = document.getElementById("tgStatusText");
  if (!dot || !statusText) return;

  if (connected) {
    dot.classList.remove("disconnected");
    dot.classList.add("connected");
    statusText.classList.remove("disconnected");
    statusText.classList.add("connected");
  } else {
    dot.classList.remove("connected");
    dot.classList.add("disconnected");
    statusText.classList.remove("connected");
    statusText.classList.add("disconnected");
  }
}

/* ============================================================
     SETTINGS: сохранение полей (заглушки под fetch)
     ============================================================ */

function saveField(field) {
  const statusEl = document.getElementById("status-" + field);

  if (field === "username") {
    const val = (document.getElementById("newUsername")?.value || "").trim();
    if (!val) return showStatus(statusEl, "ERR: EMPTY_VALUE", "err");
    // fetch('/api/settings/username', { method: 'POST', ... })
    const upper = val.toUpperCase();
    const cur = document.getElementById("currentUsername");
    const disp = document.getElementById("displayName");
    if (cur) cur.textContent = upper;
    if (disp) disp.textContent = upper;
    document.getElementById("newUsername").value = "";
    showStatus(statusEl, "OK: USERNAME_UPDATED", "ok");
  }

  if (field === "email") {
    const val = (document.getElementById("newEmail")?.value || "").trim();
    if (!val || !val.includes("@"))
      return showStatus(statusEl, "ERR: INVALID_EMAIL", "err");
    // fetch('/api/settings/email', { method: 'POST', ... })
    const cur = document.getElementById("currentEmail");
    const disp = document.getElementById("displayEmail");
    if (cur) cur.textContent = val;
    if (disp) disp.textContent = val;
    document.getElementById("newEmail").value = "";
    showStatus(statusEl, "OK: EMAIL_UPDATED", "ok");
  }

  if (field === "notif") {
    // fetch('/api/settings/notifications', { method: 'POST', ... })
    showStatus(statusEl, "OK: CHANNELS_SAVED", "ok");
  }
}

function savePassword() {
  const current = document.getElementById("currentPassword")?.value || "";
  const newPass = document.getElementById("newPassword")?.value || "";
  const confirm = document.getElementById("confirmPassword")?.value || "";
  const statusEl = document.getElementById("status-password");
  const wrapper = document.getElementById("confirmWrapper");

  if (!current) return showStatus(statusEl, "ERR: ENTER_CURRENT_KEY", "err");
  if (newPass.length < 8)
    return showStatus(statusEl, "ERR: KEY_TOO_SHORT (MIN 8)", "err");
  if (newPass !== confirm) {
    if (wrapper) wrapper.style.borderColor = "var(--accent-danger)";
    return showStatus(statusEl, "ERR: KEYS_DO_NOT_MATCH", "err");
  }

  if (wrapper) wrapper.style.borderColor = "";
  // fetch('/api/settings/password', { method: 'POST', ... })
  document.getElementById("currentPassword").value = "";
  document.getElementById("newPassword").value = "";
  document.getElementById("confirmPassword").value = "";
  const fill = document.getElementById("strengthFill");
  const label = document.getElementById("strengthLabel");
  if (fill) fill.style.width = "0%";
  if (label) label.textContent = "STRENGTH: —";
  showStatus(statusEl, "OK: ACCESS_KEY_UPDATED", "ok");
}

/* ── Password strength ─────────────────────────────────────── */
function checkStrength(val) {
  const fill = document.getElementById("strengthFill");
  const label = document.getElementById("strengthLabel");
  if (!fill || !label) return;

  let score = 0;
  if (val.length >= 8) score++;
  if (val.length >= 12) score++;
  if (/[A-Z]/.test(val)) score++;
  if (/[0-9]/.test(val)) score++;
  if (/[^A-Za-z0-9]/.test(val)) score++;

  const levels = [
    { pct: "0%", color: "var(--text-dim)", text: "STRENGTH: —" },
    { pct: "20%", color: "var(--accent-danger)", text: "STRENGTH: WEAK" },
    { pct: "45%", color: "var(--accent-danger)", text: "STRENGTH: LOW" },
    { pct: "65%", color: "var(--accent-warn)", text: "STRENGTH: MEDIUM" },
    { pct: "85%", color: "var(--accent-warn)", text: "STRENGTH: STRONG" },
    { pct: "100%", color: "var(--accent)", text: "STRENGTH: OPTIMAL" },
  ];

  const level = val.length === 0 ? levels[0] : levels[Math.min(score, 5)];
  fill.style.width = level.pct;
  fill.style.background = level.color;
  label.textContent = level.text;
  label.style.color = level.color;
}

/* ── Confirm password match ────────────────────────────────── */
function checkConfirm() {
  const newPass = document.getElementById("newPassword")?.value || "";
  const confirm = document.getElementById("confirmPassword")?.value || "";
  const wrapper = document.getElementById("confirmWrapper");
  if (!wrapper) return;

  wrapper.style.borderColor =
    confirm.length === 0
      ? ""
      : newPass === confirm
        ? "var(--accent)"
        : "var(--accent-danger)";
}

/* ============================================================
     ADD-PRODUCT PAGE: слайдеры + тип порога
     ============================================================ */

function initAddPage() {
  const form = document.querySelector(".config-form[data-product-price]");
  if (!form) return;

  const productPrice = parseInt(form.dataset.productPrice, 10) || 0;

  const threshRange = document.getElementById("thresholdRange");
  const threshInput = document.getElementById("thresholdValue");
  const threshBadge = document.getElementById("thresholdBadge");
  const threshSuffix = document.getElementById("thresholdSuffix");
  const threshMax = document.getElementById("thresholdMax");
  const threshHidden = document.getElementById("thresholdType");
  const targetRange = document.getElementById("targetRange");
  const targetInput = document.getElementById("targetValue");
  const targetBadge = document.getElementById("targetBadge");

  let currentType = "absolute";

  function formatBadge(val, type) {
    return type === "percent"
      ? val + " %"
      : Number(val).toLocaleString("ru-RU") + " ₽";
  }

  function updateTrack(el) {
    const min = parseFloat(el.min) || 0;
    const max = parseFloat(el.max) || 1;
    const val = parseFloat(el.value) || 0;
    el.style.setProperty(
      "--val",
      (((val - min) / (max - min)) * 100).toFixed(2) + "%",
    );
  }

  function syncFromRange(range, input, badge, type) {
    input.value = range.value;
    badge.textContent = formatBadge(range.value, type);
    updateTrack(range);
  }

  function syncFromInput(input, range, badge, type) {
    let v = Math.max(
      parseFloat(range.min) || 0,
      Math.min(parseFloat(range.max), parseFloat(input.value) || 0),
    );
    input.value = v;
    range.value = v;
    badge.textContent = formatBadge(v, type);
    updateTrack(range);
  }

  /* тип порога */
  document.querySelectorAll(".type-toggle-btn").forEach((btn) => {
    btn.addEventListener("click", (e) => {
      e.preventDefault();
      e.stopPropagation();
      const type = btn.getAttribute("data-type");
      if (type === currentType) return;
      currentType = type;

      document
        .querySelectorAll(".type-toggle-btn")
        .forEach((b) => b.classList.remove("active"));
      btn.classList.add("active");

      if (threshHidden) threshHidden.value = type;

      if (type === "percent") {
        threshRange.max = 100;
        if (threshSuffix) threshSuffix.textContent = "%";
        if (threshMax) threshMax.textContent = "100 %";
      } else {
        threshRange.max = productPrice;
        if (threshSuffix) threshSuffix.textContent = "₽";
        if (threshMax)
          threshMax.textContent =
            Number(productPrice).toLocaleString("ru-RU") + " ₽";
      }

      threshRange.value = 0;
      if (threshInput) threshInput.value = "";
      if (threshBadge) threshBadge.textContent = formatBadge(0, type);
      updateTrack(threshRange);
    });
  });

  /* слайдер порога */
  if (threshRange && threshInput && threshBadge) {
    threshRange.addEventListener("input", () =>
      syncFromRange(threshRange, threshInput, threshBadge, currentType),
    );
    threshInput.addEventListener("input", () =>
      syncFromInput(threshInput, threshRange, threshBadge, currentType),
    );
    updateTrack(threshRange);
  }

  /* слайдер целевой цены */
  if (targetRange && targetInput && targetBadge) {
    targetRange.addEventListener("input", () =>
      syncFromRange(targetRange, targetInput, targetBadge, "absolute"),
    );
    targetInput.addEventListener("input", () =>
      syncFromInput(targetInput, targetRange, targetBadge, "absolute"),
    );
    updateTrack(targetRange);
  }
}

/* ============================================================
     УТИЛИТА: статус-сообщение с авто-скрытием
     ============================================================ */
function showStatus(el, text, type) {
  if (!el) return;
  el.textContent = text;
  el.className = "save-status visible " + type;
  clearTimeout(el._timer);
  el._timer = setTimeout(() => el.classList.remove("visible"), 3500);
}

/* ============================================================
     DASHBOARD GROUPS — localStorage-based filtering
     ============================================================ */

/* Структура в localStorage:
     groups: { id: { name, ids: [productId, ...] } }
     productGroups: { productId: groupId }
  */

const GROUPS_KEY = "pc_groups";
const PGROUPS_KEY = "pc_product_groups";

function getGroups() {
  try {
    return JSON.parse(localStorage.getItem(GROUPS_KEY) || "{}");
  } catch (e) {
    return {};
  }
}
function getProductGroups() {
  try {
    return JSON.parse(localStorage.getItem(PGROUPS_KEY) || "{}");
  } catch (e) {
    return {};
  }
}
function saveGroups(g) {
  localStorage.setItem(GROUPS_KEY, JSON.stringify(g));
}
function saveProductGroups(g) {
  localStorage.setItem(PGROUPS_KEY, JSON.stringify(g));
}

let activeGroupFilter = "all";

function initDashboardGroups() {
  const grid = document.getElementById("productsGrid");
  if (!grid) return;

  renderGroupFilters();
  restoreCardGroups();
  updateGroupSelects();
  filterByGroup("all");

  // Открыть модалку
  const openBtn = document.getElementById("openGroupModal");
  if (openBtn) openBtn.addEventListener("click", openGroupModal);

  // Enter в инпуте модалки
  const inp = document.getElementById("groupNameInput");
  if (inp)
    inp.addEventListener("keydown", (e) => {
      if (e.key === "Enter") createGroup();
    });

  // Закрыть по клику на оверлей
  const overlay = document.getElementById("groupModal");
  if (overlay)
    overlay.addEventListener("click", (e) => {
      if (e.target === overlay) closeGroupModal();
    });
}

/* ── Рендер кнопок-фильтров ────────────────────────────────── */
function renderGroupFilters() {
  const container = document.getElementById("groupFilters");
  if (!container) return;

  const groups = getGroups();
  const pg = getProductGroups();
  const cards = document.querySelectorAll(
    "#productsGrid .product-card[data-id]",
  );

  // Считаем кол-во в "ALL"
  const allCount = document.getElementById("countAll");
  if (allCount) allCount.textContent = "(" + cards.length + ")";

  // Убираем старые группы, оставляем ALL
  container
    .querySelectorAll('[data-group]:not([data-group="all"])')
    .forEach((b) => b.remove());

  Object.entries(groups).forEach(([id, group]) => {
    const count = Object.values(pg).filter((gid) => gid === id).length;

    const btn = document.createElement("button");
    btn.type = "button";
    btn.className =
      "group-filter-btn" + (activeGroupFilter === id ? " active" : "");
    btn.dataset.group = id;

    // Текст + счётчик
    const label = document.createElement("span");
    label.textContent = group.name;
    btn.appendChild(label);

    const countSpan = document.createElement("span");
    countSpan.className = "group-count";
    countSpan.textContent = "(" + count + ")";
    btn.appendChild(countSpan);

    // × кнопка удаления
    const delX = document.createElement("span");
    delX.className = "group-delete-x";
    delX.textContent = "×";
    delX.title = "Удалить группу";
    delX.addEventListener("click", (e) => {
      e.stopPropagation();
      // Если кнопка уже в режиме подтверждения — сразу удаляем
      if (btn.dataset.confirming === "1") {
        clearTimeout(btn._cancelTimer);
        _doDeleteGroup(id);
      } else {
        deleteGroup(id, group.name);
      }
    });
    btn.appendChild(delX);

    btn.addEventListener("click", () => filterByGroup(id));
    container.appendChild(btn);
  });

  // ALL btn listener
  const allBtn = container.querySelector('[data-group="all"]');
  if (allBtn) {
    allBtn.onclick = null;
    allBtn.addEventListener("click", () => filterByGroup("all"));
  }
}

/* ── Фильтрация карточек ───────────────────────────────────── */
function filterByGroup(groupId) {
  activeGroupFilter = groupId;
  const cards = document.querySelectorAll(
    "#productsGrid .product-card[data-id]",
  );
  const pg = getProductGroups();
  const label = document.getElementById("gridLabel");

  let visible = 0;

  cards.forEach((card) => {
    const id = card.dataset.id;
    const cardGroup = pg[id] || "";
    const show = groupId === "all" || cardGroup === groupId;
    card.classList.toggle("hidden-by-group", !show);
    if (show) visible++;
  });

  // Обновляем grid-label
  if (label) {
    if (groupId === "all") {
      label.textContent = "[ ACTIVE_MONITORING_NODES ]";
    } else {
      const g = getGroups()[groupId];
      label.textContent =
        "[ GROUP: " + (g ? g.name : "—") + " // " + visible + "_NODES ]";
    }
  }

  // Подсветить активную кнопку
  document.querySelectorAll(".group-filter-btn").forEach((b) => {
    b.classList.toggle("active", b.dataset.group === groupId);
  });

  // Пустой экран
  const empty = document.getElementById("emptyGroupMsg");
  if (visible === 0 && !empty) {
    const el = document.createElement("div");
    el.className = "empty-node";
    el.id = "emptyGroupMsg";
    el.innerHTML =
      '// NO_NODES_IN_GROUP<br><span style="color:var(--text-dim);">Добавьте товары в эту группу через карточку</span>';
    document.getElementById("productsGrid").appendChild(el);
  } else if (visible > 0 && empty) {
    empty.remove();
  }
}

/* ── Назначить карточку в группу ───────────────────────────── */
function assignGroup(selectEl) {
  const productId = selectEl.dataset.id;
  const groupId = selectEl.value;
  const pg = getProductGroups();

  if (groupId) {
    pg[productId] = groupId;
  } else {
    delete pg[productId];
  }

  saveProductGroups(pg);
  updateCardBadge(productId, groupId);
  renderGroupFilters();
  if (activeGroupFilter !== "all") filterByGroup(activeGroupFilter);
}

/* ── Бейдж группы на карточке ──────────────────────────────── */
function updateCardBadge(productId, groupId) {
  const badge = document.getElementById("badge-" + productId);
  if (!badge) return;
  if (groupId) {
    const g = getGroups()[groupId];
    badge.textContent = g ? g.name : "";
    badge.style.display = "block";
  } else {
    badge.textContent = "";
    badge.style.display = "none";
  }
}

/* ── Восстановить группы при загрузке ─────────────────────── */
function restoreCardGroups() {
  const pg = getProductGroups();
  Object.entries(pg).forEach(([productId, groupId]) => {
    updateCardBadge(productId, groupId);
    // данные на карточке
    const card = document.querySelector(
      `.product-card[data-id="${productId}"]`,
    );
    if (card) card.dataset.group = groupId;
  });
}

/* ── Наполнить select-ы групп во всех карточках ────────────── */
function updateGroupSelects() {
  const groups = getGroups();
  const pg = getProductGroups();

  document.querySelectorAll(".card-group-select").forEach((sel) => {
    const productId = sel.dataset.id;
    const currentGroup = pg[productId] || "";

    // Очистить, оставив первый option
    while (sel.options.length > 1) sel.remove(1);

    Object.entries(groups).forEach(([id, group]) => {
      const opt = document.createElement("option");
      opt.value = id;
      opt.textContent = group.name;
      if (id === currentGroup) opt.selected = true;
      sel.appendChild(opt);
    });

    sel.value = currentGroup;
  });
}

/* ── Создать группу ────────────────────────────────────────── */
function createGroup() {
  const inp = document.getElementById("groupNameInput");
  const name = inp.value.trim().toUpperCase();
  if (!name) {
    inp.focus();
    return;
  }

  const groups = getGroups();
  const id = "g_" + Date.now();
  groups[id] = { name };
  saveGroups(groups);

  inp.value = "";
  closeGroupModal();
  renderGroupFilters();
  updateGroupSelects();
}

/* ── Удалить группу — с inline-тостом вместо confirm() ─────── */
function deleteGroup(groupId, groupName) {
  // Находим кнопку группы и показываем inline-подтверждение
  const btn = document.querySelector(
    `.group-filter-btn[data-group="${groupId}"]`,
  );
  if (!btn) return;

  // Если уже в режиме подтверждения — выполняем удаление
  if (btn.dataset.confirming === "1") {
    _doDeleteGroup(groupId);
    return;
  }

  // Переводим в режим подтверждения
  btn.dataset.confirming = "1";
  const prevClass = btn.className;
  btn.style.borderColor = "var(--accent-danger)";
  btn.style.color = "var(--accent-danger)";
  btn.querySelector("span:first-child").textContent = "УДАЛИТЬ?";

  // Автоотмена через 2.5 сек
  btn._cancelTimer = setTimeout(() => {
    btn.dataset.confirming = "0";
    btn.style.borderColor = "";
    btn.style.color = "";
    btn.querySelector("span:first-child").textContent = groupName;
  }, 2500);
}

function _doDeleteGroup(groupId) {
  const groups = getGroups();
  delete groups[groupId];
  saveGroups(groups);

  const pg = getProductGroups();
  Object.keys(pg).forEach((pid) => {
    if (pg[pid] === groupId) delete pg[pid];
  });
  saveProductGroups(pg);

  renderGroupFilters();
  updateGroupSelects();
  restoreCardGroups();
  filterByGroup("all");
}

/* ── Модалка ───────────────────────────────────────────────── */
function openGroupModal() {
  document.getElementById("groupModal")?.classList.add("open");
  setTimeout(() => document.getElementById("groupNameInput")?.focus(), 150);
}

function closeGroupModal() {
  document.getElementById("groupModal")?.classList.remove("open");
}

// Вызываем при DOMContentLoaded
document.addEventListener("DOMContentLoaded", initDashboardGroups);

/* ============================================================
     CITY SELECTION MODAL
     ============================================================ */

function initCityModal() {
  const modal = document.getElementById("cityModal");
  const form = document.getElementById("cityForm");
  const errEl = document.getElementById("cityError");
  if (!modal || !form) return;

  /* Валидация перед сабмитом */
  form.addEventListener("submit", (e) => {
    const checked = form.querySelector('input[name="city"]:checked');
    if (!checked) {
      e.preventDefault();
      errEl && errEl.classList.add("visible");
      /* потрясти модалку */
      const box = modal.querySelector(".modal-box");
      if (box) {
        box.style.animation = "none";
        box.offsetHeight; // reflow
        box.style.animation = "shake 0.35s ease";
      }
    }
  });

  /* Скрыть ошибку при выборе */
  form.querySelectorAll('input[name="city"]').forEach((radio) => {
    radio.addEventListener("change", () => {
      errEl && errEl.classList.remove("visible");
    });
  });

  /* Клавиша Escape — запрещаем закрытие (выбор обязателен) */
  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape" && modal.classList.contains("open")) {
      e.preventDefault();
      /* небольшой shake чтобы дать понять что закрыть нельзя */
      const box = modal.querySelector(".modal-box");
      if (box) {
        box.style.animation = "none";
        box.offsetHeight;
        box.style.animation = "shake 0.35s ease";
      }
    }
  });

  /* Клик на оверлей — тоже не закрываем, shake */
  modal.addEventListener("click", (e) => {
    if (e.target === modal) {
      const box = modal.querySelector(".modal-box");
      if (box) {
        box.style.animation = "none";
        box.offsetHeight;
        box.style.animation = "shake 0.35s ease";
      }
    }
  });
}

/* Вызываем при DOMContentLoaded */
document.addEventListener("DOMContentLoaded", initCityModal);
