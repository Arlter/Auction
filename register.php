<?php include_once("header.php")?>

<?php

// oops, i used an alternative method than $_GET['error'], is it ok
if(isset($_SESSION["alert"])) {
  $alert = $_SESSION["alert"];
  echo

  "<div class='alert' style='background-color:pink; color:black'>
  <button type='button' class='close' data-dismiss='alert'>&times;</button>
  <h5><i class='icon fa fa-close'></i> Error</h5>$alert
  </div>";
  
unset($_SESSION["alert"]);  // alert disappears after refreshing
}
?>

<div class="container">
<h2 class="my-3">Register new account</h2>
<!-- <h6 id="AccountRegHelp" class="form-text-inline text-muted" style="line-height:20px"><span class="text-danger">* All details are required.</span></h6> -->


<!-- Note: this form is modified:
- a lot of the inputs did not have a name 
- user can keep form input field values before successful registration
- separate alerts depending on input
-->

<!-- Create auction form -->
<form method="POST" action="process_registration.php">
  <div class="form-group row">
    <label for="accountType" class="col-sm-2 col-form-label text-right">Registering as a:</label>
	<div class="col-sm-10">
	  <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="accountType" id="accountBuyer" value="buyer" <?php if (!isset($_SESSION["accountType"]) || $_SESSION["accountType"] == "buyer") {echo"checked";}?> >
        <label class="form-check-label" for="accountBuyer">Buyer</label>
      </div>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="accountType" id="accountSeller" value="seller" <?php if ($_SESSION["accountType"] == "seller") {echo"checked";}?> >
        <label class="form-check-label" for="accountSeller">Seller</label>
      </div>
      <small id="accountTypeHelp" class="form-text-inline text-muted"><span class="text-danger">* One account can only have one role.</span></small>
	</div>
  </div>

  <div class="form-group row">
    <label for="username" class="col-sm-2 col-form-label text-right">Username</label>
	<div class="col-sm-10">
      <input type="text" class="form-control" name="username" id="username" minlength="4" maxlength="20" value="<?php echo $_SESSION["username"];?>" placeholder="Username" required>
      <?php 
      if(empty($_SESSION["username"]) || ctype_space($_SESSION["username"])) {
        echo '<small id="usernameHelp" class="form-text text-muted"><span class="text-danger">* Required. Must be 4 to 20 characters long, consists of only alphanumeric characters (A-Z, a-z, and 0-9), and has no space.</span></small>';
      }
      ?>
    </div>
  </div>

  <div class="form-group row">
    <label for="password" class="col-sm-2 col-form-label text-right">Password</label>
    <div class="col-sm-10">
      <input type="password" class="form-control" name="password" id="password" minlength="8" maxlength="20" placeholder="Password" required>
      <small id="passwordHelp" class="form-text text-muted"><span class="text-danger">
        * Required. Must be 8 to 20 characters long, and must contain at least 1 letter and 1 number. 
        May contain any of these characters: !@#$%</span></small>
    </div>
  </div>
  <div class="form-group row">
    <label for="passwordConfirmation" class="col-sm-2 col-form-label text-right">Confirm password</label>
    <div class="col-sm-10">
      <input type="password" class="form-control"name="passwordConfirmation" id="passwordConfirmation" placeholder="Enter password again" required>
      <small id="passwordConfirmationHelp" class="form-text text-muted"><span class="text-danger">* Required.</span></small>
        </div>
  </div>

  <div class="form-group row">
    <label for="fisrtName" class="col-sm-2 col-form-label text-right">First name</label>
	<div class="col-sm-10">
      <input type="text" class="form-control" name="firstName" id="firstName" maxlength="20" value="<?php echo $_SESSION["firstName"];?>" placeholder="First name" required>
      <?php 
      if(empty($_SESSION["firstName"]) || ctype_space($_SESSION["firstName"])) {
        echo '<small id="firstnameHelp" class="form-text text-muted"><span class="text-danger">* Required.</span></small>';
      }
      ?>
        </div>
  </div>

  <div class="form-group row">
    <label for="lastName" class="col-sm-2 col-form-label text-right">Last name</label>
	<div class="col-sm-10">
      <input type="text" class="form-control" name="lastName" id="lastName" maxlength="20" value="<?php echo $_SESSION["lastName"];?>" placeholder="Last name" required>
      <?php 
      if(empty($_SESSION["lastName"]) || ctype_space($_SESSION["lastName"])) {
        echo '<small id="lastnameHelp" class="form-text text-muted"><span class="text-danger">* Required.</span></small>';
      }
      ?>
        </div>
  </div>

  <div class="form-group row">
    <label for="email" class="col-sm-2 col-form-label text-right">Email</label>
	<div class="col-sm-10">
      <input type="email" class="form-control" name="email" id="email" maxlength="50" value="<?php echo $_SESSION["email"];?>" placeholder="Email" required>
      <?php 
      if(empty($_SESSION["email"]) || ctype_space($_SESSION["email"])) {
        echo '<small id="emailHelp" class="form-text text-muted"><span class="text-danger">* Required.</span></small>';
      }
      ?>
	</div>
  </div>
  
  <div class="form-group row">
    <label for="phoneNumber" class="col-sm-2 col-form-label text-right">International phone number</label>
	<div class="col-sm-10">
      <input type="text" class="form-control" name="phoneNumber" id="phoneNumber" maxlength="20" value="<?php echo $_SESSION["phoneNumber"];?>" placeholder="International phone number" required>
      <?php 
      if (empty($_SESSION["phoneNumber"]) || ctype_space($_SESSION["phoneNumber"])) {
        echo '<small id="phoneHelp" class="form-text text-muted"><span class="text-danger">* Required. Please start with + sign (not 00) and country code.</span></small>';
      }
      ?>
        </div>
  </div>
  <div style="text-align: center">
    <button type="submit" name="submit" class="btn btn-primary form-control" style="margin-bottom: 20px; width:200px">Register</button>
  </div>
</form>

<div class="text-center" style="margin-bottom:30px">Already have an account? <a href="login.php">Login</a>

</div>

<?php include_once("footer.php")?>