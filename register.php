<?php
// Include config file
require_once "config.php";
// Define variables and initialize with empty values
$firstname = $lastname = $email = $password = "";
$role = "test";
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
    <!-- Required meta tags-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Colorlib Templates">
    <meta name="author" content="Colorlib">
    <meta name="keywords" content="Colorlib Templates">

    <!-- Title Page-->
    <title>Au Register Forms by Colorlib</title>

    <!-- Icons font CSS-->
    <link href="vendor/mdi-font/css/material-design-iconic-font.min.css" rel="stylesheet" media="all">
    <link href="vendor/font-awesome-4.7/css/font-awesome.min.css" rel="stylesheet" media="all">
    <!-- Font special for pages-->
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Vendor CSS-->
    <link href="vendor/select2/select2.min.css" rel="stylesheet" media="all">
    <link href="vendor/datepicker/daterangepicker.css" rel="stylesheet" media="all">

    <!-- Main CSS-->
    <link href="css/main.css" rel="stylesheet" media="all">
</head>

<body>
    <div class="page-wrapper bg-gra-02 p-t-130 p-b-100 font-poppins">
        <div class="wrapper wrapper--w680">
            <div class="card card-4">
                <div class="card-body">
			<img src="images/calpersLogo.jpg" style="height:100px;widht:150px;margin:-20px;margin-left:0px;" />
                    <h2 class="title">Sign Up</h2>
                    <p>Please fill the form below to create an account.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER[" PHP_SELF"]); ?>
                        " method="post">
                        <div class="row row-space">
                            <div class="col-2">
                                <div class="input-group form-group <?php echo (!empty($firstname_err)) ? 'has-error' : ''; ?>">
                                    <label class="label">first name</label>
                                    <input type="text" name="firstname" class="input--style-4" value="<?php echo $firstname; ?>">
                                    <span class="help-block"><?php echo $firstname_err; ?></span>
                                </div>
                            </div>

                        </div>
                        <div class="row row-space">
                            <div class="col-2">
                                <div class="input-group form-group <?php echo (!empty($lastname_err)) ? 'has-error' : ''; ?>"
                                    <label>Last Name</label>
                                    <input type="text" name="lastname" class="input--style-4" value="<?php echo $lastname; ?>">
                                    <span class="help-block"><?php echo $lastname_err; ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="row row-space">
                            <div class="col-2">
                                <div class="input-group">
                                    Are you currently employed by CalPERS?

                                    <label class="radio-container">
                                        Yes
                                        <input type="radio" name="gender" onchange="javascript:is_calpers_employee();" name="employee" id="employee_true" value="yes" <?php if (isset($_POST['employee']) && $_POST['employee']=="yes") echo "checked";?>   >
                                        <span class="checkmark"></span>
                                    </label>
                                    <label class="radio-container">
                                        No
                                        <input type="radio" name="gender" onchange="javascript:is_calpers_employee();" name="employee" id="employee_false" value="no" <?php if (isset($_POST['employee']) && $_POST['employee']=="no") echo "checked";?>   >
                                        <span class="checkmark"></span>
                                    </label>
                                    <!--<label>Are you currently employed by CalPERS?</label><br>
                                    <span><input type="radio" onchange="javascript:is_calpers_employee();" name="employee" id="employee_true" value="yes" <?php if (isset($_POST['employee']) && $_POST['employee']=="yes") echo "checked";?>   > Yes </span>
                                    <span><input type="radio" onchange="javascript:is_calpers_employee();" name="employee" id="employee_false" value="no"  <?php if (isset($_POST['employee']) && $_POST['employee']=="no") echo "checked";?> > No</span><br>-->
                                    <!--   <span class="help-block"><?php echo $employee_err; ?></span>-->
                                </div>
                            </div>
                            <div class="col-2">

                                <div id="calpers_block" style="display:none">
                                    
                                    <div class="input-group <?php echo (!empty($calpers_id_err)) ? 'has-error' : ''; ?>">
                                        <label>CalPERS ID# </label>
                                        <input type="text" name="calpers_id" class="input--style-4" value="<?php echo $calpers_id; ?>">
                                        <span class="help-block"><?php echo $calpers_id_err; ?></span>
                                    </div>
                                </div>
                                <div id="outsider_block" style="display:none">
                                    <div class="input-group <?php echo (!empty($company_err)) ? 'has-error' : ''; ?>">
                                        <label>Company</label>
                                        <input type="text" name="company" class="input--style-4" value="<?php echo $company; ?>">
                                        <span class="help-block"><?php echo $company_err; ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row row-space">
                            <div class="col-2">
                                <div class="input-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                                    <label class="label">Email</label>
                                    <input type="text" name="email" class="input--style-4" value="<?php echo $email; ?>">
                                    <span class="help-block"><?php echo $email_err; ?></span>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-2">
                                <div class="input-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                                    <label class="label">Password</label>
                                    <input type="password" name="password" class="input--style-4" value="<?php echo $password; ?>">
                                    <span class="help-block"><?php echo $password_err; ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-2">
                                <div class="input-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                                    <label class="label">Confirm Password</label>
                                    <input type="password" name="confirm_password" class="input--style-4" value="<?php echo $confirm_password; ?>">
                                    <span class="help-block"><?php echo $confirm_password_err; ?></span>
                                </div>
                            </div>
                        </div>

                        <div class="p-t-15" style="text-align:center;">

                            <input type="submit" style="width:30%" class="btn btn--radius-2 btn--blue" value="Sign Up">
                            <input type="reset" style="width:30%" class="btn btn--radius-2 btn--blue" value="Reset">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Jquery JS-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <!-- Vendor JS-->
    <script src="vendor/select2/select2.min.js"></script>
    <script src="vendor/datepicker/moment.min.js"></script>
    <script src="vendor/datepicker/daterangepicker.js"></script>

    <!-- Main JS-->
    <script src="js/global.js"></script>

</body><!-- This templates was made by Colorlib (https://colorlib.com) -->

</html>
<!-- end document-->