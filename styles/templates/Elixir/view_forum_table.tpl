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
      <ol class="breadcrumb">
        {$BREADCRUMBS}
      </ol>
    </div>
  </div>
  <h3 class="inline">{$FORUM_TITLE}</h3>
  <span class="pull-right">{$NEW_TOPIC_BUTTON}</span>
  <br /><br />
  <div class="row">
    <div class="col-md-12">
      <div class="panel" style="box-shadow:none;margin-bottom:0;">
        {if $SUBFORUMS_EXIST == 1}
        <div class="panel panel-forum">
          <div class="panel-body">
            {foreach from=$SUBFORUMS item=subforum}
            <div class="row node">
              <span class="node-icon">
              <i class="fas fa-comment"></i>
              </span>
              <div class="node-info">
                <a class="node-title" href="/forum/view_forum/?fid={$subforum.forum_id}">{$subforum.forum_title}</a>
                <br />
                <span class="node-stats">
                <i class="far fa-comments"></i> {$subforum.forum_topics}
                <i class="far fa-edit"></i> {$subforum.forum_posts}
                </span>
              </div>
            </div>
            {/foreach}
          </div>
        </div>
        {/if}
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
                  <th>{$TOPIC}</th>
                  <th style="padding: 0 10px;">{$STATS}</th>
                  <th style="text-align:right;">{$LAST_POST}</th>
                  <th></th>
              </thead>
              {foreach from=$STICKIES item=topic}
              <tr>
                <td>
                  <i class="fas fa-thumbtack"></i> {if $topic.locked == 1}<i class="fas fa-lock"></i> {/if}{$topic.label}<a class="table-topic-name" href="/forum/view_topic/?tid={$topic.topic_id}">{$topic.topic_title}</a>
                  <br />
                  <span class="table-topic-meta">{$BY} <a href="/profile/{$topic.topic_poster_mcname}">{$topic.topic_poster}</a>, <span rel="tooltip" data-trigger="hover" data-original-title="{$topic.topic_created}">{$sticky.topic_created_rough}</span></span>
                </td>
                <td>
                  <div class="pairs-justified" style="border-right: solid 1px #EAEAEA; border-left: solid 1px #EAEAEA; padding: 0 10px">
                    <dl>
                      <dt>{$VIEWS}:</dt>
                      <dd>{$topic.views}</dd>
                    </dl>
                    <dl>
                      <dt>{$POSTS}:</dt>
                      <dd>{$topic.posts}</dd>
                    </dl>
                  </div>
                </td>
                <td style="text-align:right;">
                  <a class="table-post-name" href="/profile/{$topic.last_reply_mcname}">{$topic.last_reply_username}</a>
                  <br />
                  <span class="table-post-meta" rel="tooltip" data-trigger="hover" data-original-title="{$topic.last_post_created}">{$topic.last_post_rough}</span>
                </td>
                <td>
                  <div class="frame">
                    <a href="/profile/{$topic.last_reply_mcname}">{$topic.last_reply_avatar}</a>
                  </div>
                </td>
              </tr>
              {/foreach}
              {foreach from=$TOPICS item=topic}
              <tr>
                <td>
                  {if $topic.locked == 1}<i class="fas fa-lock"></i> {/if}{$topic.label}<a class="table-topic-name" href="/forum/view_topic/?tid={$topic.topic_id}">{$topic.topic_title}</a>
                  <br />
                  <span class="table-topic-meta">{$BY} <a href="/profile/{$topic.topic_poster_mcname}">{$topic.topic_poster}</a>, <span rel="tooltip" data-trigger="hover" data-original-title="{$topic.topic_created}">{$topic.topic_created_rough}</span></span>
                </td>
                <td>
                  <div class="pairs-justified" style="border-right: solid 1px #EAEAEA; border-left: solid 1px #EAEAEA; padding: 0 10px">
                    <dl>
                      <dt>{$VIEWS}:</dt>
                      <dd>{$topic.views}</dd>
                    </dl>
                    <dl>
                      <dt>{$POSTS}:</dt>
                      <dd>{$topic.posts}</dd>
                    </dl>
                  </div>
                </td>
                <td style="text-align:right;">
                  <a class="table-post-name" href="/profile/{$topic.last_reply_mcname}">{$topic.last_reply_username}</a>
                  <br />
                  <span class="table-post-meta" rel="tooltip" data-trigger="hover" data-original-title="{$topic.last_post_created}">{$topic.last_post_rough}</span>
                </td>
                <td>
                  <div class="frame">
                    <a href="/profile/{$topic.last_reply_mcname}">{$topic.last_reply_avatar}</a>
                  </div>
                </td>
              </tr>
              {/foreach}
            </table>
          </div>
        </div>
        {$PAGINATION}
      </div>
    </div>
  </div>
</div>
