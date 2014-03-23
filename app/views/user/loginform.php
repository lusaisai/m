<div class="login well well-large">
  <ul id="account" class="nav nav-tabs">
    <li <?php if ($page == "login") { echo "class='active'"; } ?> ><a href="#login" data-toggle="tab">Login</a></li>
    <li <?php if ($page == "register") { echo "class='active'"; } ?> ><a href="#register" data-toggle="tab">Create an account</a></li>
  </ul>

  <div class="tab-content">
    <div class="tab-pane <?php if ($page == "login") { echo "active"; } ?>" id="login">
      <form id="loginform" class="form-horizontal" method="post" action="<?php echo URL::to("user/login") ?>">
        <div class="form-group">
          <label class="col-sm-2 control-label" for="inputUser">Username</label>
          <div class="col-sm-6">
            <input class="form-control" type="text" id="inputUser" name="username" placeholder="Username">
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label" for="inputPassword">Password</label>
          <div class="col-sm-6">
            <input class="form-control" type="password" id="inputPassword" name="password" placeholder="Password">
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-6">
            <button type="submit" class="btn btn-primary">Sign in</button>
          </div>
        </div>
      </form>

    </div>
    <div class="tab-pane <?php if ($page == "register") { echo "active"; } ?>" id="register">
      <form id="registerform" class="form-horizontal" method="post" action="<?php echo URL::to("user/register") ?>">
        <div class="form-group">
          <label class="col-sm-2 control-label" for="inputUser">Username</label>
          <div class="col-sm-6">
            <input class="form-control" type="text" id="inputUser" name="username" placeholder="Username">
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label" for="inputPassword">Password</label>
          <div class="col-sm-6">
            <input class="form-control" type="password" id="inputPassword" name="password" placeholder="Password">
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label" for="inputEmail">Email</label>
          <div class="col-sm-6">
            <input class="form-control" type="text" id="inputEmail" name="email" placeholder="Email">
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-6">
            <button type="submit" class="btn btn-primary">Register</button>
          </div>
        </div>
      </form>
    </div>
  </div>

</div>




