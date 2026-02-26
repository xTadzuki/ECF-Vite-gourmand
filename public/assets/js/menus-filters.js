// public/assets/js/menus-filters.js
(() => {
  "use strict";

  const form = document.getElementById("filtersForm");
  const list = document.getElementById("menusList");
  const countEl = document.getElementById("menusCount");

  if (!form || !list) return;

  const $ = (sel, root = document) => root.querySelector(sel);

  const debounce = (fn, delay = 250) => {
    let t = null;
    return (...args) => {
      clearTimeout(t);
      t = setTimeout(() => fn(...args), delay);
    };
  };

  const esc = (s) =>
    String(s ?? "")
      .replaceAll("&", "&amp;")
      .replaceAll("<", "&lt;")
      .replaceAll(">", "&gt;")
      .replaceAll('"', "&quot;")
      .replaceAll("'", "&#039;");

  const formatPrice = (n) => {
    const num = Number(n || 0);
    return num.toLocaleString("fr-FR", { minimumFractionDigits: 2, maximumFractionDigits: 2 });
  };

  const stockBadge = (stock) => {
    const s = Number(stock || 0);
    if (s <= 0) return { cls: "badge-stock-out", label: "Rupture" };
    if (s <= 3) return { cls: "badge-stock-low", label: "Stock faible" };
    return { cls: "badge-stock-ok", label: "En stock" };
  };

  const render = (menus) => {
    const items = Array.isArray(menus) ? menus : [];

    if (countEl) countEl.textContent = `${items.length} menu${items.length > 1 ? "s" : ""}`;

    if (!items.length) {
      list.innerHTML = `<p class="vg-muted mb-0">Aucun menu correspondant aux filtres.</p>`;
      return;
    }

    list.innerHTML = items
      .map((m) => {
        const id = Number(m.id || 0);
        const badge = stockBadge(m.stock);
        const minPeople = Number(m.min_people || 0);
        const thumb = String(m.thumb || "");

        const media = thumb
          ? `<img class="vg-menu-card__img" src="${esc(thumb)}" alt="${esc(m.title || "Menu")}">`
          : `<div class="vg-menu-card__ph" aria-hidden="true">🍽️</div>`;

        const details = id > 0
          ? `<a class="vg-btn vg-btn--primary" href="?r=menu_show&id=${encodeURIComponent(id)}">Voir détails</a>`
          : `<span class="vg-muted small">ID manquant</span>`;

        return `
          <article class="vg-menu-card">
            <div class="vg-menu-card__media">${media}</div>

            <div class="vg-menu-card__body">
              <div class="vg-menu-card__top">
                <h3 class="vg-menu-card__title">${esc(m.title)}</h3>
                <span class="${esc(badge.cls)}">${esc(badge.label)}</span>
              </div>

              <div class="vg-menu-card__meta">
                <span class="vg-badge">${esc(m.theme ?? "—")}</span>
                <span class="vg-badge">${esc(m.diet ?? "—")}</span>
                <span class="vg-badge">Min ${minPeople} pers.</span>
              </div>

              <p class="vg-menu-card__desc">${esc(m.description ?? "").slice(0, 160)}${(m.description ?? "").length > 160 ? "…" : ""}</p>
            </div>

            <div class="vg-menu-card__cta">
              <div class="vg-price">
                <div class="vg-muted small">À partir de</div>
                <div class="vg-price__value">${formatPrice(m.price)} €</div>
              </div>
              ${details}
            </div>
          </article>
        `;
      })
      .join("");
  };

  const setLoading = (on) => {
    if (!countEl) return;
    countEl.textContent = on ? "Chargement…" : countEl.textContent;
  };

  const buildQuery = () => {
    const fd = new FormData(form);
    const params = new URLSearchParams();

    for (const [k, v] of fd.entries()) {
      const val = String(v ?? "").trim();
      if (val !== "") params.set(k, val);
    }

    // r=menus_json
    params.set("r", "menus_json");
    return params.toString();
  };

  const fetchMenus = async () => {
    setLoading(true);

    const url = `?${buildQuery()}`;
    const res = await fetch(url, { headers: { Accept: "application/json" } });
    if (!res.ok) throw new Error(`HTTP ${res.status}`);
    const data = await res.json();

    if (!data || data.success !== true) return [];
    return data.menus || [];
  };

  const run = async () => {
    try {
      const menus = await fetchMenus();
      render(menus);
    } catch (e) {
      list.innerHTML = `<div class="col-12"><div class="alert alert-danger mb-0">Erreur lors du chargement des menus.</div></div>`;
      if (countEl) countEl.textContent = "";
    }
  };

  // Update on any input/change
  const debounced = debounce(run, 250);
  form.addEventListener("input", debounced);
  form.addEventListener("change", debounced);

  // Initial count on first render (server side)
  const existingCards = list.querySelectorAll(".vg-menu-card").length;
  if (countEl && existingCards) countEl.textContent = `${existingCards} menu${existingCards > 1 ? "s" : ""}`;

  // Optional: reset button if exists
  const resetBtn = $("#filtersReset");
  if (resetBtn) {
    resetBtn.addEventListener("click", (e) => {
      e.preventDefault();
      form.reset();
      run();
    });
  }
})();
