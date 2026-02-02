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
    header("Location: http://localhost/projects/banking-app/?router=admin-page");
    exit();
}

// Getting sender informations
$sql = "SELECT first_name, last_name, phone_number, email, account_in_usd
FROM accounts WHERE id = $current_user_id";
$run = $db->query($sql);
$results = $run->fetch_assoc();
$sender = $results;

$recipient_phone_number_error = false;
$amount_error = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $recipient_phone_number = htmlspecialchars(trim($_POST['recipient-phone-number']));
    $amount = htmlspecialchars(trim($_POST['amount']));
    $currency = 'USD';
    $tr_status = 'success';

    if ($recipient_phone_number === '') {
        $recipient_phone_number_error = 'Please enter the recipient phone number';

    } else if (!is_numeric($recipient_phone_number)) {
        $recipient_phone_number_error = 'Recipient phone number must be numeric';

    } else if (str_contains($recipient_phone_number, '=')) {
        $recipient_phone_number_error = 'Please enter recipient phone number in a valid format';

    } else if (strlen($recipient_phone_number) > 7) {
        $recipient_phone_number_error = 'Recipient phone number must contain 7 digits';

    } else if (strlen($recipient_phone_number) < 7) {
        $recipient_phone_number_error = 'Recipient phone number must contain 7 digits';

    } else if ($recipient_phone_number === $sender['phone_number']) {
        $recipient_phone_number_error = 'You cannot send money to your account';    

    } else if ($amount === '') {
        $amount_error = 'Please enter the amount';

    } else if ((int)$amount > (int)$sender['account_in_usd']) {
        $amount_error = "You don't have enough money on the account to make a payment";

    } else if ((int)$amount < 0) {
        $amount_error = 'Incorrect value';

    } else {

        // Getting recipient informations
        $sql = "SELECT id, first_name, last_name, phone_number, email, account_in_usd FROM accounts WHERE phone_number = $recipient_phone_number;";
        $run = $db->query($sql);
        $results = $run->fetch_assoc();
        $recipient = $results;

        $sender_id = (int)$_SESSION['user_id'];
        $recipient_id = (int)$recipient['id'];

        $addition = (int)$recipient['account_in_usd'] + (int)$amount;
        $subtraction = (int)$sender['account_in_usd'] - (int)$amount;

        // Making new transaction //////////////////////////////////////////////////////////////////////
        $sql = "INSERT INTO transactions (sender_id, recipient_id, amount, currency, tr_status)
        VALUES (?, ?, ?, ?, ?);";

        $run = $db->prepare($sql);
        $run->bind_param("iisss", $sender_id, $recipient_id, $amount, $currency, $tr_status);
        $run->execute();

        // Updating recipient //////////////////////////////////////////////////////////////////////
        $sql = "UPDATE accounts SET account_in_usd = ? WHERE id = ?";
        $run = $db->prepare($sql);
        $run->bind_param("ii", $addition, $recipient_id);
        $run->execute();

        // Updating sender //////////////////////////////////////////////////////////////////////
        $sql = "UPDATE accounts SET account_in_usd = ? WHERE id = ?";
        $run = $db->prepare($sql);
        $run->bind_param("ii", $subtraction, $sender_id);
        $run->execute();
    }
}

?>

<div class="paying">
    <h2>Paying</h2>

    <a href="http://localhost/projects/banking-app/?router=homepage">Back to homepage</a><br><br>

    <form method="POST">
        <input type="text" name="recipient-phone-number" placeholder="Recipient phone number"><br><br>
        <?php if ($recipient_phone_number_error): ?>
            <p><?php echo $recipient_phone_number_error ?></p>
        <?php endif; ?>

        <input type="text" name="amount" placeholder="Amount"> $<br><br>
        <?php if ($amount_error): ?>
            <p><?php echo $amount_error ?></p>
        <?php endif; ?>

        <button type="submit">Make a payment</button>
    </form>
</div>