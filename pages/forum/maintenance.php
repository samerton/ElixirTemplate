<?php 
/* 
 *	Made by Partydragen
 *  http://partydragen.com/
 *  License: MIT
 */
 
// Maintenance mode?
// Todo: cache this
$maintenance_mode = $queries->getWhere('settings', array('name', '=', 'maintenance'));
if($maintenance_mode[0]->value == 'false'){
	// Maintenance mode is disabled, redirect back to forum
	if(!$user->isLoggedIn() || !$user->canViewACP($user->data()->id)){
		Redirect::to('/forum');
		die();
	}
}

// Maintenance page
$page = 'forum'; // for navbar
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Maintenance <?php echo $sitename; ?> community">
    <meta name="author" content="Partydragen">
    <?php if(isset($custom_meta)){ echo $custom_meta; } ?>
	<?php $title = $admin_language['maintenance_mode']; ?>
	<?php require('core/includes/template/generate.php'); ?>
  </head>

  <body>
    <?php $smarty->display('styles/templates/' . $template . '/navbar.tpl'); ?>
    <div class="container index">
      <h3><?php echo $admin_language['maintenance_mode']; ?></h3>
      <span class="tagline"><?php echo $admin_language['forum_in_maintenance']; ?></span>
      <br /><br />
      <button class="btn btn-danger btn-md" onclick="javascript:history.go(-1)"><?php echo $general_language['back']; ?></button>
      <a href="/" class="btn btn-primary btn-md"><?php echo $navbar_language['home']; ?></a>
    </div>
    <?php require('core/includes/template/footer.php'); ?>
    <?php $smarty->display('styles/templates/' . $template . '/footer.tpl'); ?>
    <?php require('core/includes/template/scripts.php'); ?>
  </body>
</html>
