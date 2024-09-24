<?php
session_start();
if(empty($_SESSION['loggedin'])){
    header("location: login.php");
}