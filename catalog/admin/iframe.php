<?php
$url = $_GET['url'];
if (preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $url)) {
  echo '<iframe class="update-message" height="480px" frameborder="0" width="480px" src="' . $url . '">Sorry, your browser does not support iFrames.</iframe>';
}
?>