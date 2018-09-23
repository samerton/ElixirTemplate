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

<style>
    hr {
        display: inline-block;
    }
    @media only screen and (min-width:768px) {
        label {
            float: right;
            margin-top: 12px;
        }
    }
</style>

<div class="container announcement">
  {$SESSION_FLASH}
</div>

<div class="container index">
  <h3>{$CREATE_AN_ACCOUNT}</h3>
  <br />
  {$REGISTRATION_ERROR}
  <div class="row">
    <div class="col-xs-12 col-sm-10 col-md-10 col-sm-offset-0 col-md-offset-0">
      <form role="form" action="" method="post">
        {$FORM_CONTENT}
        <br />
        {$FORM_SUBMIT}
      </form>
    </div>
  </div>
</div>