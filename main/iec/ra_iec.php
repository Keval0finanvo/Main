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
    <title>Find Companies by RA Office</title>

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

                        // Fetch distinct RA Offices from the database
                        $query = "SELECT DISTINCT DGFT_RA_Office FROM iec";
                        $result = $conn->query($query);

                        // Fetch the RA Offices and add them to the $raOffices array
                        while ($row = $result->fetch_assoc()) {
                            $raOffices[] = $row['DGFT_RA_Office'];
                        }

                        // Default RA Office (show all records)
                        $selectedButtonValue = isset($_GET["ra_office"]) ? $_GET["ra_office"] : '';

                        // Pagination variables for the table
                        $tableLimit = 10;
                        $tablePage = isset($_GET['table_page']) ? $_GET['table_page'] : 1;
                        $tableOffset = ($tablePage - 1) * $tableLimit;

                        $tableCurrentPage = isset($_GET['table_page']) ? intval($_GET['table_page']) : 1;

                        // Calculate previous and next page values for the table
                        $tablePrevPage = max(1, $tableCurrentPage - 1);
                        $tableNextPage = $tableCurrentPage + 1;

                        // Pagination variables for the RA Office buttons
                        $raOfficeLimit = 30;
                        $raOfficePage = isset($_GET['ra_office_page']) ? $_GET['ra_office_page'] : 1;
                        $raOfficeOffset = ($raOfficePage - 1) * $raOfficeLimit;

                        // Calculate previous and next page values for the RA Office buttons
                        $raOfficePrevPage = max(1, $raOfficePage - 1);
                        $raOfficeNextPage = $raOfficePage + 1;

                        // Display the table
                        echo '<table class="table table-bordered">';
                        echo '<thead>';
                        echo '<tr>';
                        echo '<th>IEC</th>';
                        echo '<th>Firm Name</th>';
                        echo '<th>Address</th>';
                        echo '<th>RA Office</th>';
                        echo '</tr>';
                        echo '</thead>';
                        echo '<tbody>';

                        // Your SQL query for the table
                        $tableSql = "SELECT IEC_Number AS 'IEC', Firm_Name  AS 'Firm Name', iec.iec_Address AS 'Address' , DGFT_RA_Office as 'RA Office' FROM iec ";

                        if (!empty($selectedButtonValue)) {
                            $tableSql .= " WHERE DGFT_RA_Office = '$selectedButtonValue'";
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
                            echo '<td>' . $row['RA Office'] . '</td>';
                            echo '</tr>';
                        }

                        echo '</tbody>';
                        echo '</table>';

                        // Add Table Pagination
                        echo '<div>';
                        if ($tableCurrentPage > 1) {
                            echo '<a href="?table_page=' . $tablePrevPage . '&ra_office=' . $selectedButtonValue . '" class="btn btn-primary">Previous</a>';
                        }
                        echo '<span style="margin-left:10px"></span>';
                        echo '<a href="?table_page=' . $tableNextPage . '&ra_office=' . $selectedButtonValue . '" class="btn btn-primary">Next</a>';
                        echo '</div>';
                    ?>
                </div>
            </div>
            <div class="col-12" id='main-col'>
                <form method="get" id="ra-office-form">
                <?php
                    // Display 30 RA Offices per page
                    $visibleRaOffices = array_slice($raOffices, $raOfficeOffset, $raOfficeLimit);

                    // Generate buttons dynamically for RA Offices
                    foreach ($visibleRaOffices as $raOffice) {
                        echo '<button class="btn btn-primary ra-office-button" style="margin: 5px; background-color:#645df9;color:#ffffff;" name="ra_office" type="submit" value="' . $raOffice . '">' . $raOffice . '</button>';
                    }
                ?>
                </form>
                <br>
                <!-- Add RA Office Pagination -->
                <div>
                    <?php
                        // Add RA Office Pagination
                        if ($raOfficePage > 1) {
                            echo '<a href="?ra_office_page=' . $raOfficePrevPage . '" class="btn btn-primary">Previous</a>';
                        }
                        echo '<span style="margin-left:10px"></span>';
                        echo '<a href="?ra_office_page=' . $raOfficeNextPage . '" class="btn btn-primary">Next</a>';
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
