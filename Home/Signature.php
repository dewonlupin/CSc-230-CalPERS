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
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

  <meta name="description" content="Signature Pad - HTML5 canvas based smooth signature drawing using variable width spline interpolation.">

  <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=no">

  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black">

  <!--<link rel="stylesheet" href="build/css/signature-pad.css">-->
  
  <style type="text/css">

    .canvasbody {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-pack: center;
      -ms-flex-pack: center;
          justify-content: center;
  -webkit-box-align: center;
      -ms-flex-align: center;
          align-items: center;
  width: 70%;
  height: 70% !important;
  -webkit-user-select: none;
     -moz-user-select: none;
      -ms-user-select: none;
          user-select: none;
  margin: 0;
  border: 1px solid blue;
  background: url("data:image/png;base64,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") repeat scroll center center #b3b3b3;
  font-family: Helvetica, Sans-Serif;
}

  </style>


  <!--[if IE]>
    <link rel="stylesheet" type="text/css" href="build/css/ie9.css">
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

      <!-- Sidebar -->
      <?php include 'sidebar.php'; ?>
      <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0 text-dark">Signature</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#" style="color: #276a91">Home</a></li>
                <li class="breadcrumb-item active">Signature</li>
              </ol>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->
      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-12">
              <!-- Custom Tabs -->
              <div class="card">
                <div class="card-header d-flex p-0">
                  <ul class="nav nav-pills ml-auto p-2">
                    <li class="nav-item"><a class="nav-link active" href="#tab_1" data-toggle="tab">Draw</a></li>
                    <li class="nav-item"><a class="nav-link" href="#tab_2" data-toggle="tab">Upload</a></li>
                    <li class="nav-item"><a class="nav-link" href="#tab_3" data-toggle="tab">Saved Signature</a></li>
                  </ul>
                </div><!-- /.card-header -->
                <div class="card-body">
                  <div class="tab-content">
                    <div class="tab-pane active" id="tab_1">
                      <div id="signature-pad" class="signature-pad">
                        <div class="signature-pad--body" >
                          <canvas class="canvasbody" style="height: 400px !important;"></canvas>
                        </div>
                        <div class="signature-pad--footer">
                          <div class="description">Sign above</div>

                          <div class="signature-pad--actions">
                            <div>
                              <button type="button" class="button clear" data-action="clear">Clear</button>
                              <button type="button" class="button" data-action="change-color">Change color</button>
                              <form action="app.js" method="GET">
                                <button type="submit" data-jscolor="{valueElement:'hexclr',value:'pick color'}" class="button" data-action="change-color">Pick color</button>
                                (chosen color: <input id="hexclr" type="text">)
                              </form>

                              <button type="button" class="button" data-action="undo">Undo</button>

                            </div>
                            <div>
                              <button type="button" class="button save" data-action="save-png">Save as PNG</button>
                              <button type="button" class="button save" data-action="save-jpg">Save as JPG</button>
                              <button type="button" class="button save" data-action="save-svg">Save as SVG</button>
                            </div>
                          </div>
                        </div>
                      </div>

                      <script src="js/signature_pad.js"></script>
                      <script src="js/app.js"></script>
                      <script src="js/jscolor.js"></script>
                    </div>
                    <!-- /.tab-pane -->
                    <div class="tab-pane" id="tab_2">
                      <div class="row">
          <!-- left column -->
          <div class="col-md-6">
            <!-- general form elements -->
            <div class="card card-primary">
              
              <!-- /.card-header -->
              <!-- form start -->
              <form role="form">
                <div class="card-body">
                  
                  
                  <div class="form-group">
                    <label for="exampleInputFile">File input</label>
                    <div class="input-group">
                      <div class="custom-file">
                        <input type="file" class="custom-file-input" id="exampleInputFile">
                        <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                      </div>
                      <div class="input-group-append">
                        <span class="input-group-text" id="">Upload</span>
                      </div>
                    </div>
                  </div>
                  </div>
                </div>
                <!-- /.card-body -->

                
              </form>
            </div>
            <!-- /.card -->

            

          </div>
          <!--/.col (left) -->
          <!-- right column -->
          
          <!--/.col (right) -->
        </div>
                    <!-- /.tab-pane -->
                    <div class="tab-pane" id="tab_3">
                     
                    </div>
                    <!-- /.tab-pane -->
                  </div>
                  <!-- /.tab-content -->
                </div><!-- /.card-body -->
              </div>
              <!-- ./card -->
            </div>
            <!-- /.col -->
          </div>
        </div><!-- /.container-fluid -->
      </section>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <footer class="main-footer">

      <div class="float-right d-none d-sm-inline-block">

      </div>
    </footer>

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
<script>
  $.widget.bridge('uibutton', $.ui.button)
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
</body>
</html>
