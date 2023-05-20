//get i tags with name fav
const fav = document.querySelector('[name="fav"]');

fav.addEventListener('click', () => {
    console.log('clicked');

    //get the data-fav attribute
    const state = fav.getAttribute('data-fav');
    //get the data-id attribute
    const id = fav.getAttribute('data-id');

    //create formdata object
    let formData = new FormData();

    //append data to formdata object
    formData.append('state', state);
    formData.append('id', id);

    //send ajax request
    fetch('ajax/favPrompts.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(result => {
            //change the data-fav attribute
            fav.setAttribute('data-fav', result.state);
            if (result.state == 'add') {
                fav.classList.remove('fa-solid');
                fav.classList.add('fa-regular');
            } else {
                fav.classList.remove('fa-regular');
                fav.classList.add('fa-solid');
            }
        })
});