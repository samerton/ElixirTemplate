<?php 
/*
 *	Made by Samerton
 *  http://worldscapemc.co.uk
 *
 *  License: MIT
 */
 
require('core/includes/password.php');

if(!isset($_GET['c'])){
	Redirect::to('/');
	die();
} else {
	$check = $queries->getWhere('users', array('reset_code', '=', $_GET['c']));
	if(count($check)){
		if(Input::exists()) {
			if(Token::check(Input::get('token'))) {
				$validate = new Validate();
				$validation = $validate->check($_POST, array(
					'password_new' => array(
						'required' => true,
						'min' => 6,
						'max' => 30
					),
					'password_new_again' => array(
						'required' => true,
						'min' => 6,
						'matches' => 'password_new'
					)
				));
				
				if($validation->passed()) {
					$password = password_hash(Input::get('password_new'), PASSWORD_BCRYPT, array("cost" => 13));
					$queries->update('users', $check[0]->id, array(
						'password' => $password,
						'reset_code' => '',
						'active' => 1
					));
					
					Session::flash('home', '<div class="alert alert-info">' . $user_language['your_password_has_been_changed'] . '</div>');
					Redirect::to('/');
					die();
					
				} else {
					$error_message = '<div class="alert alert-danger">';
					foreach($validation->errors() as $error){
						if(strpos($error, 'is required') !== false){
							// empty input
							$error_message .= $user_language['password_required'] . '<br />';
						} else if(strpos($error, 'password_new must be a minimum') !== false){
							// below the minimum 6 chars
							$error_message .= $user_language['password_minimum_6'] . '<br />';
						} else if(strpos($error, 'password_new must be a maximum') !== false){
							// above the maximum 30 chars
							$error_message .= $user_language['password_maximum_30'] . '<br />';
						} else if(strpos($error, 'must match') !== false){
							// password must match password again
							$error_message .= $user_language['passwords_dont_match'] . '<br />';
							
						}
					}
					$error_message .= '</div>';
					Session::flash('error', $error_message);
				}
				
			}
		}
	} else {
		Session::flash('error', '<div class="alert alert-danger">' . $general_language['error'] . '</div>');
		Redirect::to('/');
		die();
	}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?php echo $user_language['change_password']; ?> &bull; <?php echo $sitename; ?>">
    <meta name="author" content="<?php echo $sitename; ?>">
    <meta name="theme-color" content="#454545" />
    <?php if(isset($custom_meta)){ echo $custom_meta; } ?>
    <?php $title = $user_language['change_password']; ?>
    <?php require('core/includes/template/generate.php'); ?>
  </head>
  
  <body>
    <?php $smarty->display('styles/templates/' . $template . '/navbar.tpl'); ?>
    <div class="container index">
      <div class="row">
        <div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
          <form action="/change_password/?c=<?php echo $_GET['c']; ?>" method="post">
            <h2><?php echo $user_language['change_password']; ?></h2>
            <?php if(Session::exists('error')){ echo Session::flash('error'); } ?>
            <input class="form-control" type="password" name="password_new" id="password_new" placeholder="<?php echo $user_language['password']; ?>" autocomplete="off">
            <br />
            <input class="form-control" type="password" name="password_new_again" id="password_new_again" placeholder="<?php echo $user_language['confirm_password']; ?>" autocomplete="off">
            <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
            <br />
            <input class="btn btn-primary" type="submit" value="<?php echo $general_language['submit']; ?>">
          </form>
        </div>
      </div>
    </div>
	<?php require('core/includes/template/footer.php'); ?>
	<?php $smarty->display('styles/templates/' . $template . '/footer.tpl'); ?>
	<?php require('core/includes/template/scripts.php'); ?>
  </body>
</html>
<?php } ?>
