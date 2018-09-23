<?php 
/* 
 *  Made by Partydragen
 *  http://partydragen.com/
 *
 *  Modified by Samerton
 *  http://worldscapemc.co.uk
 *
 *  License: MIT
 */

// Members addon page
$page = $members_language['members_icon'] . $members_language['members']; // for navbar

// Ensure the addon is enabled
if(!in_array('Members', $enabled_addon_pages)){
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
    <meta name="description" content="Member list for the <?php echo $sitename; ?> community">
    <meta name="author" content="<?php echo $sitename; ?>">
    <meta name="theme-color" content="#454545" />
    <?php if(isset($custom_meta)){ echo $custom_meta; } ?>
    <?php $title = $members_language['members']; ?>
    <?php require('core/includes/template/generate.php'); ?>
  </head>
  <body>
    <?php $smarty->display('styles/templates/' . $template . '/navbar.tpl'); ?>
    <div class="container index">
      <h3><?php echo $members_language['members']; ?></h3>
      <br />
      <div class="row">
        <div class="col-md-12">
          <div class="panel-body">
            <ul class="nav nav-pills">
              <li<?php if(!isset($_GET['v'])) echo ' class="active"'; ?>><a href="/members"><?php echo $members_language['members']; ?></a></li>
              <?php
                // Staff groups
                $staff_groups = $queries->getWhere('groups', array('staff', '=', 1));
                foreach($staff_groups as $staff_group){
                ?>
              <li<?php if(isset($_GET['v']) && $_GET['v'] == $staff_group->name) echo ' class="active"'; ?>><a href="/members/?v=<?php echo htmlspecialchars($staff_group->name); ?>"><?php echo htmlspecialchars($staff_group->name); ?></a></li>
              <?php
                  if(isset($_GET['v']) && $staff_group->name == $_GET['v']) $selected_staff_group = $staff_group;
                }
                // Ensure selected group exists
                if(isset($_GET['v']) && !isset($selected_staff_group)){
                  echo '<script data-cfasync="false">window.location.replace(\'/\');</script>';
                  die();
                }
              ?>
            </ul>
          </div>
          <hr />
          <?php
            // Get members/staff members
            if(!isset($_GET['v'])){
             // All users/groups
             $users = $queries->orderAll("users", "USERNAME", "ASC");
             $groups = $queries->getAll("groups", array("id", "<>", 0));
            } else {
             // Just get users in the selected group
             $users = $queries->orderWhere('users', 'group_id = ' . htmlspecialchars($selected_staff_group->id), 'USERNAME', 'ASC');
            }
          ?>
          <div class="panel panel-primary">
            <div class="panel-body">
              <h4><?php if(!isset($_GET['v'])) echo $members_language['members']; else echo htmlspecialchars($_GET['v']); ?></h4>
              <br />
              <table class="table table-formed dataTables-users">
                <colgroup>
                  <col style="width:5%">
                  <col style="width:30%">
                  <col style="width:20%">
                  <col style="width:30%">
                </colgroup>
                <thead>
                  <tr>
                    <th></th>
                    <th><?php echo $members_language['username']; ?></th>
                    <th><?php echo $members_language['group']; ?></th>
                    <th><?php echo $members_language['created']; ?></th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                    foreach($users as $individual){
                    if(isset($selected_staff_group)){
                    $user_group = $selected_staff_group->group_html;
                    } else {
                    $user_group = "";
                    foreach($groups as $group){
                    	if($group->id === $individual->group_id){
                    	  $user_group = $group->group_html;
                    	  break;
                    	}
                    }
                    }
                    // Get avatar
                    $avatar = '<img class="img-circle" style="width:35px; height:35px;" src="' . $user->getAvatar($individual->id, "../", 35) . '" />';
                    ?>
                  <tr>
                    <td style="text-align:center"><?php echo ($avatar) ?></td>
                    <td><a href="/profile/<?php echo htmlspecialchars($individual->mcname); ?>"><?php echo htmlspecialchars($individual->username); ?></a></td>
                    <td><?php echo $user_group; ?></td>
                    <td><?php echo date('d M Y', $individual->joined); ?></td>
                  </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php require('core/includes/template/footer.php'); ?>
    <?php $smarty->display('styles/templates/' . $template . '/footer.tpl'); ?>
    <?php require('core/includes/template/scripts.php'); ?>
    <script src="/core/assets/js/tables/jquery.dataTables.min.js"></script>
    <script src="/core/assets/js/tables/dataTables.bootstrap.js"></script>
    <script type="text/javascript">
      $(document).ready(function() {
          $('.dataTables-users').dataTable({
              responsive: true,
      language: {
      "lengthMenu": "<?php echo $table_language['display_records_per_page']; ?>",
      "zeroRecords": "<?php echo $table_language['nothing_found']; ?>",
      "info": "<?php echo $table_language['page_x_of_y']; ?>",
      "infoEmpty": "<?php echo $table_language['no_records']; ?>",
      "infoFiltered": "<?php echo $table_language['filtered']; ?>",
      "search": "<?php echo $general_language['search']; ?> "
      }
          });
      });
    </script>
  </body>
</html>
