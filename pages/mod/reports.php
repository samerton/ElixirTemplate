<?php 
/* 
 *	Made by Samerton
 *  http://worldscapemc.co.uk
 *
 *  License: MIT
 */

// Mod check
if($user->isLoggedIn()){
	if(!$user->canViewMCP($user->data()->id)){
		Redirect::to('/');
		die();
	}
} else {
	Redirect::to('/');
	die();
}

// page for ModCP sidebar
$mod_page = 'reports';

require('core/includes/htmlpurifier/HTMLPurifier.standalone.php'); // HTMLPurifier

if(Input::exists()) {
	if(Token::check(Input::get('token'))) {
		if(Input::get('type') === "update_status") {
			try {
				$queries->update("reports", Input::get('report_id'), array(
					'status' => 1,
					'date_updated' => date('Y-m-d H:i:s'),
					'updated_by' => $user->data()->id
				));
				Session::flash('success_comment_report', '<div class="alert alert-info alert-dismissable"> <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span></button>' . $mod_language['report_closed'] . '</div>');
				Redirect::to('/mod/reports/?rid=' . Input::get('report_id'));
				die();
			} catch(Exception $e){
				die($e->getMessage());
			}
		} else if(Input::get('type') === "comment") {
			$validate = new Validate();
			$validation = $validate->check($_POST, array(
				'comment' => array(
					'required' => true,
					'min' => 2,
					'max' => 255
				)
			));
			if($validation->passed()){
				try {
					$queries->create("reports_comments", array(
						'report_id' => Input::get('report_id'),
						'commenter_id' => $user->data()->id,
						'comment_date' => date('Y-m-d H:i:s'),
						'comment_content' => htmlspecialchars(Input::get('comment'))
					));
					$queries->update("reports", Input::get('report_id'), array(
						'date_updated' => date('Y-m-d H:i:s'),
						'updated_by' => $user->data()->id
					));
					Session::flash('success_comment_report', '<div class="alert alert-info alert-dismissable"> <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span></button>' . $mod_language['comment_added'] . '</div>');
					Redirect::to('/mod/reports/?rid=' . Input::get('report_id'));
					die();
				} catch(Exception $e){
					die($e->getMessage());
				}
			} else {
				foreach($validation->errors() as $error) {
					$error_string .= ucfirst($error) . '<br />';
				}
				Session::flash('failure_comment_report', '<div class="alert alert-danger alert-dismissable"> <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span></button>' . $error_string . '</div>');
				Redirect::to('/mod/reports/?rid=' . Input::get('report_id'));
				die();
			}
		}
	} else {
		Redirect::to("/mod");
		die();
	}
}

$token = Token::generate();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Moderator panel">
    <meta name="author" content="<?php echo $sitename; ?>">
    <meta name="robots" content="noindex">
    <?php if(isset($custom_meta)){ echo $custom_meta; } ?>
    <?php $title = $mod_language['mod_cp']; ?>
    <?php require('core/includes/template/generate.php'); ?>
  </head>
  
  <body>
    <?php $smarty->display('styles/templates/' . $template . '/navbar.tpl'); ?>
    <br />
    <div class="container index">
      <h3><?php echo $mod_language['mod_cp']; ?></h3>
      <br />
      <div class="row">
        <div class="col-md-3">
          <?php require('pages/mod/sidebar.php'); ?>
        </div>
        <div class="col-md-9">
          <?php 
            if(!isset($_GET["rid"])){
              if($reports == true){
            	$reports = $queries->getWhere('reports', array('status', '<>', '1')); // Get a list of open reports
          ?>
          <div class="panel-body">
            <table class="table table-formed">
              <thead>
                <tr>
                  <th><?php echo $mod_language['type']; ?></th>
                  <th><?php echo $mod_language['user_reported']; ?></th>
                  <th><?php echo $mod_language['comments']; ?></th>
                  <th><?php echo $mod_language['updated_by']; ?></th>
                </tr>
              </thead>
              <tbody>
                <?php foreach($reports as $report){ ?>
                  <tr>
                    <td><a href="/mod/reports/?rid=<?php echo $report->id; ?>"><?php
                      if($report->type == 0){
                        echo $mod_language['forum_post'];
                      } else if ($report->type == 1) {
                        echo $mod_language['ingame_report'];
                      }
                    ?></a></td>
                    <td><a href="/profile/<?php echo htmlspecialchars(($report->type == 0 ? $user->idToMCName($report->reported_id) : $report->reported_mcname)); ?>"><?php echo htmlspecialchars(($report->type == 0 ? $user->idToName($report->reported_id) : $report->reported_mcname)); ?></a></td>
                    <td><?php echo(count($queries->getWhere("reports_comments", array('report_id' , '=', $report->id)))); ?></td>
                    <td><a href="/profile/<?php echo htmlspecialchars($user->idToMCName($report->updated_by)); ?>"><img class="img-circle" style="height:30px; width:30px;" src="<?php echo $user->getAvatar($report->updated_by, '../', 30); ?>" /></a>&nbsp;&nbsp;&nbsp;<a href="/profile/<?php echo htmlspecialchars($user->idToName($report->updated_by)); ?>"><?php echo htmlspecialchars($user->idToName($report->updated_by)); ?></a></td>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
            <?php } else { ?>
              <?php echo $mod_language['no_open_reports']; ?>
            <?php } ?>
          </div>
          <?php } else {
            if(!is_numeric($_GET["rid"])){
            	echo '<script>window.location.replace("/mod/reports");</script>';
            	die();
            }
            $report = $queries->getWhere("reports", array('id' , '=', $_GET["rid"]));
            if(!count($_GET["rid"])){
            	echo 'No report with that ID';
            } else {
            	if($report[0]->type == 0){
            		$url = "/forum/view_topic/?tid=" . $report[0]->reported_post_topic . "&pid=" . $report[0]->reported_post;
            	} else {
            		$url = "/profile/" . htmlspecialchars($user->idToName($report[0]->reported_id));
            	}
            	if(Session::exists('failure_comment_report')){
            		echo '<center>' . Session::flash('failure_comment_report') . '</center>';
            	}
            	if(Session::exists('success_comment_report')){
            		echo '<center>' . Session::flash('success_comment_report') . '</center>';
            	}
              ?>
          <div class="panel-body post-body">
            <h4 class="inline"><?php echo $mod_language['report']; ?> <a href="/profile/<?php echo htmlspecialchars(($report[0]->type == 0 ? $user->idToMCName($report[0]->reported_id) : $report[0]->reported_mcname)); ?>"><?php echo htmlspecialchars(($report[0]->type == 0 ? $user->idToName($report[0]->reported_id) : $report[0]->reported_mcname));?></a></h4>
            <span class="pull-right">
              <form action="" method="post">
                <?php echo '<input type="hidden" name="type" value="update_status">'; ?>
                <?php echo '<input type="hidden" name="token" value="' .  $token . '">'; ?>
                <?php echo '<input type="hidden" name="report_id" value="' . $_GET["rid"] . '">'; ?>
                <?php if($report[0]->status == 0) { ?>
                  <button style="display: inline;" type="submit" class="btn btn-danger"><?php echo $mod_language['close_issue']; ?></button>
                <?php } else { ?>
                  <button style="display: inline;" type="submit" class="btn btn-danger disabled"><?php echo $mod_language['report_closed']; ?></button>
                <?php } ?>
              </form>
            </span>
            <br />
            <span class="tagline"><?php if($report[0]->type != 1){ ?><a href="<?php echo $url; ?>"><?php echo $mod_language['view_reported_content']; ?></a><?php } ?></span>
            <br /><br />
            <div class="panel-body">
              <div class="message-content">
                <strong><?php echo $mod_language['reported_by']; ?> <a href="/profile/<?php echo htmlspecialchars($user->idToMCName($report[0]->reporter_id));?>"><?php echo htmlspecialchars($user->idToName($report[0]->reporter_id));?></a></strong>
                <br />
                <?php
                  $config = HTMLPurifier_Config::createDefault();
                  $config->set('HTML.Doctype', 'XHTML 1.0 Transitional');
                  $config->set('URI.DisableExternalResources', false);
                  $config->set('URI.DisableResources', false);
                  $config->set('HTML.Allowed', 'u,p,b,a,i,small,blockquote,span[style],span[class],p,strong,em,li,ul,ol,div[align],br,img');
                  $config->set('CSS.AllowedProperties', array('text-align', 'float', 'color','background-color', 'background', 'font-size', 'font-family', 'text-decoration', 'font-weight', 'font-style', 'font-size'));
                  $config->set('HTML.AllowedAttributes', 'target, href, src, height, width, alt, class, *.style');
                  $config->set('Attr.AllowedFrameTargets', array('_blank', '_self', '_parent', '_top'));
                  $config->set('HTML.SafeIframe', true);
                  $config->set('Core.EscapeInvalidTags', true);
                  $config->set('URI.SafeIframeRegexp', '%^(https?:)?//(www\.youtube(?:-nocookie)?\.com/embed/|player\.vimeo\.com/video/)%');
                  $purifier = new HTMLPurifier($config);
                  echo $purifier->purify(str_replace('\n', '<br />', htmlspecialchars_decode($report[0]->report_reason)));
                  ?>
                </div>
              <div class="message-meta">
                <?php echo date("jS M Y , g:ia", strtotime($report[0]->date_reported)); ?>
              </div>
            </div>
            <br />
            <?php
              $comments = $queries->getWhere("reports_comments", array('report_id' , '=', $_GET["rid"]));
              if(count($comments)){
            ?>
              <h4><?php echo $mod_language['comments']; ?></h4>
              <span class="tagline"><?php echo $mod_language['only_viewed_by_staff']; ?></span>
              <br /><br />
              <?php foreach($comments as $comment){ ?>
                <div class="panel-body">
                  <div class="message-content">
                    <strong><a href="/profile/<?php echo htmlspecialchars($user->idToMCName($comment->commenter_id));?>"><?php echo htmlspecialchars($user->idToName($comment->commenter_id));?></a></strong>
                    <br />
                    <?php echo $purifier->purify(htmlspecialchars_decode($comment->comment_content)); ?>
                   </div>
                  </div>
                  <div class="message-meta">
                    <?php echo date("jS M Y , g:ia", strtotime($comment->comment_date)); ?>
                  </div>
                </div>
                <br />
              <?php } ?>
            <?php } ?>
            <h4><?php echo $mod_language['new_comment']; ?><h4>
            <form action="" method="post">
              <textarea name="comment" class="form-control" rows="3"></textarea>
              <br />
              <?php echo '<input type="hidden" name="type" value="comment">'; ?>
              <?php echo '<input type="hidden" name="token" value="' .  $token . '">'; ?>
              <?php echo '<input type="hidden" name="report_id" value="' . $_GET["rid"] . '">'; ?>
              <button type="submit" class="btn btn-primary"><?php echo $general_language['submit']; ?></button>
            </form>
          </div>
          <?php } } ?>
        </div>
      </div>
    </div>
	<?php require('core/includes/template/footer.php'); ?>
	<?php $smarty->display('styles/templates/' . $template . '/footer.tpl'); ?>
	<?php require('core/includes/template/scripts.php'); ?>
  </body>
</html>