function initCarousel(root) {
  const track = root.querySelector("[data-track]");
  const prev = root.querySelector("[data-prev]");
  const next = root.querySelector("[data-next]");

  if (!track || !prev || !next) return;

  let index = 0;

  function cardWidth() {
    const first = track.children[0];
    if (!first) return 0;
    const gap = parseFloat(getComputedStyle(track).gap || "0");
    return first.getBoundingClientRect().width + gap;
  }

  function maxIndex() {
    const vw = root.querySelector("[data-viewport]")?.getBoundingClientRect().width || 0;
    const cw = cardWidth();
    if (!cw) return 0;
    const visible = Math.max(1, Math.floor(vw / cw));
    return Math.max(0, track.children.length - visible);
  }

  function update() {
    const cw = cardWidth();
    index = Math.min(index, maxIndex());
    track.style.transform = `translateX(${-index * cw}px)`;
  }

  prev.addEventListener("click", () => { index = Math.max(0, index - 1); update(); });
  next.addEventListener("click", () => { index = Math.min(maxIndex(), index + 1); update(); });

  window.addEventListener("resize", update);
  update();
}

document.querySelectorAll("[data-carousel]").forEach(initCarousel);