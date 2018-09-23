<?php 
/* 
 *	Made by Partydragen
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
$banappeal = $queries->getWhere('addons', array('name', '=', 'BanAppeal'));
if($banappeal[0]->enabled == 0){
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
		
		$queries->create('banappeal_replies', array(
			'uid' => $user->data()->id,
			'time' => date('U'),
			'content' => $content
		));
		
		$app_id = $queries->getLastId();
		
		// Moderator alerts
		$mod_groups = $queries->getWhere('groups', array('banappeal', '=', 1));
		foreach($mod_groups as $mod_group){
			$mod_users = $queries->getWhere('users', array('group_id', '=', $mod_group->id));
			foreach($mod_users as $individual){
				$queries->create('alerts', array(
					'user_id' => $individual->id,
					'type' => $banappeal_language['ban_appeal'],
					'url' => '/mod/banappeal/?app=' . $app_id,
					'content' => str_replace('{x}', htmlspecialchars($user->data()->username), $banappeal_language['new_ban_appeal_submitted_alert']),
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

if(!isset($completed)){
	// Has the user already submitted an Ban Appeal?
	$already_submitted = $queries->getWhere('banappeal_replies', array('uid', '=', $user->data()->id));
	foreach($already_submitted as $item){
		if($item->status == 0){
			$completed = 2;
			break;
		}
	}
	$already_submitted = null;
}

$page = $banappeal_language['ban_appeal'];

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Ban Appeal page for the <?php echo $sitename; ?> community">
    <meta name="author" content="<?php echo $sitename; ?>">
	<meta name="robots" content="noindex">
	<?php if(isset($custom_meta)){ echo $custom_meta; } ?>
	<?php $title = $banappeal_language['ban_appeal']; ?>
	<?php require('core/includes/template/generate.php'); ?>
  </head>

  <body>
    <?php $smarty->display('styles/templates/' . $template . '/navbar.tpl'); ?>
    <div class="container index">
      <?php if(Session::exists('staff_app')){echo Session::flash('staff_app');} ?>
      <h3><?php echo $banappeal_language['ban_appeal']; ?></h3>
      <br />
      <div class="row">
        <div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
          <?php if(!isset($completed)){ ?>
              <form action="" method="post">
                <?php 
                  $questions = $queries->getWhere('banappeal_questions', array('id', '<>', 0)); 
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
              <?php } else { if(Session::exists('app_success')){ echo Session::flash('app_success'); } ?>
              <?php if($completed === 2){ ?>
                <div class="alert alert-info"><?php echo $user_language['application_already_submitted']; ?></div>
              <?php } ?>
            <?php } ?>
        </div>
      </div>
    </div>
	<?php require('core/includes/template/footer.php'); ?>
	<?php $smarty->display('styles/templates/' . $template . '/footer.tpl'); ?>
	<?php require('core/includes/template/scripts.php'); ?>
  </body>
</html>
