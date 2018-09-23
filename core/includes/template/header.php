<?php
/*
 *	Made by Samerton
 *  http://worldscapemc.co.uk
 *
 *  License: MIT
 */
 
/*
 *  Create page header (which css to load)
 */

// Title and meta tags
echo '<title>' . str_replace('&amp;', '&', $title) . ' | ' . $sitename . '</title>';
echo '<meta property="og:title" content="Elixir Theme " />' . PHP_EOL;
echo '<meta property="og:site_name" content="Elixir Theme" />' . PHP_EOL;
echo '<meta property="og:description" content="Elixir Theme for NamelessMC V1" />' . PHP_EOL;
echo '<meta property="og:url" content="https://elixir.xemah.xyz/" />' . PHP_EOL;
echo '<meta property="og:image" content="https://elixir.xemah.xyz/styles/themes/' . $theme_result . '/img/logo.png" />' . PHP_EOL;
 
// Check to see if the theme actually exists..
if(!is_dir('styles/themes/' . $theme_result)){
	// Doesn't exist
	// Display an error
	Session::flash('global', '<div class="alert alert-danger">' . $general_language['theme_not_exist'] . '</div>');
	// Load default css instead
	echo '<link href="' . PATH . 'styles/themes/Bootstrap/css/bootstrap.min.css" rel="stylesheet">' . PHP_EOL;
	echo '<link href="' . PATH . 'styles/themes/Bootstrap/css/custom.css" rel="stylesheet">' . PHP_EOL;
	echo '<link href="' . PATH . 'styles/themes/Bootstrap/css/font-awesome.min.css" rel="stylesheet">' . PHP_EOL;
} else {
	// Exists
	// Load the css
	echo '<link href="' . PATH . 'styles/themes/' . $theme_result . '/css/bootstrap.min.css" rel="stylesheet">' . PHP_EOL;
	echo '<link href="' . PATH . 'styles/themes/' . $theme_result . '/css/custom.css" rel="stylesheet">' . PHP_EOL;
	echo '<link href="' . PATH . 'styles/themes/' . $theme_result . '/css/font-awesome.min.css" rel="stylesheet">' . PHP_EOL;
}

// Global
echo '<link href="' . PATH . 'core/assets/css/toastr.css" rel="stylesheet">' . PHP_EOL;
echo '<link href="' . PATH . 'core/assets/css/custom_core.css" rel="stylesheet">' . PHP_EOL;
if(file_exists("' . PATH . 'core/assets/favicon.ico")){ 
    echo '<link rel="icon" href="' . PATH . 'core/assets/favicon.ico">' . PHP_EOL;
} else {
    echo '<link rel="icon" type="image/png" href="' . PATH . 'core/assets/favicon.png">' . PHP_EOL;
}

// Elixir 
echo '<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600" rel="stylesheet">' . PHP_EOL;
echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">' . PHP_EOL;
echo '<link rel="stylesheet" href="' . PATH . 'styles/themes/' . $theme_result . '/css/hover.css">' . PHP_EOL;
echo '<link rel="stylesheet" href="' . PATH . 'styles/themes/' . $theme_result . '/css/hover-min.css">' . PHP_EOL;

// Custom
foreach($custom_css as $item){
	echo $item;
}

// Google Analytics module
if(isset($ga_script)){
	echo $ga_script;
}

// Announcements
if(isset($page)){
	$page_announcements = $queries->getWhere('announcements_pages', array('page', '=', $page));
	if(count($page_announcements)){
		if($user->isLoggedIn()) $group_id = $user->data()->group_id;
		else $group_id = 0;
		$announcements = array();
		foreach($page_announcements as $page_announcement){
		    // Permissions
			$permissions = $queries->getWhere('announcements_permissions', array('announcement_id', '=', $page_announcement->announcement_id));
			foreach($permissions as $permission){
				if($permission->view == 1 && $permission->group_id == $group_id){
					$announcement = $queries->getWhere('announcements', array('id', '=', $page_announcement->announcement_id));
					$announcement = $announcement[0];
					$announcements[] = array(
						'type' => htmlspecialchars($announcement->type),
						'content' => Output::getPurified(htmlspecialchars_decode($announcement->content)),
						'can_close' => $announcement->can_close,
						'id' => $announcement->id
					);
				}
			}
		}
		$smarty->assign('ANNOUNCEMENTS', $announcements);
	}
}

$smarty->assign('page', $page);

//Online Players
if($page != "play") {
    $online_users = $queries->getWhere('users', array('last_online', '>', strtotime("-10 minutes")));
    if(count($online_users)){
        $online_users_string = '';
        foreach($online_users as $online_user){
	        $online_users_string .= '<a href="/profile/' . htmlspecialchars($online_user->mcname) . '">' . htmlspecialchars($online_user->username) . '</a>, ';
        }
        $smarty->assign('ONLINE_USERS_LIST', rtrim($online_users_string, ', '));
    } else {
        $smarty->assign('ONLINE_USERS_LIST', $forum_language['no_users_online']);
    }
    $smarty->assign('ONLINE_USERS', $forum_language['online_users']);

    // Server query
    // Get the main IP
    $main_ip = $queries->getWhere('mc_servers', array('is_default', '=', 1));
    $pre17 	 = $main_ip[0]->pre;
    $query_ip = htmlspecialchars($main_ip[0]->query_ip);
    $main_ip = htmlspecialchars($main_ip[0]->ip);
    
    
    // Get port of Minecraft server
    $parts = explode(':', $query_ip);
    if(count($parts) == 1){
    	$domain = $parts[0];
    	$default_ip = $parts[0];
    	$default_port = 25565;
    } else if(count($parts) == 2){
    	$domain = $parts[0];
    	$default_ip = $parts[0];
    	$default_port = $parts[1];
    	$port = $parts[1];
    } else {
    	echo 'Invalid Query IP';
    	die();
    }
    
    // Get IP to display
    $parts = explode(':', $main_ip);
    if(count($parts) == 1){
    	$display_domain = $parts[0];
    } else if(count($parts) == 2){
    	$display_domain = $parts[0];
    	$display_port = $parts[1];
    } else {
    	echo 'Invalid Display IP';
    	die();
    }
    if((!isset($display_port))||($display_port == "25565")){
    	$address = $display_domain;
    } else {
    	$address = $display_domain . ':' . $port;
    }
    $connect_with = str_replace('{x}', htmlspecialchars($address), $general_language['connect_with']);
    $smarty->assign('CONNECT_WITH', $connect_with);
    
    // Query the main IP
    // Are we using the built-in query or an external API?
    $external_query = $queries->getWhere('settings', array('name', '=', 'external_query'));
    $external_query = $external_query[0]->value;
    if($external_query == 'false'){
    	// Built in query, continue as normal
    	require('core/integration/status/global.php'); 
    } else {
    	// External query
    	$cache = new Cache();
    	require('core/integration/status/global_external.php');
    }
    if(empty($Info)){
    	// Unable to query, offline
    	$smarty->assign('MAIN_ONLINE', 0);
    } else {
    	// Able to query, online
    	$smarty->assign('MAIN_ONLINE', 1);
    }
    // Player count
    if($pre17 == 0) {
    	$player_count = $Info['players']['online'];
    } else {
    	$player_count = $Info['Players'];
    }
    if($player_count == 1) {
    	$smarty->assign('PLAYERS_ONLINE', $general_language['1_player_online']);
    } else if($player_count > 1) {
    	$smarty->assign('PLAYERS_ONLINE', str_replace('{x}', $player_count, $general_language['x_players_online']));
    } else {
    	$smarty->assign('PLAYERS_ONLINE', $general_language['no_players_online']);
    }
}

// Statistics
$users_query = $queries->orderAll('users', 'joined', 'DESC');
$stats_table = '
    <dl>
      <dt>' . $forum_language['users_registered'] . '</dt>
      <dd>' . count($users_query) . '</dd>
    </dl>
    <dl>
      <dt>' . $forum_language['latest_member'] . '</dt>
      <dd>' . '<a href="/profile/' . htmlspecialchars($users_query[0]->mcname) . '">' . htmlspecialchars($users_query[0]->username) . '</a></dd>
    </dl>';
    
$users_registered = '<strong>' . $forum_language['users_registered'] . '</strong> ' . count($users_query);
$latest_member = '<strong>' . $forum_language['latest_member'] . '</strong> <a href="/profile/' . htmlspecialchars($users_query[0]->mcname) . '">' . htmlspecialchars($users_query[0]->username) . '</a>';
$users_query = null;

$smarty->assign('STATISTICS', $forum_language['statistics']);
$smarty->assign('STATS_TABLE', $stats_table);
$smarty->assign('USERS_REGISTERED', $users_registered);
$smarty->assign('LATEST_MEMBER', $latest_member);