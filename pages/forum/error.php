<?php
/* 
 *	Made by Samerton
 *  http://worldscapemc.co.uk
 *
 *  License: MIT
 */

// Maintenance mode?
// Todo: cache this
$maintenance_mode = $queries->getWhere('settings', array('name', '=', 'maintenance'));
if($maintenance_mode[0]->value == 'true'){
	// Maintenance mode is enabled, only admins can view
	if(!$user->isLoggedIn() || !$user->canViewACP($user->data()->id)){
		require('pages/forum/maintenance.php');
		die();
	}
}
 
// Set the page name for the active link in navbar
$page = "forum";
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
    <?php echo $title = $navbar_language['forum']; ?>
    <?php require('core/includes/template/generate.php'); ?>
  </head>
  
  <body>
    <?php $smarty->display('styles/templates/' . $template . '/navbar.tpl'); ?>
    <div class="container index">
      <h3><?php echo $sitename . ' - ' . $general_language['error']; ?></h3>
      <span class="tagline"><?php echo $forum_language['forum_error'] . $forum_language['are_you_logged_in']; ?></span>
      <br /><br />
      <button class="btn btn-default btn-md" onclick="javascript:history.go(-1)"><?php echo $general_language['back']; ?></button>
      <a href="/" class="btn btn-primary btn-md"><?php echo $navbar_language['home']; ?></a>
    </div>
    <?php require('core/includes/template/footer.php'); ?>
    <?php $smarty->display('styles/templates/' . $template . '/footer.tpl'); ?>
    <?php require('core/includes/template/scripts.php'); ?>
  </body>
</html>