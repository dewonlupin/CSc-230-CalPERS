<?php
// Include config file
require_once "config.php";
// Define variables
$email = "";
$email_err = "";
$new_password = "";
$firstname = "";
$lastname = "";
$id = "";
// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate email
    if (empty(trim($_POST["username"]))) {
        $email_err = "Please enter your email.";
    } else {
        $email = trim($_POST["username"]);
    }
    // Validate credentials
    if (empty($email_err)) {
        $sql = "SELECT id, firstname, lastname FROM users WHERE email = ?";
        // Prepares the statement
        if ($stmt = mysqli_prepare($link, $sql)) {
            // Binds variables
            mysqli_stmt_bind_param($stmt, "s", $param_email);
            // Set parameter
            $param_email = $email;
            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Store result
                mysqli_stmt_store_result($stmt);
                // Check if username exists
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    mysqli_stmt_bind_result($stmt, $id, $firstname, $lastname);
                    mysqli_stmt_fetch($stmt);
                    mysqli_stmt_close($stmt);
                    $new_password = base64_encode(random_bytes(10));
                    // Prepare an update statement
                    $sql = "UPDATE users SET password = ?, password_expires = ? WHERE email = ?";
                    if ($stmt = mysqli_prepare($link, $sql)) {
                        // Bind variables to the prepared statement as parameters
                        mysqli_stmt_bind_param($stmt, "sss", $param_new_password,$param_password_expires, $param_email);
                        // Set parameters
                        $param_new_password = password_hash($new_password, PASSWORD_DEFAULT);
                        $param_email = $email;
                        $now = new DateTime();
                        $param_password_expires = $now->modify('+1 hour')->format('Y-m-d H:i:s');
                        // Attempt to execute the prepared statement
                        if (mysqli_stmt_execute($stmt)) {
                            try {
                                $html = "<html>
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
                                width: 400px;
                              }
                              -->
                              </style>
                              <head>
                              <body>
                              <div id ='message' >
                              <h5> Hi " . $firstname . " " . $lastname . ",</h5>
                              <br>
                              Below is your temporary password for your account:
                              <br>
                              <center>
                              " . $new_password . "
                              </center>
                              <p> Please note that the temporary password expires in one hour. </p>
                              <center>
                              <a href='http://localhost/CSc-230-CalPERS/Home/login.php' class='btn btn-primary btn-lg'>Log in to your account</a>
                              </center>
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
                                $message = (new Swift_Message('Password Reset'))->setFrom([EMAIL => 'Software Chasers'])->setTo([$email])->setBody($html, 'text/html');
                                // Send the message
                                if ($mailer->send($message)) {
                                    mysqli_stmt_close($stmt);
                                    mysqli_close($link);
                                    $alert = "We sent you a new password to your email address.";
                                    echo "<script type='text/javascript'>alert('$alert');</script>";
                                    header("Refresh:0; url=./login.php");
                                    exit();
                                } else {
                                    echo "Something went wrong. Please try again later.";
                                }
                            }
                            catch(Exception $e) {
                                echo "Something went wrong. Please try again later.";
                            }
                        } else {
                            echo "Failed Executing the SQL statement. Please try again later.";
                        }
                        // Close statement
                        mysqli_stmt_close($stmt);
                    }
                } else {
                    $email_err = "No account found.";
                }
            } else {
                echo "SQL failed executing the statement. Please try again later.";
            }
            // Close statement
            mysqli_stmt_close($stmt);
        } else {
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
                    <h2 class="title">Request New Password</h2>
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
                        <div class="p-t-15">
                      		<input type="submit" class="btn btn-primary" value="Submit">
                        </div>
                        <div class="row">
                          <p style="padding-top: 15px;padding-left: 30px;">Remembered your password? <a href="login.php">Sign in here</a>.</p>
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
</body>
</html>
