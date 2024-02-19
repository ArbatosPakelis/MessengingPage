<?php 
// Get full URL
$currentUrl = $_SERVER['REQUEST_URI'];

// Split the URL by '/'
$urlParts = explode('/', rtrim($currentUrl, '/'));

// Get the last part of the URL
$lastPart = end($urlParts);

?>

<nav class="navbar navbar-inverse" style="width:100%; border-radius: 0">
  <div class="container-fluid " style="padding-top:0px;">
    <button id="toggleBtn" class="toggle-btn"><span class="glyphicon glyphicon-align-justify"></span></button>
    <ul class="nav navbar-nav">
      <li class="<?php if($lastPart=='home'){echo 'active';}?>"><a class="<?php if($lastPart !='home'){echo 'navv';}?>" href="home">Home</a></li>
      <li class="<?php if($lastPart=='message'){echo 'active';}?>"><a class="<?php if($lastPart !='message'){echo 'navv';}?>" href="message">Text message</a></li>
      <li class="<?php if($lastPart=='fileMessage'){echo 'active';}?>"><a class="<?php if($lastPart !='fileMessage'){echo 'navv';}?>" href="fileMessage">File message</a></li>
    </ul>
  </div>
  <script>
      // Get the button element
      var toggleBtn = document.getElementById("toggleBtn");

      // Get the navbar items ul element
      var navbarItems = document.querySelector(".nav.navbar-nav");

      // Add click event listener to the toggle button
      toggleBtn.addEventListener("click", function() {
        // Toggle the visibility of the navbar items
        if (navbarItems.style.display === "block") {
          navbarItems.style.display = "none";
        } else {
          navbarItems.style.display = "block";
        }
      });
  </script>
</nav>