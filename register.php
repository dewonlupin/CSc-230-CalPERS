<?php
// Include config file
require_once "config.php";
// Define variables and initialize with empty values
$firstname = $lastname = $email = $role = $password = "";
$confirm_password = $employee = $calpers_id = $company =  "";
$firstname_err = $lastname_err = $email_err = $role_err = $password_err = "";
$confirm_password_err = $employee_err = $calpers_id_err = $company_err = "";

// Processing form data when form is submitted	
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Validate email
    if(empty(trim($_POST["email"]))){
        $email_err = "Please enter an Email address.";
    }
    else{
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE email = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_email);
            // Set parameters
            $param_email = trim($_POST["email"]);
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);

                if(mysqli_stmt_num_rows($stmt) == 1){
                    $email_err = "This email has already been registered.";
                }
                else{
                    $email = trim($_POST["email"]);
                }
            }
            else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    // validate firstname
    if(empty(trim($_POST["firstname"]))){
        $firstname_err = "Please enter a first name.";
    }
    else{
        $firstname = trim($_POST["firstname"]);
    }
    // validate lastname
    if(empty(trim($_POST["lastname"]))){
        $lastname_err = "Please enter a last name.";
    }
    else{
        $lastname = trim($_POST["lastname"]);
    }
    //validate employee status
    if (!isset($_POST["employee"])){
      $employee_err = "Please select one.";
    }
    else{
        $employee = trim($_POST["employee"]);
    }
    // validate role
    if(empty(trim($_POST["role"]))){
        $role_err = "Please enter a role.";
    }
    else{
        $role = trim($_POST["role"]);
    }
    //validate CalPERSID
    if(empty(trim($_POST["calpers_id"]))){
        $calpers_id_err = "Please enter your CalPERS ID.";
    }
    else{
        $calpers_id = trim($_POST["calpers_id"]);
    }

    // validate company
    if(empty(trim($_POST["company"]))){
        //echo "pirayesh";
        $company_err = "Please enter the name of company.";
    }
    else{
        $company = trim($_POST["company"]);
    }
    if ($employee=="yes"){
      $company_err=$company="";

    }elseif ($employee=="no") {
      $calpers_id_err=$calpers_id="";
      $role_err=$role="";
    }

    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Password must have atleast 6 characters.";
    } else{
        $password = trim($_POST["password"]);
    }

    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }
    // Check input errors before inserting in database
    if( empty($firstname_err) && empty($lastname_err)&& empty($employee_err) &&
    empty($role_err) && empty($calpers_id_err)&& empty($company_err)&&
    empty($email_err) && empty($password_err) && empty($confirm_password_err) ){

        // Prepare an insert statement
        $sql = "INSERT INTO users (firstname,lastname,role,calpersid,company,email, password) VALUES (?,?,?,?,?,?,?)";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssisss", $param_firstname,$param_lastname,
            $param_role, $param_calpers_id,$param_company,$param_email, $param_password);

            // Set parameters
            $param_firstname=$firstname;
            $param_lastname=$lastname;
            if ($employee=="yes")
            {
              $param_role=$role;
              $param_company="CalPERS";
              $param_calpers_id=(int)$calpers_id;
            }
            elseif ($employee=="no") {
              $param_company=$company;
              $param_role="NA";
              $param_calpers_id=-1;
            }

            $param_email = $email;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash

            // Attempt to execute the prepared statement

            if(mysqli_stmt_execute($stmt)){

                // Redirect to login page
                header("location:welcome.php");

            } else{
                echo "Something went wrong. Please try again later.";
            }
            // Close statement
            mysqli_stmt_close($stmt);
        }

    }
    // Close connection
    mysqli_close($link);
}
?>
<script type="text/javascript">
function is_calpers_employee() {
    if (document.getElementById('employee_true').checked) {
        document.getElementById('calpers_block').style.display = 'block';
        document.getElementById('outsider_block').style.display = 'none';
    }
    else if (document.getElementById('employee_false').checked) {
    document.getElementById('calpers_block').style.display = 'none';
    document.getElementById('outsider_block').style.display = 'block';
  }

}

</script>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Sign Up</h2>
        <p>Please fill the form below to create an account.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($firstname_err)) ? 'has-error' : ''; ?>">
                <label>First Name</label>
                <input type="text" name="firstname" class="form-control" value="<?php echo $firstname; ?>">
                <span class="help-block"><?php echo $firstname_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($lastname_err)) ? 'has-error' : ''; ?>">
                <label>Last Name</label>
                <input type="text" name="lastname" class="form-control" value="<?php echo $lastname; ?>">
                <span class="help-block"><?php echo $lastname_err; ?></span>
            </div>
            <div  class="form-group <?php echo (!empty($employee_err)) ? 'has-error' : ''; ?>">
                <label>Are you currently employed by CalPERS?</label><br>
                <span><input type="radio" onchange="javascript:is_calpers_employee();" name="employee" id="employee_true"   value="yes" <?php if (isset($_POST['employee']) && $_POST['employee']=="yes") echo "checked";?>   > Yes </span>
                <span><input type="radio" onchange="javascript:is_calpers_employee();" name="employee" id="employee_false"  value="no"  <?php if (isset($_POST['employee']) && $_POST['employee']=="no") echo "checked";?> > No</span><br>
                <span class="help-block"><?php echo $employee_err; ?></span>
            </div>
            <div id="calpers_block" style="display:none">
              <div class="form-group <?php echo (!empty($role_err)) ? 'has-error' : ''; ?>">
                  <label>Role</label>
                  <input type="text" name="role" class="form-control" value="<?php echo $role; ?>">
                  <span class="help-block"><?php echo $role_err; ?></span>
              </div>
              <div class="form-group <?php echo (!empty($calpers_id_err)) ? 'has-error' : ''; ?>">
                  <label>CalPERS ID# </label>
                  <input type="text" name="calpers_id" class="form-control" value="<?php echo $calpers_id; ?>">
                  <span class="help-block"><?php echo $calpers_id_err; ?></span>
              </div>
            </div>
            <div id="outsider_block" style="display:none">
              <div class="form-group <?php echo (!empty($company_err)) ? 'has-error' : ''; ?>">
                  <label>Company</label>
                  <input type="text" name="company" class="form-control" value="<?php echo $company; ?>">
                  <span class="help-block"><?php echo $company_err; ?></span>
              </div>
            </div>
            <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                <label>Email Address</label>
                <input type="text" name="email" class="form-control" value="<?php echo $email; ?>">
                <span class="help-block"><?php echo $email_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Sign Up">
                <input type="reset" class="btn btn-default" value="Reset">
            </div>
            <p>Already have an account? <a href="login.php">Log in here</a>.</p>
        </form>
    </div>
    <script type="text/javascript">is_calpers_employee();</script>

</body>
</html>
