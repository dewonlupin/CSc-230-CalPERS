<?php
// Initialize the session
session_start();

// Checks if the user is already logged in, if yes then redirect them to profile page
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true)
{
    header("location: profile.php");
    exit;
}

// Include config file
require_once "config.php";

$email = $password = "";
$email_err = $password_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    // Validate email
    if (empty(trim($_POST["username"])))
    {
        $email_err = "Please enter your email.";
    }
    else
    {
        $email = trim($_POST["username"]);
    }

    // Validate password
    if (empty(trim($_POST["password"])))
    {
        $password_err = "Please enter your password.";
    }
    else
    {
        $password = trim($_POST["password"]);
    }

    // Validate credentials
    if (empty($email_err) && empty($password_err))
    {
        $sql = "SELECT id,email,password,verified FROM users WHERE email = ?";

        // Prepares the statement
        if ($stmt = mysqli_prepare($link, $sql))
        {
            // Binds variables
            mysqli_stmt_bind_param($stmt, "s", $param_email);

            // Set parameter
            $param_email = $email;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt))
            {
                // Store result
                mysqli_stmt_store_result($stmt);

                // Check if username exists
                if (mysqli_stmt_num_rows($stmt) == 1)
                {
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $email, $hashed_password, $vefified);
                    if (mysqli_stmt_fetch($stmt))
                    {
                        if ($vefified)
                        {
                            if (password_verify($password, $hashed_password))
                            {
                                // Password is correct, so start a new session
                                if (!isset($_SESSION))
                                {
                                    session_start();
                                }

                                // Store data in session variables
                                $_SESSION["loggedin"] = true;
                                $_SESSION["id"] = $id;

                                // Redirects user to profile page
                                header("location: profile.php");
                            }
                            else
                            {
                                // Display an error message if password is not valid
                                $password_err = "The password you entered was not correct.";
                            }
                        }
                        else
                        {
                            $alert = "We have already sent you a verification email. Please verify your account to log in.";
                            echo "<script type='text/javascript'>alert('$alert');</script>";
                        }
                    }
                    else {
                      echo "Results were not fetched properly.";
                    }
                }
                else
                {
                    $email_err = "No account found. Please sign up.";
                }
            }
            else
            {
                echo "SQL failed executing the statement. Please try again later.";
            }
            // Close statement
            mysqli_stmt_close($stmt);
        }
        else
        {
          echo "Preparing SQL statement failed. Please try again later.";
        }
    }
    // Close connection
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
      <title>Login</title>
    <!-- Required meta tags-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Colorlib Templates">
    <meta name="author" content="Colorlib">
    <meta name="keywords" content="Colorlib Templates">

    <!-- Title Page-->
    <title><img src="images/Calpers-logo.png" />login</title>

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
<style type="text/css">
.wrapper--w680 {
 max-width: 500px !important;
}
</style>
</head>
<body>
    <div class="page-wrapper bg-gra-02 p-t-130 p-b-100 font-poppins">
        <div class="wrapper wrapper--w680">
            <div class="card card-4">
                <div class="card-body">
                    <h2 class="title">Sign In</h2>
                     <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="row row-space">
                            <div class="col-2">
                              <div class="input-container">
                                <i class="fa fa-user icon"></i>
                                <input class="input-field input--style-4" type="text"  name="username" placeholder="Email"  value="<?php echo $email; ?>">
                              </div>
                              <span class="help-block"><?php echo $email_err; ?></span>

                            </div>

                        </div>
                        <div class="row">
                            <div class="col-2">
                              <div class="input-container">
                                <i class="fa fa-key icon"></i>
                                <input class="input-field input--style-4"  type="password" name="password" placeholder="Password">
                                </div>
                                <span class="help-block"><?php echo $password_err; ?></span>
                            </div>
                        </div>
                        <div class="p-t-15">
                      		<input type="submit" class="btn btn-primary" value="Log In">
                        </div>
                        <div class="row"><p style="padding-top: 15px;padding-left: 52px;">Don't have an account? <a href="register.php">Sign up here</a>.</p></div>
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
