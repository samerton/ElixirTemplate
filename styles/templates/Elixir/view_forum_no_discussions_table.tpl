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
  {$NEW_TOPIC_BUTTON}
  <br /><br />
  <div class="row">
    <div class="col-md-12">
      <div class="panel" style="box-shadow:none">
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
                <tr>
                  <th>{$TOPIC}</th>
                  <th style="padding: 0 10px;">{$STATS}</th>
                  <th style="text-align:right;">{$LAST_POST}</th>
                  <th></th>
                </tr>
              </thead>
			    <tbody>
				  <tr>
					<td><span class="table-topic-meta">{$NO_TOPICS}</span></td>
					<td></td><td></td><td></td>
			      </tr>
				</tbody>
            </table>
          </div>
        </div>
        {$PAGINATION}
      </div>
    </div>
  </div>
</div>