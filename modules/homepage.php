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

?>

<div class="homepage">
    <div class="container">
        <h2>Homepage</h2>

        <?php if ($username): ?>
            <h3>Welcome, <?php echo $username ?></h3>
        <?php endif; ?>

        <div class="account-informations">
            <div class="accounts">
                <h4>Account in USD: <?php echo $logged_user['account_in_usd'] ?></h4>
                <h4>Account in EUR: <?php echo $logged_user['account_in_eur'] ?></h4>
            </div>

            <div class="debit-cards">
                <h4>Mastercard debit: <?php echo $logged_user['mastercard_debit'] ? 'Active' : 'Inactive' ?></h4>
                <?php if (!$mastercard_debit_active): ?>
                    <a href="http://localhost/projects/banking-app/?router=mastercard-activation">Activate</a>
                <?php endif; ?>

                <h4>Country card debit: <?php echo $logged_user['country_card_debit'] ? 'Active' : 'Inactive' ?></h4>
                <?php if (!$country_card_debit_active): ?>
                    <a href="http://localhost/projects/banking-app/?router=country-card-activation">Activate</a>
                <?php endif; ?>
            </div>
        </div>

        <a href="http://localhost/projects/banking-app/?router=paying">Make a payment</a>
    </div>
</div>