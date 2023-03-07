// Function to update the page based on the response from the PHP script
function updatePage(response) {
  // Do something with the response, such as updating the content on the page
  document.getElementById("billet").innerHTML = response;
}

// Function to send an AJAX request to the PHP script
function refreshBillet() {
  var xhr = new XMLHttpRequest();
  xhr.open("GET", "check_date.php?date=" + encodeURIComponent(document.getElementById("date").value), true);
  xhr.onreadystatechange = function() {
    if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
      updatePage(xhr.responseText);
    }
  };
  xhr.send();
}

// Listen for changes to the date input
document.getElementById("date").addEventListener("change", refreshBillet);
