// -----------------------------------------------------------
// ADVANCED PRODUCT SEARCH MODULE
// -----------------------------------------------------------
document.addEventListener("DOMContentLoaded", () => {
  const searchInput = document.querySelector("#search-input");
  const searchBtn = document.querySelector("#search-btn");

  if (!searchInput || !searchBtn) return;

  const productContainers = [
    ...document.querySelectorAll(
      "#deals, .deals, [data-products='true'], .product-grid, .products"
    ),
  ];

  const products = [...document.querySelectorAll(".product-card")];

  if (products.length === 0) {
    searchBtn.addEventListener("click", (e) => e.preventDefault());
    return;
  }

  // -----------------------------------------------------------
  // NO MATCH MESSAGE
  // -----------------------------------------------------------
  const noMatchMsg = document.createElement("p");
  Object.assign(noMatchMsg.style, {
    display: "none",
    textAlign: "center",
    marginTop: "20px",
    color: "var(--text-clr)",
    fontFamily: '"Montserrat", sans-serif',
    fontSize: "0.95rem",
    transition: "opacity 0.3s ease",
  });

  const lastContainer =
    productContainers[productContainers.length - 1] || document.body;
  lastContainer.insertAdjacentElement("afterend", noMatchMsg);

  let lastQuery = "";
  let activeIndex = -1; // For arrow key navigation
  let visibleProducts = [];

  // -----------------------------------------------------------
  // BASIC + FUZZY TEXT MATCH
  // -----------------------------------------------------------
  function fuzzyMatch(text, search) {
    if (!search) return true;
    text = text.toLowerCase();
    search = search.toLowerCase();

    if (text.includes(search)) return true;

    let mismatches = 0;
    let j = 0;
    for (let i = 0; i < text.length && j < search.length; i++) {
      if (text[i] === search[j]) j++;
      else mismatches++;
      if (mismatches > 2) return false;
    }
    return j >= search.length - 1;
  }

  // -----------------------------------------------------------
  // HIGHLIGHT MATCHES
  // -----------------------------------------------------------
  function highlight(text, search) {
    if (!search) return text;
    const r = new RegExp(`(${search})`, "ig");
    return text.replace(r, "<mark>$1</mark>");
  }

  function clearHighlights() {
    products.forEach((p) => {
      const title = p.querySelector(".product-info h3");
      const desc = p.querySelector(".product-info p");

      if (title) title.innerHTML = title.textContent;
      if (desc) desc.innerHTML = desc.textContent;
    });
  }

  // -----------------------------------------------------------
  // SCROLLING
  // -----------------------------------------------------------
  function scrollToProducts() {
    const container = productContainers[0];
    if (container) {
      container.scrollIntoView({ behavior: "smooth", block: "start" });
    }
  }

  function scrollToTop() {
    window.scrollTo({ top: 0, behavior: "smooth" });
  }

  // -----------------------------------------------------------
  // VIDEO CONTROL
  // -----------------------------------------------------------
  function pauseAllVideos() {
    document.querySelectorAll(".product-video").forEach((v) => v.pause());
  }

  function resumeVisibleVideos() {
    visibleProducts.forEach((product) => {
      const video = product.querySelector(".product-video");
      if (!video) return;
      video.play().catch(() => {});
    });
  }

  // -----------------------------------------------------------
  // RESET STATE
  // -----------------------------------------------------------
  function resetSearch() {
    clearHighlights();
    products.forEach((p) => (p.style.display = ""));
    visibleProducts = [...products];
    noMatchMsg.style.display = "none";
    activeIndex = -1;
    resumeVisibleVideos();
  }

  // -----------------------------------------------------------
  // RUN SEARCH (ONLY ON BUTTON or ENTER)
  // -----------------------------------------------------------
  function performSearch() {
    const query = searchInput.value.trim().toLowerCase();
    clearHighlights();

    if (!query) {
      resetSearch();
      scrollToTop();
      return;
    }

    lastQuery = query;
    visibleProducts = [];
    let found = false;

    products.forEach((product) => {
      const nameEl = product.querySelector(".product-info h3");
      const descEl = product.querySelector(".product-info p");
      const name = nameEl?.textContent.toLowerCase() || "";
      const desc = descEl?.textContent.toLowerCase() || "";

      const match = fuzzyMatch(name, query) || fuzzyMatch(desc, query);

      if (match) {
        product.style.display = "";
        found = true;
        visibleProducts.push(product);

        if (nameEl) nameEl.innerHTML = highlight(nameEl.textContent, query);
        if (descEl) descEl.innerHTML = highlight(descEl.textContent, query);
      } else {
        product.style.display = "none";
      }
    });

    if (!found) {
      noMatchMsg.textContent = `No results found for "${query}".`;
      noMatchMsg.style.display = "block";
      pauseAllVideos();
    } else {
      noMatchMsg.style.display = "none";
      resumeVisibleVideos();
    }

    scrollToProducts();
  }

  // -----------------------------------------------------------
  // KEYBOARD NAVIGATION (↑ ↓ + ENTER)
  // -----------------------------------------------------------
  function updateActiveHighlight() {
    visibleProducts.forEach((p, index) => {
      p.classList.toggle("active-search-item", index === activeIndex);
    });

    if (visibleProducts[activeIndex]) {
      visibleProducts[activeIndex].scrollIntoView({
        block: "center",
        behavior: "smooth",
      });
    }
  }

  searchInput.addEventListener("keydown", (e) => {
    if (e.key === "ArrowDown") {
      e.preventDefault();
      if (visibleProducts.length > 0) {
        activeIndex = (activeIndex + 1) % visibleProducts.length;
        updateActiveHighlight();
      }
    }

    if (e.key === "ArrowUp") {
      e.preventDefault();
      if (visibleProducts.length > 0) {
        activeIndex =
          (activeIndex - 1 + visibleProducts.length) % visibleProducts.length;
        updateActiveHighlight();
      }
    }

    if (e.key === "Enter") {
      e.preventDefault();
      performSearch();
    }

    if (e.key === "Escape") {
      searchInput.value = "";
      resetSearch();
      scrollToTop();
    }
  });

  // -----------------------------------------------------------
  // AUTOCOMPLETE (simple suggestion under input)
  // -----------------------------------------------------------
  const suggestionBox = document.createElement("div");
  Object.assign(suggestionBox.style, {
    position: "absolute",
    background: "white",
    color: "black",
    padding: "8px 12px",
    borderRadius: "8px",
    marginTop: "6px",
    fontSize: "0.85rem",
    display: "none",
    zIndex: 50,
  });
  searchInput.parentElement.appendChild(suggestionBox);

  searchInput.addEventListener("input", () => {
    const value = searchInput.value.trim().toLowerCase();
    if (!value) {
      suggestionBox.style.display = "none";
      return;
    }

    const suggestion = products.find((p) => {
      const title =
        p.querySelector(".product-info h3")?.textContent.toLowerCase() || "";
      return title.startsWith(value);
    });

    if (suggestion) {
      suggestionBox.textContent =
        suggestion.querySelector(".product-info h3").textContent;
      suggestionBox.style.display = "block";
    } else {
      suggestionBox.style.display = "none";
    }
  });

  suggestionBox.addEventListener("click", () => {
    searchInput.value = suggestionBox.textContent;
    suggestionBox.style.display = "none";
    performSearch();
  });

  // -----------------------------------------------------------
  // SEARCH BUTTON
  // -----------------------------------------------------------
  searchBtn.addEventListener("click", (e) => {
    e.preventDefault();
    performSearch();
  });
});
