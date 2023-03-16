<!DOCTYPE html>
<html>

<head>
    <title>Login Form</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="login_container">
        <h2>Login Form</h2>
        <form method="POST" action="login.php">
            <label for="username">Username:</label><br>
            <input type="text" name="username" required><br>
            <label>Password:</label><br>
            <input type="password" name="password" required><br><br>
            <input type="submit" value="Login">
        </form>

        <?php
        // connect to database
        $conn = oci_connect('system', 'tiger', '//localhost/XE');

        // Check connection
        if (!$conn) {
            echo 'Failed to connect to oracle' . "<br>";
        }

        // prepare SQL statement
        $sql = "SELECT * FROM login WHERE username = :username AND password = :password";

        $stmt = oci_parse($conn, $sql);
        if (!$stmt) {
            $m = oci_error($conn);
            trigger_error('Could not parse statement: ' . $m['message'], E_USER_ERROR);
        }

        // bind parameters
        oci_bind_by_name($stmt, ":username", $_POST['username']);
        oci_bind_by_name($stmt, ":password", $_POST['password']);

        // execute statement
        $r = oci_execute($stmt);
        if (!$r) {
            $m = oci_error($stmt);
            trigger_error('Could not execute statement: ' . $m['message'], E_USER_ERROR);
        }

        // check if login credentials are valid
        if ($row = oci_fetch_array($stmt, OCI_RETURN_NULLS + OCI_ASSOC)) {
            echo '<p class="correct">Login successful!</p>';
            header("Location: home.php");
            exit();
        } else {
            echo '<p class="error">Invalid username or password.</p>';
        }

        oci_free_statement($stmt);
        oci_close($conn);
        ?>
    </div>

</body>

</html>