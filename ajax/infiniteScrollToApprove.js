var offset = 0;
var limit = 20;

// Initialize loading flag
var loading = false;

function loadPrompts() {
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
      var prompts = JSON.parse(xhr.responseText);
      console.log("Parsed prompts:", prompts);

      // Loop through prompts and create img elements
      if (prompts.length > 0) {
        for (var i = 0; i < prompts.length; i++) {
          var img = document.createElement("img");
          img.setAttribute("src", prompts[i].cover_url);
          img.classList.add("object-contain", "h-full", "w-full");
          img.setAttribute("id", "prompt");

          var a = document.createElement("a");
          a.classList.add("block", "flex", "w-1/4");
          a.setAttribute("href", "promptDetails.php?id=" + prompts[i].id);
          a.appendChild(img);

          document.getElementById("image-container").appendChild(a);
        }

        // Update offset to keep track of number of prompts loaded
        offset += limit;

        // Set loading flag to false to allow new requests to be made
        loading = false;
      } else {
        // If there are no more prompts to load, remove scroll event listener to prevent further requests
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
    // If user has scrolled to bottom of page and no requests are currently loading, call loadPrompts() function to fetch new prompts
    if (!loading) {
      loadPrompts();
    }
  }
}

// Attach scroll event listener to window object to trigger checkScroll() function when user scrolls
window.addEventListener("scroll", checkScroll);

// Load initial prompts
loadPrompts();
