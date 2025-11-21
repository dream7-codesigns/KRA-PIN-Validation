<?php
$file = 'kra_data.txt';

// Check if file exists
if(!file_exists($file)) {
    echo "<p>No records found.</p>";
    exit;
}

$records = file($file, FILE_IGNORE_NEW_LINES);

// Count total submissions
$total = count($records);

// Count entries starting with a given letter (e.g., 'A')
$letter = isset($_GET['letter']) ? strtoupper($_GET['letter']) : 'A';
$countByLetter = 0;

foreach($records as $record){
    list($name, $email, $pin) = explode("|", $record);
    if(strtoupper($pin[0]) === $letter){
        $countByLetter++;
    }
}

// Find most common starting letter
$freq = array_fill_keys(range('A','Z'), 0);

foreach($records as $record){
    list($name, $email, $pin) = explode("|", $record);
    $first = strtoupper($pin[0]);
    $freq[$first]++;
}

arsort($freq);
$mostCommonLetter = key($freq); 
$mostCommonCount  = current($freq);
?>

<!DOCTYPE html>
<html>
<head>
    <title>KRA Records</title>
</head>
<body>

<h2>Saved KRA Records</h2>

<p><strong>Total submissions:</strong> <?php echo $total; ?></p>

<form method="GET">
    <label>Check how many entries start with letter:</label>
    <input type="text" name="letter" maxlength="1" required>
    <button type="submit">Check</button>
</form>

<p>
Entries starting with <strong><?php echo $letter; ?></strong>: 
<strong><?php echo $countByLetter; ?></strong>
</p>

<h3>Most Common Starting Letter</h3>
<p>
<strong><?php echo $mostCommonLetter; ?></strong> appears 
<strong><?php echo $mostCommonCount; ?></strong> times.
</p>

<h3>All Records</h3>
<table border="1" cellpadding="5">
<tr><th>Name</th><th>Email</th><th>KRA PIN</th></tr>

<?php
foreach($records as $record){
    list($name, $email, $pin) = explode("|", $record);
    echo "<tr>
            <td>$name</td>
            <td>$email</td>
            <td>$pin</td>
          </tr>";
}
?>
</table>

<p><a href="index.php">Register New User</a></p>

</body>
</html>