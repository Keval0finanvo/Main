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
    <title>Find Companies by Nature of concern firm</title>

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

                        // Fetch distinct nature of concern firms from the database
                        $query = "SELECT DISTINCT Nature_of_concern_firm FROM iec";
                        $result = $conn->query($query);

                        // Fetch the nature of concern firms and add them to the $natureOfConcernFirms array
                        while ($row = $result->fetch_assoc()) {
                            $natureOfConcernFirms[] = $row['Nature_of_concern_firm'];
                        }

                        // Default nature of concern firm (show all records)
                        $selectedButtonValue = isset($_GET["nature_of_concern_firm"]) ? $_GET["nature_of_concern_firm"] : '';

                        // Pagination variables for the table
                        $tableLimit = 10;
                        $tablePage = isset($_GET['table_page']) ? $_GET['table_page'] : 1;
                        $tableOffset = ($tablePage - 1) * $tableLimit;

                        $tableCurrentPage = isset($_GET['table_page']) ? intval($_GET['table_page']) : 1;

                        // Calculate previous and next page values for the table
                        $tablePrevPage = max(1, $tableCurrentPage - 1);
                        $tableNextPage = $tableCurrentPage + 1;

                        // Pagination variables for the nature of concern firm buttons
                        $natureOfConcernFirmLimit = 30;
                        $natureOfConcernFirmPage = isset($_GET['nature_of_concern_firm_page']) ? $_GET['nature_of_concern_firm_page'] : 1;
                        $natureOfConcernFirmOffset = ($natureOfConcernFirmPage - 1) * $natureOfConcernFirmLimit;

                        // Calculate previous and next page values for the nature of concern firm buttons
                        $natureOfConcernFirmPrevPage = max(1, $natureOfConcernFirmPage - 1);
                        $natureOfConcernFirmNextPage = $natureOfConcernFirmPage + 1;

                        // Display the table
                        echo '<table class="table table-bordered">';
                        echo '<thead>';
                        echo '<tr>';
                        echo '<th>IEC</th>';
                        echo '<th>Firm Name</th>';
                        echo '<th>Address</th>';
                        echo '<th>Nature of concern firm</th>';
                        echo '</tr>';
                        echo '</thead>';
                        echo '<tbody>';

                        // Your SQL query for the table
                        $tableSql = "SELECT IEC_Number AS 'IEC', Firm_Name  AS 'Firm Name', iec_Address AS 'Address' , Nature_of_concern_firm as 'Nature of concern firm' FROM iec ";

                        if (!empty($selectedButtonValue)) {
                            $tableSql .= " WHERE Nature_of_concern_firm = '$selectedButtonValue'";
                        }

                        $tableSql .= " LIMIT " . ($tableCurrentPage * $tableLimit) . " OFFSET " . (($tableCurrentPage - 1) * $tableLimit);

                        // Execute the query and get $records_on_page
                        $tableRecords = $conn->query($tableSql);

                        foreach ($tableRecords as $row) {
                            echo '<tr>';
                            echo '<td>';
                            echo '<a class="link-opadist-10" href="nic.php?udyam_number=' . urlencode($row['IEC']) . '" style="text-decoration: underline !important; color: blue !important;">';
                            echo $row['IEC'];
                            echo '</td>';
                            echo '<td>' . $row['Firm Name'] . '</td>';
                            echo '<td>' . $row['Address'] . '</td>';
                            echo '<td>' . $row['Nature of concern firm'] . '</td>';
                            echo '</tr>';
                        }

                        echo '</tbody>';
                        echo '</table>';

                        // Add Table Pagination
                        echo '<div>';
                        if ($tableCurrentPage > 1) {
                            echo '<a href="?table_page=' . $tablePrevPage . '&nature_of_concern_firm=' . $selectedButtonValue . '" class="btn btn-primary">Previous</a>';
                        }
                        echo '<span style="margin-left:10px"></span>';
                        echo '<a href="?table_page=' . $tableNextPage . '&nature_of_concern_firm=' . $selectedButtonValue . '" class="btn btn-primary">Next</a>';
                        echo '</div>';
                    ?>
                </div>
            </div>
            <div class="col-12" id='main-col'>
                <form method="get" id="nature-of-concern-firm-form">
                <?php
                    // Display 30 nature of concern firms per page
                    $visibleNatureOfConcernFirms = array_slice($natureOfConcernFirms, $natureOfConcernFirmOffset, $natureOfConcernFirmLimit);

                    // Generate buttons dynamically for nature of concern firms
                    foreach ($visibleNatureOfConcernFirms as $natureOfConcernFirm) {
                        echo '<button class="btn btn-primary nature-of-concern-firm-button" style="margin: 5px; background-color:#645df9;color:#ffffff;" name="nature_of_concern_firm" type="submit" value="' . $natureOfConcernFirm . '">' . $natureOfConcernFirm . '</button>';
                    }
                ?>
                </form>
                <br>
                <!-- Add nature of concern firm Pagination -->
                <div>
                    <?php
                        // Add nature of concern firm Pagination
                        if ($natureOfConcernFirmPage > 1) {
                            echo '<a href="?nature_of_concern_firm_page=' . $natureOfConcernFirmPrevPage . '" class="btn btn-primary">Previous</a>';
                        }
                        echo '<span style="margin-left:10px"></span>';
                        echo '<a href="?nature_of_concern_firm_page=' . $natureOfConcernFirmNextPage . '" class="btn btn-primary">Next</a>';
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
