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

<div class="container index">
  <div class="row">
    <div class="col-md-12">
      {$BREADCRUMBS}
    </div>
  </div>
  <h3 class="inline">{$TOPIC_TITLE}</h3>
  {$BUTTONS}
  <br />
  <span class="tagline">By {$TOPIC_AUTHOR_USERNAME}</span>
  <br /><br />
  {$SESSION_SUCCESS_POST}
  {$SESSION_FAILURE_POST}
  {$COOKIE_MESSAGE}
  {foreach from=$REPLIES item=reply}
  <div class="panel">
    <div class="panel-body post-body" id="{$reply.post_id}">
      <div class="row">
        <div class="col-md-2">
          <div class="post-sidebar">
            <center class="post-user-avatar">
              {$reply.avatar}
            </center>
            <div class="post-user-info">
              <span class="post-user-name"><a href="/profile/{$reply.mcname}">{$reply.username}</a></span><br>
              <span class="post-user-title">{$reply.user_title}</span>
              <span class="post-user-label">{$reply.user_group}</span>
              {if !is_null($reply.user_group2)}<br /><span class="post-user-label">{$reply.user_group2}</span>{/if}
            </div>
            <div class="post-user-stats">
              <div class="pairs-justified">
                <dl>
                  <dt>{$POSTS}:</dt>
                  <dd>{$reply.user_posts_count}</dd>
                </dl>
                <dl>
                  <dt>{$REPUTATION}:</dt>
                  <dd>{$reply.user_reputation}</dd>
                </dl>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-10 post-content">
          <div class="forum_post">
            {$reply.content}
          </div>
          <hr />
          <span class="post-meta">{$BY} <a href="/profile/{$reply.mcname}">{$reply.username}</a> : <span rel="tooltip" data-trigger="hover" data-original-title="{$reply.post_date}">{$reply.post_date_rough} {$AGO}</span></span>
          <br />{$reply.signature}<br />
          {$reply.reputation}<span class="pull-right">{$reply.buttons}</span>
        </div>
      </div>
    </div>
  </div>
  {/foreach}
  {$PAGINATION}
  {$QUICK_REPLY}
</div>