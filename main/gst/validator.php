<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        rel="stylesheet"
    />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GST/PAN Checker</title>
</head>
<body>
<style>
    .table-bordered {
        border: 3px solid #dee2e6;
    }
    .container{
          background-color:white;
            padding:20px;
            border-radius:10px;
    }
        html {
            position: relative;
            min-height: 100%;
        }
        body{
            background-color:#f0f2f8;
            margin-bottom: 60px;
        }
        footer {
            background: white;
            position: absolute;
            bottom: 0;
            left: 50%; /* Center the footer horizontally */
            transform: translateX(-50%); /* Adjust for the width of the footer */
            height: auto;
            width: 100%;
            padding: 10px; /* Adjust padding as needed */
            overflow:hidden;

        }
</style>
<?php include 'nav.php';?>
<div class="container mt-5">
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group">
            <label for="inputValue">Enter GST or PAN:</label>
            <input type="text" class="form-control" id="inputValue" name="inputValue"required>
        </div>
        <br>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
    <br>
    <?php
    // Check if form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve input value
        $inputValue = $_POST["inputValue"];

        // Validate input (You may need to add more validation based on your requirements)
        if (!empty($inputValue)) {
            // Connect to your database (replace with your actual database credentials)
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "test";

            $conn = new mysqli($servername, $username, $password, $dbname);

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Prepare and execute the query
            $query = "SELECT gstin, lgnm as 'Company name', stj, stateCode from gstin_bipin.gst_detail WHERE gstin = '$inputValue' OR pan = '$inputValue'";
            $result = $conn->query($query);

            // Display results or no data message
            if ($result->num_rows > 0) {
                echo "<table border='1' class='table table-bordered'>
                        <tr>
                            <th>GSTIN</th>
                            <th>Company name</th>
                            <th>STJ</th>
                            <th>State Code</th>
                        </tr>";

                // Output data of each row
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td><a class='link-opacity-10' href='gstin_detail.php?gstin=" . urlencode($row['gstin']) . "&company_name=" . urlencode(str_replace(' ', '-', $row['Company name'])) . "' style='text-decoration: underline !important; color: blue !important;'>" . $row["gstin"] . "</a></td>
                            <td>" . $row["Company name"] . "</td>
                            <td>" . $row["stj"] . "</td>
                            <td>" . $row["stateCode"] . "</td>
                        </tr>";
                }

                echo "</table>";
            }
            else {
                echo "No data found.";
            }

            // Close database connection
            $conn->close();
        } else {
            echo "Please enter a GST number or PAN number.";
        }
    }
    ?>
</div>
    <br><br><br><br><br><br><br><br><br><br>
    <footer>
    <div class="row">
        <div class="col">
            <p>Browse 20+ Lakhs Indian Companies</p>
            <p>Need help? E-mail us at support@finanvo.in</p>
            <p>API Docs | Down Docs</p>
            <p>Terms & Privacy & About Us</p>
        </div>
        <div class="col">
            <h4>Services</h4>
            <li><a class href="index.php">Home</a></li>
            <li><a class href="GST_Number_List.php">GST Number List</a></li>
            <li><a class href="validator.php">GST Validator</a></li>
        </div>
        <div class="col">
            <h4>Contact Us</h4>
            <p>Sales: +91 9537 33 0099</p>
            <p>Support: +91 9537 33 0099</p>
            <h4>Social media</h4>
        </div>
    </div>
    </footer>
</body>
</html>
