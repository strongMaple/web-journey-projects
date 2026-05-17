document.addEventListener("DOMContentLoaded", () => {
  const btn = document.createElement("button");
  btn.innerHTML = "▲";
  btn.classList.add("back-to-top-btn");
  document.body.appendChild(btn);

  const style = document.createElement("style");
  style.innerHTML = `
    .back-to-top-btn {
      position: fixed;
      bottom: 28px;
      right: 28px;
      width: 52px;
      height: 52px;
      border-radius: 50%;
      font-size: 22px;
      border: 2px solid var(--glass-border);
      background: var(--glass);
      backdrop-filter: blur(10px);
      color: var(--acent-gold2);
      box-shadow: 0 6px 16px rgba(0,0,0,0.4);
      cursor: pointer;
      opacity: 0;
      pointer-events: none;
      transition: opacity 0.35s ease, transform 0.25s ease;
      z-index: 99999;
    }

    .back-to-top-btn:hover {
      background: var(--acent-gold);
      color: var(--accent-text);
      transform: translateY(-4px);
    }

    .back-to-top-btn.show {
      opacity: 1;
      pointer-events: auto;
    }

    @media (max-width: 600px) {
      .back-to-top-btn {
        width: 46px;
        height: 46px;
        font-size: 18px;
        bottom: 20px;
        right: 20px;
      }
    }
  `;
  document.head.appendChild(style);

  const showAt = 250;

  window.addEventListener("scroll", () => {
    if (window.scrollY > showAt) {
      btn.classList.add("show");
    } else {
      btn.classList.remove("show");
    }
  });

  btn.addEventListener("click", () => {
    window.scrollTo({ top: 0, behavior: "smooth" });
  });
});
