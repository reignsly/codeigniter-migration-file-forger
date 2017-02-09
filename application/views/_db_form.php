
<fieldset>

<!-- Form Name -->
<legend>Form Name</legend>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="host">Host</label>  
  <div class="col-md-4">
  <input id="host" name="host" type="text" placeholder="Hostname" value="<?=isset($_GET['host']) ? $_GET['host'] : 'localhost'?>" class="form-control input-md">  
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="user">User</label>  
  <div class="col-md-4">
  <input id="user" name="user" type="text" placeholder="User" value="<?=isset($_GET['user']) ? $_GET['user'] : 'root'?>" class="form-control input-md">  
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="pass">Password</label>  
  <div class="col-md-4">
  <input id="pass" name="pass" type="text" placeholder="Password" value="<?=isset($_GET['pass']) ? $_GET['pass'] : '1234asdf'?>" class="form-control input-md">  
  </div>
</div>


<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="db">Database</label>  
  <div class="col-md-4">
  <input id="db" name="db" type="text" placeholder="Database" value="<?=isset($_GET['db']) ? $_GET['db'] : 'database'?>" class="form-control input-md">  
  </div>
</div>



</fieldset>
