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
    <ul class="nav navbar-nav" style="width:100%">
      <li class="<?php if($lastPart=='home'){echo 'active';}?>"><a class="<?php if($lastPart !='home'){echo 'navv';}?>" href="home">Home</a></li>
      <li class="<?php if($lastPart=='message'){echo 'active';}?>"><a class="<?php if($lastPart !='message'){echo 'navv';}?>" href="message">Text message</a></li>
      <li class="<?php if($lastPart=='fileMessage'){echo 'active';}?>"><a class="<?php if($lastPart !='fileMessage'){echo 'navv';}?>" href="fileMessage">File message</a></li>
      <?php if ($id < 1): ?>
        <li style="float: right" class="<?php if($lastPart=='signup'){echo 'active';}?>"><a class="<?php if($lastPart !='signup'){echo 'navv';}?>" href="login">Log in</a></li>
      <?php else: ?>
        <li style="float: right" class="<?php if($lastPart=='logout'){echo 'active';}?>"><a class="<?php if($lastPart !='logout'){echo 'navv';}?>" id="logout" href="logout">Log out</a></li>
        <li style="float: right" class="<?php if($lastPart=='profile'){echo 'active';}?>"><a class="<?php if($lastPart !='profile'){echo 'navv';}?>" id="profile" href="profile" style="border-style:solid;border-color:#dee4ea; border-radius:20px;width:40px;height:40px;margin-top:5px;padding-top:10px;padding-left:10px"><span class="glyphicon glyphicon-user" style="font-size:14px;color:#dee4ea;"></a></li>
      <?php endif; ?>
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
      $(document).ready(function () 
      {
        $('#logout').click(function (e) 
        {
            e.preventDefault();
            // if session has no user
            if (<?= $id ?> > 0) {
              $.ajax({
                  url: '<?php echo base_url('public/logout')?>',
                  type: 'POST',
                  success: function (response) {
                      console.log('Logout successful');
                      window.location.href = '<?php echo base_url('public/login')?>';
                  },
                  error: function (xhr, status, error) {
                      console.error('Error:', error);
                  }
              });
            }
        })
      })

  </script>
</nav>