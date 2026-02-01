<?php

if (!isset($_SESSION['user_id'])) {
    header("Location: http://localhost/projects/banking-app/?router=login");
    exit();
}

// Validating country card
$current_user_id = $_SESSION['user_id'];

$sql = "SELECT country_card_debit FROM accounts WHERE id = $current_user_id";
$run = $db->query($sql);
$results = $run->fetch_assoc();

if ($results['country_card_debit'] === '1') {
    header("Location: http://localhost/projects/banking-app/?router=homepage");
    exit();
}

// Activating country card
$logged_user_id = (int)$_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $country_card = (int)htmlspecialchars(trim($_POST['country-card']));

    if ($country_card !== 1) {
        session_destroy();
        header("Location: http://localhost/projects/banking-app/?router=login");
        exit();

    } else {
        $sql = "UPDATE accounts SET country_card_debit = ? WHERE id = ?";

        $run = $db->prepare($sql);
        $run->bind_param("ii", $country_card, $logged_user_id);
        $run->execute();

        header("Location: http://localhost/projects/banking-app/?router=homepage");
        exit();
    }
}

?>

<div class="country-card-activation">
    <h2>Country Card Activation</h2>

    <form method="POST">
        <input type="hidden" name="country-card" value="1">
        <button type="submit">Activate</button>
    </form>
</div>