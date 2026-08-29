<?php echo doctype("html5"); ?>
<html class="white-bg-login" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <title>Sign in</title>
    <link rel="SHORTCUT ICON" href="<?= base_url("uploads/images/$siteinfos->photo") ?>" />
    <!-- bootstrap 3.0.2 -->
    <link href="<?php echo base_url('assets/bootstrap/bootstrap.min.css'); ?>" rel="stylesheet" type="text/css">
    <!-- font Awesome -->
    <link href="<?php echo base_url('assets/fonts/font-awesome.css'); ?>" rel="stylesheet" type="text/css">
    <!-- Style -->
    <link href="<?php echo base_url($backendThemePath . '/style.css'); ?>" rel="stylesheet" type="text/css">
    <!-- iNilabs css -->
    <link href="<?php echo base_url($backendThemePath . '/inilabs.css'); ?>" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url('assets/inilabs/responsive.css'); ?>" rel="stylesheet" type="text/css">
    <style>
        .form-box-new .header {
            -webkit-border-top-left-radius: 4px;
            -webkit-border-top-right-radius: 4px;
            -webkit-border-bottom-right-radius: 0;
            -webkit-border-bottom-left-radius: 0;
            -moz-border-radius-topleft: 4px;
            -moz-border-radius-topright: 4px;
            -moz-border-radius-bottomright: 0;
            -moz-border-radius-bottomleft: 0;
            border-top-left-radius: 4px;
            border-top-right-radius: 4px;
            border-bottom-right-radius: 0;
            border-bottom-left-radius: 0;
            background: none repeat scroll 0 0 #1A2229;
            box-shadow: inset 0px -3px 0px rgba(0, 0, 0, 0.2);
            padding: 20px 10px;
            text-align: center;
            font-size: 26px;
            font-weight: 300;
            color: #fff;
        }
        .form-box-new {
  width: 360px;
  margin: 10px auto 0 auto;
}
.form-box-new .header {
  -webkit-border-top-left-radius: 4px;
  -webkit-border-top-right-radius: 4px;
  -webkit-border-bottom-right-radius: 0;
  -webkit-border-bottom-left-radius: 0;
  -moz-border-radius-topleft: 4px;
  -moz-border-radius-topright: 4px;
  -moz-border-radius-bottomright: 0;
  -moz-border-radius-bottomleft: 0;
  border-top-left-radius: 4px;
  border-top-right-radius: 4px;
  border-bottom-right-radius: 0;
  border-bottom-left-radius: 0;
  background: none repeat scroll 0 0 #1A2229;
  box-shadow: inset 0px -3px 0px rgba(0, 0, 0, 0.2);
  padding: 20px 10px;
  text-align: center;
  font-size: 26px;
  font-weight: 300;
  color: #fff;
}
.form-box-new .body,
.form-box-new .footer {
  padding: 10px 20px;
  background: #fff;
  color: #444;
}
.form-box-new .body > .form-group,
.form-box-new .footer > .form-group {
  margin-top: 20px;
}
.form-box-new .body > .form-group > input,
.form-box-new .footer > .form-group > input {
  border: #fff;
}
.form-box-new .body > .btn,
.form-box-new .footer > .btn {
  margin-bottom: 10px;
}
.form-box-new .footer {
  -webkit-border-top-left-radius: 0;
  -webkit-border-top-right-radius: 0;
  -webkit-border-bottom-right-radius: 4px;
  -webkit-border-bottom-left-radius: 4px;
  -moz-border-radius-topleft: 0;
  -moz-border-radius-topright: 0;
  -moz-border-radius-bottomright: 4px;
  -moz-border-radius-bottomleft: 4px;
  border-top-left-radius: 0;
  border-top-right-radius: 0;
  border-bottom-right-radius: 4px;
  border-bottom-left-radius: 4px;
}
@media (max-width: 767px) {
  .form-box-new {
    width: 90%;
  }
}


        .form-box-new .body,
        .form-box-new .footer {
            border-radius: 0px 0px 4px 4px;
            -moz-border-radius: 0px 0px 4px 4px;
            -webkit-border-radius: 0px 0px 4px 4px;
        }

        .form-box-new .body>.form-group>input,
        .form-box-new .footer>.form-group>input {
            border: 1px solid #E2E7EB;
            box-shadow: 0 0 0 rgba(0, 0, 0, 0.070) inset;
        }
    </style>
</head>

<body class="white-bg-login">

    <div class="col-md-4 col-md-offset-4 marg" style="margin-top:30px;">
        <?php
        if (customCompute($siteinfos->photo)) {
            echo "<center><img width='25%' src=" . base_url('uploads/images/' . $siteinfos->photo) . " /></center>";
        }
        ?>
        <center>
            <h4><?php echo namesorting($siteinfos->sname, 50); ?></h4>
        </center>
    </div>
    <div class="col-md-4 col-md-offset-4 marg">
        <?php $this->load->view($subview); ?>
    </div>



    <script type="text/javascript" src="<?php echo base_url('assets/inilabs/jquery.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/bootstrap/bootstrap.min.js'); ?>"></script>


</body>

</html>