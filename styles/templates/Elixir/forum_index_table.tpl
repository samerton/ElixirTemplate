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
      {foreach from=$FORUMS item=parent}
      {assign var=counter value=1}
      <div class="panel panel-forum">
        <div class="panel-heading" id="{$parent.forum_title}">
          {if $parent.forum_type == "category"}
          <i class="fa fa-folder-open"></i>{$parent.forum_title}
          {else}
          <i class="fa fa-folder"></i><a href="/forum/view_forum/?fid={$parent.id}">{$parent.forum_title}</a>
          {/if}
        </div>
        {if count($parent.forums) > 0}
        <div class="panel-body">
          {foreach from=$parent.forums item=forum}
          <div class="row node">
            <span class="node-icon">
              <i class=" fas fa-comment"></i>
            </span>
            <div class="node-info">
              <span class="node-title">
              <a href="/forum/view_forum/?fid={$forum.forum_id}">{$forum.forum_title}</a>
              </span>
              <br />
              <span class="node-stats">
                <i class="far fa-comments"></i> {$forum.forum_topics}
                <i class="far fa-edit"></i> {$forum.forum_posts}
              </span>
            </div>
          </div>
          {assign var=counter value=$counter+1}
          {/foreach}
        </div>
        {/if}
      </div>
      {/foreach}
    </div>
    <div class="col-md-3">
      {include file='styles/templates/Elixir/sidebar.tpl'}
    </div>
  </div>
</div>