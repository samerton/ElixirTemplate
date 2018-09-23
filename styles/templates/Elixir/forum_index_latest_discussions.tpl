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
  <div class="row">
    <div class="col-md-9">
      <div class="panel panel-primary">
        <div class="panel-body">
          <table class="table table-formed">
            <colgroup>
              <col style="width:55%">
              <col style="width:20%">
              <col style="width:20%">
              <col style="width:5%">
            </colgroup>
            <thead>
              <tr>
                <th>{$DISCUSSION}</th>
                <th style="padding: 0 10px;">{$STATS}</th>
                <th style="text-align:right;">{$LAST_REPLY}</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              {foreach from=$LATEST_DISCUSSIONS item=discussion}
                <tr>
        		  <td>
        		    {if $discussion.locked == 1}<i class="fa fa-lock"></i> {/if}{$discussion.label} <a class="table-topic-name" href="/forum/view_topic/?tid={$discussion.topic_id}">{$discussion.topic_title}</a><br />
        			<span class="table-topic-meta"><span rel="tooltip" data-trigger="hover" data-original-title="{$discussion.topic_created}">{$discussion.topic_created_rough}</span> {$BY} <a href="/profile/{$discussion.topic_created_mcname}">{$discussion.topic_created_username}</a> {$IN} <a href="/forum/view_forum/?fid={$discussion.forum_id}">{$discussion.forum_name}</a></span>
        		  </td>
        		  <td>
                    <div class="pairs-justified" style="border-right: solid 1px #EAEAEA; border-left: solid 1px #EAEAEA; padding: 0 10px">
                      <dl>
                        <dt>{$VIEWS}:</dt>
                        <dd>{$discussion.views}</dd>
                      </dl>
                      <dl>
                        <dt>{$POSTS}:</dt>
                        <dd>{$discussion.posts}</dd>
                      </dl>
                    </div>
                  </td>
                  <td style="text-align:right;">
                    <a class="table-post-name" href="/profile/{$discussion.last_reply_mcname}">{$discussion.last_reply_username}</a>
                    <br />
                    <span class="table-post-meta" rel="tooltip" data-trigger="hover" data-original-title="{$discussion.last_reply}">{$discussion.last_reply_rough}</span>
                  </td>
                  <td>
                    <div class="frame">
                      <a href="/profile/{$discussion.last_reply_mcname}">{$discussion.last_reply_avatar}</a>
                    </div>
                  </td>
                </tr>
              {/foreach}
            </tbody>
          </table>
        </div>
      </div>
      {$PAGINATION}
    </div>
    <div class="col-md-3">
      <div class="panel panel-default">
        <div class="panel-heading"><i class="fas fa-comment-alt" style="font-size:12px;"></i>{$FORUMS}</div>
        <div class="panel-body">
          <ul class="nav nav-list">
            <li class="nav-header">{$OVERVIEW}</li>
            <li class="active"><a href="/forum">{$LATEST_DISCUSSIONS_TITLE}</a></li>
            {foreach from=$SIDEBAR_FORUMS key=category item=subforums}
              {if !empty($subforums)}
                <li class="nav-header">{$category}</li>
                  {foreach $subforums item=subforum}
                    <li><a href="/forum/view_forum/?fid={$subforum.id}">{$subforum.title}</a></li>
                  {/foreach}
              {/if}
            {/foreach}
          </ul>
        </div>
      </div>
      {include file='styles/templates/Elixir/sidebar.tpl'}
    </div>
  </div>
</div>
