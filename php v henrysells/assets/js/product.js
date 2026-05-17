import { app, db } from "../../firebase/firebaseConfig.js";
import {
  doc,
  getDoc,
} from "https://www.gstatic.com/firebasejs/11.0.0/firebase-firestore.js";

/* ===============================
   Get product ID from URL
================================ */
function getProductId() {
  const params = new URLSearchParams(window.location.search);
  return params.get("id");
}

const productId = getProductId();
if (!productId) {
  alert("Invalid product link.");
  window.location.href = "/";
}

/* DOM Elements */
const carouselContainer = document.getElementById("carousel");
const specList = document.getElementById("specList");
const closeBtn = document.getElementById("closeBtn");

closeBtn.addEventListener("click", () => {
  window.history.back();
});

/* ===============================
   Load Product
================================ */
async function loadProduct() {
  const ref = doc(db, "products", productId);
  const snap = await getDoc(ref);

  if (!snap.exists()) {
    alert("Product not found.");
    window.location.href = "/";
    return;
  }

  const p = snap.data();

  buildCarousel(p.media);
  buildSpecs(p.specs);
  updateMetaSEO(p, productId);
}

loadProduct();

/* ===============================
   Build Carousel (images & videos)
================================ */
function buildCarousel(media) {
  if (!media) return;

  let gallery = [];

  if (media.primaryUrl) {
    gallery.push({
      type: media.type === "video" ? "video" : "image",
      url: media.primaryUrl,
    });
  }

  if (Array.isArray(media.gallery)) {
    gallery.push(...media.gallery);
  }

  gallery.forEach((item, index) => {
    const el = document.createElement("div");
    el.classList.add("carousel-item");
    if (index === 0) el.classList.add("active");

    el.innerHTML =
      item.type === "video"
        ? `<video src="${item.url}" controls></video>`
        : `<img src="${item.url}" alt="Product media" />`;

    carouselContainer.appendChild(el);
  });

  initCarousel(carouselContainer);
}

/* ===============================
   Specifications List
================================ */
function buildSpecs(arr) {
  if (!Array.isArray(arr)) return;
  arr.forEach((spec) => {
    const li = document.createElement("li");
    li.textContent = spec;
    specList.appendChild(li);
  });
}

/* ===============================
   Carousel Animation Logic
================================ */
function initCarousel(carousel) {
  const items = carousel.querySelectorAll(".carousel-item");
  const videos = carousel.querySelectorAll("video");

  let index = 0;
  let playing = true;

  const showSlide = (newIndex) => {
    items[index].classList.remove("active");
    index = (newIndex + items.length) % items.length;
    items[index].classList.add("active");
  };

  setInterval(() => {
    if (playing) showSlide(index + 1);
  }, 4000);

  videos.forEach((v) => {
    v.addEventListener("play", () => (playing = false));
    v.addEventListener("pause", () => (playing = true));
    v.addEventListener("ended", () => (playing = true));
  });
}

/* ===============================
   SEO META UPDATER
================================ */
function updateMetaSEO(p, id) {
  const url = `${window.location.origin}/product.html?id=${id}`;

  const productName = p.name || "Product";
  const description =
    p.description || p.shortSpec || "Product details and specifications.";
  const price = p.priceNumeric || "";
  const firstImg = p.media?.primaryUrl || "";

  /* Basic */
  document.getElementById("meta-title").innerText =
    productName + " • HenrySells";
  document.getElementById("meta-description").content = description;
  document.getElementById("meta-canonical").href = url;

  /* Open Graph */
  document.getElementById("og-title").content = productName;
  document.getElementById("og-desc").content = description;
  document.getElementById("og-image").content = firstImg;
  document.getElementById("og-url").content = url;

  /* Twitter Card */
  document.getElementById("tw-title").content = productName;
  document.getElementById("tw-desc").content = description;
  document.getElementById("tw-image").content = firstImg;

  /* JSON-LD (Product Rich Snippet) */
  const jsonLdTag = document.getElementById("json-ld");
  const schema = {
    "@context": "https://schema.org/",
    "@type": "Product",
    name: productName,
    image: [firstImg],
    description: description,
    brand: { "@type": "Brand", name: "HenrySells" },
    offers: {
      "@type": "Offer",
      url: url,
      priceCurrency: "NGN",
      price: price,
      availability: "https://schema.org/InStock",
    },
  };

  jsonLdTag.textContent = JSON.stringify(schema, null, 2);
}
