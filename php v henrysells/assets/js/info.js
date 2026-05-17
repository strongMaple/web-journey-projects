// Auto year update
document.getElementById("year").textContent = new Date().getFullYear();

// Popup functionality
const popup = document.getElementById("popup");
const popupTitle = document.getElementById("popup-title");
const popupText = document.getElementById("popup-text");

// Reusable Google Maps Embed (UNN)
const unnMapEmbed = `
  <div style="width:100%; height:300px; border-radius:10px; overflow:hidden; margin-top:1rem;">
    <iframe 
      src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3979.8545325745925!2d7.41252897373187!3d6.857341118966986!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x10439d148c7e8b1d%3A0xc5d481f5cd6243ad!2sUniversity%20of%20Nigeria%2C%20Nsukka!5e0!3m2!1sen!2sng!4v1700000000000"
      width="100%"
      height="100%"
      style="border:0;"
      allowfullscreen=""
      loading="lazy"
      referrerpolicy="no-referrer-when-downgrade">
    </iframe>
  </div>
`;

const popupData = {
  about: {
    title: "About Strong Maple",
    text: `Strong Maple delivers curated, high-quality phones, laptops, and accessories—offering genuine products, fair pricing, and a refined shopping experience.`,
  },

  help: {
    title: "Help & Support",
    text: `
      <strong>Need Help?</strong><br><br>
      I personally handle every order, inquiry, and customer request. Whether you're interested in a phone, laptop, or accessory, I’m here to guide you through a smooth buying process.
      <br><br>
      <strong>How I Support You:</strong><br>
      • Product recommendations<br>
      • Price inquiries<br>
      • Availability checks<br>
      • Order confirmation<br>
      • Delivery information<br>
      • Warranty or after-sale support
    `,
  },

  contact: {
    title: "Find Me Here",
    text: `
      I'm a UNN student & verified gadget supplier, offering nationwide delivery with reliability you can trust.  
      Whether you're around campus or anywhere in Nigeria, I make sure every order is handled personally, quickly, and professionally.
      <br><br>
      <strong>My Location:</strong>
      ${unnMapEmbed}
    `,
  },
};

// Open popup
document.querySelectorAll(".footer-btn").forEach((btn) => {
  btn.addEventListener("click", () => {
    const key = btn.getAttribute("data-popup");
    const { title, text } = popupData[key];

    popupTitle.innerHTML = title;
    popupText.innerHTML = text;

    popup.style.display = "flex";
    document.body.style.overflow = "hidden";
  });
});

// Close popup (X + click outside)
document.addEventListener("click", (e) => {
  if (e.target.classList.contains("close-btn") || e.target === popup) {
    popup.style.display = "none";
    document.body.style.overflow = "auto";
  }
});
