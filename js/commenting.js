const form = document.getElementById('comment-form');
const commentsContainer = document.getElementById('comments-container');
// get p with id error
const errorElement = document.getElementById('error');
const credits = document.getElementById('credits');
form.addEventListener('submit', function(event) {
  // prevent the default form submission behavior
  event.preventDefault();
  
  // get the value of the comment textarea
  const comment = document.getElementById('comment').value;

  // get data-id attribute of the form
  const id = event.target.dataset.id;
  const user = event.target.dataset.user;

  if (comment != '') {
    // create a new paragraph element to hold the comment text
  const nameElement = document.createElement('p');
  const commentElement = document.createElement('p');
  commentElement.textContent = comment;
  nameElement.textContent = user;
  
  // append the comment element to the comments container
  commentsContainer.insertBefore(commentElement,commentsContainer.firstChild);
  commentsContainer.insertBefore(nameElement, commentsContainer.firstChild);
  
  // clear the comment textarea
  document.getElementById('comment').value = '';

  // style the comment element
    nameElement.style.color = 'white';
    commentElement.style.backgroundColor = 'white';
    commentElement.style.padding = '10px';
    commentElement.style.marginTop = '10px';
    commentElement.style.marginBottom = '10px';
    commentElement.style.borderRadius = '10px';
    commentsContainer.style.maxWidth = '470px';
    commentsContainer.style.marginLeft = 'auto';
    commentsContainer.style.marginRight = 'auto';

  }
  
  
    let formData = new FormData();
    //append user id to formdata
    formData.append("id", id);
    formData.append("comment", comment);
    fetch("ajax/comments.php", {
            method: "POST",
            body: formData,
        })
        .then(function(response) {
            return response.json();
        })
        .then(function(json) {
          if(json.status == 'error'){
            errorElement.innerHTML= json.message;
          }else{
            errorElement.innerHTML = '';
          }
          credits.innerHTML =  "Credits " + json.credits;
          
        });

});
