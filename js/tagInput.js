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
    const newTagLabel = document.createElement("label");
    newTagLabel.setAttribute("for", `tag${tagCount + 1}`);
    newTagLabel.innerText = `tag ${tagCount + 1}`;
    tagContainer.appendChild(newTagLabel);
    tagContainer.appendChild(newTagInput);
    tagCount++;
  } else {
    addTagButton.disabled = true;
  }
});