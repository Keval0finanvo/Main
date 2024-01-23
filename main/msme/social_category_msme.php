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
    <title>Find MSME by Social Category</title>

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

                        // Fetch distinct dists from the database
                        $query = "SELECT DISTINCT social_category FROM msme_nic";
                        $result = $conn->query($query);

                        // Fetch the dists and add them to the $dists array
                        while ($row = $result->fetch_assoc()) {
                            $dists[] = $row['social_category'];
                        }

                        // Default dist (show all records)
                        $selectedButtonValue = isset($_GET["dist"]) ? $_GET["dist"] : '';

                        // Pagination variables for the table
                        $tableLimit = 10;
                        $tablePage = isset($_GET['table_page']) ? $_GET['table_page'] : 1;
                        $tableOffset = ($tablePage - 1) * $tableLimit;

                        $tableCurrentPage = isset($_GET['table_page']) ? intval($_GET['table_page']) : 1;

                        // Calculate previous and next page values for the table
                        $tablePrevPage = max(1, $tableCurrentPage - 1);
                        $tableNextPage = $tableCurrentPage + 1;

                        // Pagination variables for the dist buttons
                        $distLimit = 30;
                        $distPage = isset($_GET['dist_page']) ? $_GET['dist_page'] : 1;
                        $distOffset = ($distPage - 1) * $distLimit;

                        // Calculate previous and next page values for the dist buttons
                        $distPrevPage = max(1, $distPage - 1);
                        $distNextPage = $distPage + 1;

                        // Display the table
                        echo '<table class="table table-bordered">';
                        echo '<thead>';
                        echo '<tr>';
                        echo '<th>Udyam Number</th>';
                        echo '<th>Enterprise Name</th>';
                        echo '<th>Social Category</th>';
                        echo '</tr>';
                        echo '</thead>';
                        echo '<tbody>';

                        // Your SQL query for the table
                        $tableSql = "SELECT udyam_number AS 'Udyam Number', enterpris_name AS 'Enterprise Name', social_category AS 'Social Category' FROM msme_nic";

                        if (!empty($selectedButtonValue)) {
                            $tableSql .= " WHERE social_category = '$selectedButtonValue'";
                        }

                        $tableSql .= " LIMIT " . ($tableCurrentPage * $tableLimit) . " OFFSET " . (($tableCurrentPage - 1) * $tableLimit);

                        // Execute the query and get $records_on_page
                        $tableRecords = $conn->query($tableSql);

                        foreach ($tableRecords as $row) {
                            echo '<tr>';
                            echo '<td>';
                            echo '<a class="link-opadist-10" href="nic.php?udyam_number=' . urlencode($row['Udyam Number']) . '" style="text-decoration: underline !important; color: blue !important;">';
                            echo $row['Udyam Number'];
                            echo '</a>';
                            echo '</td>';
                            echo '<td>' . $row['Enterprise Name'] . '</td>';
                            echo '<td>' . $row['Social Category'] . '</td>';
                            echo '</tr>';
                        }

                        echo '</tbody>';
                        echo '</table>';

                        // Add Table Pagination
                        echo '<div>';
                        if ($tableCurrentPage > 1) {
                            echo '<a href="?table_page=' . $tablePrevPage . '&social_category=' . $selectedButtonValue . '" class="btn btn-primary">Previous</a>';
                        }
                        echo '<span style="margin-left:10px"></span>';
                        echo '<a href="?table_page=' . $tableNextPage . '&social_category=' . $selectedButtonValue . '" class="btn btn-primary">Next</a>';
                        echo '</div>';
                    ?>
                </div>
            </div>
            <div class="col-12" id='main-col'>
                <form method="get" id="dist-form">
                <?php
                    // Display 30 dists per page
                    $visibledists = array_slice($dists, $distOffset, $distLimit);

                    // Generate buttons dynamically for dists
                    foreach ($visibledists as $dist) {
                        echo '<button class="btn btn-primary dist-button" style="margin: 5px; background-color:#645df9;color:#ffffff;" name="dist" type="submit" value="' . $dist . '">' . $dist . '</button>';
                    }
                ?>
                </form>
                <br>
                <!-- Add dist Pagination -->
                <div>
                    <?php
                        // Add dist Pagination
                        if ($distPage > 1) {
                            echo '<a href="?social_category_page=' . $distPrevPage . '" class="btn btn-primary">Previous</a>';
                        }
                        echo '<span style="margin-left:10px"></span>';
                        echo '<a href="?social_category_page=' . $distNextPage . '" class="btn btn-primary">Next</a>';
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
