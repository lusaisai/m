<?php if ($errors != ""): ?>
  <div class="alert alert-error"><?php echo $errors; ?></div>
<?php endif ?>
<?php if ($successes != ""): ?>
  <div class="alert alert-success"><?php echo $successes; ?></div>
<?php endif ?>
<form id="updateform" class="form-horizontal" method="post">
  <div class="control-group">
    <label class="control-label" for="inputUser">Username</label>
    <div class="controls">
      <input type="text" id="inputUser" name="username" value="<?php echo "$name"; ?>" disabled>
    </div>
  </div>
  <div class="control-group">
    <label class="control-label" for="oldpassword">Old Password</label>
    <div class="controls">
      <input type="password" id="oldpassword" name="oldpassword" placeholder="Password">
    </div>
  </div>
  <div class="control-group">
    <label class="control-label" for="newpassword">New Password</label>
    <div class="controls">
      <input type="password" id="newpassword" name="newpassword" placeholder="Password">
    </div>
  </div>
  <div class="control-group">
    <label class="control-label" for="newpasswordrpt">Repeat Password</label>
    <div class="controls">
      <input type="password" id="newpasswordrpt" name="newpasswordrpt" placeholder="Password">
    </div>
  </div>
  <div class="control-group">
    <label class="control-label" for="inputEmail">Email</label>
    <div class="controls">
      <input type="text" id="inputEmail" name="email" value="<?php echo "$email"; ?>" disabled>
    </div>
  </div>
  <div class="control-group">
    <div class="controls">
      <button type="submit" class="btn btn-primary">Update</button>
    </div>
  </div>
</form>
<script>
$('#updateform').submit(function(event) {
    $.post( "<?php echo URL::to('user/updateinfo') ?>", $('#updateform').serialize(), function (data) {
        $('#content').empty().append(data);
    });
    return false;
});
</script>
