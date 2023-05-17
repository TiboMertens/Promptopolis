// get the button with the name "report"
const flag = document.querySelector("#flagUser");
console.log("tets");
// add console.log when follow button is clicked
flag.addEventListener("click", (e) => {
  console.log("clicked");
  e.preventDefault();
  // get the id of the user being followed
  const id = e.target.dataset.id;

  const state = e.target.dataset.flag;
  console.log(state);

  let formData = new FormData();
  //append user id to formdata
  formData.append("id", id);
  formData.append("state", state);

  fetch("ajax/reportUser.php", {
    method: "POST",
    body: formData,
  })
    .then(function (response) {
      return response.json();
    })
    .then(function (json) {
      console.log(json);

      if (json.state == "reported") {
        flag.classList.remove("fa-regular");
        flag.classList.add("fa-solid");
      } else {
        flag.classList.remove("fa-solid");
        flag.classList.add("fa-regular");
      }

      flag.setAttribute("data-flag", json.state);
    });
});
