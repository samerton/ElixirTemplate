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
    <div class="col-md-9">
      {if !empty($SUBFORUMS)}
      <div class="well well-sm">
        {$SUBFORUMS_LANGUAGE} {$SUBFORUMS}
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
              <th>{$DISCUSSION}</th>
              <th>{$STATS}</th>
              <th style="text-align:right">{$LAST_REPLY}</th>
              <th></th>
            </thead>
            <tr>
              <td><span class="table-topic-meta">{$NO_TOPICS}</span></td>
              <td></td><td></td><td></td>
            </tr>
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
            <li><a href="/forum">{$LATEST_DISCUSSIONS_TITLE}</a></li>
            {foreach from=$SIDEBAR_FORUMS key=category item=subforums}
            {if !empty($subforums)}
            <li class="nav-header">{$category}</li>
            {foreach $subforums item=subforum}
            <li{if $subforum.title == $FORUM_TITLE} class="active"{/if}><a href="/forum/view_forum/?fid={$subforum.id}">{$subforum.title}</a></li>
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