<?php include_once("header.php")?>

<?php
  // Prevent logged in user from registering
  if ($_SESSION['logged_in'] == true) {
    header('Location: browse.php');
  }
?>

<?php 
// shows connection error
if($_GET["error"]) {
  $error = $_GET["error"];
  echo
  "<div class='alert' style='background-color:pink; color:black'>
  <button type='button' class='close' data-dismiss='alert'>&times;</button>
  <h5><i class='icon fa fa-close'></i> Error</h5>$error
  </div>";
}
?>

<div class="container">
<h2 class="my-3">Register new account</h2>
<!-- <h6 id="AccountRegHelp" class="form-text-inline text-muted" style="line-height:20px"><span class="text-danger">* All details are required.</span></h6> -->


<!-- Create auction form -->
<form name="register" method="POST" action="process_registration.php">
  <div class="form-group row">
    <label for="accountType" class="col-sm-2 col-form-label text-right">Registering as a:</label>
	<div class="col-sm-10">
	  <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" style="height:15px; width:15px" name="accountType" id="accountBuyer" value="buyer" checked>
        <label class="form-check-label" for="accountBuyer">Buyer</label>
      </div>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="radio" style="height:15px; width:15px" name="accountType" id="accountSeller" value="seller">
        <label class="form-check-label" for="accountSeller">Seller</label>
      </div>
      <small id="accountTypeHelp" class="form-text-inline text-muted"><span class="text-danger">* Required.</span> One account can only have one role.</small>
	</div>
  </div>

  <div class="form-group row">
    <label for="username" class="col-sm-2 col-form-label text-right">Username</label>
	<div class="col-sm-10">
      <span id="check_username"></span>
      <input type="text" class="form-control" name="username" id="username" minlength="4" maxlength="20" placeholder="Username" oninput="check_username()" required>
      <small id="usernameHelp" class="form-text text-muted"><span class="text-danger">* Required.</span> Must be 4 to 20 characters long, contain only alphanumeric characters (A-Z, a-z, and 0-9), and contain no space.</small>
  </div>
  </div>

  <div class="form-group row">
    <label for="password" class="col-sm-2 col-form-label text-right">Password</label>
    <div class="col-sm-10">
      <span id="check_password"></span>
      <input type="password" class="form-control" name="password" id="password" minlength="8" maxlength="20" placeholder="Password" oninput="check_password(); confirm_password()" required>
      <input type="checkbox" onclick="show_password()"> Show Password
      <small id="passwordHelp" class="form-text text-muted"><span class="text-danger">
        * Required.</span> Must be 8 to 20 characters long, contain at least 1 letter and 1 number, and contain no space. 
        May contain any of these characters: !@#$%&</small>
    </div>
  </div>
  <div class="form-group row">
    <label for="passwordConfirmation" class="col-sm-2 col-form-label text-right">Confirm password</label>
    <div class="col-sm-10">
      <span id="confirm_password"></span> <!-- FIXME: hide this input line if password is empty-->
      <input type="password" class="form-control" name="passwordConfirmation" id="passwordConfirmation" placeholder="Enter password again" oninput="confirm_password()" disabled required>
      <small id="passwordConfirmationHelp" class="form-text text-muted"><span class="text-danger">* Required.</span> You can only confirm your password if it is valid.</small>
    </div>
  </div>

  <div class="form-group row">
    <label for="fisrtName" class="col-sm-2 col-form-label text-right">First name</label>
	  <div class="col-sm-10">
      <input type="text" class="form-control" name="firstName" id="firstName" maxlength="20" placeholder="First name" required>
      <small id="firstnameHelp" class="form-text text-muted"><span class="text-danger">* Required.</span></small>
    </div>
  </div>

  <div class="form-group row">
    <label for="lastName" class="col-sm-2 col-form-label text-right">Last name</label>
	  <div class="col-sm-10">
      <input type="text" class="form-control" name="lastName" id="lastName" maxlength="20" placeholder="Last name" required>
      <small id="lastnameHelp" class="form-text text-muted"><span class="text-danger">* Required.</span></small>
    </div>
  </div>

  <div class="form-group row">
    <label for="email" class="col-sm-2 col-form-label text-right">Email</label>
	<div class="col-sm-10">
      <input type="email" class="form-control" name="email" id="email" maxlength="50" placeholder="Email" required>
      <small id="emailHelp" class="form-text text-muted"><span class="text-danger">* Required.</span></small>
	</div>
  </div>
  
  <div class="form-group row">
    <label for="phoneNumber" class="col-sm-2 col-form-label text-right">International phone number</label>
    <div class="col-sm-10">
        <span id="check_phone"></span>
        <input type="text" class="form-control" name="phoneNumber" id="phoneNumber" maxlength="20" placeholder="International phone number" oninput="check_phone()" required>
        <small id="phoneHelp" class="form-text text-muted"><span class="text-danger">* Required.</span> Please start with + sign (not 00) and country code.</small>
    </div>
  </div>
  <div style="text-align: center">
    <button type="submit" name="submit" id="submit" class="btn btn-primary form-control" style="margin-bottom: 20px; width:200px">Register</button>
  </div>
</form>

<div class="text-center" style="margin-bottom:30px">Already have an account? <a href="login.php">Login</a>

</div>

<?php include_once("footer.php")?>

<script src="https://code.jquery.com/jquery-3.6.1.min.js"></script>

<script>
function check_username() {
  jQuery.ajax({
  url: "process_registration.php",
  data: {username:$("#username").val()},
  type: "POST",
  success:function(data){
    $("#check_username").html(data);
  },
  error:function (){}
  });
}
</script>

<script>
function check_password() {
  jQuery.ajax({
  url: "process_registration.php",
  data: {password:$("#password").val()},
  type: "POST",
  success:function(data){
    $("#check_password").html(data);
  },
  error: function (){}
  });
}
</script>

<script>
function confirm_password() {
  jQuery.ajax({
  url: "process_registration.php",
  data: {password_c:$("#password").val(), passwordConfirmation:$("#passwordConfirmation").val()},
  type: "POST",
  success:function(data){
    $("#confirm_password").html(data);
  },
  error: function (){}
  });
}
</script>

<script>
function check_phone() {
  jQuery.ajax({
  url: "process_registration.php",
  data: {phone:$("#phoneNumber").val()},
  type: "POST",
  success:function(data){
    $("#check_phone").html(data);
  },
  error: function (){}
  });
}
</script>

<script>
function show_password() {
  var x = document.getElementById("password");
  if (x.type === "password") {
    x.type = "text";
  } else {
    x.type = "password";
  }
}
</script>

<!--reload page alert, except when form is being submitted-->
<script>
window.onbeforeunload = function() {
  return "Data will be lost if you leave the page, are you sure?";
};

$(document).on("submit", "form", function(event){
  window.onbeforeunload = null;
});
</script>