{*
  ______  _       _____ __   __ _____  _____  
 |  ____|| |     |_   _|\ \ / /|_   _||  __ \ 
 | |__   | |       | |   \ V /   | |  | |__) |
 |  __|  | |       | |    > <    | |  |  _  / 
 | |____ | |____  _| |_  / . \  _| |_ | | \ \ 
 |______||______||_____|/_/ \_\|_____||_|  \_\
                   BY XEMAH
             https://www.xemah.xyz
                    
*}

{if isset($LOGGED_IN_USER)}
  <div class="navbar-user-sm">
      <a class="hvr-radial-out pull-right" href="/signout"><i class="fas fa-sign-out-alt"></i></a>
      <a class="hvr-radial-out pull-right" href="/user">{$LOGGED_IN_USER.username}</a>
      {if $LOGGED_IN_USER.admincp}
        <a class="hvr-radial-out" href="/admin">{$ADMINCP}</a>
      {/if}
      {if $LOGGED_IN_USER.modcp}
        <a class="hvr-radial-out" href="/mod">{$MODCP}</a>
      {/if}
  </div>
{else}
  <div class="navbar-user-sm">
      <a class="hvr-radial-out pull-right" href="/signin">{$SIGNIN}</a>
      <a class="hvr-radial-out pull-right" href="/register">{$REGISTER}</a>
  </div>
{/if}
  
<div class="logo-header">
  <div class="logo-background">
    <center>
      <img class="animated pulse infinite" src="/styles/themes/Elixir/img/logo.png">
    </center>
  </div>
</div>

<nav class="navbar navbar-default" id="navbar">
  <div class="container">
    <div class="navbar-header">
      <div class="navbar-toggle nav-toggle">
        &nbsp&nbsp&nbsp<a class="navbar-toggle nav-menu collapsed hvr-radial-out" data-toggle="collapse" data-target="#main_navbar_collapse" aria-expanded="false">
        <i class="fas fa-bars"></i> MENU</a>
      </div>
    </div>
    <div class="collapse navbar-collapse" id="main_navbar_collapse">
      {$NAVBAR_LINKS}
      <ul class="nav navbar-nav navbar-right navbar-user">{$USER_AREA}</ul>
    </div>
  </div>
</nav>

{if (isset($CONNECT_WITH))}
<div class="server-banner" id="server-banner">
  <center class="fa-1x">
    {if $page == "play"}
    <span><i class="fas fa-circle-notch fa-spin"></i>Connect with {$CONNECT_WITH} to play</span>
    {else}
    <span><i class="fas fa-circle-notch fa-spin"></i>{$PLAYERS_ONLINE} {$CONNECT_WITH}</span>
    {/if}
  </center>
</div>
{/if}

{if isset($GLOBAL_MESSAGES) && !empty($GLOBAL_MESSAGES)}
<div class="container announcement">
  {$GLOBAL_MESSAGES}
</div>
{/if}

{if isset($ANNOUNCEMENTS) && !empty($ANNOUNCEMENTS)}
<div class="container announcement">
  {foreach from=$ANNOUNCEMENTS item=item}
  <div class="alert alert-{$item.type}{if $item.can_close == 1} alert-announcement-{$item.id} alert-dismissible{/if}" id="{$item.id}">
    {if $item.can_close == 1}
    <button type="button" class="close close-announcement" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
    {/if}
    {$item.content}
  </div>
  {/foreach}
</div>
{/if}
