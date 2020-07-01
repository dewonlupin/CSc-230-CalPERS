<?php
// Initialize the session
session_start();

// Include config file
require_once "config.php";

// Check if the user is logged in, if not then redirect them to login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true)
{
    header("location: login.php");
    exit;
}
// Define variables
$created = $firstname = $lastname = $email = $role = $password = "";
$employee = $phone_number = $company = "";
$firstname_err = $lastname_err = $email_err = $role_err = $password_err = "";
$employee_err = $phone_number_err = $company_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST")
{

    if (empty(trim($_POST["firstname"])))
    {

        $firstname_err = "Please enter your first name.";
    }
    else
    {
        $firstname = ucfirst(trim($_POST["firstname"]));
    }
    // validate lastname
    if (empty(trim($_POST["lastname"])))
    {
        $lastname_err = "Please enter your last name.";
    }
    else
    {
        $lastname = ucfirst(trim($_POST["lastname"]));
    }
    // validate role
    if (empty(trim($_POST["role"])))
    {
        //$role_err = "Please enter your role.";
    }
    else
    {
        $role = trim($_POST["role"]);
    }

    // validate company
    if (empty(trim($_POST["company"])))
    {
        //$company_err = "Please enter the name of the company you work for.";
    }
    else
    {
        $company = trim($_POST["company"]);
    }
    // validate phone number
    if (empty(trim($_POST["phone_number"])))
    {
        //$phone_number_err = "Please enter your phone number.";
    }
    else
    {
        $phone_number = trim($_POST["phone_number"]);
    }

    if (empty($firstname_err) && empty($lastname_err) && empty($role_err) && empty($company_err) && empty($phone_number_err))
    {
        $sql = "UPDATE users SET firstname = '$firstname',lastname='$lastname',company = '$company',role='$role', phone_number='$phone_number' WHERE id = ?";
        if ($stmt = mysqli_prepare($link, $sql))
        {
            mysqli_stmt_bind_param($stmt, "i", $param_user_id);
            $param_user_id = $_SESSION["id"];
            if (mysqli_stmt_execute($stmt))
            {
                //close the statement
                mysqli_stmt_close($stmt);
                echo "<html><head><script src='plugins/jquery/jquery.min.js'></script>
<!-- jQuery UI 1.11.4 -->
<script src='plugins/jquery-ui/jquery-ui.min.js'></script>
 <link rel='stylesheet' href='plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css'>
  <!-- Toastr -->
  <link rel='stylesheet' href='plugins/toastr/toastr.min.css'>
  <script src='plugins/sweetalert2/sweetalert2.min.js'></script>
<!-- Toastr -->
<script src='plugins/toastr/toastr.min.js'></script> </head><body><script>
                toastr.success('Profile Updated Successfully!');
                  
                </script></body></html>";
                
          }
            else
            {
                echo "Executing SQL statement failed. Please try again later.";
            }
        }
        else
        {
            echo "ooops! Something went wrong. Please try again later2.";
        }

    }
}

$sql = "SELECT created,firstname,lastname,role,company,email,phone_number FROM users WHERE id = ?";
if ($stmt = mysqli_prepare($link, $sql))
{
    mysqli_stmt_bind_param($stmt, "i", $param_user_id);
    $param_user_id = $_SESSION["id"];
    if (mysqli_stmt_execute($stmt))
    {
        mysqli_stmt_store_result($stmt);
        if (mysqli_stmt_num_rows($stmt) == 1)
        {
            if (mysqli_stmt_bind_result($stmt, $created, $firstname, $lastname, $role, $company, $email,$phone_number))
            {
                if (mysqli_stmt_fetch($stmt))
                {
                    //echo "all good.";

                }
                else
                {
                    echo "Failed fetching the values. Please try again later.";
                }
            }
            else
            {
                echo "Failed binding the results";
            }
        }
        else
        {
            echo "No account has found.";
        }
        //close the statement
        mysqli_stmt_close($stmt);
    }
}
else
{
    echo "failed at preparing the SQL statement.";
}

// Close connection
mysqli_close($link);

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Software Chasers</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bbootstrap 4 -->
  <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- JQVMap -->
  <link rel="stylesheet" href="plugins/jqvmap/jqvmap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
  <!-- summernote -->
  <link rel="stylesheet" href="plugins/summernote/summernote-bs4.css">
   <!-- SweetAlert2 -->
  <link rel="stylesheet" href="plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
  <!-- Toastr -->
  <link rel="stylesheet" href="plugins/toastr/toastr.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

  <meta name="description" content="Signature Pad - HTML5 canvas based smooth signature drawing using variable width spline interpolation.">

  <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">

  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black">

  <!--<link rel="stylesheet" href="css/signature-pad.css">-->

  <style type="text/css">

    .wrapper{

    min-height:10% !important;

  }

.emp-profile{
    padding: 3%;
    margin-top: 3%;
    margin-bottom: 3%;
    border-radius: 0.5rem;
    background: #fff;
}
.profile-img{
    text-align: center;
}
.profile-img img{
    width: 70%;
    height: 100%;
}
.profile-img .file {
    position: relative;
    overflow: hidden;
    margin-top: -20%;
    width: 70%;
    border: none;
    border-radius: 0;
    font-size: 15px;
    background: #212529b8;
}
.profile-img .file input {
    position: absolute;
    opacity: 0;
    right: 0;
    top: 0;
}
.profile-head h5{
    color: #333;
}
.profile-head h6{
    color: #0062cc;
}
.profile-edit-btn{
    border: none;
    border-radius: 1.5rem;
    width: 70%;
    padding: 2%;
    font-weight: 600;
    color: #6c757d;
    cursor: pointer;
}
.proile-rating{
    font-size: 12px;
    color: #818182;
    margin-top: 5%;
}
.proile-rating span{
    color: #495057;
    font-size: 15px;
    font-weight: 600;
}
.profile-head .nav-tabs{
    margin-bottom:5%;
}
.profile-head .nav-tabs .nav-link{
    font-weight:600;
    border: none;
}
.profile-head .nav-tabs .nav-link.active{
    border: none;
    border-bottom:2px solid #0062cc;
}
.profile-work{
    padding: 14%;
    margin-top: -15%;
}
.profile-work p{
    font-size: 12px;
    color: #818182;
    font-weight: 600;
    margin-top: 10%;
}
.profile-work a{
    text-decoration: none;
    color: #495057;
    font-weight: 600;
    font-size: 14px;
}
.profile-work ul{
    list-style: none;
}
.profile-tab label{
    font-weight: 600;
}
.profile-tab p{
    font-weight: 600;
    color: #0062cc;
}

  </style>


  <!--[if IE]>
    <link rel="stylesheet" type="text/css" href="css/ie9.css">
  <![endif]-->

  <script type="text/javascript">
    var _gaq = _gaq || [];
    _gaq.push(['_setAccount', 'UA-39365077-1']);
    _gaq.push(['_trackPageview']);

    (function () {
      var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
      ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
      var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
    })();
  </script>


  </head>
  <body>

    <?php include 'sidebar.php'; ?>
    </aside>
    <div class="content-wrapper">

    <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0 text-dark">Profile</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="profile.php" style="color: #276a91">Home</a></li>
                <li class="breadcrumb-item active">Profile</li>
              </ol>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->

    <section class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-12">

    <div class="container emp-profile d-flex justify-content-center">
            <!-- edit form column -->
            <div class="col-md-8 col-sm-6 col-xs-12 personal-info">

            <h3 class="d-flex justify-content-center">Personal Information</h3>
            <br><br>
            <form class="form-horizontal" role="form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group row">
                <label class="col-lg-3 control-label ">First name:</label>
                <div class="col-lg-8">
                    <input  class="form-control edit-profile" style="text-transform: capitalize;" value="<?php echo $firstname; ?>" name="firstname" type="text" disabled>
                    <span class="help-block"><?php echo $firstname_err; ?></span>
                </div>
                </div>
                <div class="form-group row">
                <label class="col-lg-3 control-label">Last name:</label>
                <div class="col-lg-8">
                    <input class="form-control edit-profile" style="text-transform: capitalize;" name = "lastname" value="<?php echo $lastname; ?>" type="text" disabled>
                    <span class="help-block"><?php echo $lastname_err; ?></span>
                </div>
                </div>

                <div class="form-group row">
                <label class="col-lg-3 control-label">Account created:</label>
                <div class="col-lg-8">
                    <input class="form-control" value="<?php echo $created; ?>" type="text" disabled>
                </div>
                </div>

                <div class="form-group row">
                <label class="col-lg-3 control-label">Email address:</label>
                <div class="col-lg-8">
                    <input class="form-control" value="<?php echo $email; ?>" type="text" disabled>
                </div>
                </div>

                <div class="form-group row">
                <label class="col-lg-3 control-label">Phone number:</label>
                <div class="col-lg-8">
                    <input class="form-control edit-profile" value="<?php echo $phone_number; ?>" name ="phone_number" type="text" disabled>
                    <span class="help-block"><?php echo $phone_number_err; ?></span>
                </div>
                </div>

                <div class="form-group row">
                <label class="col-lg-3 control-label">Company:</label>
                <div class="col-lg-8">
                    <input class="form-control edit-profile" style="text-transform: capitalize;" value="<?php echo $company; ?>" name ="company" type="text" disabled>
                    <span class="help-block"><?php echo $company_err; ?></span>
                </div>
                </div>
                <div class="form-group row">
                <label class="col-lg-3 control-label">Role:</label>
                <div class="col-lg-8">
                    <input class="form-control edit-profile" style="text-transform: capitalize;" value="<?php echo $role; ?>" name ="role" type="text" disabled>
                    <span class="help-block"><?php echo $role_err; ?></span>
                </div>
                </div>







                <div class="form-group row">
                <label class="col-md-3 control-label"></label>
                <div class="col-md-8">
                    <input class="btn btn-primary" value="Save Changes" type="submit" id="save" style="display:none">

                    <span></span>
                    <input class="btn btn-primary" value="Edit Profile" type="button" id="edit" onclick="editprofile()">
                    <span></span>
                    <input class="btn btn-default" value="Cancel" type="reset" id="cancel"style="display:none" onclick="cancel()">
                    <button type="button" style="display:none" class="btn btn-success toastrDefaultSuccess">
                 
                </button>
                </div>
                </div>
            </form>
            </div>
            </div>
        </div>
    <!-- /.content-wrapper -->
  </div></div></div>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
      <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
  </div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<!-- SweetAlert2 -->
<script src="plugins/sweetalert2/sweetalert2.min.js"></script>
<!-- Toastr -->
<script src="plugins/toastr/toastr.min.js"></script>
<!-- AdminLTE App -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
  function msg()
  {
   toastr.success('Lorem ipsum dolor sit amet, consetetur sadipscing elitr.');
  }
  $(document).ready(function(){
    $('.toastrDefaultSuccess').click(function() {
      toastr.success('Lorem ipsum dolor sit amet, consetetur sadipscing elitr.')
    });
});
</script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- ChartJS -->
<script src="plugins/chart.js/Chart.min.js"></script>
<!-- Sparkline -->
<script src="plugins/sparklines/sparkline.js"></script>
<!-- JQVMap -->
<script src="plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<!-- jQuery Knob Chart -->
<script src="plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="plugins/moment/moment.min.js"></script>
<script src="plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<script src="plugins/summernote/summernote-bs4.min.js"></script>
<!-- overlayScrollbars -->
<script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="dist/js/pages/dashboard.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>


<script>

    function editprofile() {
    $("#edit").hide();
    $('.edit-profile').removeAttr("disabled");

    $("#save").show();
    $("#cancel").show();

    }

    function cancelgg() {
    $("#edit").show();
    $('.edit-profile').addAttr("disabled");

    $("#save").hide();
    $("#cancel").hide();
    }


$( "#cancel" ).click(function() {
    $("#save").hide();
    $("#cancel").hide();
  $("#edit").show();
    $('.edit-profile').attr("disabled", true);


});


$( "#save" ).click(function() {
    $("#save").hide();
    $("#cancel").hide();
  $("#edit").show();
    //$('.edit-profile').attr("disabled", true);
/*
    /swal({
  title: "Profile Updated Successfully!",
  icon: "success",
});
*/


});

</script>
</body>
</html>
