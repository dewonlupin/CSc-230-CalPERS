<?php
// Include config file
require_once "config.php";
// define variables
$firstname = $lastname = $email = $password = "";
$confirm_password = $employee = "";
$firstname_err = $lastname_err = $password_err = "";
$confirm_password_err = $employee_err = $email_err = "";
$is_email_valid = 0;
// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //validate employee status
    if (!isset($_POST["employee"])) {
        $employee_err = "Please select one.";
    } else {
        $employee = trim($_POST["employee"]);
    }
    // Validate email
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter an Email address.";
    } else {
        $sql = "SELECT id FROM users WHERE email = ?";
        // Prepare a select statement
        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_email);
            // Set parameters
            $param_email = trim($_POST["email"]);
            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    $email_err = "This email has already been registered.";
                } else {
                    $email = strtolower(trim($_POST["email"]));
                }
            } else {
                echo "Executing the SQL statement failed. Please try again later.";
            }
            // Close statement
            mysqli_stmt_close($stmt);
            if ($employee == "yes" && !(preg_match('/@calpers.ca.gov$/', $email))) {
                $email_err = "This is not a valid CalPERS account.";
            }
        } else {
            echo "Failed at preparing the SQL statement.";
        }
    }
    // validate firstname
    if (empty(trim($_POST["firstname"]))) {
        $firstname_err = "Please enter your first name.";
    } else {
        $firstname = ucfirst(trim($_POST["firstname"]));
    }
    // validate lastname
    if (empty(trim($_POST["lastname"]))) {
        $lastname_err = "Please enter your last name.";
    } else {
        $lastname = ucfirst(trim($_POST["lastname"]));
    }
    // Validate password
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.";
    } elseif (strlen(trim($_POST["password"])) < 8) {
        $password_err = "Password must have at least eight characters.";
    } elseif (!preg_match('@[A-Z]@', trim($_POST["password"]))) {
        $password_err = "Password must have at least one uppercase letter";
    } elseif (!preg_match('@[a-z]@', trim($_POST["password"]))) {
        $password_err = "Password must have at least one lowercase letter";
    } elseif (!preg_match('@[0-9]@', trim($_POST["password"]))) {
        $password_err = "Password must have at least one number digit";
    } elseif (!preg_match('@[^\w]@', trim($_POST["password"]))) {
        $password_err = "Password must have at least one special character";
    } else {
        $password = trim($_POST["password"]);
    }
    // Validate confirm password
    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Please confirm password.";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if (empty($password_err) && ($password != $confirm_password)) {
            $confirm_password_err = "Password did not match.";
        }
    }
    // Check input errors before inserting in database
    if (empty($firstname_err) && empty($lastname_err) && empty($employee_err) && empty($email_err) && empty($password_err) && empty($confirm_password_err)) {
        $sql = "INSERT INTO users (firstname,lastname,company,email, password,token,password_expires) VALUES (?,?,?,?,?,?,?)";
        // Prepares SQL statement
        $token = password_hash($email . time(), PASSWORD_DEFAULT);
        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssssss", $param_firstname, $param_lastname, $param_company, $param_email, $param_password, $param_token,$param_password_expires);
            // Set parameters
            $param_firstname = $firstname;
            $param_lastname = $lastname;
            if ($employee == "yes") {
                $param_company = "CalPERS";
            }
            $param_token = $token;
            $param_email = $email;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            $now = new DateTime();
            $param_password_expires = $now->modify('+1 year')->format('Y-m-d H:i:s');
            try {
                $html = "
              <html>
              <head>
              <title>Alerting System</title>
              <link rel='stylesheet' href='https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css' integrity='sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk' crossorigin='anonymous'>
              <style type=\"text/css\">
              <!--
              body {
                margin-top: 20px;
                margin-left: 20px;
                margin-right: 20px;
                margin-bottom: 20px;
              }
              #message {
                padding: 11px;
                border-color: CornflowerBlue;
                border-style: solid;
                display: inline-block;
                width: 700px;
              }
              -->
              </style>
              <head>
              <body>
              <div id ='message' >
              <h5> Hi " . $firstname . " " . $lastname . ",</h5>
              <br>
              Thank you for signing up. Please click on the button below to verify your email address:
              <br>
              <br>
              <center>
              <a href='http://localhost/CSc-230-CalPERS/Home/activation.php?token=" . $token . "' class='btn btn-primary btn-lg'>Verify your email address</a>
              </center>
              <br>
              Alternatively, you may also click on the following link to verify your email address: <br><a href='http://localhost/CSc-230-CalPERS/Home/activation.php?token=" . $token . "'>http://localhost/CSc-230-CalPERS/Home/activation.php?token=" . $token . " </a>
              <br>
              <br>
              Cheers,
              <br>
              Software Chasers Team
              </div>
              </body>
              </html>
              ";
                // Create the Transport
                $transport = (new Swift_SmtpTransport('smtp.gmail.com', 587, 'tls'))->setUsername(EMAIL)->setPassword(PASS);
                // Create the Mailer using your created Transport
                $mailer = new Swift_Mailer($transport);
                // Create a message
                $message = (new Swift_Message('Verification Email'))->setFrom([EMAIL => 'Software Chasers'])->setTo([$email])->setBody($html, 'text/html');
                // Send the message
                if ($mailer->send($message)) {
                    $is_email_valid = 1;
                } else {
                    $is_email_valid = 0;
                    $email_err = "Please use a valid email address.";
                }
            }
            catch(Exception $e) {
                $is_email_valid = 0;
                $email_err = "Please use a valid email address.";
            }
            if ($is_email_valid == 1 && mysqli_stmt_execute($stmt)) {
                // Redirect to login page
                $alert = "Thank you for signing up. We sent you a link to the address you provided. Please verify your account.";
                echo "<script type='text/javascript'>alert('$alert');</script>";
                header("Refresh:0; url=./login.php");
            } else {
                //echo "Failed executing the SQL statement. Please try again later.";

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
            document.getElementById('email_label').innerHTML  = 'CalPERS Email';
        }
        else if (document.getElementById('employee_false').checked) {
            document.getElementById('email_label').innerHTML  = 'Email';
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
    <title>Sign Up</title>

    <!-- Icons font CSS-->
    <link href="vendor/mdi-font/css/material-design-iconic-font.min.css" rel="stylesheet" media="all">
    <link href="vendor/font-awesome-4.7/css/font-awesome.min.css" rel="stylesheet" media="all">
    <!-- Font special for pages-->
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Vendor CSS-->
    <link href="vendor/select2/select2.min.css" rel="stylesheet" media="all">
    <link href="vendor/datepicker/daterangepicker.css" rel="stylesheet" media="all">

    <!-- Main CSS-->
    <link href="build/css/main.css" rel="stylesheet" media="all">
</head>

<body>
    <div class="page-wrapper bg-gra-02 p-t-130 p-b-100 font-poppins">
        <div class="wrapper wrapper--w680">
            <div class="card card-4">
                <div class="card-body">
                    <h2 class="title">Sign Up</h2
                      <p style="margin-bottom: 10px;margin-top: -15px;">Please fill the form below to create an account.</p>
                    </br>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
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
                                <div class="input-group form-group <?php echo (!empty($lastname_err)) ? 'has-error' : ''; ?>">
                                    <label class="label">Last Name</label>
                                    <input type="text" name="lastname" class="input--style-4" value="<?php echo $lastname; ?>">
                                    <span class="help-block"><?php echo $lastname_err; ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="row row-space">
                            <div class="col-2">
                                <div class="input-group">
                                    <label class="label" style="text-transform:none;" > Do you have a CalPERS Email address?
                                    <label class="radio-container">
                                        Yes
                                        <input type="radio" onchange="javascript:is_calpers_employee();" name="employee" id="employee_true" value="yes" <?php if (isset($_POST['employee']) && $_POST['employee'] == "yes") echo "checked"; ?>   >
                                        <span class="checkmark"></span>
                                    </label>
                                    <label class="radio-container">
                                        No
                                        <input type="radio" onchange="javascript:is_calpers_employee();" name="employee" id="employee_false" value="no" <?php if (isset($_POST['employee']) && $_POST['employee'] == "no") echo "checked"; ?>   >
                                        <span class="checkmark"></span>
                                    </label>
                                  </label>
                                    <span class="help-block"><?php echo $employee_err; ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="row row-space">
                            <div class="col-2">
                                <div class="input-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
                                    <label id ="email_label" class="label">Email</label>
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

                          <input type="submit" style="width:30%" class="btn btn-primary" value="Sign Up">
                          <input type="reset" style="width:30%" class="btn btn-primary" value="Reset">
                        </div>
                        <div class="row"> <p style="padding-top: 15px;padding-left: 134px;">Already have an account? <a href="login.php">Sign in here</a>.</p></div>
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
</body>

</html>
<!-- end document-->
