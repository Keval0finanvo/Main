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
    <title>Find GST by PinCodes</title>
</head>
<body>
    <?php include 'nav.php';?>

    <div class="container-fluid mt-3">
        <div class="row" id='row'>
            <div class="col-12" id='main-col'>
                <div class="grid-container">
                    <?php
                        // Database connection details
                        include 'config.php';

                        // Fetch distinct pin codes from the database
                        $query = "SELECT DISTINCT addrPncd from gstin";
                        $result = $conn->query($query);

                        // Fetch the pin codes and add them to the $pinCodes array
                        while ($row = $result->fetch_assoc()) {
                            $pinCodes[] = $row['addrPncd'];
                        }

                        // Default pin code (show all records)
                        $selectedButtonValue = isset($_GET["pincode"]) ? $_GET["pincode"] : '';

                        // Pagination variables for the table
                        $tableLimit = 10;
                        $tablePage = isset($_GET['table_page']) ? $_GET['table_page'] : 1;
                        $tableOffset = ($tablePage - 1) * $tableLimit;

                        $tableCurrentPage = isset($_GET['table_page']) ? intval($_GET['table_page']) : 1;

                        // Calculate previous and next page values for the table
                        $tablePrevPage = max(1, $tableCurrentPage - 1);
                        $tableNextPage = $tableCurrentPage + 1;

                        // Pagination variables for the pin code buttons
                        $pinCodeLimit = 30;
                        $pinCodePage = isset($_GET['pincode_page']) ? $_GET['pincode_page'] : 1;
                        $pinCodeOffset = ($pinCodePage - 1) * $pinCodeLimit;

                        // Calculate previous and next page values for the pin code buttons
                        $pinCodePrevPage = max(1, $pinCodePage - 1);
                        $pinCodeNextPage = $pinCodePage + 1;

                        // Display the table
                        echo '<table class="table table-bordered">';
                        echo '<thead>';
                        echo '<tr>';
                        echo '<th>GST</th>';
                        echo '<th>Company Name</th>';
                        echo '<th>REGISTERED OFFICE ADDRESS</th>';
                        '</tr>';
                        echo '</thead>';
                        echo '<tbody>';

                        // Your SQL query for the table
                        $tableSql = "SELECT gstin as 'GST', lgnm as 'Company Name', addrLoc as 'REGISTERED OFFICE ADDRESS' from gstin_bipin.gst_detail";

                        if (!empty($selectedButtonValue)) {
                            $tableSql .= " WHERE addrPncd = '$selectedButtonValue'";
                        }

                        $tableSql .= " LIMIT " . ($tableCurrentPage * $tableLimit) . " OFFSET " . (($tableCurrentPage - 1) * $tableLimit);

                        // Execute the query and get $records_on_page
                        $tableRecords = $conn->query($tableSql);

                        foreach ($tableRecords as $row) {
                            echo '<tr>';
                            echo '<td>';
                            echo '<a class="link-opadist-10" href="gstin_detail.php?gstin=' . urlencode($row['GST']) . '&company_name=' . urlencode(str_replace(' ', '-', $row['Company Name'])) . '" style="text-decoration: underline !important; color: blue !important;">';
                            echo $row['GST'];
                            echo '</a>';
                            echo '</td>';
                            echo '<td>' . $row['Company Name'] . '</td>';
                            echo '<td>' . $row['REGISTERED OFFICE ADDRESS'] . '</td>';
                            echo '</tr>';
                        }

                        echo '</tbody>';
                        echo '</table>';

                        // Add Table Pagination
                        echo '<div>';
                        if ($tableCurrentPage > 1) {
                            echo '<a href="?table_page=' . $tablePrevPage . '&pincode=' . $selectedButtonValue . '" class="btn btn-primary">Previous</a>';
                        }
                        echo '<span style="margin-left:10px"></span>';
                        echo '<a href="?table_page=' . $tableNextPage . '&pincode=' . $selectedButtonValue . '" class="btn btn-primary">Next</a>';
                        echo '</div>';
                    ?>
                </div>
            </div>
            <div class="col-12" id='main-col'>
                <form method="get" id="pincode-form">
                <?php
                    // Display 30 pin codes per page
                    $visiblePinCodes = array_slice($pinCodes, $pinCodeOffset, $pinCodeLimit);

                    // Generate buttons dynamically for pin codes
                    foreach ($visiblePinCodes as $pinCode) {
                        echo '<button class="btn btn-primary pincode-button" style="margin: 5px; background-color:#645df9;color:#ffffff;" name="pincode" type="submit" value="' . $pinCode . '">' . $pinCode . '</button>';
                    }
                ?>
                </form>
                <br>
                <!-- Add Pin Code Pagination -->
                <div>
                    <?php
                        // Add Pin Code Pagination
                        if ($pinCodePage > 1) {
                            echo '<a href="?pincode_page=' . $pinCodePrevPage . '" class="btn btn-primary">Previous Pin Code</a>';
                        }
                        echo '<span style="margin-left:10px"></span>';
                        echo '<a href="?pincode_page=' . $pinCodeNextPage . '" class="btn btn-primary">Next Pin Code</a>';
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
