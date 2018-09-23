<?php 
/* 
 *	Made by Samerton
 *  http://worldscapemc.co.uk
 *
 *  License: MIT
 */

// Vote addon page
$page = $vote_language['vote_icon'] . $vote_language['vote']; // for navbar

// Ensure the addon is enabled
if(!in_array('Vote', $enabled_addon_pages)){
	// Not enabled, redirect to homepage
	echo '<script data-cfasync="false">window.location.replace(\'/\');</script>';
	die();
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Vote page for the <?php echo $sitename; ?> community">
    <meta name="author" content="<?php echo $sitename; ?>">
    <meta name="theme-color" content="#454545" />
    <?php if(isset($custom_meta)){ echo $custom_meta; } ?>
    <?php $title = $vote_language['vote']; ?>
    <?php require('core/includes/template/generate.php'); ?>
  </head>
  
  <body>
    <?php $smarty->display('styles/templates/' . $template . '/navbar.tpl'); ?>
    <div class="container index">
    <h3><?php echo $vote_language['vote']; ?></h3>
    <br />
    <div class="row">
      <div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
        <?php 
          $vote_message = $queries->getWhere("vote_settings", array("name", "=", "vote_message"));
          $vote_message = $vote_message[0]->value;
          ?>
        <?php if(!empty($vote_message)){ ?>
        <div class="alert alert-info">
          <center><?php echo htmlspecialchars($vote_message); ?></center>
          /div><?php } ?>
          <?php
            $sites = $queries->getWhere("vote_sites", array("id", "<>", 0));
            foreach($sites as $site){ ?>
              <center><a class="btn btn-lg btn-block btn-primary" href="<?php echo str_replace("&amp;", "&", htmlspecialchars($site->site)) ?>" target="_blank" role="button"><?php echo htmlspecialchars($site->name) ?></a></center><br />
          <?php } ?> 
        </div>
      </div>
    </div>
    <?php require('core/includes/template/footer.php'); ?>
    <?php $smarty->display('styles/templates/' . $template . '/footer.tpl'); ?>
    <?php require('core/includes/template/scripts.php'); ?>
  </body>
</html>
