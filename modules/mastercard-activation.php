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

// Validating mastercard
$current_user_id = $_SESSION['user_id'];

$sql = "SELECT mastercard_debit FROM accounts WHERE id = $current_user_id";
$run = $db->query($sql);
$results = $run->fetch_assoc();

if ($results['mastercard_debit'] === '1') {
    header("Location: http://localhost/projects/banking-app/?router=homepage");
    exit();
}

// Activating mastercard
$logged_user_id = (int)$_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $mastercard = (int)htmlspecialchars(trim($_POST['mastercard']));
    
    if ($mastercard !== 1) {
        session_destroy();
        header("Location: http://localhost/projects/banking-app/?router=login");
        exit();

    } else {
        $sql = "UPDATE accounts SET mastercard_debit = ? WHERE id = ?;";

        $run = $db->prepare($sql);
        $run->bind_param("ii", $mastercard, $logged_user_id);
        $run->execute();

        header("Location: http://localhost/projects/banking-app/?router=homepage");
        exit();
    }
}

?>

<div class="mastercard-activation">
    <div class="container">
        <h2>Mastercard Activation</h2>

        <form method="POST">
            <input type="hidden" name="mastercard" value="1">
            <button type="submit" class="activate-btn">Activate</button>
        </form>
    </div>
</div>