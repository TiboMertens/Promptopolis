// get the button with the name "report"
const flagBtn = document.querySelector('button[name="report"]');
const flagIcon = document.querySelector('#flag');
// add console.log when follow button is clicked
flagBtn.addEventListener('click', (e) => {
    e.preventDefault();
    // get the id of the user being followed
    const id = e.target.dataset.id;
    
    
    let formData = new FormData();
    //append user id to formdata
    formData.append("id", id);
    fetch("ajax/reportPrompt.php", {
            method: "POST",
            body: formData,
        })
        .then(function(response) {
            return response.json();
        })
        .then(function(json) {     
            flagIcon.classList.remove("fa-regular");
            flagIcon.classList.add("fa-solid");
        });
});