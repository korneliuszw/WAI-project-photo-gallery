<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'commons/head.php'; ?>
</head>
<body>
<?php
require_once 'models/RegisterModel.php';
include "commons/error.php";
include "commons/navbar.php";
?>
<form method="post" action="/register" class="w-50 m-auto">
    <div class="form-group">
        <label for="username">
            Email:
        </label>
        <input class="form-control" id="email" name="email" type="email" required>
    </div>
    <div class="form-group">
        <label for="username">
            Username:
        </label>
        <input class="form-control" id="username" name="username" type="text" required>
    </div>
    <div class="form-group">
        <label for="password">
            Password:
        </label>
        <input class="form-control" id="password" name="password" type="password" required>
    </div>
    <div class="form-group">
        <label for="repeatedPassword">
            Retype password:
        </label>
        <input class="form-control" id="repeatedPassword" name="repeatedPassword" type="password" required>
    </div>
    <button class="btn btn-primary mt-2" type="submit">Register</button>
</form>
</body>
</html>