// /js/handle.js
// Dynamic product loader + full card interactions

import { app, db } from "../../firebase/firebaseConfig.js";
import {
  collection,
  getDocs,
  query,
  where,
} from "https://www.gstatic.com/firebasejs/11.0.0/firebase-firestore.js";

import {
  addDoc,
  serverTimestamp,
} from "https://www.gstatic.com/firebasejs/11.0.0/firebase-firestore.js";

document.addEventListener("DOMContentLoaded", async () => {
  const dealsSection = document.querySelector("#deals");

  /* ============================================================
     1. DETERMINE PAGE TARGET
     ============================================================ */

  function getRenderTargetForPage() {
    const path = window.location.pathname.toLowerCase();

    if (path.includes("accessories")) return "accessories";
    if (path.includes("phones")) return "phones";
    if (path.includes("laptops")) return "laptops";
    return "index"; // homepage default
  }

  const pageTarget = getRenderTargetForPage();

  /* ============================================================
     2. FETCH PRODUCTS FROM FIRESTORE
     ============================================================ */

  async function loadProducts() {
    const q = query(
      collection(db, "products"),
      where("renderTarget", "==", pageTarget),
    );

    const snap = await getDocs(q);

    snap.forEach((doc) => {
      const p = doc.data();
      const id = doc.id;
      const mediaType = p.media?.type;
      const mediaUrl = p.media?.primaryUrl;

      dealsSection.insertAdjacentHTML(
        "beforeend",
        buildProductCard(id, p, mediaType, mediaUrl),
      );
    });
  }

  /* ============================================================
     3. PRODUCT CARD BUILDER
     ============================================================ */

  function buildProductCard(id, p, mediaType, mediaUrl) {
    const isVideo = mediaType === "video";

    return `
      <div class="product-card" data-product="${id}">
        <div class="product-container">

          ${
            isVideo
              ? `<video class="product-image" src="${mediaUrl}" autoplay loop muted></video>`
              : `<img class="product-image" src="${mediaUrl}" alt="${p.name}" />`
          }

          <div class="menu-icon" title="Options">
            <i class="fa-solid fa-ellipsis-vertical"></i>
          </div>

          <div class="menu-options">
            <button class="preview-btn" data-id="${id}">Preview</button>
            <button class="select-btn">Select</button>
          </div>

          <div class="selection-overlay"></div>

          <div class="price-tag">
            <span class="currency">₦</span><span class="amount">${
              p.priceDisplay
            }</span>
          </div>
        </div>

        <div class="product-info">
          <h3>${p.name}</h3>
          <p>(${p.shortSpec || p.category})</p>
          <button class="place-order-btn">Place Order</button>
        </div>
      </div>
    `;
  }

  /* ============================================================
     4. LOAD DYNAMIC PRODUCTS FIRST → THEN BIND EVENTS
     ============================================================ */

  await loadProducts();

  const cards = Array.from(document.querySelectorAll(".product-card"));
  const orderSelectedBtn = document.querySelector(".order-selected-btn");

  let selectedCards = new Set();
  let longPressTimer;
  let selectionMode = false;

  /* ============================================================
     PRICE PARSING / FORMATTING
     ============================================================ */

  function parsePriceToNumber(raw) {
    if (!raw) return 0;

    let s = String(raw)
      .toLowerCase()
      .replace(/#/g, "")
      .replace(/,/g, "")
      .trim();

    let total = 0;

    s = s.replace(/(\d+(?:\.\d+)?)m/gi, (_, num) => {
      total += parseFloat(num) * 1_000_000;
      return "";
    });

    s = s.replace(/(\d+(?:\.\d+)?)k/gi, (_, num) => {
      total += parseFloat(num) * 1_000;
      return "";
    });

    const leftover = parseFloat(s);
    if (!isNaN(leftover)) total += leftover;

    return Math.round(total);
  }

  function formatNaira(num) {
    return "₦" + Number(num).toLocaleString("en-NG");
  }

  /* ============================================================
     MENU TOGGLE
     ============================================================ */

  document.querySelectorAll(".menu-icon").forEach((icon) => {
    icon.addEventListener("click", (e) => {
      e.stopPropagation();
      const menu = icon.nextElementSibling;

      document.querySelectorAll(".menu-options").forEach((m) => {
        if (m !== menu) m.style.display = "none";
      });

      menu.style.display = menu.style.display === "flex" ? "none" : "flex";
    });
  });

  document.addEventListener("click", () => {
    document
      .querySelectorAll(".menu-options")
      .forEach((m) => (m.style.display = "none"));
  });

  /* ============================================================
     SELECTION LOGIC
     ============================================================ */

  function toggleSelect(card) {
    card.classList.toggle("selected");

    if (card.classList.contains("selected")) selectedCards.add(card);
    else selectedCards.delete(card);

    selectionMode = selectedCards.size > 0;
    updateOrderButton();
  }

  function clearSelection() {
    selectedCards.forEach((c) => c.classList.remove("selected"));
    selectedCards.clear();
    selectionMode = false;
    updateOrderButton();
  }

  function updateOrderButton() {
    if (!orderSelectedBtn) return;

    if (selectedCards.size > 1) {
      orderSelectedBtn.style.display = "flex";
      orderSelectedBtn.style.opacity = "1";
      orderSelectedBtn.style.transform = "translate(-50%, 0)";
    } else {
      orderSelectedBtn.style.opacity = "0";
      orderSelectedBtn.style.transform = "translate(-50%, 100%)";
      setTimeout(() => (orderSelectedBtn.style.display = "none"), 300);
    }
  }

  /* ============================================================
     CARD CLICK HANDLING
     ============================================================ */

  cards.forEach((card) => {
    const placeOrderBtn = card.querySelector(".place-order-btn");
    const selectBtn = card.querySelector(".select-btn");

    function startLongPress() {
      longPressTimer = setTimeout(() => toggleSelect(card), 1000);
    }
    function cancelLongPress() {
      clearTimeout(longPressTimer);
    }

    card.addEventListener("mousedown", () => {
      if (!selectionMode) startLongPress();
    });
    card.addEventListener("mouseup", cancelLongPress);
    card.addEventListener("mouseleave", cancelLongPress);

    card.addEventListener("touchstart", () => {
      if (!selectionMode) startLongPress();
    });
    card.addEventListener("touchend", cancelLongPress);

    card.addEventListener("click", () => {
      if (selectionMode || card.classList.contains("selected"))
        toggleSelect(card);
    });

    if (selectBtn) {
      selectBtn.addEventListener("click", (e) => {
        e.stopPropagation();
        toggleSelect(card);
        selectBtn.closest(".menu-options").style.display = "none";
      });
    }

    if (placeOrderBtn) {
      placeOrderBtn.addEventListener("click", (e) => {
        e.stopPropagation();
        handlePlaceOrder([card]);
      });
    }

    const previewBtn = card.querySelector(".preview-btn");
    if (previewBtn) {
      previewBtn.addEventListener("click", (e) => {
        e.stopPropagation();
        const pid = previewBtn.dataset.id;
        window.location.href = `/product.html?id=${pid}`;
      });
    }
  });

  if (orderSelectedBtn) {
    orderSelectedBtn.addEventListener("click", () => {
      handlePlaceOrder([...selectedCards]);
    });
  }

  /* ============================================================
     ORDER LOGIC
     ============================================================ */

  async function handlePlaceOrder(cardsArray) {
    const productSummaries = cardsArray.map((c) => {
      const name = c.querySelector("h3")?.textContent.trim() || "Unnamed";
      const priceRawText =
        c.querySelector(".amount")?.textContent.trim() || "0";

      const priceNumeric = parsePriceToNumber(priceRawText);
      const priceFormatted = formatNaira(priceNumeric);

      return { name, priceNumeric, priceFormatted };
    });

    const refName = await promptReferenceModal(productSummaries);
    if (!refName) return;

    const orderId = generateOrderId(refName);

    const products = productSummaries.map((p) => ({
      productId: generateProductId(p.name),
      name: p.name,
      priceNumeric: p.priceNumeric,
      priceFormatted: p.priceFormatted,
    }));

    const totalAmount = products.reduce((s, p) => s + (p.priceNumeric || 0), 0);

    const orderDoc = {
      orderId,
      referenceName: refName,
      products,
      totalAmount,
      createdAt: serverTimestamp(),
      buyerUid: null,
      buyerEmail: null,
    };

    try {
      await addDoc(collection(db, "orders"), orderDoc);
    } catch (err) {
      console.error("Order save failed:", err);
      alert("Could not create order. Try again.");
      return;
    }

    clearSelection();

    const proceed = await chooseChatPlatformModal([
      { orderId, referenceName: refName, products, totalAmount },
    ]);

    if (!proceed) return;

    if (proceed === "telegram")
      sendToTelegram([
        { orderId, referenceName: refName, products, totalAmount },
      ]);
    else
      sendToWhatsApp([
        { orderId, referenceName: refName, products, totalAmount },
      ]);
  }

  /* ============================================================
     MODALS / HELPERS
     ============================================================ */

  function generateOrderId(name = "") {
    const now = Date.now();
    const d = new Date(now);
    const yy = String(d.getFullYear()).slice(2);
    const mm = String(d.getMonth() + 1).padStart(2, "0");
    const dd = String(d.getDate()).padStart(2, "0");
    const ts = String(Math.floor(now / 1000)).slice(-6);
    const rand = Math.random().toString(36).slice(2, 5).toUpperCase();
    const short = name.replace(/\s+/g, "").slice(0, 3).toUpperCase();
    return `HNS-${yy}${mm}${dd}-${ts}-${short}-${rand}`;
  }

  function generateProductId(name = "") {
    const short = name.replace(/\s+/g, "").slice(0, 3).toUpperCase();
    const rand = Math.random().toString(36).slice(2, 6).toUpperCase();
    return `PID-${short}-${rand}`;
  }

  function promptReferenceModal(products) {
    return new Promise((resolve) => {
      const overlay = document.createElement("div");
      overlay.style.cssText = `
        position:fixed; inset:0; background:rgba(0,0,0,0.45);
        display:flex; align-items:center; justify-content:center;
        z-index:999999; padding:18px;
      `;

      const card = document.createElement("div");
      card.style.cssText = `
        background:var(--card); padding:18px; border-radius:12px;
        width:100%; max-width:420px; box-shadow:0 12px 40px rgba(0,0,0,0.28);
        border:1px solid var(--glass-border);
      `;

      let productListHTML = "";
      products.forEach((p, i) => {
        productListHTML += `
          <div style="display:flex;justify-content:space-between;margin-bottom:6px;">
            <strong>${escapeHtml(p.name)}</strong>
            <span>${escapeHtml(p.priceFormatted)}</span>
          </div>`;
      });

      card.innerHTML = `
        <h3 style="margin-bottom:8px;color:var(--accent);">Confirm Order</h3>
        <div style="margin-bottom:10px; color:var(--muted); font-size:0.95rem;">
          You're ordering:<div style="margin-top:6px">${productListHTML}</div>
        </div>

        <label style="display:block;margin-bottom:6px;font-weight:600;">
          Reference name (buyer)
        </label>
        <input id="__orderRefInput" placeholder="Type buyer name or reference"
          style="width:100%;padding:10px;border-radius:10px;border:1px solid #e6e6e6;margin-bottom:12px;" />

        <div style="display:flex;gap:10px;justify-content:flex-end;">
          <button id="__orderCancel"
            style="background:transparent;border:1px solid #ddd;padding:8px 12px;border-radius:8px;cursor:pointer;">
            Cancel
          </button>
          <button id="__orderConfirm"
            style="background:var(--accent);color:#fff;border:none;padding:8px 12px;border-radius:8px;cursor:pointer;">
            Confirm
          </button>
        </div>
      `;

      overlay.appendChild(card);
      document.body.appendChild(overlay);

      const input = card.querySelector("#__orderRefInput");
      input.focus();

      card.querySelector("#__orderCancel").onclick = () => {
        overlay.remove();
        clearSelection();
        resolve(null);
      };
      card.querySelector("#__orderConfirm").onclick = () => {
        const val = input.value.trim();
        if (!val) {
          input.style.border = "1px solid #ff7373";
          input.focus();
          return;
        }
        overlay.remove();
        resolve(val);
      };
      overlay.onclick = (e) => {
        if (e.target === overlay) {
          overlay.remove();
          clearSelection();
          resolve(null);
        }
      };
    });
  }

  function chooseChatPlatformModal() {
    return new Promise((resolve) => {
      const overlay = document.createElement("div");
      overlay.style.cssText = `
        position:fixed; inset:0; background:rgba(0,0,0,0.45);
        display:flex; align-items:center; justify-content:center;
        z-index:999999; padding:18px;
      `;

      const modal = document.createElement("div");
      modal.style.cssText = `
        background:var(--card); padding:18px; border-radius:12px;
        width:100%; max-width:360px; box-shadow:0 12px 40px rgba(0,0,0,0.28);
        border:1px solid var(--glass-border); text-align:center;
      `;

      modal.innerHTML = `
        <h3 style="margin-bottom:8px;color:var(--accent);">Continue in Chat</h3>
        <p style="margin-bottom:12px;color:var(--muted);">
          Your order has been saved. Choose where you'd like to continue.
        </p>
        <div style="display:flex;gap:8px;justify-content:center;">
          <button id="__tg"
            style="background:#0088cc;color:#fff;border:none;padding:10px 12px;border-radius:8px;cursor:pointer;">
            Telegram
          </button>
          <button id="__wa"
            style="background:#25D366;color:#fff;border:none;padding:10px 12px;border-radius:8px;cursor:pointer;">
            WhatsApp
          </button>
        </div>
        <div style="margin-top:10px;">
          <button id="__skip"
            style="background:transparent;border:1px solid #ddd;padding:8px 12px;border-radius:8px;cursor:pointer;">
            Close
          </button>
        </div>
      `;

      overlay.appendChild(modal);
      document.body.appendChild(overlay);

      modal.querySelector("#__tg").onclick = () => {
        overlay.remove();
        resolve("telegram");
      };
      modal.querySelector("#__wa").onclick = () => {
        overlay.remove();
        resolve("whatsapp");
      };
      modal.querySelector("#__skip").onclick = () => {
        overlay.remove();
        resolve(null);
      };

      overlay.onclick = (e) => {
        if (e.target === overlay) {
          overlay.remove();
          resolve(null);
        }
      };
    });
  }

  /* ============================================================
     MESSAGES
     ============================================================ */

  function generateOrderMessage(orders) {
    if (!orders.length) return "Hello, I'd like to place an order.";

    const order = orders[0];
    const hour = new Date().getHours();

    const greeting =
      hour < 12
        ? "Good morning"
        : hour < 18
          ? "Good afternoon"
          : "Good evening";

    const productNames = order.products.map((p) => p.name).join(", ");

    return `${greeting}, I'd like to buy ${productNames} — ${order.orderId}\n💬 strongMaple`;
  }

  function sendToTelegram(orders) {
    const text = generateOrderMessage(orders);
    window.open(
      `https://t.me/strongMaple?text=${encodeURIComponent(text)}`,
      "_blank",
    );
  }

  function sendToWhatsApp(orders) {
    const text = generateOrderMessage(orders);
    window.open(
      `https://wa.me/2341231231231?text=${encodeURIComponent(text)}`,
      "_blank",
    );
  }

  /* ============================================================
     UTILS
     ============================================================ */

  function escapeHtml(str) {
    return String(str)
      .replace(/&/g, "&amp;")
      .replace(/"/g, "&quot;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;");
  }
});
