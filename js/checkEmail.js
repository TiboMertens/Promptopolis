//get the input with name email
const email = document.querySelector('input[name="email"]');
//get p tag with id errEmail
const errEmail = document.querySelector('#errEmail');

//add eventlistener to email onchange
email.addEventListener('change', function() {
//get email value
const emailValue = email.value;

    //create formdata object
    let formData = new FormData();
    //append user id to formdata
    formData.append('email', emailValue);

    //make a fetch request to check if email is available
    fetch('ajax/checkEmail.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(result => {
            //if result is true, email is available
            if (result.status == 'error') {
                //add error class
                email.classList.add('border-red-500');
                errEmail.innerHTML = result.message;
            } else {
                //remove error class
                email.classList.remove('border-red-500');
                errEmail.innerHTML = '';
            }
        });
});