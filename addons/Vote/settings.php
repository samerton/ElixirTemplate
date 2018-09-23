<?php 
/*
 *	Made by Samerton
 *  https://worldscapemc.com
 *
 *  License: MIT
 */

// Settings for the Vote addon

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

if(!isset($_GET['do']) && !isset($_GET['vid'])){
	// Display information first
?>
<div class="panel-body post-body">
  <h4 class="inline"><?php echo $vote_language['vote']; ?></h4>
  <span class="pull-right"><a href="/admin/addons/?action=edit&amp;addon=Vote&amp;do=new" class="btn btn-primary">New Vote Site</a></span>
  <br />
  <span class="tagline"><b>Authors:</b> Samerton<br />
  <b>Version:</b> 1.0.4<br />
  <b>Description:</b> Adds a page where users can vote for your server</span>
  <br /><br />
  <?php
    $vote_settings = $queries->tableExists('vote_settings');
    if(empty($vote_settings)){
    	// Hasn't been installed yet
    	// Install now
    	$data = $queries->createTable("vote_settings", " `id` int(11) NOT NULL AUTO_INCREMENT, `name` varchar(20) NOT NULL, `value` varchar(2048) NOT NULL, PRIMARY KEY (`id`)", "ENGINE=InnoDB DEFAULT CHARSET=latin1");
    	echo '<strong>Vote Settings</strong> table successfully initialised<br />';
    	$data = $queries->createTable("vote_sites", " `id` int(11) NOT NULL AUTO_INCREMENT, `site` varchar(512) NOT NULL, `name` varchar(64) NOT NULL, PRIMARY KEY (`id`)", "ENGINE=InnoDB DEFAULT CHARSET=latin1");
    	echo '<strong>Vote Sites</strong> table successfully initialised<br />';
    	
    	// Insert data
    	$queries->create('vote_settings', array(
    		'name' => 'vote_message',
    		'value' => ''
    	));
    	
    	echo '<script>window.location.replace(\'/admin/addons/?action=edit&addon=Vote\');</script>';
    	die();
    }
  ?>
  <div class="panel-body">
    <h5>Vote Sites</h5>
    <br />
    <?php
      // Get vote sites from database
      $vote_sites = $queries->getWhere('vote_sites', array('id', '<>', 0));
      if(!count($vote_sites)){
      	// No sites defined
    ?>
    <strong>No vote sites defined.</strong>
    <?php } else { ?>
    <?php 
      // Loop through each vote site
      foreach($vote_sites as $site){
    ?>
    <?php echo '<span style="display:inline-block;padding:4px;"><a href="/admin/addons/?action=edit&addon=Vote&amp;vid=' . $site->id . '">' . htmlspecialchars($site->name) . '</a></span>'; ?>
    <span class="pull-right">
    <a href="/admin/addons/?action=edit&addon=Vote&amp;do=delete&amp;vid=<?php echo $site->id;?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this site?');"><span class="fas fa-trash-alt"></span></a>
    </span>
    <hr>
    <?php } ?>
  </div>
  <?php
      }
    // Deal with input
    if(Input::exists()){
    if(Token::check(Input::get('token'))){
    	$validate = new Validate();
    	$validation = $validate->check($_POST, array(
    		'message' => array(
    			'max' => 2048
    		)
    	));
    	
    	if($validation->passed()){
    		// Link location
    		$c->setCache('voteaddon');
    		$c->store('linklocation', htmlspecialchars(Input::get('linkposition')));
    		
    		try {
    			$queries->update('vote_settings', 1, array(
    				'value' => Input::get('message')
    			));
    			echo '<script>window.location.replace("/admin/addons/?action=edit&addon=Vote");</script>';
    			die();
    		} catch(Exception $e){
    			die($e->getMessage());
    		}
    	} else {
      ?>
  <div class="alert alert-danger">Your vote message must be a maximum of 2048 characters</div>
  <?php 
        }
      } else {
        echo '<div class="alert alert-warning">' . $admin_language['invalid_token'] . '</div>';
      }
    }
    // Get vote message
    $vote_message = $queries->getWhere('vote_settings', array('id', '=', 1));
    $vote_message = htmlspecialchars($vote_message[0]->value);
  ?>
  <br />
  <div class="panel-body">
    <form action="" method="post">
      <div class="form-group">
        <label for="InputMessage">Message to display at top of Vote page <em>(This can be left blank)</em></label><br />
        <textarea name="message" rows="3" id="InputMessage" class="form-control"><?php echo htmlspecialchars($vote_message); ?></textarea>
      </div>
      <div class="form-group">
        <label for="InputLinkPosition"><?php echo $admin_language['page_link_location']; ?></label>
        <?php
          // Get position of link
          $c->setCache('voteaddon');
          if($c->isCached('linklocation')){
          	$link_location = $c->retrieve('linklocation');
          } else {
          	$c->store('linklocation', 'navbar');
          	$link_location = 'navbar';
          }
          ?>
        <select name="linkposition" id="InputLinkPosition" class="form-control">
          <option value="navbar" <?php if($link_location == 'navbar'){ echo 'selected="selected"'; } ?>><?php echo $admin_language['page_link_navbar']; ?></option>
          <option value="more" <?php if($link_location == 'more'){ echo 'selected="selected"'; } ?>><?php echo $admin_language['page_link_more']; ?></option>
          <option value="footer" <?php if($link_location == 'footer'){ echo 'selected="selected"'; } ?>><?php echo $admin_language['page_link_footer']; ?></option>
          <option value="none" <?php if($link_location == 'none'){ echo 'selected="selected"'; } ?>><?php echo $admin_language['page_link_none']; ?></option>
        </select>
      </div>
      <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
      <input type="submit" value="<?php echo $general_language['submit']; ?>" class="btn btn-primary">
    </form>
  </div>
  <?php
    } else {
    	if(isset($_GET['do']) && $_GET['do'] == 'new'){
    		// new vote site
    		if(Input::exists()){
    			if(Token::check(Input::get('token'))){
    				// process addition of site
    				$validate = new Validate();
    				$validation = $validate->check($_POST, array(
    					'vote_site_name' => array(
    						'required' => true,
    						'min' => 2,
    						'max' => 64
    					),
    					'vote_site_url' => array(
    						'required' => true,
    						'min' => 2,
    						'max' => 255
    					)
    				));
    				
    				if($validation->passed()){
    					// input into database
    					try {
    						$queries->create('vote_sites', array(
    							'site' => htmlspecialchars(Input::get('vote_site_url')),
    							'name' => htmlspecialchars(Input::get('vote_site_name'))
    						));
    						echo '<script>window.location.replace("/admin/addons/?action=edit&addon=Vote");</script>';
    						die();
    					} catch(Exception $e){
    						die($e->getMessage());
    					}
    				} else {
    					// validation failed
    					echo '<div class="alert alert-danger">';
    					foreach($validation->errors() as $error){
    						echo str_replace("_", " ", ucfirst($error)), '<br />';
    					}
    					echo '</div>';
    				}
    			} else {
    				echo '<div class="alert alert-warning">' . $admin_language['invalid_token'] . '</div>';
    			}
    		}
    	  ?>
  <div class="panel-body">
    <h4>New Vote Site</h4>
    <br />
    <form action="" method="post">
      <div class="form-group">
        <label for="InputVoteName">Vote Site Name</label>
        <input type="text" id="InputVoteName" placeholder="Vote site name" name="vote_site_name" class="form-control">
      </div>
      <div class="form-group">
        <label for="InputVoteURL">Vote Site URL <em>(With preceding http://)</em></label>
        <input type="text" id="InputVoteURL" placeholder="Vote site URL" name="vote_site_url" class="form-control">
      </div>
      <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
      <input type="submit" value="<?php echo $general_language['submit']; ?>" class="btn btn-primary">
      <a href="/admin/addons/?action=edit&addon=Vote" class="btn btn-danger"><?php echo $general_language['back']; ?></a>
    </form>
  </div>
  <?php
    } else if(isset($_GET['do']) && $_GET['do'] == 'delete' && isset($_GET['vid'])){
    	// Delete a site
    	if(!isset($_GET["vid"]) || !is_numeric($_GET["vid"])){
    		echo '<script>window.location.replace("/admin/addons/?action=edit&addon=Vote");</script>';
    		die();
    	}
    	try {
    		$queries->delete('vote_sites', array('id', '=' , $_GET["vid"]));
    		echo '<script>window.location.replace("/admin/addons/?action=edit&addon=Vote");</script>';
    		die();
    	} catch(Exception $e) {
    		die($e->getMessage());
    	}
    	
    } else if(isset($_GET['vid']) && !isset($_GET['do'])){
    	// Edit a site
    	if(!is_numeric($_GET["vid"])){
    		echo '<script>window.location.replace("/admin/addons/?action=edit&addon=Vote");</script>';
    		die();
    	} else {
    		$site = $queries->getWhere("vote_sites", array("id", "=", $_GET["vid"]));
    		if(!count($site)){
    			echo '<script>window.location.replace("/admin/addons/?action=edit&addon=Vote");</script>';
    			die();
    		}
    		$site = $site[0];
    	}
    	
    	if(Input::exists()){
    		if(Token::check(Input::get('token'))){
    			$validate = new Validate();
    			$validation = $validate->check($_POST, array(
    				'vote_name' => array(
    					'required' => true,
    					'min' => 2,
    					'max' => 64
    				),
    				'vote_url' => array(
    					'required' => true,
    					'min' => 2,
    					'max' => 255
    				)
    			));
    			
    			if($validation->passed()){
    				// input into database
    				try {
    					$queries->update('vote_sites', $site->id, array(
    						'site' => htmlspecialchars(Input::get('vote_url')),
    						'name' => htmlspecialchars(Input::get('vote_name'))
    					));
    					echo '<script>window.location.replace("/admin/addons/?action=edit&addon=Vote");</script>';
    					die();
    				} catch(Exception $e){
    					die($e->getMessage());
    				}
    			} else {
    				// validation failed
    				echo '<div class="alert alert-danger">';
    				foreach($validation->errors() as $error){
    					echo str_replace("_", " ", ucfirst($error)), '<br />';
    				}
    				echo '</div>';
    			}
    		} else {
    			echo '<div class="alert alert-warning">' . $admin_language['invalid_token'] . '</div>';
    		}
    	}
      ?>
  <div class="panel-body">
    <h4>Editing site</h4>
    <br />
    <form role="form" action="" method="post">
      <div class="form-group">
        <label for="InputName">Vote Site Name</label>
        <input type="text" name="vote_name" class="form-control" id="InputName" placeholder="Vote site name" value="<?php echo htmlspecialchars($site->name); ?>">
      </div>
      <div class="form-group">
        <label for="InputURL">Vote Site URL <em>(With preceding http://)</em></label>
        <input type="text" name="vote_url" id="InputURL" placeholder="Vote site URL" class="form-control" value="<?php echo htmlspecialchars($site->site); ?>">
      </div>
      <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
      <input type="submit" value="<?php echo $general_language['submit']; ?>" class="btn btn-primary">
      <a href="/admin/addons/?action=edit&addon=Vote" class="btn btn-danger"><?php echo $general_language['back']; ?></a>
    </form>
  </div>
  <?php } } ?>
</div>
