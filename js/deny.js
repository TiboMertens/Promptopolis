const deny = document.getElementById("deny");
const overlay = document.getElementById("denyPopup");
const close2 = document.querySelector(".close");

deny.addEventListener("click", (e) => {
    e.preventDefault();
    overlay.classList.remove("hidden");
    overlay.classList.add('flex');
});

close2.addEventListener("click", () => {
    overlay.classList.add("hidden");
    overlay.classList.add('flex');
});