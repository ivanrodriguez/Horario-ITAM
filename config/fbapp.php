<?php
    require 'libs/facebook.php';
    $appId = '';
    $appSecret = '';
    $facebook = new Facebook(array(
      'appId'  => $appId,
      'secret' => $appSecret,
      'cookie' => true,
      'domain' => '',
    ));
?>