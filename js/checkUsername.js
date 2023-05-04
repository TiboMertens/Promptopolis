//get the input with name username
const username = document.querySelector('input[name="username"]');
//get p tag with id errUsername
const errUsername = document.querySelector("#errUsername");

//add eventlistener to username onchange
username.addEventListener("change", function () {
  //get username value
  const usernameValue = username.value;

  //create formdata object
  let formData = new FormData();
  //append user id to formdata
  formData.append("username", usernameValue);

  //make a fetch request to check if username is available
  fetch("ajax/checkUsername.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.json())
    .then((result) => {
      //if result is true, username is available
      if (result.status == "error") {
        //add error class
        username.classList.add("border-red-500");
        errUsername.innerHTML = result.message;
      } else {
        //remove error class
        username.classList.remove("border-red-500");
        errUsername.innerHTML = "";
      }
    });
});
