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

// Check if we're viewing an alert
if(isset($_GET['a']) && is_numeric($_GET['a'])){
	// Get the alert, check the user can actually view it
	$alert = $queries->getWhere('alerts', array('id', '=', $_GET['a']));
	
	if(count($alert)){
		if($alert[0]->user_id == $user->data()->id){
			// Mark the alert as read
			$queries->update('alerts', $alert[0]->id, array(
				'`read`' => 1
			));
			
			if($alert[0]->url != '#'){
				// Redirect
				echo '<script data-cfasync="false">window.location.replace(\'' . str_replace('&amp;', '&', $alert[0]->url) . '\');</script>';
				die();
			} else {
				echo '<script data-cfasync="false">window.location.replace(\'/user/alerts\');</script>';
				die();
			}
		}
	}
}

// page for UserCP sidebar
$user_page = 'alerts';

$timeago = new Timeago();
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
  <body>
    <?php $smarty->display('styles/templates/' . $template . '/navbar.tpl'); ?>
    <div class="container index">
      <h3><?php echo $user_language['alerts']; ?></h3>
      <br />
      <div class="row">
        <div class="col-md-3">
          <?php require('pages/user/sidebar.php'); ?>
        </div>
        <div class="col-md-9">
          <?php if(!isset($_GET['action']) && !isset($_GET['aid'])){ ?>
          <div class=" panel-default panel-body">
            <?php if(Session::exists('user_alerts')){ echo Session::flash('user_alerts'); } ?>
            <?php
              // Get alerts for user
              $alerts = $queries->orderWhere('alerts', 'user_id = ' . $user->data()->id, 'created', 'DESC');
              
              // Arrays to store unread and read alerts
              $unread_alerts = array();
              $read_alerts = array();
              
              foreach($alerts as $alert){
              	if($alert->read == 0){
              		$unread_alerts[] = $alert;
              	} else {
              		$read_alerts[] = $alert;
              	}
              }
              
              if(!isset($_GET['view'])){ 
              	// Unread alerts
              	echo $user_language['viewing_unread_alerts'] . '<br /><br />';
              	if(count($unread_alerts)){
            ?>
            <table class="table table-formed">
              <colgroup>
                <col style="width:20%">
                <col style="width:45%">
                <col style="width:20%">
                <col style="width:15%">
              </colgroup>
              <thead>
                <tr>
                  <th><?php echo $user_language['view']; ?></th>
                  <th><?php echo $user_language['alert']; ?></th>
                  <th><?php echo $user_language['when']; ?></th>
                  <th><?php echo $admin_language['actions']; ?></th>
                </tr>
              </thead>
              <tbody>
                <?php foreach($unread_alerts as $alert){ ?>
                  <tr>
                    <td><a href="<?php echo $alert->url; ?>"><?php echo htmlspecialchars($alert->type); ?></a></td>
                    <td><?php echo $alert->content; ?></td>
                    <td><span rel="tooltip" data-trigger="hover" data-original-title="<?php echo date('d M Y, H:i', $alert->created); ?>"><?php echo $timeago->inWords(date('d M Y, H:i', $alert->created), $time_language); ?></span></td>
                    <td><a href="/user/alerts/?action=read&amp;aid=<?php echo $alert->id; ?>"><span class="label label-success">Mark as Read</span></a></td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
            <?php 
              } else {
                echo $user_language['no_unread_alerts'];
              }
            } else if(isset($_GET['view']) && $_GET['view'] == 'read') { 
              // Read alerts
              echo $user_language['viewing_read_alerts'] . '<br /><br />';
              if(count($read_alerts)){
            ?>
            <table class="table table-formed">
              <colgroup>
                <col style="width:20%">
                <col style="width:45%">
                <col style="width:20%">
                <col style="width:15%">
              </colgroup>
              <thead>
                <tr>
                  <th><?php echo $user_language['view']; ?></th>
                  <th><?php echo $user_language['alert']; ?></th>
                  <th><?php echo $user_language['when']; ?></th>
                  <th><?php echo $admin_language['actions']; ?></th>
                </tr>
              </thead>
              <tbody>
                <?php foreach($read_alerts as $alert){ ?>
                  <tr>
                    <td><a href="<?php echo $alert->url; ?>"><?php echo htmlspecialchars($alert->type); ?></a></td>
                    <td><?php echo $alert->content; ?></td>
                    <td><span rel="tooltip" data-trigger="hover" data-original-title="<?php echo date('d M Y, H:i', $alert->created); ?>"><?php echo $timeago->inWords(date('d M Y, H:i', $alert->created), $time_language); ?></span></td>
                    <td><a href="/user/alerts/?action=read&amp;aid=<?php echo $alert->id; ?>"><span class="label label-success">Mark as Read</span></a></td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
            <?php } else { echo $user_language['no_read_alerts']; } } ?>
          </div>
          <?php 
            } else { 
            if(isset($_GET['action']) && $_GET['action'] == 'read' && isset($_GET['aid']) && is_numeric($_GET['aid'])){
            // Mark alert as read
            $alert = $queries->getWhere('alerts', array('id', '=', $_GET['aid']));
            if($alert[0]->user_id == $user->data()->id){
            	$queries->update('alerts', $alert[0]->id, array(
            		'`read`' => 1
            	));
            }
            echo '<script>window.location.replace(\'/user/alerts\');</script>';
            die();
            } else if(isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['aid']) && is_numeric($_GET['aid'])){
            // Delete alert
            $alert = $queries->getWhere('alerts', array('id', '=', $_GET['aid']));
            if($alert[0]->user_id == $user->data()->id){
              $queries->delete('alerts', array('id', '=', $alert[0]->id));
              Session::flash('user_alerts', '<div class="alert alert-info">' . $user_language['deleted_alert'] . '</div>"');
            }
              echo '<script>window.location.replace(\'/user/alerts\');</script>';
              die();
              }
            } 
          ?>
        </div>
      </div>
    </div>
	<?php require('core/includes/template/footer.php'); ?>
	<?php $smarty->display('styles/templates/' . $template . '/footer.tpl'); ?>
	<?php require('core/includes/template/scripts.php'); ?>
  </body>
</html>