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
  <h3>Minecraft Server</h3>
  <br />
  <div class="row">
    <div class="col-md-3">
      <div class="panel panel-default">
        <div class="panel-heading">
          <i class="fas fa-chart-bar"></i>Stats
        </div>
        <div class="panel-body">
          <div class="pairs-justified">
            <dl>
              <dt>{$STATUS}</dt>
              <dd>{if $MAIN_ONLINE == 1}{$ONLINE}{else}{$OFFLINE}{/if}</dd>
            </dl>
            <dl>
              <dt>{$PLAYERS_ONLINE}</dt>
              <dd>{$PLAYER_COUNT}</dd>
            </dl>
            <dl>
              <dt>{$QUERIED_IN}</dt>
              <dd>{$TIMER}</dd>
            </dl>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-9">
      <div class="panel panel-primary">
        <div class="panel-heading">
          {$PLAY_TITLE}
        </div>
        <div class="panel-body">
          {$SERVER_STATUS}
        </div>
      </div>
    </div>
  </div>
</div>