/* ─────────────────────────────────────────────
   DYNAMIC OVERLAY + CARD CSS (uses your theme)
   ───────────────────────────────────────────── */
const css = `
/* OVERLAY */
.overlay {
  position: fixed;
  inset: 0;
  width: 100vw;
  height: 100vh;
  display: none;
  justify-content: center;
  align-items: center;
  background: rgba(0,0,0,0.45);
  backdrop-filter: blur(10px);
  -webkit-backdrop-filter: blur(10px);
  padding: clamp(12px, 4vw, 30px);
  z-index: 9999999;
}

/* CARD */
.card {
  background: var(--card-bg);
  backdrop-filter: blur(20px);
  -webkit-backdrop-filter: blur(20px);
  border-radius: var(--radius);
  padding: clamp(22px, 3vw, 36px);
  width: 100%;
  max-width: min(600px, 90vw);
  max-height: 92vh;
  overflow-y: auto;
  position: relative;
  animation: pop 0.28s ease;
  box-shadow: 0 14px 40px var(--shadow-green-1);
  border: 1px solid var(--glass-border);
  display: none;
  z-index: 10000000;
  color: var(--text); /* improved text color */
  transition: background 0.25s, color 0.25s, box-shadow 0.25s;
}

.card:hover {
  background: var(--card-hover);
  box-shadow: 0 18px 44px var(--shadow-green-2);
}

.card h2 {
  margin: 0 0 15px;
  font-size: clamp(1.5rem, 3vw, 1.85rem);
  font-weight: 700;
  color: var(--green-1); /* beautiful accent heading */
}

.card p {
  font-size: clamp(0.95rem, 1vw, 1rem);
  line-height: 1.68;
  color: var(--text-muted); /* softer, elegant */
}

/* CLOSE BUTTON */
.close-btn {
  position: absolute;
  top: 14px;
  right: 18px;
  font-size: clamp(22px, 3vw, 28px);
  cursor: pointer;
  color: var(--accent-1);
  transition: 0.2s ease;
  z-index: 10000001;
}

.close-btn:hover {
  color: var(--accent-2);
  transform: scale(1.2);
}

/* MAP */
.card iframe {
  width: 100%;
  height: clamp(240px, 35vw, 320px);
  border: none;
  border-radius: 12px;
  margin-top: 8px;
}

/* ANIMATION */
@keyframes pop {
  from { opacity: 0; transform: scale(0.88); }
  to   { opacity: 1; transform: scale(1); }
}

/* RESPONSIVE */
@media (max-width: 900px) {
  .card { max-width: 520px; padding: clamp(20px, 4vw, 30px); }
  .card iframe { height: clamp(220px, 40vw, 290px); }
}
@media (max-width: 600px) {
  .overlay { padding: 10px; }
  .card { max-width: 95%; padding: clamp(18px, 5vw, 26px); }
  .close-btn { top: 12px; right: 14px; }
  .card iframe { height: clamp(200px, 45vw, 260px); }
}
@media (max-width: 420px) {
  .card { padding: 18px; border-radius: 14px; }
  .card h2 { font-size: 1.45rem; }
  .card iframe { border-radius: 10px; }
  .close-btn { font-size: 22px; }
}
@media (max-width: 340px) {
  .card { padding: 14px; }
  .card h2 { font-size: 1.35rem; }
  .card p { font-size: 0.9rem; }
  .card iframe { height: 190px; }
}
`;

const styleTag = document.createElement("style");
styleTag.textContent = css;
document.head.appendChild(styleTag);

/* ─────────────────────────────────────────────
   OVERLAY / CARD POPUP LOGIC
   ───────────────────────────────────────────── */

const overlay = document.getElementById("overlay");
const cards = document.querySelectorAll(".card");
const buttons = document.querySelectorAll(".more-info-btn");

/* OPEN CARD */
buttons.forEach((button) => {
  button.addEventListener("click", () => {
    const targetId = button.getAttribute("data-target");
    const targetCard = document.getElementById(targetId);

    // Hide all cards
    cards.forEach((c) => (c.style.display = "none"));

    // Show overlay + target card
    overlay.style.display = "flex";
    targetCard.style.display = "block";

    // Lock scroll behind the overlay
    document.body.style.overflow = "hidden";
  });
});

/* CLOSE ON CLICK OVERLAY OR X */
overlay.addEventListener("click", (e) => {
  const clickedOverlay = e.target === overlay;
  const clickedClose = e.target.classList.contains("close-btn");

  if (clickedOverlay || clickedClose) {
    overlay.style.display = "none";
    cards.forEach((c) => (c.style.display = "none"));

    // Restore background scrolling
    document.body.style.overflow = "auto";
  }
});
