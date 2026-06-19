// Snow Effect
let canvas, ctx;
let snowflakes = [];
let snowing = false;
let animationId;

function setupCanvas() {
  canvas.width = window.innerWidth;
  canvas.height = window.innerHeight;
}

function createCanvas() {
  canvas = document.createElement('canvas');
  canvas.style.position = 'fixed';
  canvas.style.top = '0';
  canvas.style.left = '0';
  canvas.style.pointerEvents = 'none';
  canvas.style.zIndex = '999';
  document.body.appendChild(canvas);
  ctx = canvas.getContext('2d');
  setupCanvas();
}

function createSnowflake() {
  return {
    x: Math.random() * canvas.width,
    y: Math.random() * canvas.height,
    radius: Math.random() * 3 + 1,
    speed: Math.random() * 2 + 0.5,
    wind: Math.random() * 2 - 1,
  };
}

function updateSnowflakes() {
  snowflakes.forEach((flake) => {
    flake.y += flake.speed;
    flake.x += flake.wind;

    if (flake.y > canvas.height) {
      flake.y = 0;
      flake.x = Math.random() * canvas.width;
    }

    if (flake.x > canvas.width) {
      flake.x = 0;
    } else if (flake.x < 0) {
      flake.x = canvas.width;
    }
  });
}

function drawSnowflakes() {
  ctx.clearRect(0, 0, canvas.width, canvas.height);
  ctx.fillStyle = 'white';
  ctx.beginPath();

  snowflakes.forEach((flake) => {
    ctx.moveTo(flake.x, flake.y);
    ctx.arc(flake.x, flake.y, flake.radius, 0, Math.PI * 2);
  });

  ctx.fill();
}

function animateSnowflakes() {
  updateSnowflakes();
  drawSnowflakes();
  animationId = requestAnimationFrame(animateSnowflakes);
}

function startSnowing() {
  if (!canvas) createCanvas();
  setupCanvas();
  snowflakes = Array.from({ length: 100 }, createSnowflake);
  animateSnowflakes();
  window.addEventListener('resize', setupCanvas);
}

function stopSnowing() {
  cancelAnimationFrame(animationId);
  if (canvas) {
    window.removeEventListener('resize', setupCanvas);
    canvas.remove();
    canvas = null;
    ctx = null;
  }
}

document.querySelector('.snowButton').addEventListener('click', () => {
  snowing = !snowing;
  if (snowing) {
    startSnowing();
  } else {
    stopSnowing();
  }
});
