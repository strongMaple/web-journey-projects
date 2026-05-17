const githubBtn = document.querySelector(".github-btn");
const modal = document.getElementById("githubModal");
const closeBtn = modal.querySelector(".close-modal");

const openModal = () => {
  modal.style.display = "flex";
  document.body.style.overflow = "hidden"; // lock scroll
};

const closeModal = () => {
  modal.style.display = "none";
  document.body.style.overflow = "";
};

githubBtn.addEventListener("click", openModal);
closeBtn.addEventListener("click", closeModal);

modal.addEventListener("click", (e) => {
  if (e.target === modal) {
    closeModal();
  }
});
