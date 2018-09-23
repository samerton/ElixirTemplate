<?php
/*
 *	Made by Samerton
 *  http://worldscapemc.co.uk
 *
 *  License: MIT
 *  Copyright (c) 2016 Samerton
 */

header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="<?php echo $sitename; ?>">
    <link rel="icon" href="/core/assets/favicon.ico">
    <?php if(isset($custom_meta)){ echo $custom_meta; } ?>
    <?php echo '<title>Error | ' . $sitename . '</title>'; ?>
    <?php require('core/includes/template/generate.php'); ?>
  </head>
  
  <body>
    <?php $smarty->display('styles/templates/' . $template . '/navbar.tpl'); ?>
    <div class="container announcement">
      {$SESSION_FLASH}
    </div>
    <div class="container index">
      <h3><?php echo $sitename; ?> - Error</h3>
      <span class="tagline">The requested page could not be found.</span>
      <br /><br />
      <button class="btn btn-danger btn-md" onclick="javascript:history.go(-1)"><?php echo $general_language['back']; ?></button>
      <a href="/" class="btn btn-primary btn-md"><?php echo $navbar_language['home']; ?></a>
    </div>
    <?php require('core/includes/template/footer.php'); ?>
    <?php $smarty->display('styles/templates/' . $template . '/footer.tpl'); ?>
    <?php require('core/includes/template/scripts.php'); ?>
  </body>
</html>
