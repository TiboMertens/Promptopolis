const tagContainer = document.getElementById("tag-container");
const addTagButton = document.getElementById("add-tag-btn");

let tagCount = 1;

addTagButton.addEventListener("click", function(e) {
    e.preventDefault();
  if (tagCount < 3) {
    const newTagInput = document.createElement("input");
    newTagInput.setAttribute("type", "text");
    newTagInput.setAttribute("name", `tag${tagCount + 1}`);
    newTagInput.setAttribute("id", `tag${tagCount + 1}`);
    newTagInput.setAttribute("class","w-full px-3 py-2 border-[3px] rounded hover:border-[#A25AFB] active:border-[#A25AFB]");
    const newTagLabel = document.createElement("label");
    newTagLabel.setAttribute("for", `tag${tagCount + 1}`);
    newTagLabel.innerText = `tag ${tagCount + 1}`;
    newTagLabel.setAttribute("class","block font-bold mb-0.5 text-white");
    tagContainer.appendChild(newTagLabel);
    tagContainer.appendChild(newTagInput);
    tagCount++;
  } else {
    addTagButton.disabled = true;
  }
});

function previewFile() {
  var preview = document.getElementById('preview');
  var file = document.getElementById('mainImage').files[0];
  var reader = new FileReader();

  reader.onloadend = function() {
    preview.src = reader.result;
  }

  if (file) {
    reader.readAsDataURL(file);
  } else {
    preview.src = "<?php echo $mainImage ?>";
  }
}
function previewFileOverview() {
  var preview = document.getElementById('previewOverview');
  var file = document.getElementById('overviewImage').files[0];
  var reader = new FileReader();

  reader.onloadend = function() {
    preview.src = reader.result;
  }

  if (file) {
    reader.readAsDataURL(file);
  } else {
    preview.src = "<?php echo $overviewImage ?>";
  }
}