// lumos.js
let glowLayer;
let mouseX = 0;
let mouseY = 0;

function createGlowLayer() {
  glowLayer = document.createElement('div');
  glowLayer.style.position = 'fixed';
  glowLayer.style.top = '0';
  glowLayer.style.left = '0';
  glowLayer.style.width = '100vw';
  glowLayer.style.height = '100vh';
  glowLayer.style.pointerEvents = 'none';
  glowLayer.style.zIndex = '1';
  glowLayer.style.overflow = 'hidden';

  const glow = document.createElement('div');
  glow.id = 'glow-circle';
  glow.style.position = 'absolute';
  glow.style.width = '1000px';
  glow.style.height = '1000px';
  glow.style.background =
    'radial-gradient(circle, var(--white-clr, #fff) 0%, transparent 60%)';
  glow.style.filter = 'blur(200px)';
  glow.style.opacity = '0.2';
  glow.style.pointerEvents = 'none';
  glow.style.zIndex = '1';
  glowLayer.appendChild(glow);

  document.body.appendChild(glowLayer);
}

function updateGlowPosition() {
  const glow = document.getElementById('glow-circle');
  if (glow) {
    glow.style.left = `${mouseX - 500}px`;
    glow.style.top = ` ${mouseY - 500}px`;
  }
  requestAnimationFrame(updateGlowPosition);
}

function startLumos() {
  createGlowLayer();
  requestAnimationFrame(updateGlowPosition);
}

window.addEventListener('mousemove', (e) => {
  mouseX = e.clientX;
  mouseY = e.clientY;
});

startLumos();
