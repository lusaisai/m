<?php if ($errors != ""): ?>
  <div class="alert alert-danger"><?php echo $errors; ?></div>
<?php endif ?>
<?php if ($successes != ""): ?>
  <div class="alert alert-success"><?php echo $successes; ?></div>
<?php endif ?>
<form id="updateform" class="form-horizontal" method="post">
  <div class="form-group">
    <label class="col-sm-4 control-label" for="inputUser">Username</label>
    <div class="col-sm-6">
      <input class="form-control" type="text" id="inputUser" name="username" value="<?php echo "$name"; ?>" disabled>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-4 control-label" for="oldpassword">Old Password</label>
    <div class="col-sm-6">
      <input class="form-control" type="password" id="oldpassword" name="oldpassword" placeholder="Password">
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-4 control-label" for="newpassword">New Password</label>
    <div class="col-sm-6">
      <input class="form-control" type="password" id="newpassword" name="newpassword" placeholder="Password">
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-4 control-label" for="newpasswordrpt">Repeat Password</label>
    <div class="col-sm-6">
      <input class="form-control" type="password" id="newpasswordrpt" name="newpasswordrpt" placeholder="Password">
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-4 control-label" for="inputEmail">Email</label>
    <div class="col-sm-6">
      <input class="form-control" type="text" id="inputEmail" name="email" value="<?php echo "$email"; ?>" disabled>
    </div>
  </div>
  <div class="form-group">
    <div class="col-sm-6">
      <button type="submit" class="btn btn-primary">Update</button>
    </div>
  </div>
</form>
<script>
$('#updateform').submit(function(event) {
    $.post( "<?php echo URL::to('user/updateinfo') ?>", $('#updateform').serialize(), function (data) {
        $('#data').empty().append(data);
    });
    return false;
});
</script>
