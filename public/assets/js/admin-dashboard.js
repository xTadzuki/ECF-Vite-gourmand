// public/assets/js/admin-dashboard.js
(() => {
  "use strict";

  const statusEl = document.getElementById("statsStatus");
  const cStatus = document.getElementById("chartOrdersByStatus");
  const cRevenue = document.getElementById("chartRevenueByMonth");
  const cTop = document.getElementById("chartTopMenus");

  if (!cStatus || !cRevenue || !cTop) return;

  const fetchStats = async () => {
    const res = await fetch("?r=admin_stats_json", { headers: { Accept: "application/json" } });
    if (!res.ok) throw new Error(`HTTP ${res.status}`);
    return res.json();
  };

  const toNum = (v) => Number(v || 0);

  const buildDoughnut = (ctx, labels, data) =>
    new Chart(ctx, {
      type: "doughnut",
      data: { labels, datasets: [{ label: "Commandes", data }] },
      options: { responsive: true, plugins: { legend: { position: "bottom" } } },
    });

  const buildBar = (ctx, labels, data, label) =>
    new Chart(ctx, {
      type: "bar",
      data: { labels, datasets: [{ label, data }] },
      options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true } },
      },
    });

  const buildLine = (ctx, labels, data) =>
    new Chart(ctx, {
      type: "line",
      data: { labels, datasets: [{ label: "CA (€)", data }] },
      options: { responsive: true, plugins: { legend: { display: false } } },
    });

  (async () => {
    try {
      if (statusEl) statusEl.textContent = "Chargement…";

      const stats = await fetchStats();
      if (!stats || stats.success !== true) throw new Error("Bad payload");

      // orders by status
      const obs = Array.isArray(stats.ordersByStatus) ? stats.ordersByStatus : [];
      buildDoughnut(
        cStatus,
        obs.map((x) => x.status ?? "—"),
        obs.map((x) => toNum(x.total))
      );

      // revenue by month
      const rbm = Array.isArray(stats.revenueByMonth) ? stats.revenueByMonth : [];
      buildLine(
        cRevenue,
        rbm.map((x) => x.ym ?? ""),
        rbm.map((x) => toNum(x.revenue))
      );

      // top menus
      const top = Array.isArray(stats.topMenus) ? stats.topMenus : [];
      buildBar(
        cTop,
        top.map((x) => x.title ?? ""),
        top.map((x) => toNum(x.orders_count)),
        "Commandes"
      );

      if (statusEl) statusEl.textContent = "";
    } catch (e) {
      if (statusEl) statusEl.textContent = "Erreur : impossible de charger les statistiques.";
    }
  })();
})();
