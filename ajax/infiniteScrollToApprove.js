var offset = 0;
var limit = 20;

// Initialize loading flag
var loading = false;

function loadImages() {
  // Set loading flag to true to prevent multiple requests from being made simultaneously
  loading = true;

  // Create new XMLHttpRequest object
  var xhr = new XMLHttpRequest();

  // Set request method, URL and headers
  xhr.open("POST", "./loadPromptsToApprove.php", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

  // Handle response from server
  xhr.onload = function () {
    if (xhr.status === 200) {
      // Parse JSON response
      var images = JSON.parse(xhr.responseText);
      console.log("Parsed images:", images);

      // Loop through images and create img elements
      if (images.length > 0) {
        for (var i = 0; i < images.length; i++) {
          var img = document.createElement("img");
          img.setAttribute("src", images[i].cover_url);
          img.classList.add("flex", "w-1/4");
          document.getElementById("image-container").appendChild(img);
        }

        // Update offset to keep track of number of images loaded
        offset += limit;

        // Set loading flag to false to allow new requests to be made
        loading = false;
      } else {
        // If there are no more images to load, remove scroll event listener to prevent further requests
        window.removeEventListener("scroll", checkScroll);
      }
    }
  };

  // Send AJAX request with offset and limit parameters
  xhr.send("offset=" + offset + "&limit=" + limit);
}

// Function to check if user has scrolled to bottom of page
function checkScroll() {
  if (
    window.scrollY + window.innerHeight >=
    document.documentElement.scrollHeight
  ) {
    // If user has scrolled to bottom of page and no requests are currently loading, call loadImages() function to fetch new images
    if (!loading) {
      loadImages();
    }
  }
}

// Attach scroll event listener to window object to trigger checkScroll() function when user scrolls
window.addEventListener("scroll", checkScroll);

// Load initial images
loadImages();