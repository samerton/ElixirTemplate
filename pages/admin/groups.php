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

// Set page name for sidebar
$adm_page = "users";
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
    <?php $title = $admin_language['groups']; ?>
    <?php require('core/includes/template/generate.php'); ?>
    <link href="/core/assets/plugins/switchery/switchery.min.css" rel="stylesheet">
  </head>
  <body>
    <?php $smarty->display('styles/templates/' . $template . '/navbar.tpl'); ?>
    <?php if(Session::exists('adm-alert')){ echo Session::flash('adm-alert'); } ?>
    <div class="container index">
      <h3><?php echo $admin_language['users_and_groups']; ?></h3>
      <br />
      <div class="row">
        <div class="col-md-3">
          <?php require('pages/admin/sidebar.php'); ?>
        </div>
        <div class="col-md-9">
          <div class="panel-body">
            <ul class="nav nav-pills">
              <li><a href="/admin/users"><?php echo $admin_language['users']; ?></a></li>
              <li class="active"><a href="/admin/groups"><?php echo $admin_language['groups']; ?></a></li>
            </ul>
          </div>
          <br />
          <?php if(Session::exists('adm-groups')) { echo Session::flash('adm-groups'); } ?>
          <?php if(!isset($_GET["action"]) && !isset($_GET["group"])){ ?>
          <div class="panel-body post-body">
            <h4 class="inline"><?php echo $admin_language['groups']; ?></h4>
            <a href="/admin/groups/?action=new" class="btn btn-primary pull-right"><?php echo $admin_language['new_group']; ?></a>
            <br /><br /><br />
            <div class="panel-body">
              <?php $groups = $queries->getAll("groups", array("id", "<>", 0)); ?>
              <table class="table table-formed">
                <colgroup>
                  <col style="width:10%">
                  <col style="width:40%">
                  <col style="width:40%">
                  <col style="width:10%">
                </colgroup>
                <thead>
                  <tr>
                    <th><?php echo $admin_language['id']; ?></th>
                    <th><?php echo $admin_language['name']; ?></th>
                    <th><?php echo $admin_language['users']; ?></th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                    foreach($groups as $group){
                    ?>
                  <tr>
                    <td><?php echo $group->id; ?></td>
                    <td><?php echo $group->name; ?></td>
                    <td><?php echo count($queries->getWhere("users", array("group_id", "=", $group->id))); ?></td>
                    <td><a href="/admin/groups/?group=<?php echo $group->id; ?>" class="btn btn-primary btn-sm"><?php echo $admin_language['edit']; ?></a></td>
                  </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
          </div>
          <?php
            } else if(isset($_GET["action"])){
            	if($_GET["action"] === "new"){
            		if(Input::exists()) {
            			if(Token::check(Input::get('token'))) {
            				$validate = new Validate();
            				$validation = $validate->check($_POST, array(
            					'groupname' => array(
            						'required' => true,
            						'min' => 2,
            						'max' => 20
            					)
            				));
            
            				if($validation->passed()){
            					try {
            						$queries->create("groups", array(
            							'name' => htmlspecialchars(Input::get('groupname')),
            							'group_html' => '',
            							'group_html_lg' => '',
            							'buycraft_id' => htmlspecialchars(Input::get('buycraft_id'))
            						));
            
            						echo '<script data-cfasync="false">window.location.replace("/admin/groups");</script>';
            						die();
            
            					} catch(Exception $e){
            						die($e->getMessage());
            					}
            				}
            			}
            		}
            
            		// Generate token for form
            		$token = Token::generate();
            
            		if(isset($validation)){
            			if(!$validation->passed()){
            		?>
          <div class="alert alert-danger">
            <?php
              foreach($validation->errors() as $error){
              	if(strpos($error, 'is required') !== false){
              		echo $admin_language['group_name_required'];
              	} else if(strpos($error, 'minimum') !== false){
              		echo $admin_language['group_name_minimum'];
              	} else if(strpos($error, 'maximum') !== false){
              		echo $admin_language['group_name_maximum'];
              	}
              }
            ?>
          </div>
          <?php } } ?>
          <div class="panel-body">
            <form action="" method="post">
              <h4><?php echo $admin_language['create_group']; ?></h4>
              <br />
              <div class="form-group">
                <label><?php echo $admin_language['group_name']; ?></label>
                <input class="form-control" type="text" name="groupname" id="groupname" value="<?php echo escape(Input::get('groupname')); ?>" autocomplete="off">
              </div>
              <div class="form-group">
                <label><?php echo $admin_language['donor_group_id']; ?></label><span data-toggle="popover" data-content="<?php echo $admin_language['donor_group_id_help']; ?>">&nbsp;<i class="fas fa-question-circle"></i></span>
                <input class="form-control" type="text" name="buycraft_id" id="buycraft_id">
              </div>
              <input type="hidden" name="token" value="<?php echo $token; ?>">
              <input class="btn btn-primary" type="submit" value="<?php echo $general_language['submit']; ?>">
            </form>
            <br />
            <div class="alert alert-info">
              <?php echo $admin_language['donor_group_instructions']; ?>
            </div>
          </div>
          <div class="modal fade" id="donor_package_help" tabindex="-1" role="dialog" aria-labelledby="donor_package_ModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h4 class="modal-title" id="donor_package_ModalLabel"><?php echo $general_language['help']; ?></h4>
                </div>
                <div class="modal-body">
                  <?php echo $admin_language['donor_group_id_help']; ?>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $general_language['close']; ?></button>
                </div>
              </div>
            </div>
          </div>
          <?php
            } } else if(isset($_GET["group"])){
            if(Input::exists()) {
            	if(Token::check(Input::get('token'))) {
            		if(Input::get('action') === "update"){
            			$validate = new Validate();
            			$validation = $validate->check($_POST, array(
            				'groupname' => array(
            					'required' => true,
            					'min' => 2,
            					'max' => 20
            				),
            				'html' => array(
            					'max' => 1024
            				),
            				'html_lg' => array(
            					'max' => 1024
            				)
            			));
            
            			if($validation->passed()){
            				try {
            					$queries->update('groups', $_GET["group"], array(
            						'name' => Input::get('groupname'),
            						'buycraft_id' => Input::get('buycraft_id'),
            						'group_html' => Input::get('html'),
            						'group_html_lg' => Input::get('html_lg'),
            						'mod_cp' => Input::get('modcp'),
            						'admin_cp' => Input::get('admincp'),
            						'staff' => Input::get('staff')
            					));
            
            					echo '<script data-cfasync="false">window.location.replace("/admin/groups/?group=' . htmlspecialchars($_GET['group'], ENT_QUOTES) . '");</script>';
            					die();
            				} catch(Exception $e) {
            					die($e->getMessage());
            				}
            
            			} else {
            				echo '<div class="alert alert-danger">';
            				foreach($validation->errors() as $error) {
            					if(strpos($error, 'is required') !== false){
            						echo $admin_language['group_name_required'];
            					} else if(strpos($error, 'minimum') !== false){
            						echo $admin_language['group_name_minimum'];
            					} else if(strpos($error, 'maximum') !== false){
            						switch($error){
            							case (strpos($error, 'groupname') !== false):
            								echo $admin_language['group_name_maximum'] . '<br />';
            							break;
            							case (strpos($error, 'html') !== false):
            								echo $admin_language['html_maximum'] . '<br />';
            							break;
            						}
            					}
            				}
            				echo '</div>';
            			}
            		} else if(Input::get('action') == "delete"){
            			try {
            				$queries->delete('groups', array('id', '=' , Input::get('id')));
            				echo '<script data-cfasync="false">window.location.replace("/admin/groups/");</script>';
            				die();
            			} catch(Exception $e) {
            				die($e->getMessage());
            			}
            		}
            	}
            }
            
            // Generate token for form
            $token = Token::generate();
            
            if(!is_numeric($_GET["group"])){
            	$group = $queries->getWhere("groups", array("name", "=", $_GET["group"]));
            } else {
            	$group = $queries->getWhere("groups", array("id", "=", $_GET["group"]));
            }
            if(count($group)){
          ?>
          <div class="panel-body">
            <h4><?php echo htmlspecialchars($group[0]->name) ?></h4>
            <br />
            <form role="form" action="" method="post">
              <div class="form-group">
                <label for="InputGroupname"><?php echo $admin_language['group_name']; ?></label>
                <input type="text" name="groupname" class="form-control" id="InputGroupname" placeholder="<?php echo $admin_language['group_name']; ?>" value="<?php echo htmlspecialchars($group[0]->name); ?>">
              </div>
              <div class="form-group">
                <label for="InputHTML"><?php echo $admin_language['group_html']; ?></label>
                <input type="text" name="html" class="form-control" id="InputHTML" placeholder="<?php echo $admin_language['group_html']; ?>" value="<?php echo htmlspecialchars($group[0]->group_html); ?>">
              </div>
              <div class="form-group">
                <label for="InputHTML_Lg"><?php echo $admin_language['group_html_lg']; ?></label>
                <input type="text" name="html_lg" class="form-control" id="InputHTML_Lg" placeholder="<?php echo $admin_language['group_html_lg']; ?>" value="<?php echo htmlspecialchars($group[0]->group_html_lg); ?>">
              </div>
              <?php if($group[0]->staff == 1){} else { ?>
              <div class="form-group">
                <label for="InputBuycraft"><?php echo $admin_language['donor_group_id']; ?></label>
                <input type="text" name="buycraft_id" class="form-control" id="InputBuycraft" placeholder="<?php echo $admin_language['donor_group_id']; ?>" value="<?php echo htmlspecialchars($group[0]->buycraft_id); ?>">
              </div>
              <?php } ?>
              <div class="form-group">
                <input type="hidden" name="staff" value="0">
                <input type="checkbox" name="staff" id="InputStaff" class="js-switch" placeholder="<?php echo $admin_language['group_staff']; ?>" value="1" <?php if($group[0]->staff == 1){ ?> checked<?php } ?>>
                <label for="InputStaff">&nbsp;&nbsp;<?php echo $admin_language['group_staff']; ?></label>
              </div>
              <div class="form-group">
                <input type="hidden" name="modcp" value="0">
                <input type="checkbox" name="modcp" id="InputModCP" class="js-switch" placeholder="<?php echo $admin_language['group_modcp']; ?>" value="1" <?php if($group[0]->mod_cp == 1){ ?> checked<?php } ?>>
                <label for="InputModCP">&nbsp;&nbsp;<?php echo $admin_language['group_modcp']; ?></label>
              </div>
              <div class="form-group">
                <input type="hidden" name="admincp" value="0">
                <input type="checkbox" name="admincp" id="InputAdminCP" class="js-switch" placeholder="<?php echo $admin_language['group_admincp']; ?>" value="1" <?php if($group[0]->admin_cp == 1){ ?> checked<?php } ?>>
                <label for="InputAdminCP">&nbsp;&nbsp;<?php echo $admin_language['group_admincp']; ?></label>
              </div>
              <input type="hidden" name="token" value="<?php echo $token; ?>">
              <input type="hidden" name="action" value="update">
              <input type="submit" value="<?php echo $general_language['submit']; ?>" class="btn btn-primary">
            </form>
          </div>
          <?php if($group[0]->id == 2 || $group[0]->id == 3 || $group[0]->id == 1){} else { ?>
          <br />
          <form role="form" action="" method="post">
            <input type="hidden" name="token" value="<?php echo $token; ?>">
            <input type="hidden" name="action" value="delete">
            <input type="hidden" name="id" value="<?php echo $group[0]->id; ?>">
            <input onclick="return confirm('<?php echo str_replace('{x}', htmlspecialchars($group[0]->name), $admin_language['confirm_group_deletion']); ?>');" type="submit" value="<?php echo $admin_language['delete_group']; ?>" class="btn btn-danger">
          </form>
          <?php
            } } else {
            Session::flash('adm-groups', '<div class="alert alert-info">' . $admin_language['group_not_exist'] . '</div>');
            echo '<script data-cfasync="false">window.location.replace("/admin/groups/");</script>';
            die();
            } }
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
