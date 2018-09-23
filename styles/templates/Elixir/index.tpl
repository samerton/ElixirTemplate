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

<div class="container announcement">
  {$SESSION_FLASH}
</div>

<div class="container index">
  <div class="row">
    <div class="col-md-9">
      {foreach from=$newsArray item=news}
      <div class="panel panel-primary">
        <div class="panel-heading">
          {$news.label} <a href="/forum/view_topic/?tid={$news.id}">{$news.title}</a>
        </div>
        <div class="panel-body">
          <div>{$news.content}</div>
          <hr />
          <span class="post-meta">By <a href="/profile/{$news.author_mcname}">{$news.author_username}</a> : <a href="/forum/view_topic/?tid={$news.id}">{$news.date}</a></span>
        </div>
      </div>
      {/foreach}
    </div>
    <div class="col-md-3">
      {include file='styles/templates/Elixir/sidebar.tpl'}
      {if !empty($TWITTER_FEED)}
      {$TWITTER_FEED}
      {/if}
      {if !empty($VOICE_VIEWER)}
      {if {$VOICE_VIEWER_TITLE} != 'Discord'}
      <div class="panel panel-default">
        <div class="panel-heading">
          {$VOICE_VIEWER_TITLE}
        </div>
        <div class="panel-body">
          {$VOICE_VIEWER_IP}
          <br />
          {$VOICE_VIEWER}
        </div>
      </div>
      {else}
      {$VOICE_VIEWER}
      {/if}
      {/if}
    </div>
  </div>
</div>