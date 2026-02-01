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
    <h2>Admin page</h2>

    <form method="POST">
        <button type="submit">Logout</button>
    </form>

    <?php foreach ($results as $acc): ?>
        <div class="account"><br>
            <p>First name: <?php echo $acc['first_name'] ?></p>
            <p>Last name: <?php echo $acc['last_name'] ?></p>
            <p>Phone number: <?php echo $acc['phone_number'] ?></p>
            <p>Email: <?php echo $acc['email'] ?></p>
            <p>Last login: <?php echo $acc['last_login'] ?></p>
            <p>Mastercard debit: <?php echo $acc['mastercard_debit'] ?></p>
            <p>Country card debit: <?php echo $acc['country_card_debit'] ?></p>
            <p>Account in USD: <?php echo $acc['account_in_usd'] ?></p>
            <p>Account in EUR: <?php echo $acc['account_in_eur'] ?></p>
            <p>Created at: <?php echo $acc['created_at'] ?></p>
        </div><br>
    <?php endforeach; ?>
</div>