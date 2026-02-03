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

$sql = "SELECT first_name, mastercard_debit, country_card_debit, account_in_usd, account_in_eur
FROM accounts WHERE id = $current_user_id";
$run = $db->query($sql);
$results = $run->fetch_assoc();

$logged_user = $results;
$logged_user_id = $_SESSION['user_id'];

// Username
$username = $logged_user['first_name'];

// Debit cards
$mastercard_debit_active = false;
$country_card_debit_active = false;

if ($logged_user['mastercard_debit'] === "1") {
    $mastercard_debit_active = true;
}

if ($logged_user['country_card_debit'] === "1") {
    $country_card_debit_active = true;
}

// Displaying transactions
$sql = "SELECT sender_id, recipient_id, amount, currency, tr_status, created_at
FROM transactions
WHERE sender_id = $logged_user_id UNION
SELECT sender_id, recipient_id, amount, currency, tr_status, created_at
FROM transactions
WHERE recipient_id = $logged_user_id;";

$run = $db->query($sql);
$results = $run->fetch_all(MYSQLI_ASSOC);

$transactions = array_reverse($results);

// Logging out
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    session_destroy();
    header("Location: http://localhost/projects/banking-app/?router=login");
    exit();
}

?>

<div class="homepage">
    <div class="container">
        <h2>Homepage</h2>

        <?php if ($username): ?>
            <h3>Welcome, <?php echo $username ?></h3>
        <?php endif; ?>

        <a href="http://localhost/projects/banking-app/?router=profile" class="profile-link">Profile</a>

        <div class="switch-buttons">
            <button class="active-btn" id="balance-btn">Balance</button>
            <button id="cards-btn">Cards</button>
        </div>

        <div class="account-informations">
            <div class="accounts show">
                <h4>Account in USD: <?php echo $logged_user['account_in_usd'] ?></h4>
                <h4>Account in EUR: <?php echo $logged_user['account_in_eur'] ?></h4>
            </div>

            <div class="debit-cards">
                <h4>Mastercard debit: <?php echo $logged_user['mastercard_debit'] ? 'Active' : 'Inactive' ?></h4>
                <?php if (!$mastercard_debit_active): ?>
                    <a href="http://localhost/projects/banking-app/?router=mastercard-activation" class="activate-link">Activate</a>
                <?php endif; ?>

                <h4>Country card debit: <?php echo $logged_user['country_card_debit'] ? 'Active' : 'Inactive' ?></h4>
                <?php if (!$country_card_debit_active): ?>
                    <a href="http://localhost/projects/banking-app/?router=country-card-activation" class="activate-link">Activate</a>
                <?php endif; ?>
            </div>
        </div>

        <a href="http://localhost/projects/banking-app/?router=paying" class="payment-link">Make a payment</a>

        <form method="POST">
            <button type="submit" class="logout-btn">Logout</button>
        </form>

        <div class="transactions">
            <?php foreach ($transactions as $transaction): ?>
                <div class="transaction">
                    <p>Sender ID: <?php
                    
                    $sender_id = $transaction['sender_id'];
                    $sql = "SELECT first_name FROM accounts WHERE id = $sender_id";
                    $run = $db->query($sql);
                    $results = $run->fetch_assoc();
                    echo $results['first_name'];
                    
                    ?></p>
                    <p>Recipient ID: <?php
                    
                    $recipient_id = $transaction['recipient_id'];
                    $sql = "SELECT first_name FROM accounts WHERE id = $recipient_id";
                    $run = $db->query($sql);
                    $results = $run->fetch_assoc();
                    echo $results['first_name'];
                    
                    ?></p>
                    <p>Amount: <?php echo $transaction['amount'] ?></p>
                    <p>Currency: <?php echo $transaction['currency'] ?></p>
                    <p>Status: <?php echo $transaction['tr_status'] ?></p>
                    <p>Created at: <?php echo $transaction['created_at'] ?></p>
                </div>
            <?php endforeach; ?>    
        </div>
    </div>
</div>