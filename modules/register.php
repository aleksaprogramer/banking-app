<?php

if (!isset($_SESSION['user_id'])) {
    header("Location: http://localhost/projects/banking-app/?router=login");
    exit();
}

// Validating admin
$current_user_id = $_SESSION['user_id'];

$sql = "SELECT is_admin FROM accounts WHERE id = $current_user_id";
$run = $db->query($sql);
$results = $run->fetch_assoc();

if ($results['is_admin'] === '0') {
    header("Location: http://localhost/projects/banking-app/?router=homepage");
    exit();

}

?>

<div class="register">
    <h2>Register</h2>

    <form method="POST">
        <input type="text" name="first-name" placeholder="First name"><br><br>
        <input type="text" name="last-name" placeholder="Last name"><br><br>
        <input type="text" name="phone-number" placeholder="Phone number"><br><br>
        <input type="email" name="email" placeholder="Email"><br><br>
        <input type="password" name="pin" placeholder="PIN"><br><br>
        <button type="submit">Register new account</button>
    </form>
</div>