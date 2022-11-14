<?php include_once("header.php")?>

<<<<<<< Updated upstream
<?php
session_start();


=======
<<<<<<< Updated upstream
=======
<?php
session_start();

// oops, i used an alternative method than $_GET['error'], is it ok
>>>>>>> Stashed changes
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


<<<<<<< Updated upstream
=======
>>>>>>> Stashed changes
>>>>>>> Stashed changes
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
        <input class="form-check-input" type="radio" name="accountType" id="accountBuyer" value="buyer" checked>
        <label class="form-check-label" for="accountBuyer">Buyer</label>
      </div>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" name="accountType" id="accountSeller" value="seller">
        <label class="form-check-label" for="accountSeller">Seller</label>
      </div>
      <small id="accountTypeHelp" class="form-text-inline text-muted"><span class="text-danger">* One account can only have one role.</span></small>
	</div>
  </div>

  <div class="form-group row">
    <label for="username" class="col-sm-2 col-form-label text-right">Username</label>
	<div class="col-sm-10">
<<<<<<< Updated upstream
      <input type="text" class="form-control" name="username" id="username" value="<?php echo $_SESSION["username"];?>" placeholder="Username">
      <?php 
      if(empty($_SESSION["username"]) || ctype_space($_SESSION["username"])) {
=======
<<<<<<< Updated upstream
      <input type="text" class="form-control" id="email" placeholder="Email">
      <small id="emailHelp" class="form-text text-muted"><span class="text-danger">* Required.</span></small>
	</div>
=======
      <input type="text" class="form-control" name="username" id="username" value="<?php echo $_SESSION["username"];?>" placeholder="Username" required>
      <?php 
      if(!($_SESSION["username"]) || ctype_space($_SESSION["username"])) {
>>>>>>> Stashed changes
        echo '<small id="usernameHelp" class="form-text text-muted"><span class="text-danger">* Required. Must be 4 to 20 characters long, consists of only alphanumeric characters (A-Z, a-z, and 0-9), and has no space.</span></small>';
      }
      ?>
    </div>
<<<<<<< Updated upstream
=======
>>>>>>> Stashed changes
>>>>>>> Stashed changes
  </div>

  <div class="form-group row">
    <label for="password" class="col-sm-2 col-form-label text-right">Password</label>
    <div class="col-sm-10">
<<<<<<< Updated upstream
      <input type="password" class="form-control" name="password" id="password" placeholder="Password">
      <small id="passwordHelp" class="form-text text-muted"><span class="text-danger">
        * Required. Must be 8 to 20 characters long, and must contain at least 1 letter and 1 number. 
        May contain any of these characters: !@#$%</span></small>
=======
<<<<<<< Updated upstream
      <input type="password" class="form-control" id="password" placeholder="Password">
      <small id="passwordHelp" class="form-text text-muted"><span class="text-danger">* Required.</span></small>
=======
      <input type="password" class="form-control" name="password" id="password" placeholder="Password" required>
      <small id="passwordHelp" class="form-text text-muted"><span class="text-danger">
        * Required. Must be 8 to 20 characters long, and must contain at least 1 letter and 1 number. 
        May contain any of these characters: !@#$%</span></small>
>>>>>>> Stashed changes
>>>>>>> Stashed changes
    </div>
  </div>
  <div class="form-group row">
    <label for="passwordConfirmation" class="col-sm-2 col-form-label text-right">Confirm password</label>
    <div class="col-sm-10">
<<<<<<< Updated upstream
      <input type="password" class="form-control"name="passwordConfirmation" id="passwordConfirmation" placeholder="Enter password again">
      <small id="passwordConfirmationHelp" class="form-text text-muted"><span class="text-danger">* Required.</span></small>
        </div>
=======
<<<<<<< Updated upstream
      <input type="password" class="form-control" id="passwordConfirmation" placeholder="Enter password again">
      <small id="passwordConfirmationHelp" class="form-text text-muted"><span class="text-danger">* Required.</span></small>
    </div>
=======
      <input type="password" class="form-control"name="passwordConfirmation" id="passwordConfirmation" placeholder="Enter password again" required>
      <small id="passwordConfirmationHelp" class="form-text text-muted"><span class="text-danger">* Required.</span></small>
        </div>
  </div>

  <div class="form-group row">
    <label for="fisrtName" class="col-sm-2 col-form-label text-right">First name</label>
	<div class="col-sm-10">
      <input type="text" class="form-control" name="firstName" id="firstName"value="<?php echo $_SESSION["firstName"];?>" placeholder="First name" required>
      <?php 
      if(!($_SESSION["firstName"]) || ctype_space($_SESSION["firstName"])) {
        echo '<small id="firstnameHelp" class="form-text text-muted"><span class="text-danger">* Required.</span></small>';
      }
      ?>
        </div>
  </div>

  <div class="form-group row">
    <label for="lastName" class="col-sm-2 col-form-label text-right">Last name</label>
	<div class="col-sm-10">
      <input type="text" class="form-control" name="lastName" id="lastName" value="<?php echo $_SESSION["lastName"];?>" placeholder="Last name" required>
      <?php 
      if(!($_SESSION["lastName"]) || ctype_space($_SESSION["lastName"])) {
        echo '<small id="lastnameHelp" class="form-text text-muted"><span class="text-danger">* Required.</span></small>';
      }
      ?>
        </div>
  </div>

  <div class="form-group row">
    <label for="email" class="col-sm-2 col-form-label text-right">Email</label>
	<div class="col-sm-10">
      <input type="email" class="form-control" name="email" id="email" value="<?php echo $_SESSION["email"];?>" placeholder="Email" required>
      <?php 
      if(!isset($_SESSION["email"]) || ctype_space($_SESSION["email"])) {
        echo '<small id="emailHelp" class="form-text text-muted"><span class="text-danger">* Required.</span></small>';
      }
      ?>
	</div>
  </div>
  
  <div class="form-group row">
    <label for="phoneNumber" class="col-sm-2 col-form-label text-right">International phone number</label>
	<div class="col-sm-10">
      <input type="text" class="form-control" name="phoneNumber" id="phoneNumber" value="<?php echo $_SESSION["phoneNumber"];?>" placeholder="International phone number" required>
      <?php 
      if (!isset($_SESSION["phoneNumber"]) || ctype_space($_SESSION["phoneNumber"])) {
        echo '<small id="phoneHelp" class="form-text text-muted"><span class="text-danger">* Please start with + sign (not 00) and country code.</span></small>';
      }
      ?>
        </div>
>>>>>>> Stashed changes
>>>>>>> Stashed changes
  </div>

  <div class="form-group row">
    <label for="fisrtName" class="col-sm-2 col-form-label text-right">First name</label>
	<div class="col-sm-10">
      <input type="text" class="form-control" name="firstName" id="firstName"value="<?php echo $_SESSION["firstName"];?>" placeholder="First name">
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
      <input type="text" class="form-control" name="lastName" id="lastName" value="<?php echo $_SESSION["lastName"];?>" placeholder="Last name">
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
      <input type="email" class="form-control" name="email" id="email" value="<?php echo $_SESSION["email"];?>" placeholder="Email">
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
      <input type="text" class="form-control" name="phoneNumber" id="phoneNumber" value="<?php echo $_SESSION["phoneNumber"];?>" placeholder="International phone number">
      <?php 
      if(empty($_SESSION["phoneNumber"])) {
        echo '<small id="phoneHelp" class="form-text text-muted"><span class="text-danger">* Required. Please start with + sign (not 00) and country code.</span></small>';
      } elseif (!isset($_SESSION["phoneNumber"]) || ctype_space($_SESSION["phoneNumber"])) {
        echo '<small id="phoneHelp" class="form-text text-muted"><span class="text-danger">* Please start with + sign (not 00) and country code.</span></small>';
      }
      ?>
        </div>
  </div>

  <div class="form-group row">
    <button type="submit"name="submit" class="btn btn-primary form-control">Register</button>
  </div>
</form>

<div class="text-center">Already have an account? <a href="" data-toggle="modal" data-target="#loginModal">Login</a>

</div>

<?php include_once("footer.php")?>