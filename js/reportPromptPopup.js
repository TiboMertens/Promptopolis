const flagBtn2 = document.querySelector('button[name="report"]');
        const flag = document.getElementById("flag");
        const overlay2 = document.getElementById("reportPopup");
        const close = document.querySelector(".close");

        flagBtn2.addEventListener("click", (e) => {
            e.preventDefault();
            overlay2.classList.add("hidden");
            overlay2.classList.remove('flex');
        });

        flag.addEventListener("click", (e) => {
            e.preventDefault();
            overlay2.classList.remove("hidden");
            overlay2.classList.add('flex');
        });

        close.addEventListener("click", () => {
            overlay2.classList.add("hidden");
            overlay2.classList.add('flex');
        });