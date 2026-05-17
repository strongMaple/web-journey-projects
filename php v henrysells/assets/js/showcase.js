document.addEventListener("DOMContentLoaded", () => {
  const showcase = document.querySelector(".showcase");
  if (!showcase) return;

  /*  
  -------------------------------------------------------
    PAGE-AWARE VIDEO PATH FIX
    index.html is reached via ../../index.html in your
    folder structure. So we detect the page and load
    the correct video path.
  -------------------------------------------------------
  */
  const currentPage = window.location.pathname.split("/").pop();
  const videoPath = currentPage === "index.html" ? "./video" : "../video/";

  /*
  -------------------------------------------------------
    CREATE VIDEO ELEMENT
  -------------------------------------------------------
  */
  const video = document.createElement("video");
  video.src = videoPath;
  video.muted = true;
  video.autoplay = true;
  video.loop = true;
  video.playsInline = true;
  video.preload = "auto";
  video.controls = false;
  video.setAttribute(
    "controlsList",
    "nodownload nofullscreen noremoteplayback"
  );
  video.setAttribute("disablePictureInPicture", "");

  // Fill container
  Object.assign(video.style, {
    width: "100%",
    height: "100%",
    objectFit: "cover",
    position: "absolute",
    inset: "0",
  });

  showcase.appendChild(video);

  /*
  -------------------------------------------------------
    VIDEO MEMORY SYSTEM — LocalStorage
    KEY: "showcase_video_time"
  -------------------------------------------------------
  */

  const STORAGE_KEY = "showcase_video_time";

  // Restore saved timestamp
  const savedTime = localStorage.getItem(STORAGE_KEY);
  if (savedTime) {
    video.currentTime = parseFloat(savedTime);
  }

  // Save currentTime every 500ms
  setInterval(() => {
    if (!video.paused && !video.seeking) {
      localStorage.setItem(STORAGE_KEY, video.currentTime);
    }
  }, 500);

  /*
  -------------------------------------------------------
    FORCE PLAYBACK (iOS + Chrome Autoplay Fix)
  -------------------------------------------------------
  */
  const tryPlay = () => {
    video.play().catch(() => {
      window.addEventListener(
        "touchstart",
        () => {
          video.play();
        },
        { once: true }
      );
    });
  };

  tryPlay();
});
