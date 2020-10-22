<?php
session_start();
unset($_SESSION['sessionUsername']);
session_destroy();
header("Location: login.php");
