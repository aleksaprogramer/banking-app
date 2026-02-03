<?php

if (isset($_SESSION['user_id'])) {
    header("Location: http://localhost/projects/banking-app/?router=homepage");
    exit();
}

$phone_number_error = false;
$pin_error = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $phone_number = htmlspecialchars(trim($_POST['phone-number']));
    $pin = htmlspecialchars(trim($_POST['pin']));

    if ($phone_number === '') {
        $phone_number_error = 'Please enter phone number';

    } else if (!is_numeric($phone_number)) {
        $phone_number_error = 'Please enter phone number in a valid format';

    } else if ($pin === '') {
        $pin_error = 'Please enter PIN';

    } else if (strlen($pin) > 4) {
        $pin_error = 'PIN can contains only 4 digits';

    } else {

        $sql = "SELECT * FROM accounts WHERE phone_number = $phone_number";
        $run = $db->query($sql);
        $results = $run->fetch_assoc();

        $current_user = $results;

        if (!$current_user) {
            $phone_number_error = 'Please insert correct data';

        } else if (!password_verify($pin, $current_user['hashed_pin'])) {
            $pin_error = 'Please insert correct data';

        } else {

            $logged_user = $current_user;
            $_SESSION['user_id'] = $logged_user['id'];

            // Updating last login
            $logged_user_id = (int)$logged_user['id'];
            $login_time = date('Y-m-d H:i:s');

            $sql = "UPDATE accounts SET last_login = ? WHERE id = ?";
            $run = $db->prepare($sql);
            $run->bind_param("si", $login_time, $logged_user_id);
            $run->execute();
            
            header("Location: http://localhost/projects/banking-app/?router=homepage");
            exit();
        }
    }
}

?>

<div class="login">
    <div class="container">
        <h2>Login</h2>

        <form method="POST">
            <input type="text" name="phone-number" placeholder="Phone number">
            <?php if ($phone_number_error): ?>
                <p class="error-message"><?php echo $phone_number_error; ?></p>
            <?php endif; ?>

            <input type="password" name="pin" maxlength="4" placeholder="PIN">
            <?php if ($pin_error): ?>
                <p class="error-message"><?php echo $pin_error; ?></p>
            <?php endif; ?>

            <button type="submit">Login</button>
        </form>
    </div>
</div>