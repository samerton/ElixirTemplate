<?php 
/*
 *	Made by Samerton
 *  http://worldscapemc.co.uk
 *
 *  License: MIT
 */
 
/* 
 *  Generate HTML for navbar
*/
 
// First, set Smarty variable to see if the navbar is inverse or not
if($inverse_navbar == 1){
	$smarty->assign('NAVBAR_INVERSE', ' navbar-inverse');
} else {
	$smarty->assign('NAVBAR_INVERSE', '');
}

// Main navbar
$main_navbar = array(
	'/' => array(1 => $navbar_language['home_icon'] . $navbar_language['home'], 2 => 'index'),
	'/forum' => array(1 => $navbar_language['forum_icon'] . $navbar_language['forum'], 2 => 'forum')
);

// Is the play page enabled?
if(isset($play_enabled) && $play_enabled == '1'){
	$main_navbar['/play'] = array(1 => $navbar_language['play_icon'] . $navbar_language['play'], 2 => 'play');
}

$navbar_links = '<ul class="nav navbar-nav">';
foreach($main_navbar as $key => $item){
	$navbar_links .= '<li';
	if(isset($page) && $page == $item[2]){
		$navbar_links .= ' class="active"';
	}
	$navbar_links .= '><a class="hvr-radial-out" href="' . htmlspecialchars($key) . '">' . $item[1] . '</a></li>';
}

// Addons
foreach($navbar_array as $item){
	foreach($item as $key => $text){
		$navbar_links .= '<li';
		if(isset($page) && $page == $text){
			$navbar_links .= ' class="active"';
		}
		$navbar_links .= '><a class="hvr-radial-out" href="/' . htmlspecialchars($key) . '">' . $text . '</a></li>';
	}
}

// Custom pages
$custom_pages = $queries->getWhere('custom_pages', array('url', '<>', ''));
if(!isset($nav_more_dropdown)) $nav_more_dropdown = array();
foreach($custom_pages as $item){
	if($item->link_location == 1){
		$navbar_links .= '<li';
		
		if(isset($page) && $page == $item->title)
			$navbar_links .= ' class="active hvr-radial-out"';
		
		if(isset($item->icon))
			$navbar_links .= '><a class="hvr-radial-out" href="' . htmlspecialchars($item->url) . '">' . $item->icon . ' ' . $item->title . '</a></li>';
		else
			$navbar_links .= '><a class="hvr-radial-out" href="' . htmlspecialchars($item->url) . '">' . $item->title . '</a></li>';

	} else if($item->link_location == 2)
		$nav_more_dropdown[] = $item;
}

// More dropdown
if(isset($nav_more_dropdown) && !empty($nav_more_dropdown)){
	$navbar_links .= '<li class="dropdown">
	<a href="#" class="dropdown-toggle hvr-radial-out" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">' . $navbar_language['more'] . ' <span class="caret"></span></a>
	<ul class="dropdown-menu">';
	foreach($nav_more_dropdown as $key => $item){
		if(isset($item->icon)) {
			$navbar_links .= '<li><a href="' . htmlspecialchars($item->url) . '">' . $item->icon . ' ' . $item->title . '</a></li>';
		} else {
			$navbar_links .= '<li><a href="' . htmlspecialchars($item->url) . '">' . $item->title . '</a></li>';
		}
	}
	$navbar_links .= '</ul></li>';
}
$navbar_links .= '</ul>';
 
// User area
if($user->isLoggedIn()){
	// Get avatar
	$avatar = '<img class="img-circle" style="margin: -10px 0px; width:25px; height:25px;" src="' . $user->getAvatar($user->data()->id, "../", 25) . '" />';
	$user_area = '
		<li class="dropdown">
		  <a href="#" class="dropdown-toggle  hvr-radial-out" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span style="margin: -10px 0px; font-size: 16px;"><i class="fa fa-envelope"></i> <div style="display: inline;" id="pms"></div></span></a>
		  <ul class="dropdown-menu">
			<li class="dropdown-header">Messages</li>';
			if(count($unread_pms)){
			  foreach($unread_pms as $pm){
				$user_area .= '<li><a href="/user/messaging/?mid=' . $pm->id . '">' . htmlspecialchars($pm->title) . '</a></li>';
			  }
			} else {
			  $user_area .= '<li><a>' . $user_language['no_messages'] . '</a></li>';
			}
	$user_area .= '
			<li class="dropdown-footer"><a href="/user/messaging">' . $navbar_language['view_messages'] . '</a></li>
		  </ul>
		</li>
		
		<li class="dropdown">
		  <a href="#" class="dropdown-toggle  hvr-radial-out" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span style="margin: -10px 0px; font-size: 16px;"><i class="fa fa-flag"></i> <div style="display: inline;" id="alerts"></div></span></a>
		  <ul class="dropdown-menu">
			<li class="dropdown-header">Alerts</li>';
			if(count($unread_alerts)) {
			  foreach($unread_alerts as $alert){
				$user_area .= '<li><a href="/user/alerts/?a=' . $alert->id . '">';
				if($alert->type == 'tag'){
				  $user_area .= $user_language['tagged_in_post'];
				} else {
				  $user_area .= htmlspecialchars($alert->content);
				}
				  $user_area .= '</a></li>';
			  }
			} else {
			  $user_area .= '<li><a>' . $user_language['no_alerts'] . '</a></li>';
			}
	$user_area .= '
			<li class="dropdown-footer"><a href="/user/alerts">' . $navbar_language['view_alerts'] . '</a></li>
		  </ul>
		</li>

		<li class="dropdown">
		  <a href="#" class="dropdown-toggle hvr-radial-out" data-toggle="dropdown" role="button" aria-expanded="false">' . $avatar . '</a>
		  <ul class="dropdown-menu" role="menu">
		    <li class="dropdown-header">' . htmlspecialchars($user->data()->mcname) . '</li>
			<li><a href="/profile/' . htmlspecialchars($user->data()->mcname) . '">'. $user_language['profile'] .'</a></li>
			<li><a href="/user">' . $user_language['user_cp'] . '</a></li>';
			if($user->canViewMCP($user->data()->id)){
			  $user_area .= '<li><a href="/mod">' . $mod_language['mod_cp'] . '</a></li>';
			}
			if($user->canViewACP($user->data()->id)){
			  $user_area .= '<li><a href="/admin">' . $admin_language['admin_cp'] . '</a></li>';
			}
			if(isset($infractions_language) && $user->canViewACP($user->data()->id)){
			  $user_area .= '<li><a href="/infractions">' . $admin_language['infractions'] . '</a></li>';
			}
			$user_area .= '<li class="dropdown-footer"><a href="/signout">' . $user_language['sign_out'] . '</a></li>
	   	  </ul>
		</li>';
} else {
	// Guest
	$user_area = '
		<ul class="nav navbar-nav">
		  <li><a class="hvr-radial-out" href="/signin">' . $user_language['sign_in'] . '</a></li>
		  <li><a class="hvr-radial-out" href="/register">' . $user_language['register'] . '</a></li>
		</ul>
	';
}


// Global messages
$global_messages = '';
if(Session::exists('global')){
	$global_messages = Session::flash('global');
}
if($user->isLoggedIn()){
	if($infraction = $user->hasInfraction($user->data()->id)){
		$global_messages .= '
			<div class="alert alert-danger">
			  <center>';
				$global_messages .= str_replace(array('{x}', '{y}'), array(htmlspecialchars($user->IdToName($infraction[0]["staff"])), date("jS F Y", strtotime($infraction[0]["date"]))), $user_language['you_have_received_a_warning']);
				$global_messages .= '<br /><br />"' . htmlspecialchars($infraction[0]["reason"]) . '"<br /><br />
				<a href="/user/acknowledge/?iid=' . $infraction[0]["id"] . '" class="btn btn-primary">' . $user_language['acknowledge'] . '</a>
			  </center>
			</div>';
	}
}

// Assign to Smarty variables
$smarty->assign('NAVBAR_LINKS', $navbar_links);
$smarty->assign('USER_AREA', $user_area);
$smarty->assign('GLOBAL_MESSAGES', $global_messages);
$smarty->assign('ALERTS', "Alerts");
$smarty->assign('MESSAGES', "Messages");
$smarty->assign('USERCP', $user_language['user_cp']);
$smarty->assign('MODCP', $mod_language['mod_cp']);
$smarty->assign('ADMINCP', $admin_language['admin_cp']);
$smarty->assign('SIGNIN', $user_language['sign_in']);
$smarty->assign('REGISTER', $user_language['register']);
?>