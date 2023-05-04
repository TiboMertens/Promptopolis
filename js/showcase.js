const filterSelects = document.querySelectorAll(".filter-select");

// Check if there's a selected filter in localStorage for each select element
filterSelects.forEach((select) => {
  const storedFilter = localStorage.getItem(`selectedFilter_${select.name}`);
  if (storedFilter) {
    select.value = storedFilter;
  }

  // Add an event listener to each select element
  select.addEventListener("change", () => {
    localStorage.setItem(`selectedFilter_${select.name}`, select.value);
    document.getElementById("filter-form").submit();
  });
});

const approveLink = document.getElementById("approve");
if (approveLink) {
  approveLink.addEventListener("click", () => {
    const filterApprove = document.querySelector(
      'select[name="filterApprove"]'
    );
    if (filterApprove) {
      filterApprove.value = "all";
      localStorage.setItem(
        `selectedFilter_${filterApprove.name}`,
        filterApprove.value
      );
      document.getElementById("filter-form").submit();
    }
  });
}

const dateLink = document.getElementById("date");
if (approveLink) {
  dateLink.addEventListener("click", () => {
    const filterApprove = document.querySelector('select[name="filterDate"]');
    if (filterApprove) {
      filterApprove.value = "all";
      localStorage.setItem(
        `selectedFilter_${filterApprove.name}`,
        filterApprove.value
      );
      document.getElementById("filter-form").submit();
    }
  });
}

const priceLink = document.getElementById("price");
if (approveLink) {
  priceLink.addEventListener("click", () => {
    const filterApprove = document.querySelector('select[name="filterPrice"]');
    if (filterApprove) {
      filterApprove.value = "all";
      localStorage.setItem(
        `selectedFilter_${filterApprove.name}`,
        filterApprove.value
      );
      document.getElementById("filter-form").submit();
    }
  });
}

const modelLink = document.getElementById("model");
if (approveLink) {
  modelLink.addEventListener("click", () => {
    const filterApprove = document.querySelector('select[name="filterModel"]');
    if (filterApprove) {
      filterApprove.value = "all";
      localStorage.setItem(
        `selectedFilter_${filterApprove.name}`,
        filterApprove.value
      );
      document.getElementById("filter-form").submit();
    }
  });
}
