<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" type="text/css" href="<?php echo CSS_PATH ?>bootstrap.min.css">

    <!-- Font Icon -->
    <link rel="stylesheet" href="<?php echo FONTS_PATH ?>material-icon/css/material-design-iconic-font.min.css">

    <!-- Favicon -->
    <link rel="shortcut icon" href="<?php echo VIEWS_PATH ?>img/favicon.ico" type="image/x-icon" />
    <title>Pet Hero</title>

    <!-- Login & Signup -->
    <?php

    use Utils\Session;

    if ((!Session::VerifySession("owner") && !Session::VerifySession("keeper")) || $_SERVER['REQUEST_URI'] == ROOT) {
    ?>
        <link rel="stylesheet" href="<?php echo CSS_PATH ?>login-style.css">
        <script type="module" src="<?php echo THREEJS_PATH ?>pet-figure.js"></script>
    <?php
    } else {
    ?>
        <!-- Main CSS -->
        <link rel="stylesheet" href="<?php echo CSS_PATH ?>style.css">
    <?php
    }
    ?>



    <!-- Daterangepicker library -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
</head>