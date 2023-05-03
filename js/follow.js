// get the follow button
const followBtn = document.querySelector('button[name="follow"]')
// add console.log when follow button is clicked
followBtn.addEventListener('click', (e) => {
    e.preventDefault();
    // get the id of the user being followed
    const id = e.target.dataset.id;
    // get the state of the follow button
    const state = e.target.dataset.state;
    let formData = new FormData();
    //append user id to formdata
    formData.append("id", id);
    formData.append("state", state);
    fetch("ajax/follow.php", {
            method: "POST",
            body: formData,
        })
        .then(function(response) {
            return response.json();
        })
        .then(function(json) {
            followBtn.innerHTML = json.message;

            followBtn.setAttribute("data-state", json.message);


        });
});