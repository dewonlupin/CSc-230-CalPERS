<?php
// Include config file
require_once "config.php";
$status = "";
if (isset($_GET['token'])) {
    $sql = "SELECT id FROM users where token = ? and verified =?";
    if ($stmt = mysqli_prepare($link, $sql)) {
        // Bind variable to the prepared statement as parameter
        mysqli_stmt_bind_param($stmt, "si", $param_token, $param_verified);
        $param_token = $_GET['token'];
        $param_verified = 0;
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);
            if (mysqli_stmt_num_rows($stmt) == 1) {
                $sql = "UPDATE users SET verified = 1 WHERE token = ? and verified =?";
                if ($stmt = mysqli_prepare($link, $sql)) {
                    mysqli_stmt_bind_param($stmt, "si", $param_token, $param_verified);
                    $param_token = $_GET['token'];
                    $param_verified = 0;
                    if (mysqli_stmt_execute($stmt)) {
                        $status = "Thank you for verifying your account.";
                    } else {
                        echo "Failed executing the SQL statement.";
                    }
                } else {
                    echo "Failed preparing the SQL statement.";
                }
            } else {
                $status = "Verification Failed. Your account may have been already activated.";
            }
        } else {
            echo "Failed executing the SQL statement.";
        }
        // Close statement
        mysqli_stmt_close($stmt);
    } else {
        echo "Failed preparing the SQL statement";
    }
} else {
    $status = "Invalid verification link.";
}
// Close connection
mysqli_close($link);
?>
<!DOCTYPE html>
<html lang="en">
<head>
      <title>Activation</title>
    <!-- Required meta tags-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Colorlib Templates">
    <meta name="author" content="Colorlib">
    <meta name="keywords" content="Colorlib Templates">

    <!-- Title Page-->
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
                  <p> <?php echo $status; ?> </p>
                  <br>
                    Redirecting you to <a href="./login.php"> Login page</a> <?php header("Refresh:5; url=./login.php"); ?> in 5 seconds...
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
