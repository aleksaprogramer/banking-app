<?php

$phone_number_error = false;
$pin_error = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $phone_number = htmlspecialchars(trim($_POST['phone-number']));
    $pin = htmlspecialchars(trim($_POST['pin']));
}

?>

<div class="login">
    <h2>Login</h2>

    <form method="POST">
        <input type="text" name="phone-number" placeholder="Phone number"><br><br>
        <input type="password" name="pin" maxlength="4" placeholder="PIN"><br><br>
        <button type="submit">Login</button>
    </form>
</div>