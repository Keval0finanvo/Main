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
    <title>Companies by roc</title>

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

                        // Fetch distinct rocs from the database
                        $query = "SELECT DISTINCT roc FROM technowire_data.CIN";
                        $result = $conn->query($query);

                        // Fetch the rocs and add them to the $rocs array
                        while ($row = $result->fetch_assoc()) {
                            $rocs[] = $row['roc'];
                        }

                        // Default roc (show all records)
                        $selectedButtonValue = isset($_GET["roc"]) ? $_GET["roc"] : '';

                        // Pagination variables for the table
                        $tableLimit = 10;
                        $tablePage = isset($_GET['table_page']) ? $_GET['table_page'] : 1;
                        $tableOffset = ($tablePage - 1) * $tableLimit;

                        $tableCurrentPage = isset($_GET['table_page']) ? intval($_GET['table_page']) : 1;

                        // Calculate previous and next page values for the table
                        $tablePrevPage = max(1, $tableCurrentPage - 1);
                        $tableNextPage = $tableCurrentPage + 1;

                        // Pagination variables for the roc buttons
                        $rocLimit = 10;
                        $rocPage = isset($_GET['roc_page']) ? $_GET['roc_page'] : 1;
                        $rocOffset = ($rocPage - 1) * $rocLimit;

                        // Calculate previous and next page values for the roc buttons
                        $rocPrevPage = max(1, $rocPage - 1);
                        $rocNextPage = $rocPage + 1;

                        // Display the table
                        echo '<table class="table table-bordered">';
                        echo '<thead>';
                        echo '<tr>';
                        echo '<th>CIN</th>';
                        echo '<th>COMPANY_NAME</th>';
                        echo '<th>REGISTERED_OFFICE_ADDRESS</th>';
                        echo '</tr>';
                        echo '</thead>';
                        echo '<tbody>';

                        // Your SQL query for the table
                        $tableSql = "SELECT cin, COMPANY_NAME, REGISTERED_OFFICE_ADDRESS FROM technowire_data.CIN";

                        if (!empty($selectedButtonValue)) {
                            $tableSql .= " WHERE roc = '$selectedButtonValue'";
                        }

                        $tableSql .= " LIMIT " . ($tableCurrentPage * $tableLimit) . " OFFSET " . (($tableCurrentPage - 1) * $tableLimit);

                        // Execute the query and get $records_on_page
                        $tableRecords = $conn->query($tableSql);

                        foreach ($tableRecords as $row) {
                            echo '<tr>';
                            echo '<td>';
                            echo '<a class="link-opacity-10" href="cin_details.php?cin=' . urlencode($row['cin']) . '&company_name=' . urlencode(str_replace(' ', '-', $row['COMPANY_NAME'])) . '" style="text-decoration: underline !important; color: blue !important;">';
                            echo $row['cin'];
                            echo '</a>';
                            echo '</td>';
                            echo '<td>' . $row['COMPANY_NAME'] . '</td>';
                            echo '<td>' . $row['REGISTERED_OFFICE_ADDRESS'] . '</td>';
                            echo '</tr>';
                        }

                        echo '</tbody>';
                        echo '</table>';

                        // Add Table Pagination
                        echo '<div>';
                        if ($tableCurrentPage > 1) {
                            echo '<a href="?table_page=' . $tablePrevPage . '&roc=' . $selectedButtonValue . '" class="btn btn-primary">Previous</a>';
                        }
                        echo '<span style="margin-right: 20px;"></span>';
                        echo '<a href="?table_page=' . $tableNextPage . '&roc=' . $selectedButtonValue . '" class="btn btn-primary">Next</a>';
                        echo '</div>';
                    ?>
                </div>
            </div>
            <div class="col-12" id='main-col'>
                <form method="get" id="roc-form">
                <?php
                    // Display 30 rocs per page
                    $visiblerocs = array_slice($rocs, $rocOffset, $rocLimit);

                    // Generate buttons dynamically for rocs
                    foreach ($visiblerocs as $roc) {
                        echo '<button class="btn btn-primary roc-button" style="margin: 5px; background-color:#645df9;color:#ffffff;" name="roc" type="submit" value="' . $roc . '">' . $roc . '</button>';
                    }
                ?>
                </form>
                <br>
                <!-- Add roc Pagination -->
                <div>
                    <?php
                    // Add roc Pagination
                    if ($rocPage > 1) {
                        echo '<a href="?roc_page=' . $rocPrevPage . '" class="btn btn-primary">Previous rocs</a>';
                    }
                    echo '<span style="margin-right: 20px;"></span>';
                    echo '<a href="?roc_page=' . $rocNextPage . '" class="btn btn-primary">Next rocs</a>';
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
