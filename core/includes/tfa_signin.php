<?php
/*
 *	Made by Samerton
 *  http://worldscapemc.co.uk
 *
 *  License: MIT
 */

// Two Factor Auth signin
$_SESSION['username'] = Input::get('username');
$_SESSION['password'] = Input::get('password');
$_SESSION['remember'] = Input::get('remember');
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?php echo $sitename; ?> sign in page">
    <meta name="author" content="Samerton">
    <?php if(isset($custom_meta)){ echo $custom_meta; } ?>
    <?php $title = $user_language['sign_in']; ?>
    <?php require('core/includes/template/generate.php'); ?>
  </head>
  
  <body>
    <?php $smarty->display('styles/templates/' . $template . '/navbar.tpl'); ?>
    <div class="container index">
      <h3><?php echo $user_language['two_factor_authentication']; ?></h3>
      <br />
      <div class="row">
        <div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
          <form action="" method="post">
            <?php if(Session::exists('tfa_signin')) echo Session::flash('tfa_signin'); ?>
            <div class="form-group">
              <span class="field-name"><?php if($user_query[0]->tfa_type == 1) echo $user_language['tfa_enter_code']; else echo $user_language['tfa_enter_email_code']; ?></span>
              <input type="text" class="form-control" name="tfa_code">
            </div>
            <input type="hidden" name="tfa" value="true">
            <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
            <input type="submit" value="<?php echo $general_language['submit']; ?>" class="btn btn-primary">
          </form>
        </div>
      </div>
    </div>
    <?php require('core/includes/template/footer.php'); ?>
    <?php $smarty->display('styles/templates/' . $template . '/footer.tpl'); ?>
    <?php require('core/includes/template/scripts.php'); ?>
  </body>
</html>
