<?php 
/* 
 *	Made by Samerton
 *  http://worldscapemc.co.uk
 *
 *  License: MIT
 */

if(!$user->isLoggedIn()){
	Redirect::to('/');
	die();
}

// page for UserCP sidebar
$user_page = 'index';
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="User panel">
    <meta name="author" content="<?php echo $sitename; ?>">
	<meta name="robots" content="noindex">
    <?php if(isset($custom_meta)){ echo $custom_meta; } ?>
	<?php $title = $user_language['user_cp']; ?>
	<?php require('core/includes/template/generate.php'); ?>
  </head>
  
  <?php
	// Get statistics for user
	$topics = $queries->getWhere('topics', array('topic_creator', '=', $user->data()->id));
	$topics = count($topics);
	$posts = $queries->getWhere('posts', array('post_creator', '=', $user->data()->id));
	$posts = count($posts);
	$reputation = $queries->getWhere('reputation', array('user_received', '=', $user->data()->id));
	$reputation = count($reputation);
	$friends = $queries->getWhere('friends', array('user_id', '=', $user->data()->id));
	$friends = count($friends);
  ?>
  
  <body>
	<?php $smarty->display('styles/templates/' . $template . '/navbar.tpl'); ?>
    <div class="container index">
	  <h3><?php echo $mod_language['overview']; ?></h3>
	  <br />
	  <div class="row">
		<div class="col-md-3">
		  <?php require('pages/user/sidebar.php'); ?>
		</div>
		<div class="col-md-9">
		  <div class="panel-body">
			<strong><?php echo $user_language['username']; ?>:</strong> <?php echo htmlspecialchars($user->data()->username); ?><br />
			<strong><?php echo $admin_language['group']; ?>:</strong> <?php echo htmlspecialchars($user->getGroupName($user->data()->group_id)); ?><br />
		  </div>
		  <br />
		  <div class="panel-body">
			<strong><?php echo $user_language['topics']; ?>:</strong> <?php echo $topics; ?><br />
			<strong><?php echo $user_language['posts']; ?>:</strong> <?php echo $posts; ?><br />
			<strong><?php echo $user_language['reputation']; ?>:</strong> <?php echo $reputation; ?><br />
			<strong><?php echo $mod_language['registered']; ?>:</strong> <?php echo date('d M Y, H:i', $user->data()->joined); ?><br />
			<?php 
			  $use_followers = $queries->getWhere('settings', array('name', '=', 'followers'));
			  $use_followers = $use_followers[0]->value;
			  if($use_followers == '1'){
			    $followers = $user->listFollowers($user->data()->id);
			    if($followers) { $followers = count($followers); } else { $followers = 0; }
			?> 
			  <strong><?php echo $user_language['following']; ?>:</strong> <?php echo $friends; ?><br />
			  <strong><?php echo $user_language['followers']; ?>:</strong> <?php echo $followers; ?><br />
			<?php } else { ?> 
				<strong><?php echo $user_language['friends']; ?>:</strong> <?php echo $friends; ?>
			<?php } ?>
		  </div>
		</div>
      </div>	  
	</div>
	<?php require('core/includes/template/footer.php'); ?>
	<?php $smarty->display('styles/templates/' . $template . '/footer.tpl'); ?>
	<?php require('core/includes/template/scripts.php'); ?>
  </body>
</html>