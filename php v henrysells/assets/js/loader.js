document.addEventListener("DOMContentLoaded", () => {
  // We wait for the entire HTML structure to be ready first.

  // Using the 'load' event ensures all linked resources (images, CSS, etc.) are fully fetched.
  window.addEventListener("load", () => {
    const preloader = document.getElementById("preloader");

    // Safety check: ensure the preloader element exists
    if (preloader) {
      // Step 1: Hide the preloader using the CSS transition class
      preloader.classList.add("preloader-hidden");

      // Step 2: Remove the preloader element from the DOM after the transition finishes (1.5s, as defined in CSS)
      // This prevents it from interfering with any clicks or interactions.
      setTimeout(() => {
        preloader.remove();
      }, 1500); // Must match the CSS transition duration (1.5s)
    }
  });

  // Optional: Add a timeout as a fail-safe, in case the 'load' event is delayed by external factors.
  // E.g., if it hasn't loaded in 5 seconds, hide it anyway.
  setTimeout(() => {
    const preloader = document.getElementById("preloader");
    if (preloader && !preloader.classList.contains("preloader-hidden")) {
      preloader.classList.add("preloader-hidden");
      setTimeout(() => {
        preloader.remove();
      }, 1500);
      console.warn("Preloader force-hidden after timeout.");
    }
  }, 5000); // 5 second timeout limit
});
