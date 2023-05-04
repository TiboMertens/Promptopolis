//select a tag with id heart
const liked = document.querySelector("#heart");

//select the like text
const liking = document.querySelector(".liking");

//add eventlistener to the heart
liked.addEventListener("click", (e) => {
    e.preventDefault();

    console.log("clicked");

    let prompt_id = liked.getAttribute("data-id");
    const state = liked.getAttribute('data-liked');

    //create formdata object
    let formData = new FormData();
    //append user id to formdata
    formData.append("prompt_id", prompt_id);
    //get the state of the like
    formData.append("state", state);

    // fetch with ajax to likes.php and make add and remove like
    fetch("ajax/likes.php", {
            method: "POST",
            body: formData,
        })
        .then(function(response) {
            return response.json();
        })

        .then(function(json) {
            liking.innerHTML = json.likes;
            if (state == "add") {
                liked.setAttribute("data-liked", "remove");
                liked.classList.remove("fa-regular");
                liked.classList.add("fa-solid");
            } else {
                liked.setAttribute("data-liked", "add");
                liked.classList.remove("fa-solid");
                liked.classList.add("fa-regular");
            }
        })

        .catch(function(error) {
            console.log(error);
        });
});
