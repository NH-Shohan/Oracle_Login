<!DOCTYPE html>
<html>

<head>
    <title>Table with database</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="home_container">
        <table>
            <tr>
                <th>Id</th>
                <th>User Name</th>
                <th>Password</th>
            </tr>

            <?php

            // connect to database
            $conn = oci_connect('system', 'tiger', '//localhost/XE');

            // Check connection
            if (!$conn) {
                echo 'Failed to connect to oracle' . "<br>";
            } else {
                echo '<h1>Connected successfully!</h1>' . "<br>";
            }

            //query to fetch data
            $query = 'SELECT * FROM login';
            $stringId = oci_parse($conn, $query);

            if (!$stringId) {
                $message = oci_error($conn);
                trigger_error('Could not parse statement: ' . $message['message'], E_USER_ERROR);
            }
            // print "oci_parse executed";
            // echo '<br>';

            $r = oci_execute($stringId);
            if (!$r) {
                $message = oci_error($stringId);
                trigger_error('Could not execute statement: ' . $message['message'], E_USER_ERROR);
            }
            // print "oci executed" . "\n";
            // echo '<br>';

            //retrieving data as a tuple 
            while ($row = oci_fetch_array($stringId, OCI_RETURN_NULLS + OCI_ASSOC)) {
                print '<tr>';
                foreach ($row as $item) {
                    print '<td>' . ($item !== null ? htmlentities($item, ENT_QUOTES) : '&nbsp') . '</td>';
                }
                print '</tr>';
            }
            oci_close($conn);

            ?>
        </table>
    </div>
</body>

</html>