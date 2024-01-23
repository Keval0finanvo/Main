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
    <style>
        .grid-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
        }

        .grid-item {
            margin: 5px;
            background-color: #f1f1f1;
            text-align: center;
            padding: 20px;
            font-size: 16px;
            border-radius: 5px;
        }
    </style>
    <title>GST Numbers with Pagination</title>
    <script>
        function updateFormAction(buttonValue, buttonName) {
            var form = document.getElementById('second-state-form');
            var action = form.getAttribute('action');
            var updatedAction = action.split('?')[0] + '?stateName=' + encodeURIComponent(buttonName) +
                '&stateCode=' + encodeURIComponent(buttonValue) + '&submit_get=';
            form.setAttribute('action', updatedAction);
        }
    </script>
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

                        // Fetch distinct state codes from the database
                        $query = "SELECT DISTINCT stateCode FROM gstin_bipin.gst_detail";
                        $result = $conn->query($query);

                        // Fetch the state codes and add them to the $stateCodes array
                        while ($row = $result->fetch_assoc()) {
                            $stateCodes[] = $row['stateCode'];
                        }

                        // Default state code (show all records)
                        $selectedButtonValue = isset($_GET["statecode"]) ? $_GET["statecode"] : '';

                        // Pagination variables for the table
                        $tableLimit = 10;
                        $tablePage = isset($_GET['table_page']) ? $_GET['table_page'] : 1;
                        $tableOffset = ($tablePage - 1) * $tableLimit;

                        $tableCurrentPage = isset($_GET['table_page']) ? intval($_GET['table_page']) : 1;

                        // Calculate previous and next page values for the table
                        $tablePrevPage = max(1, $tableCurrentPage - 1);
                        $tableNextPage = $tableCurrentPage + 1;

                        // Pagination variables for the state code buttons
                        $stateCodeLimit = 30;
                        $stateCodePage = isset($_GET['statecode_page']) ? $_GET['statecode_page'] : 1;
                        $stateCodeOffset = ($stateCodePage - 1) * $stateCodeLimit;

                        // Calculate previous and next page values for the state code buttons
                        $stateCodePrevPage = max(1, $stateCodePage - 1);
                        $stateCodeNextPage = $stateCodePage + 1;

                        // Define the array of state codes and their corresponding names
                        $states = array(
                            '01' => 'Jammu and Kashmir',
                            '02' => 'Himachal Pradesh',
                            '03' => 'Punjab',
                            '04' => 'Chandigarh',
                            '05' => 'Uttarakhand',
                            '06' => 'Haryana',
                            '07' => 'Delhi',
                            '08' => 'Rajasthan',
                            '09' => 'Uttar Pradesh',
                            '10' => 'Bihar',
                            '11' => 'Sikkim',
                            '12' => 'Arunachal Pradesh',
                            '13' => 'Nagaland',
                            '14' => 'Manipur',
                            '15' => 'Mizoram',
                            '16' => 'Tripura',
                            '17' => 'Meghalaya',
                            '18' => 'Assam',
                            '19' => 'West Bengal',
                            '20' => 'Jharkhand',
                            '21' => 'Orissa',
                            '22' => 'Chhattisgarh',
                            '23' => 'Madhya Pradesh',
                            '24' => 'Gujarat',
                            '25' => 'Daman and Diu',
                            '26' => 'Dadra and Nagar Haveli',
                            '27' => 'Maharashtra',
                            '28' => 'Andhra Pradesh (Old)',
                            '29' => 'Karnataka',
                            '30' => 'Goa',
                            '31' => 'Lakshadweep',
                            '32' => 'Kerala',
                            '33' => 'Tamil Nadu',
                            '34' => 'Puducherry',
                            '35' => 'Andaman and Nicobar Islands',
                            '36' => 'Telangana',
                            '37' => 'Andhra Pradesh (New)'
                        );

                        // Display the table
                        echo '<table class="table table-bordered">';
                        echo '<thead>';
                        echo '<tr>';
                        echo '<th>GST</th>';
                        echo '<th>Company Name</th>';
                        echo '<th>REGISTERED OFFICE ADDRESS</th>';
                        echo '</tr>';
                        echo '</thead>';
                        echo '<tbody>';

                        // Your SQL query for the table
                        $tableSql = "SELECT gstin as 'GST', lgnm as 'Company Name', addrLoc as 'REGISTERED OFFICE ADDRESS' FROM gstin_bipin.gst_detail";

                        if (!empty($selectedButtonValue)) {
                            if ($selectedButtonValue === 'Other') {
                                $tableSql .= " WHERE stateCode NOT IN ('" . implode("', '", array_keys($states)) . "')";
                            } else {
                                $tableSql .= " WHERE stateCode = '$selectedButtonValue'";
                            }
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
                            echo '<a href="?table_page=' . $tablePrevPage . '&statecode=' . $selectedButtonValue . '" class="btn btn-primary">Previous</a>';
                        }
                        echo '<span style="margin-left:10px"></span>';
                        echo '<a href="?table_page=' . $tableNextPage . '&statecode=' . $selectedButtonValue . '" class="btn btn-primary">Next</a>';
                        echo '</div>';
                    ?>
                </div>
            </div>
            <div class="col-12" id='main-col'>
                <form method="get" id="statecode-form">
                    <?php
                        // Display 30 state codes per page
                        $visibleStateCodes = array_slice($stateCodes, $stateCodeOffset, $stateCodeLimit);

                        // Generate buttons dynamically for state codes
                        foreach ($visibleStateCodes as $stateCode) {
                            $stateName = isset($states[$stateCode]) ? $states[$stateCode] : "Other state";

                            // Skip "Other state" in the loop
                            if ($stateName !== "Other state") {
                                echo '<button class="btn btn-primary statecode-button" style="margin: 5px; background-color:#645df9;color:#ffffff;" name="statecode" type="submit" value="' . $stateCode . '">' . $stateName . '</button>';
                            }
                        }
                    ?>
                </form>


                <br>
                <!-- Add State Code Pagination -->
                <div>
                    <?php
                        // Add State Code Pagination
                        if ($stateCodePage > 1) {
                            echo '<a href="?statecode_page=' . $stateCodePrevPage . '" class="btn btn-primary">Previous State Code</a>';
                        }
                        echo '<span style="margin-left:10px"></span>';
                        echo '<a href="?statecode_page=' . $stateCodeNextPage . '" class="btn btn-primary">Next State Code</a>';
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
