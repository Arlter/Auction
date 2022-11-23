<?php 

include_once("header.php")?>

<?php

if($_GET["success"]) {
  $success = $_GET["success"];
  echo

  "<div class='alert' style='background-color:lightgreen; color:black'>
  <button type='button' class='close' data-dismiss='alert'>&times;</button>
  <h5><i class='icon fa fa-check'></i> $success</h5>
  </div>";
  
}
?>

<div class="container">
<h2 class="my-3">Login account</h2>

<!--create forgotten password?-->

      <!-- Modal Header -->
      <!-- <div class="modal-header">
        <h4 class="modal-title">Login</h4>
      </div> -->

      <!-- Modal body -->
      <div class="modal-body">
        <form method="POST" action="login_result.php">
          <div class="form-group row">
            <label for="username" class="col-sm-2 col-form-label text-right">Username</label>
            <div class="col-sm-10" style="text-align: center">
            <input type="text" class="form-control" name="username" id="username" maxlength="20" placeholder="Username">
          </div>
          </div>
          <div class="form-group row">
            <label for="password" class="col-sm-2 col-form-label text-right">Password</label>
            <div class="col-sm-10">
            <input type="password" class="form-control" name="password" id="password" maxlength="20" placeholder="Password">
          </div>
          </div>
          <div style="text-align: center">
          <button type="submit" name="submit" class="btn btn-primary form-control" style="margin-top: 20px; margin-bottom: 20px; width:200px">Sign in</button>
          </div>
        </form>
        <div class="text-center" style="margin-bottom:30px">or <a href="register.php">create an account</a></div>
      </div>

    </div>
  </div>

</div>
</div>

<?php include_once("footer.php")?>