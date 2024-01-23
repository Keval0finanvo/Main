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
    <link rel="stylesheet" type="text/css" href="stylesheet.css">
    <title>Find IEC by cities</title>

</head>
<body>
    <?php include 'nav.php';?>

    <div class="container-fluid mt-3">
        <div class="row" id='row'>
            <div class="col-12" id='main-col'>
                <div class="grid-container">
                <?php
                include 'config.php';
                $pincodes  = array(); // Change variable name to $pincodes

                // Fetch distinct pincodes from the database
                $query = "SELECT DISTINCT c.pincode
                        FROM company c
                        INNER JOIN iec i ON c.company_name = i.Firm_Name";
                $result = $conn->query($query);

                // Fetch the pincodes and add them to the $pincodes array
                while ($row = $result->fetch_assoc()) {
                    $pincodes[] = $row['pincode'];
                }

                // Default pincode (show all records)
                $selectedButtonValue = isset($_GET["pincode"]) ? $_GET["pincode"] : '';

                // Pagination variables for the table
                $tableLimit = 10;
                $tablePage = isset($_GET['table_page']) ? $_GET['table_page'] : 1;
                $tableOffset = ($tablePage - 1) * $tableLimit;

                $tableCurrentPage = isset($_GET['table_page']) ? intval($_GET['table_page']) : 1;

                // Calculate previous and next page values for the table
                $tablePrevPage = max(1, $tableCurrentPage - 1);
                $tableNextPage = $tableCurrentPage + 1;

                // Pagination variables for the pincode buttons
                $pincodeLimit = 10;
                $pincodePage = isset($_GET['pincode_page']) ? $_GET['pincode_page'] : 1;
                $pincodeOffset = ($pincodePage - 1) * $pincodeLimit;

                // Calculate previous and next page values for the pincode buttons
                $pincodePrevPage = max(1, $pincodePage - 1);
                $pincodeNextPage = $pincodePage + 1;

                // Display the table
                echo '<table class="table table-bordered">';
                echo '<thead>';
                echo '<tr>';
                echo '<th>IEC</th>';
                echo '<th>Firm Name</th>';
                echo '<th>Address</th>';
                echo '</tr>';
                echo '</thead>';
                echo '<tbody>';

                // Your SQL query for the table
                $tableSql = "SELECT iec.IEC_Number AS 'IEC', iec.Firm_Name  AS 'Firm Name', iec.iec_Address AS 'Address'FROM iec
                            WHERE iec.Firm_Name IN (
                                SELECT company.company_name
                                FROM company
                                WHERE company.pincode = '$selectedButtonValue'
                            )";

                if (!empty($selectedButtonValue)) {
                    $tableSql .= " AND iec.Firm_Name IN (
                                    SELECT company.company_name
                                    FROM company
                                    WHERE company.pincode = '$selectedButtonValue'
                                )";
                }

                $tableSql .= " LIMIT " . ($tableCurrentPage * $tableLimit) . " OFFSET " . (($tableCurrentPage - 1) * $tableLimit);

                // Execute the query and get $records_on_page
                $tableRecords = $conn->query($tableSql);

                foreach ($tableRecords as $row) {
                    echo '<tr>';
                    echo '<td>';
                    echo $row['IEC'];
                    echo '</td>';
                    echo '<td>' . $row['Firm Name'] . '</td>';
                    echo '<td>' . $row['Address'] . '</td>';
                    echo '</tr>';
                }

                echo '</tbody>';
                echo '</table>';

                // Add Table Pagination
                echo '<div>';
                if ($tableCurrentPage > 1) {
                    echo '<a href="?table_page=' . $tablePrevPage . '&pincode=' . $selectedButtonValue . '" class="btn btn-primary">Previous</a>';
                }
                echo '<span style="margin-left:10px;"></span>';
                echo '<a href="?table_page=' . $tableNextPage . '&pincode=' . $selectedButtonValue . '" class="btn btn-primary">Next</a>';
                echo '</div>';
                ?>

                </div>
            </div>
            <div class="col-12" id='main-col'>
                <form method="get" id="pincode-form">
                    <?php
                    // Display 30 pincodes per page
                    $visiblePincodes = array_slice($pincodes, $pincodeOffset, $pincodeLimit);

                    // Generate buttons dynamically for pincodes
                    foreach ($visiblePincodes as $pincode) {
                        echo '<button class="btn btn-primary pincode-button" style="margin: 5px; background-color:#645df9;color:#ffffff;" name="pincode" type="submit" value="' . $pincode . '">' . $pincode . '</button>';
                    }
                    ?>
                </form>
                <br>
                <!-- Add City Pagination -->
                <div>
                    <?php
                    // Add Pincode Pagination
                    if ($pincodePage > 1) {
                        echo '<a href="?pincode_page=' . $pincodePrevPage . '" class="btn btn-primary">Previous Pincodes</a>';
                    }
                    echo '<span style="margin-left:10px;"></span>';
                    echo '<a href="?pincode_page=' . $pincodeNextPage . '" class="btn btn-primary">Next Pincodes</a>';
                    ?>
                </div>
                <br>
            </div>
        </div>
    </div>
    <br> <br> <br>
    <?php include 'footer.php';?>
</body>
</html>
