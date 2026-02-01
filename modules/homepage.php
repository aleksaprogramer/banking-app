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

$sql = "SELECT mastercard_debit, country_card_debit, account_in_usd, account_in_eur
FROM accounts WHERE id = $current_user_id";
$run = $db->query($sql);
$results = $run->fetch_assoc();

$logged_user = $results;

// Debit cards
$mastercard_debit_inactive = true;
$country_card_debit_inactive = true;

$logged_user_id = $_SESSION['user_id'];

?>

<div class="homepage">
    <h2>Homepage</h2>

    <div class="account-informations">
        <div class="accounts">
            <h4>Account in USD: <?php echo $logged_user['account_in_usd'] ?></h4>
            <h4>Account in EUR: <?php echo $logged_user['account_in_eur'] ?></h4>
        </div>

        <div class="debit-cards">
            <h4>Mastercard debit: <?php echo $logged_user['mastercard_debit'] ? 'Active' : 'Inactive' ?></h4>
            <h4>Country card debit: <?php echo $logged_user['country_card_debit'] ? 'Active' : 'Inactive' ?></h4>
        </div>
    </div>
</div>