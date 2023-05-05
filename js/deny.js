const deny = document.getElementById("deny");
const overlay = document.getElementById("denypopup");
const close = document.querySelector(".close");

deny.addEventListener("click", (e) => {
    e.preventDefault();
    overlay.classList.remove("hidden");
    overlay.classList.add('flex');
});

close.addEventListener("click", () => {
    overlay.classList.add("hidden");
    overlay.classList.add('flex');
});