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
  <h3>{$CREATING_POST_IN}</h3>
  <br />
  {$LOCKED_MESSAGE}
  <form action="" method="post">
    {$FORM_CONTENT}
    <a href="/forum/view_topic/?tid={$TOPIC_ID}" class="btn btn-danger" onclick="return confirm('{$CONFIRM}');">{$CANCEL}</a>
  </form>
</div>