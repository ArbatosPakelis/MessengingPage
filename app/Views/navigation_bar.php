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
    <ul class="nav navbar-nav">
      <li class="<?php if($lastPart=='home'){echo 'active';}?>"><a class="<?php if($lastPart !='home'){echo 'navv';}?>" href="home">Home</a></li>
      <li class="<?php if($lastPart=='message'){echo 'active';}?>"><a class="<?php if($lastPart !='message'){echo 'navv';}?>" href="message">Text message</a></li>
      <li class="<?php if($lastPart=='fileMessage'){echo 'active';}?>"><a class="<?php if($lastPart !='fileMessage'){echo 'navv';}?>" href="fileMessage">File message</a></li>
      <li class="<?php if($lastPart=='receiveMessage'){echo 'active';}?>"><a class="<?php if($lastPart !='receiveMessage'){echo 'navv';}?>" href="receiveMessage">Receive nessage</a></li>
    </ul>
  </div>
</nav>