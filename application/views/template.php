<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Callme Indonesia</title>

    <link href="<?php echo base_url('assets/plugins/bootstrap-3.3.7/css/bootstrap.min.css')?>" rel="stylesheet">
    <!-- <link href="https://fonts.googleapis.com/css?family=Open+Sans|Raleway" rel="stylesheet"> -->
    <link href="<?php echo base_url('assets/plugins/fractionslider/css/fractionslider.css')?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url('assets/fonts/fonts.min.css')?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/css/style.css')?>" rel="stylesheet">

    <link rel="icon" type="image/png" href="<?php echo base_url('/assets/images/favicon.png')?>" />

</head>
<body>

    <?php $this->load->view('template/header') ?>
    <?php $this->load->view('template/slider') ?>
    <?php $this->load->view('template/callmeapps') ?>
    <?php $this->load->view('template/whatwedo') ?>
    <?php $this->load->view('template/ourpeople') ?>
    <?php $this->load->view('template/gallery') ?>
    <?php $this->load->view('template/contactus') ?>
    <?php $this->load->view('template/footer') ?>


    <script src="<?php echo base_url('assets/js/jquery.js')?>"></script>
    <script src="<?php echo base_url('assets/plugins/bootstrap-3.3.7/js/bootstrap.min.js')?>"></script>

    <script src="<?php echo base_url('assets/plugins/one-page-nav/jquery.nav.js')?>"></script>
    <script src="<?php echo base_url('assets/plugins/fractionslider/jquery.fractionslider.js')?>"></script>
    <script src="<?php echo base_url('assets/plugins/parallax.js-1.4.2/parallax.min.js')?>"></script>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDXNAgSbKbKkvDXpnQ2moHJWB0MLHuIWq4&callback=initMap"></script>
    <script src="<?php echo base_url('assets/js/app.js')?>"></script>
</body>
</html>
