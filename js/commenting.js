const form = document.getElementById('comment-form');
const commentsContainer = document.getElementById('comments-container');


form.addEventListener('submit', function(event) {
  // prevent the default form submission behavior
  event.preventDefault();
  
  // get the value of the comment textarea
  const comment = document.getElementById('comment').value;

  // get data-id attribute of the form
  const id = event.target.dataset.id;
  
  // create a new paragraph element to hold the comment text
  const nameElement = document.createElement('p');
  const commentElement = document.createElement('p');
  commentElement.textContent = comment;
  nameElement.textContent = id;
  
  // append the comment element to the comments container
  commentsContainer.appendChild(nameElement);
  commentsContainer.appendChild(commentElement);
  
  // clear the comment textarea
  document.getElementById('comment').value = '';

  // style the comment element
    nameElement.style.color = 'white';
    commentElement.style.backgroundColor = 'white';
    commentElement.style.padding = '10px';
    commentElement.style.margin = '10px';
    commentElement.style.borderRadius = '10px';
    commentsContainer.style.maxWidth = '470px';
    commentsContainer.style.marginLeft = 'auto';
    commentsContainer.style.marginRight = 'auto';



});
