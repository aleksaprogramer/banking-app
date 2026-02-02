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

$sql = "SELECT
first_name, last_name, phone_number, email, last_login, mastercard_debit, country_card_debit, account_in_usd, account_in_eur, created_at
FROM accounts WHERE is_admin = 0";
$run = $db->query($sql);
$results = $run->fetch_all(MYSQLI_ASSOC);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    session_destroy();
    header("Location: http://localhost/projects/banking-app/?router=login");
    exit();
}

?>

<div class="admin-page">
    <div class="container">
        <h2>Admin page</h2>

        <form method="POST">
            <button type="submit" class="logout-btn">Logout</button>
        </form>

        <?php foreach ($results as $acc): ?>
            <div class="account">
                <p>First name: <span><?php echo $acc['first_name'] ?></span></p>
                <p>Last name: <span><?php echo $acc['last_name'] ?></span></p>
                <p>Phone number: <span class="phone-number"><?php echo $acc['phone_number'] ?></span></p>
                <p>Email: <span class="email"><?php echo $acc['email'] ?></span></p>
                <p>Last login: <span><?php echo $acc['last_login'] ?></span></p>
                <p>Mastercard debit: <span><?php echo $acc['mastercard_debit'] ?></span></p>
                <p>Country card debit: <span><?php echo $acc['country_card_debit'] ?></span></p>
                <p class="account-in-usd">Account in USD: <span><?php echo $acc['account_in_usd'] ?></span></p>
                <p class="account-in-eur">Account in EUR: <span><?php echo $acc['account_in_eur'] ?></span></p>
                <p>Created at: <span><?php echo $acc['created_at'] ?></span></p>
            </div>
        <?php endforeach; ?>
    </div>
</div>