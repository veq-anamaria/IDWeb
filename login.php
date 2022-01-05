<?php include('server.php') ?>
<!DOCTYPE html>
<html>
<head>
    <title>Yoko Panel</title>
    <link rel="stylesheet" type="text/css" href="2.css">
</head>
<body>
<div class="header">
    <h2>Login</h2>
</div>

<form method="post" action="login.php">
    <?php include('errors.php'); ?>
    <div class="input-group">
        <label>Username</label>
        <input type="text" name="username" >
    </div>
    <div class="input-group">
        <label>Parola</label>
        <input type="password" name="password">
    </div>
    <div class="input-group">
        <button type="submit" class="btn" name="login_user">Logheaza-te</button>
    </div>
    <p>
        Nu te-ai inregistrat? <a href="register.php">Inregistreaza-te</a>
    </p>
</form>
</body>
</html>