<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <title>Udyam Number Details</title>
</head>
<body>
    <?php include 'nav.php';?>

    <div class="container mt-3">
        <?php
        // Check if the udyam_number is provided in the URL
        if (isset($_GET['udyam_number'])) {
            // Get the udyam_number from the URL
            $udyamNumber = $_GET['udyam_number'];

            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "test";

            // Create connection
            $conn = new mysqli($servername, $username, $password, $dbname);

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Sanitize the input to prevent SQL injection
            $udyamNumber = $conn->real_escape_string($udyamNumber);

            // SQL Query to fetch data from msme_nic table
            $msmeNicQuery = "SELECT udyam_number AS 'Udyam Number', enterprise_name AS 'Enterprise Name' FROM msme_nic WHERE udyam_number = '$udyamNumber'";
            $msmeNicResult = $conn->query($msmeNicQuery);

            // Display additional information from msme_nic table
            if ($msmeNicResult && $msmeNicResult->num_rows > 0) {
                echo '<h2>Additional Information for Udyam Number: ' . htmlspecialchars($udyamNumber) . '</h2>';
                echo '<br>';
                echo '<table class="table table-bordered table-striped">';
                $headerPrintedMsmeNic = false;

                while ($row = $msmeNicResult->fetch_assoc()) {
                    if (!$headerPrintedMsmeNic) {
                        echo '<tr>';
                        foreach ($row as $key => $value) {
                            echo '<th class="table-dark">' . htmlspecialchars($key) . '</th>';
                        }
                        echo '</tr>';
                        $headerPrintedMsmeNic = true;
                    }

                    // Output table row with hyperlinks
                    echo '<tr>';
                    foreach ($row as $key => $value) {
                            echo '<td>' . htmlspecialchars($value) . '</td>';
                    }
                    echo '</tr>';
                }

                echo '</table>';
            } else {
                echo '<p>No additional information found for the provided Udyam Number in msme_nic table.</p>';
            }

            // SQL Query to fetch data from nic_code table
            $nicCodeQuery = "SELECT udyam_number AS 'Udyam Number', Nic_2_Digit AS 'Nic 2 digit', Nic_4_Digit AS 'Nic 4 digit', Nic_5_Digit AS 'Nic 5 digit', Activity FROM nic_code WHERE udyam_number = '$udyamNumber'";
            $nicCodeResult = $conn->query($nicCodeQuery);

            // Display the result in a table
            if ($nicCodeResult && $nicCodeResult->num_rows > 0) {
                echo '<h2>Details for Udyam Number: ' . htmlspecialchars($udyamNumber) . '</h2>';
                echo '<br>';
                echo '<table class="table table-bordered table-striped">';
                $headerPrintedNicCode = false;

                while ($row = $nicCodeResult->fetch_assoc()) {
                    if (!$headerPrintedNicCode) {
                        echo '<tr>';
                        foreach ($row as $key => $value) {
                            echo '<th class="table-dark">' . htmlspecialchars($key) . '</th>';
                        }
                        echo '</tr>';
                        $headerPrintedNicCode = true;
                    }

                    // Output table row with hyperlinks
                    echo '<tr>';
                    foreach ($row as $key => $value) {
                            echo '<td>' . htmlspecialchars($value) . '</td>';
                    }
                    echo '</tr>';
                }

                echo '</table>';
            } else {
                echo '<p>No details found for the provided Udyam Number in nic_code table.</p>';
            }

            // Close the connection
            $conn->close();
        } else {
            echo '<p>Udyam Number not provided in the URL.</p>';
        }
        ?>
    </div>
    <br> <br> <br>
    <?php include 'footer.php';?>
</body>
</html>
