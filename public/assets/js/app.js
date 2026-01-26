// public/assets/js/app.js
(() => {
  "use strict";

  // Helpers
  const $ = (sel, root = document) => root.querySelector(sel);
  const $$ = (sel, root = document) => Array.from(root.querySelectorAll(sel));

  // Navbar dynamique au scroll
  const nav = $(".navbar");
  const setNavState = () => {
    if (!nav) return;
    const scrolled = window.scrollY > 10;
    nav.style.boxShadow = scrolled ? "0 10px 30px rgba(0,0,0,.35)" : "none";
    nav.style.backgroundColor = scrolled ? "rgba(0,0,0,.38)" : "rgba(0,0,0,.25)";
  };
  window.addEventListener("scroll", setNavState, { passive: true });
  setNavState();

  // Animations au scroll (fade-up)
  const prefersReduced = window.matchMedia("(prefers-reduced-motion: reduce)").matches;

  const autoTargets = [".hero", ".card", ".table-responsive", ".alert", "form"];
  const targets = autoTargets
    .flatMap((sel) => $$(sel))
    .filter((el) => !el.hasAttribute("data-no-anim"));

  if (!prefersReduced) {
    targets.forEach((el) => {
      el.style.opacity = "0";
      el.style.transform = "translateY(10px)";
      el.style.transition = "opacity .45s ease, transform .45s ease";
      el.style.willChange = "opacity, transform";
    });

    const io = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (!entry.isIntersecting) return;
          const el = entry.target;
          el.style.opacity = "1";
          el.style.transform = "translateY(0)";
          io.unobserve(el);
        });
      },
      { threshold: 0.12 }
    );

    targets.forEach((el) => io.observe(el));
  }

  // ------------------------------------------------------------
  // Indicateur de force du mot de passe (inscription)
  // ------------------------------------------------------------
  const pwd = document.querySelector('input[name="password"]');

  // On garde un flag global pour que le loader sache si c'est OK
  let passwordIsStrong = true;

  if (pwd) {
    const bar = document.getElementById("pwdBar");
    const label = document.getElementById("pwdScoreLabel");

    const rules = {
      len: document.getElementById("rule-len"),
      up: document.getElementById("rule-up"),
      low: document.getElementById("rule-low"),
      num: document.getElementById("rule-num"),
      spec: document.getElementById("rule-spec"),
    };

    function setRule(el, ok) {
      if (!el) return;
      el.classList.toggle("text-success", ok);
      el.classList.toggle("text-danger", !ok);
    }

    function evaluate(value) {
      const checks = {
        len: value.length >= 10,
        up: /[A-Z]/.test(value),
        low: /[a-z]/.test(value),
        num: /[0-9]/.test(value),
        spec: /[^A-Za-z0-9]/.test(value),
      };

      let score = 0;
      Object.values(checks).forEach((v) => v && score++);

      setRule(rules.len, checks.len);
      setRule(rules.up, checks.up);
      setRule(rules.low, checks.low);
      setRule(rules.num, checks.num);
      setRule(rules.spec, checks.spec);

      const pct = (score / 5) * 100;
      if (bar) bar.style.width = pct + "%";

      if (bar) {
        bar.classList.remove("bg-danger", "bg-warning", "bg-success");
        if (score <= 2) bar.classList.add("bg-danger");
        else if (score <= 4) bar.classList.add("bg-warning");
        else bar.classList.add("bg-success");
      }

      if (label) {
        label.textContent = score <= 2 ? "Faible" : score <= 4 ? "Moyen" : "Fort";
        label.classList.toggle("text-success", score === 5);
        label.classList.toggle("text-muted", score !== 5);
      }

      // important : retourne un booléen "ok"
      return checks.len && checks.up && checks.low && checks.num && checks.spec;
    }

    // Init + live
    passwordIsStrong = evaluate(pwd.value || "");
    pwd.addEventListener("input", (e) => {
      passwordIsStrong = evaluate(e.target.value);
    });

    // Bloquer submit si pas OK
    const form = pwd.closest("form");
    if (form) {
      form.addEventListener("submit", (e) => {
        passwordIsStrong = evaluate(pwd.value || "");
        if (!passwordIsStrong) {
          e.preventDefault();
          alert("Mot de passe trop faible : respecte toutes les règles.");
          pwd.focus();
        }
      });
    }
  }

  // ------------------------------------------------------------
  // Boutons loading sur submit (toutes les pages) - Evite double clic
  // IMPORTANT : ne pas activer si le submit a été bloqué (preventDefault)
  // ------------------------------------------------------------
  $$("form").forEach((form) => {
    form.addEventListener("submit", (e) => {
      // Si un autre handler a fait preventDefault (ex: mdp faible), on ne met pas le loader
      if (e.defaultPrevented) return;

      // Si formulaire inscription et mdp invalide (double sécurité)
      if (pwd && form.contains(pwd) && !passwordIsStrong) return;

      const btn = form.querySelector("button[type='submit']");
      if (!btn) return;
      if (btn.disabled) return;

      btn.dataset.originalText = btn.innerHTML;
      btn.disabled = true;
      btn.innerHTML = `
        <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
        Envoi...
      `;

      // Sécurité: si la page ne navigue pas (validation HTML bloquée), on réactive au bout de 2s.
      setTimeout(() => {
        if (!document.body.contains(btn)) return;
        if (btn.disabled) {
          btn.disabled = false;
          btn.innerHTML = btn.dataset.originalText || "Valider";
        }
      }, 2000);
    });
  });

  // Compteur de menus
  const menusCountEl = $("#menusCount");
  const menusList = $("#menusList");

  const refreshMenusCount = () => {
    if (!menusCountEl || !menusList) return;

    // On compte les "cards" via les colonnes directes de menusList (col-*)
    const cols = menusList.querySelectorAll(":scope > [class*='col-']");
    const count = cols.length;

    menusCountEl.textContent = count ? `${count} menu${count > 1 ? "s" : ""}` : "";
  };
  refreshMenusCount();

  // Debug: si le CSS ne charge pas
  const cssOk = (() => {
    const test = document.createElement("div");
    test.style.position = "absolute";
    test.style.left = "-9999px";
    test.className = "card";
    document.body.appendChild(test);
    const bg = getComputedStyle(test).backgroundColor;
    document.body.removeChild(test);
    return bg && bg !== "rgba(0, 0, 0, 0)" && bg !== "transparent";
  })();

  if (!cssOk) {
    console.warn("[ECF] CSS semble non chargé. Vérifie le chemin 'assets/css/style.css' dans header.php");
  }
})();
