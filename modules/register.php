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

$first_name_error = false;
$last_name_error = false;
$phone_number_error = false;
$email_error = false;
$pin_error = false;

// Registration
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $first_name = htmlspecialchars(trim($_POST['first-name']));
    $last_name = htmlspecialchars(trim($_POST['last-name']));
    $phone_number = htmlspecialchars(trim($_POST['phone-number']));
    $email = htmlspecialchars(trim($_POST['email']));
    $pin = htmlspecialchars(trim($_POST['pin']));
    $last_login = date('Y-m-d H:i:s');

    if ($first_name === '') {
        $first_name_error = 'Please enter first name';

    } else if ($last_name === '') {
        $last_name_error = 'Please enter last name';

    } else if ($phone_number === '') {
        $phone_number_error = 'Please enter phone number';

    } else if ($email === '') {
        $email_error = 'Please enter email';

    } else if ($pin === '') {
        $pin_error = 'Please enter PIN';

    } else if (!is_numeric($phone_number)) {
        $phone_number_error = 'Phone number can only contains numeric characters';

    } else if (!is_numeric($pin)) {
        $pin_error = 'PIN can only contains numbers';

    } else if (strlen($pin) > 4) {
        $pin_error = 'PIN can only contains 4 digits';

    } else if (strlen($first_name) > 20) {
        $first_name_error = 'First name is too long';

    } else if (strlen($last_name) > 20) {
        $last_name_error = 'Last name is too long';

    } else if (!str_contains($email, '@')) {
        $email_error = 'Please enter email in valid format';

    } else {

        $hashed_pin = password_hash($pin, PASSWORD_DEFAULT);

        $sql = "INSERT INTO accounts (first_name, last_name, phone_number, email, hashed_pin, last_login)
        VALUES (?, ?, ?, ?, ?, ?);";

        $run = $db->prepare($sql);
        $run->bind_param("ssssss", $first_name, $last_name, $phone_number, $email, $hashed_pin, $last_login);
        $run->execute();
    }
}

?>

<div class="register">
    <h2>Register (for admins only)</h2>

    <form method="POST">
        <input type="text" name="first-name" placeholder="First name"><br><br>
        <?php if ($first_name_error): ?>
            <p><?php echo $first_name_error; ?></p>
        <?php endif; ?>

        <input type="text" name="last-name" placeholder="Last name"><br><br>
        <?php if ($last_name_error): ?>
            <p><?php echo $last_name_error; ?></p>
        <?php endif; ?>

        <input type="text" name="phone-number" placeholder="Phone number"><br><br>
        <?php if ($phone_number_error): ?>
            <p><?php echo $phone_number_error; ?></p>
        <?php endif; ?>

        <input type="email" name="email" placeholder="Email"><br><br>
        <?php if ($email_error): ?>
            <p><?php echo $email_error; ?></p>
        <?php endif; ?>

        <input type="password" name="pin" placeholder="PIN" maxlength="4"><br><br>
        <?php if ($pin_error): ?>
            <p><?php echo $pin_error; ?></p>
        <?php endif; ?>

        <button type="submit">Register new account</button>
    </form>
</div>