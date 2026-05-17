document.addEventListener("DOMContentLoaded", () => {
  const videos = Array.from(document.querySelectorAll(".product-video"));
  let activeIndex = -1;
  let userScrolling = false;
  let scrollTimeout;

  // Track user scrolling to avoid hijacking
  window.addEventListener("scroll", () => {
    userScrolling = true;
    clearTimeout(scrollTimeout);
    scrollTimeout = setTimeout(() => {
      userScrolling = false;
    }, 600);
  });

  // Pause videos when leaving tab
  document.addEventListener("visibilitychange", () => {
    if (document.hidden) {
      videos.forEach((v) => v.pause());
    } else {
      if (activeIndex !== -1) {
        videos[activeIndex].play().catch(() => {});
      }
    }
  });

  // Intersection Observer
  const observer = new IntersectionObserver(
    (entries) => {
      const visible = entries
        .filter((e) => e.isIntersecting)
        .sort((a, b) => {
          return videos.indexOf(a.target) - videos.indexOf(b.target);
        })
        .map((e) => videos.indexOf(e.target));

      if (!visible.length) return;

      const newIndex = visible[0];

      // If the visible video changed → switch immediately
      if (newIndex !== activeIndex) {
        activateVideo(newIndex, visible);
      }
    },
    { threshold: 0.65 }
  );

  // Init
  videos.forEach((v) => {
    observer.observe(v);
    v.muted = true;
    v.playsInline = true;
    v.preload = "metadata";
    v.pause();
  });

  // 🔥 Play selected visible video & infinitely loop through visibleStack
  function activateVideo(index, visibleStack) {
    activeIndex = index;

    // Pause all other videos
    videos.forEach((v, i) => {
      if (i !== index) v.pause();
    });

    const current = videos[index];
    current.play().catch(() => {});

    current.onended = () => {
      const pos = visibleStack.indexOf(index);
      let next = visibleStack[pos + 1];

      // 🔁 If there's no next visible → loop back to the first
      if (next === undefined) {
        next = visibleStack[0];
      }

      activateVideo(next, visibleStack);
    };
  }
});
