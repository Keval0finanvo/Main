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
    <title>MSME by State</title>

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

                        // Fetch distinct states from the database
                        $query = "SELECT DISTINCT state FROM msme_nic";
                        $result = $conn->query($query);

                        // Fetch the states and add them to the $states array
                        while ($row = $result->fetch_assoc()) {
                            $states[] = $row['state'];
                        }

                        // Default state (show all records)
                        $selectedButtonValue = isset($_GET["state"]) ? $_GET["state"] : '';

                        // Pagination variables for the table
                        $tableLimit = 10;
                        $tablePage = isset($_GET['table_page']) ? $_GET['table_page'] : 1;
                        $tableOffset = ($tablePage - 1) * $tableLimit;

                        $tableCurrentPage = isset($_GET['table_page']) ? intval($_GET['table_page']) : 1;

                        // Calculate previous and next page values for the table
                        $tablePrevPage = max(1, $tableCurrentPage - 1);
                        $tableNextPage = $tableCurrentPage + 1;

                        // Pagination variables for the state buttons
                        $stateLimit = 10;
                        $statePage = isset($_GET['state_page']) ? $_GET['state_page'] : 1;
                        $stateOffset = ($statePage - 1) * $stateLimit;

                        // Calculate previous and next page values for the state buttons
                        $statePrevPage = max(1, $statePage - 1);
                        $stateNextPage = $statePage + 1;

                        // Display the table
                        echo '<table class="table table-bordered">';
                        echo '<thead>';
                        echo '<tr>';
                        echo '<th>Udyam Number</th>';
                        echo '<th>Enterprise Name</th>';
                        echo '<th>State</th>';
                        echo '</tr>';
                        echo '</thead>';
                        echo '<tbody>';

                        // Your SQL query for the table
                        $tableSql = "SELECT udyam_number AS 'Udyam Number', enterpris_name AS 'Enterprise Name', state AS 'State' FROM msme_nic";

                        if (!empty($selectedButtonValue)) {
                            $tableSql .= " WHERE State = '$selectedButtonValue'";
                        }

                        $tableSql .= " LIMIT " . ($tableCurrentPage * $tableLimit) . " OFFSET " . (($tableCurrentPage - 1) * $tableLimit);

                        // Execute the query and get $records_on_page
                        $tableRecords = $conn->query($tableSql);

                        foreach ($tableRecords as $row) {
                            echo '<tr>';
                            echo '<td>';
                            echo '<a class="link-opastate-10" href="nic.php?udyam_number=' . urlencode($row['Udyam Number']) . '" style="text-decoration: underline !important; color: blue !important;">';
                            echo $row['Udyam Number'];
                            echo '</a>';
                            echo '</td>';
                            echo '<td>' . $row['Enterprise Name'] . '</td>';
                            echo '<td>' . $row['State'] . '</td>';
                            echo '</tr>';
                        }

                        echo '</tbody>';
                        echo '</table>';

                        // Add Table Pagination
                        echo '<div>';
                        if ($tableCurrentPage > 1) {
                            echo '<a href="?table_page=' . $tablePrevPage . '&state=' . $selectedButtonValue . '" class="btn btn-primary">Previous</a>';
                        }
                        echo '<span style="margin-left:10px;"></span>';
                        echo '<a href="?table_page=' . $tableNextPage . '&state=' . $selectedButtonValue . '" class="btn btn-primary">Next</a>';
                        echo '</div>';
                    ?>
                </div>
            </div>
            <div class="col-12" id='main-col'>
                <form method="get" id="state-form">
                <?php
                    // Display 30 states per page
                    $visiblestates = array_slice($states, $stateOffset, $stateLimit);

                    // Generate buttons dynamically for states
                    foreach ($visiblestates as $state) {
                        echo '<button class="btn btn-primary state-button" style="margin: 5px; background-color:#645df9;color:#ffffff;" name="state" type="submit" value="' . $state . '">' . $state . '</button>';
                    }
                ?>
                </form>
                <br>
                <!-- Add state Pagination -->
                <div>
                    <?php
                        // Add state Pagination
                        if ($statePage > 1) {
                            echo '<a href="?state_page=' . $statePrevPage . '" class="btn btn-primary">Previous states</a>';
                        }
                        echo '<span style="margin-right: 20px;"></span>';
                        echo '<a href="?state_page=' . $stateNextPage . '" class="btn btn-primary">Next states</a>';
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
