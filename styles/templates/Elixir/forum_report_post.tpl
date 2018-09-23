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
  {$SESSION}
</div>

<div class="container index">
  <h3>{$REPORT_POST}</h3>
  <br />
  <div class="panel-group" id="accordion">
    <a class="btn btn-primary btn-block" data-toggle="collapse" data-parent="#accordion" href="#post-content">{$VIEW_POST_CONTENT}</a>
    <div class="panel">
      <div class="panel-collapse collapse" id="post-content">
        <br />
        <div class="panel">
          <div class="panel-body post-body">
            {$CONTENT}
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="panel">
    <div class="panel-body">
      <label>{$REPORT_REASON}:</label>
      {$FORM_CONTENT}
    </div>
  </div>
</div>