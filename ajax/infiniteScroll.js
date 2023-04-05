var offset = 0;
var limit = 20;
var loading = false;

function loadImages() {
  loading = true;
  var xhr = new XMLHttpRequest();
  xhr.open("POST", "./load-images.php", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xhr.onload = function () {
    if (xhr.status === 200) {
      var images = JSON.parse(xhr.responseText);
      console.log("Parsed images:", images);
      if (images.length > 0) {
        for (var i = 0; i < images.length; i++) {
          var img = document.createElement("img");
          img.setAttribute("src", images[i].image_url);
          img.classList.add("flex", "w-1/4");
          document.getElementById("image-container").appendChild(img);
        }
        offset += limit;
        loading = false;
      } else {
        window.removeEventListener("scroll", checkScroll);
      }
    }
  };
  xhr.send("offset=" + offset + "&limit=" + limit);
}

function checkScroll() {
  if (
    window.scrollY + window.innerHeight >=
    document.documentElement.scrollHeight
  ) {
    if (!loading) {
      loadImages();
    }
  }
}

window.addEventListener("scroll", checkScroll);

// Load initial images
loadImages();
