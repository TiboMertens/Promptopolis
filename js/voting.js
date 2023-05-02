//add click event to voted button
const voted = document.querySelector('[name="voted"]');

//select the voting text
const voting = document.querySelector(".voting");

//select the p tag with id message
var message = document.querySelector(".message");

//add eventlistener
voted.addEventListener("click", function(e) {
    e.preventDefault();

    let user_id = this.getAttribute("data-id");

    //create formdata object
    let formData = new FormData();
    //append user id to formdata
    formData.append("user_id", user_id);

    console.log(formData);
    fetch("ajax/votes.php", {
            method: "POST",
            body: formData,
        })
        .then(function(response) {
            return response.json();
        })
        .then(function(json) {
            voting.innerHTML = "Votes: " + json.votes + "/2";
            message.innerHTML = json.message;
        });
});