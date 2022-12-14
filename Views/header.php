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
    <link rel="shortcut icon" href="<?php echo FRONT_ROOT . VIEWS_PATH ?>img/favicon.ico" type="image/x-icon" />
    <title>Pet Hero</title>

    <!-- Login & Signup -->
    <?php

    use Utils\Session;
    use Utils\TempValues;

    if ((!Session::VerifySession("owner") && !Session::VerifySession("keeper") && !TempValues::ValueExist("keeper"))) {
    ?>
        <link rel="stylesheet" href="<?php echo CSS_PATH ?>login-style.css">
    <?php
    } else {
    ?>
        <!-- Main CSS -->
        <link rel="stylesheet" href="<?php echo CSS_PATH ?>style.css">
    <?php
    }
    ?>

    <!-- Bootstrap necessary libraries -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    <!-- Daterangepicker library -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <!-- Icons library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>