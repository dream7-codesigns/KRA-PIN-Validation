<?php
if (isset($_POST["submit"])) {

    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $kra_pin = trim($_POST["kra_pin"]);

    $errors = [];

    // Basic validation
    if (empty($name) || empty($email) || empty($kra_pin)) {
        $errors[] = "ALL FIELDS ARE REQUIRED!";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }

    // KRA PIN must start with A or P
    if (!preg_match("/^[AP][0-9]{9}[A-Z]$/", $kra_pin)) {
        $errors[] = "Invalid KRA PIN format. Must start with A or P and contain 11 characters.";
    }

    // Check for duplicates in file
    $file = "kra_data.txt";
    $existing_pins = file_exists($file) ? file($file, FILE_IGNORE_NEW_LINES) : [];

    foreach ($existing_pins as $line) {
        $data = explode("|", $line);

        if (isset($data[2]) && $data[2] === $kra_pin) {
            $errors[] = "The KRA PIN already exists.";
            break;
        }
    }

    // Save to file if no errors
    if (empty($errors)) {

        $record = $name . "|" . $email . "|" . $kra_pin . "\n";

        file_put_contents($file, $record, FILE_APPEND);

        echo "Registration Successful";

    } else {
        foreach ($errors as $error) {
            echo "<p style='color:red;'>$error</p>";
        }
    }

    // Display registered data
    if (file_exists($file)) {
        $records = file($file, FILE_IGNORE_NEW_LINES);

        echo "<h3>Registered KRA PINs</h3>";
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>Name</th><th>Email</th><th>KRA PIN</th></tr>";

        foreach ($records as $record) {
            list($name, $email, $kra_pin) = explode("|", $record);
            echo "<tr><td>$name</td><td>$email</td><td>$kra_pin</td></tr>";
        }

        echo "</table>";
    }
}
echo "<p><a href='view.php'>View All Records</a></p>";
?>
