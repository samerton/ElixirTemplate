<?php
/*
 *	Made by Samerton
 *  http://worldscapemc.co.uk
 *
 *  License: MIT
 */

// Ensure user is logged in, and is admin
if($user->isLoggedIn()){
	if($user->canViewACP($user->data()->id)){
		if($user->isAdmLoggedIn()){
			// Can view
		} else {
			Redirect::to('/admin');
			die();
		}
	} else {
		Redirect::to('/');
		die();
	}
} else {
	Redirect::to('/');
	die();
}
 
$adm_page = "addons";
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Admin panel">
    <meta name="author" content="<?php echo $sitename; ?>">
    <meta name="robots" content="noindex">
    <?php if(isset($custom_meta)){ echo $custom_meta; } ?>
    <?php $title = $admin_language['addons']; ?>
    <?php require('core/includes/template/generate.php'); ?>
    <link href="/core/assets/plugins/switchery/switchery.min.css" rel="stylesheet">
  </head>
  
  <body>
    <?php $smarty->display('styles/templates/' . $template . '/navbar.tpl'); ?>
    <div class="container index">
      <h3 class="inline"><?php echo $admin_language['addons']; ?></h3>
      <span class="pull-right"><a href="/admin/addons/?action=new" class="btn btn-primary"><?php echo $admin_language['install_addon']; ?></a></span>
      <br /><br />
      <div class="row">
        <div class="col-md-3">
          <?php require('pages/admin/sidebar.php'); ?>
        </div>
        <div class="col-md-9">
          <?php if(Session::exists('scan_complete')){ echo Session::flash('scan_complete'); } ?>
            <?php if(!isset($_GET['action']) && !isset($_GET['activate']) && !isset($_GET['deactivate'])){ ?>
              <div class="panel-body">
                <h4><?php echo $admin_language['installed_addons']; ?></h4>
                <br />
                <?php
                  if(Session::exists('addon_error')){ echo Session::flash('addon_error'); }
                  // Get a list of addons
                  $addons = $queries->getWhere('addons', array('id', '<>', '0'));
                  // Order alphabetically
                  usort($addons, function ($elem1, $elem2) {
                  return strcmp($elem1->name, $elem2->name);
                  });
                ?>
                <?php foreach($addons as $addon){ ?>
                  <span style="display:inline-block;padding:4px;"><?php echo htmlspecialchars($addon->name); ?></span>
                <?php if($addon->enabled == 1){ ?>
                  <span class="pull-right"><a href="/admin/addons/?deactivate=<?php echo htmlspecialchars($addon->name); ?>" style="width: 100px;" class="btn btn-danger btn-sm"><?php echo $admin_language['deactivate']; ?></a> <a href="/admin/addons/?action=edit&addon=<?php echo htmlspecialchars($addon->name); ?>" class="btn btn-info btn-sm"><i class="fas fa-cog"></i></a> <a href="#" class="btn btn-danger btn-sm" disabled><i class="fas fa-trash-alt"></i></a></span>
                <?php } else { ?>
                  <span class="pull-right"><a href="/admin/addons/?activate=<?php echo htmlspecialchars($addon->name); ?>" style="width: 100px;" class="btn btn-success btn-sm"><?php echo $admin_language['activate']; ?></a> <a href="/admin/addons/?action=edit&addon=<?php echo htmlspecialchars($addon->name); ?>" class="btn btn-info btn-sm"><i class="fas fa-cog"></i></a> <a href="/admin/addons/?action=delete&addon=<?php echo htmlspecialchars($addon->name); ?>" class="btn btn-danger btn-sm" disabled><i class="fas fa-trash-alt"></i></a></span>
                <?php } ?>
                <hr />
                <?php } ?>
              </div>
            <?php } else if(isset($_GET['action']) && $_GET['action'] == 'new'){ ?>
              <div class="panel-body">
                <h4><?php echo $admin_language['install_an_addon']; ?></h4>
                <br />
                <center>
                  <div class="alert alert-danger">
                    <div class="row">
                      <div class="col-md-1"><i class="fa fa-exclamation-triangle"></i></div>
                      <div class="col-md-11"><?php echo $admin_language['addon_install_warning']; ?></div>
                    </div>
                  </div>
                  <p><?php echo $admin_language['addon_install_instructions']; ?></p>
                  <a href="/admin/addons/?action=scan" class="btn btn-primary"><?php echo $admin_language['scan']; ?></a>
                </center>
              </div>
            <?php
              } else if(isset($_GET['action']) && $_GET['action'] == 'scan'){
              	// Get a list of all folders in the 'addons' directory
              	$directories = glob('addons/*' , GLOB_ONLYDIR);
              	foreach($directories as $directory){
              		$folders = explode('/', $directory);
              		// Is it already in the database?
              		
              		$exists = $queries->getWhere('addons', array('name', '=', htmlspecialchars($folders[1])));
              		if(!count($exists)){
              			// No, add it now
              			$queries->create('addons', array(
              				'name' => htmlspecialchars($folders[1])
              			));
              		}
              	}
              	Session::flash('scan_complete', '<div class="alert alert-success">' . $admin_language['addon_scan_complete'] . '</div>');
              	echo '<script>window.location.replace(\'/admin/addons\');</script>';
              	die();
              } else if(isset($_GET['action']) && $_GET['action'] == 'edit'){
              	// Edit addon settings
              	// First, check the addon actually exists
              	$addon = $queries->getWhere('addons', array('name', '=', htmlspecialchars($_GET['addon'])));
              	if(!count($addon)){
              		Session::flash('scan_complete', '<div class="alert alert-danger">' . $admin_language['addon_not_exist'] . '</div>');
              		echo '<script>window.location.replace(\'/admin/addons\');</script>';
              		die();
              	}
              	require('addons/' . $_GET['addon'] . '/settings.php');
              	
              	
              } else if(isset($_GET['activate'])){
              	// Make an addon active
              	// First, check the addon actually exists
              	$addon = $queries->getWhere('addons', array('name', '=', htmlspecialchars($_GET['activate'])));
              	if(!count($addon)){
              		Session::flash('scan_complete', '<div class="alert alert-danger">' . $admin_language['addon_not_exist'] . '</div>');
              		echo '<script>window.location.replace(\'/admin/addons\');</script>';
              		die();
              	}
              	$addon_name = $addon[0]->name;
              	$addon = $addon[0]->id;
              	
              	// Addon exists
              	
              	// Make new addon active
              	$queries->update('addons', $addon, array(
              		'enabled' => 1
              	));
              	
              	Session::flash('scan_complete', '<div class="alert alert-success">' . $admin_language['addon_enabled'] . '</div>');
              	echo '<script>window.location.replace(\'/admin/addons\');</script>';
              	die();
              } else if(isset($_GET['deactivate'])){
              	// Disable an addon
              	// First, check the addon actually exists
              	$addon = $queries->getWhere('addons', array('name', '=', htmlspecialchars($_GET['deactivate'])));
              	if(!count($addon)){
              		Session::flash('scan_complete', '<div class="alert alert-danger">' . $admin_language['addon_not_exist'] . '</div>');
              		echo '<script>window.location.replace(\'/admin/addons\');</script>';
              		die();
              	}
              	$addon_name = $addon[0]->name;
              	$addon = $addon[0]->id;
              	
              	// Addon exists
              	
              	// Disable addon
              	$queries->update('addons', $addon, array(
              		'enabled' => 0
              	));
              	
              	Session::flash('scan_complete', '<div class="alert alert-success">' . $admin_language['addon_disabled'] . '</div>');
              	echo '<script>window.location.replace(\'/admin/addons\');</script>';
              	die();
              }
            ?>
        </div>
      </div>
    </div>
    
	<?php require('core/includes/template/footer.php'); ?>
	<?php $smarty->display('styles/templates/' . $template . '/footer.tpl'); ?>
	<?php require('core/includes/template/scripts.php'); ?>
	
    <script src="/core/assets/plugins/switchery/switchery.min.js"></script>
    <script>
      var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
      
      elems.forEach(function(html) {
        var switchery = new Switchery(html, {size: 'small'});
      });
      
    </script>
  </body>
</html>