<?php 
/* 
 *	Made by Partydragen, edited by relavis
 *  http://partydragen.com/
 *
 *  License: MIT
 */

// HTMLPurifier
require('core/includes/htmlpurifier/HTMLPurifier.standalone.php');

/*
 *  User must be logged in
 */
if(!$user->isLoggedIn()){
	Redirect::to('/signin');
	die();
}


/*
 *  Check if page is enabled
 */
$bugreport = $queries->getWhere('addons', array('name', '=', 'BugReport'));
if($bugreport[0]->enabled == 0){
	Redirect::to('/');
	die();
}

/* 
 *  Handle input
 */
if(Input::exists()){
	if(Token::check(Input::get('token'))){
		// Get all answers into one string
		unset($_POST['token']);
		
		$content = array();
		foreach($_POST as $key => $item){
			$content[] = array($key, htmlspecialchars($item));
		}
		
		$content = json_encode($content);
		
		$queries->create('bugreport_replies', array(
			'uid' => $user->data()->id,
			'time' => date('U'),
			'content' => $content
		));
		
		$app_id = $queries->getLastId();
		
		// Moderator alerts
		$mod_groups = $queries->getWhere('groups', array('bugreport', '=', 1));
		foreach($mod_groups as $mod_group){
			$mod_users = $queries->getWhere('users', array('group_id', '=', $mod_group->id));
			foreach($mod_users as $individual){
				$queries->create('alerts', array(
					'user_id' => $individual->id,
					'type' => $bugreport_language['bug_report'],
					'url' => '/mod/bugreport/?app=' . $app_id,
					'content' => str_replace('{x}', htmlspecialchars($user->data()->username), $bugreport_language['new_bug_report_submitted_alert']),
					'created' => date('U')
				));
			}
		}
		
		Session::flash('app_success', '<div class="alert alert-success">' . $user_language['application_submitted'] . '</div>');
		$completed = 1;
		
	} else {
		// Invalid token
		Session::flash('app_succes', '<div class="alert alert-danger">' . $admin_language['invalid_token'] . '</div>');
	}
}

$page = $bugreport_language['bug_report'];

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Bug Report page for the <?php echo $sitename; ?> community">
    <meta name="author" content="<?php echo $sitename; ?>">
	<meta name="robots" content="noindex">
	<?php if(isset($custom_meta)){ echo $custom_meta; } ?>
	<?php $title = $bugreport_language['bug_report']; ?>
	<?php  require('core/includes/template/generate.php'); ?>
  </head>

  <body>
    <?php $smarty->display('styles/templates/' . $template . '/navbar.tpl'); ?>
    <div class="container index">
      <h3><?php echo $bugreport_language['bug_report']; ?></h3>
      <br />
      <div class="row">
        <div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
          <?php if(Session::exists('app_succes')){ echo Session::flash('app_succes'); } ?>
            <?php if(!isset($completed)){ ?>
              <form action="" method="post">
                <?php 
                  $questions = $queries->getWhere('bugreport_questions', array('id', '<>', 0)); 
                  foreach($questions as $question){
                ?>
                  <?php if($question->type == 3){ ?>
                    <label for="<?php echo htmlspecialchars($question->name); ?>"><?php echo htmlspecialchars($question->question); ?></label>
                    <textarea class="form-control" id="<?php echo htmlspecialchars($question->name); ?>" name="<?php echo $question->id; ?>"></textarea>
                    <br />
                  <?php } else if($question->type == 1){ ?>
                    <label for="<?php echo htmlspecialchars($question->name); ?>"><?php echo htmlspecialchars($question->question); ?></label>
                      <select name="<?php echo $question->id; ?>" id="<?php echo htmlspecialchars($question->name); ?>" class="form-control">
                        <?php
                          $options = explode(',', $question->options);
                          foreach($options as $option){
                        ?>
                          <option value="<?php echo htmlspecialchars($option); ?>"><?php echo htmlspecialchars($option); ?></option>
                        <?php } ?>
                      </select>
                      <br />
                  <?php } else { ?>
                    <label for="<?php echo htmlspecialchars($question->name); ?>"><?php echo htmlspecialchars($question->question); ?></label>
                    <input type="text" class="form-control" id="<?php echo htmlspecialchars($question->name); ?>" name="<?php echo $question->id; ?>">
                    <br />
                  <?php } ?>
                <?php } ?>
                <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
                <input type="submit" class="btn btn-primary" value="<?php echo $general_language['submit']; ?>">
              </form>
            <?php } else { if(Session::exists('app_success')){ echo Session::flash('app_success'); } } ?>
        </div>
      </div>
    </div>
	<?php require('core/includes/template/footer.php'); ?>
	<?php $smarty->display('styles/templates/' . $template . '/footer.tpl'); ?>
	<?php require('core/includes/template/scripts.php'); ?>
  </body>
</html>
