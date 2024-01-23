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
    <title>Companies by Status</title>

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

                        // Fetch distinct statuss from the database
                        $query = "SELECT DISTINCT status FROM technowire_data.CIN";
                        $result = $conn->query($query);

                        // Fetch the statuss and add them to the $statuss array
                        while ($row = $result->fetch_assoc()) {
                            $statuss[] = $row['status'];
                        }

                        // Default status (show all records)
                        $selectedButtonValue = isset($_GET["status"]) ? $_GET["status"] : '';

                        // Pagination variables for the table
                        $tableLimit = 10;
                        $tablePage = isset($_GET['table_page']) ? $_GET['table_page'] : 1;
                        $tableOffset = ($tablePage - 1) * $tableLimit;

                        $tableCurrentPage = isset($_GET['table_page']) ? intval($_GET['table_page']) : 1;

                        // Calculate previous and next page values for the table
                        $tablePrevPage = max(1, $tableCurrentPage - 1);
                        $tableNextPage = $tableCurrentPage + 1;

                        // Pagination variables for the status buttons
                        $statusLimit = 10;
                        $statusPage = isset($_GET['status_page']) ? $_GET['status_page'] : 1;
                        $statusOffset = ($statusPage - 1) * $statusLimit;

                        // Calculate previous and next page values for the status buttons
                        $statusPrevPage = max(1, $statusPage - 1);
                        $statusNextPage = $statusPage + 1;

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
                            $tableSql .= " WHERE status = '$selectedButtonValue'";
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
                            echo '<a href="?table_page=' . $tablePrevPage . '&status=' . $selectedButtonValue . '" class="btn btn-primary">Previous</a>';
                        }
                        echo '<span style="margin-right: 20px;"></span>';
                        echo '<a href="?table_page=' . $tableNextPage . '&status=' . $selectedButtonValue . '" class="btn btn-primary">Next</a>';
                        echo '</div>';
                    ?>
                </div>
            </div>
            <div class="col-12" id='main-col'>
                <form method="get" id="status-form">
                <?php
                    // Display 30 statuss per page
                    $visiblestatuss = array_slice($statuss, $statusOffset, $statusLimit);

                    // Generate buttons dynamically for statuss
                    foreach ($visiblestatuss as $status) {
                        echo '<button class="btn btn-primary status-button" style="margin: 5px; background-color:#645df9;color:#ffffff;" name="status" type="submit" value="' . $status . '">' . $status . '</button>';
                    }
                ?>
                </form>
                <br>
                <!-- Add status Pagination -->
                <div>
                    <?php
                    // Add status Pagination
                    if ($statusPage > 1) {
                        echo '<a href="?status_page=' . $statusPrevPage . '" class="btn btn-primary">Previous statuses</a>';
                    }
                    echo '<span style="margin-right: 20px;"></span>';
                    echo '<a href="?status_page=' . $statusNextPage . '" class="btn btn-primary">Next statuses</a>';
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
