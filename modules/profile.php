<?php

if (!isset($_SESSION['user_id'])) {
    header("Location: http://localhost/projects/banking-app/?router=login");
    exit();
}

// Validating user
$current_user_id = $_SESSION['user_id'];

$sql = "SELECT is_admin FROM accounts WHERE id = $current_user_id";
$run = $db->query($sql);
$results = $run->fetch_assoc();

if ($results['is_admin'] === '1') {
    header("Location: http://localhost/projects/banking-app/?router=register");
    exit();
}

// Getting all user informations
$sql = "SELECT first_name, last_name, phone_number, email, last_login 
FROM accounts WHERE id = $current_user_id";
$run = $db->query($sql);
$results = $run->fetch_assoc();

$logged_user = $results;

?>

<div class="profile">
    <div class="container">
        <h2>Profile</h2>

        <a href="http://localhost/projects/banking-app/?router=homepage" class="back-to-homepage-link">Back to homepage</a>

        <div class="account-info">
            <p>Last login: <?php echo $logged_user['last_login'] ?></p>
            <p>First name: <?php echo $logged_user['first_name'] ?></p>
            <p>Last name: <?php echo $logged_user['last_name'] ?></p>
            <p>Phone number: <?php echo $logged_user['phone_number'] ?></p>
            <p>Email: <?php echo $logged_user['email'] ?></p>
        </div>
    </div>
</div>