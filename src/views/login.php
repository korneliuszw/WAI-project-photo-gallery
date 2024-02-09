<!DOCTYPE html>
<html lang="en">
<head>
    <?php include 'commons/head.php'; ?>
</head>
<body>
<?php
require_once 'models/LoginModel.php';

include "commons/error.php";
include "commons/navbar.php";

if (isset($model->successMessage)) { ?>
    <div class="message-box message-box--success">
        <div class="message-box--text"><?php echo $model->successMessage ?></div>
    </div>
<?php } ?>


<form method="post" action="/login" class="w-50 m-auto">
    <div class="form-group">
        <label for="username">
            Username:
        </label>
        <input name="username" type="text" required class="form-control">
    </div>
    <div class="form-group">
        <label for="password">
            Password:
        </label>
        <input class="form-control" name="password" type="password" required>
    </div>
    <button class="btn btn-primary mt-2" type="submit">Login</button>
</form>
</body>
</html>