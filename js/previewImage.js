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