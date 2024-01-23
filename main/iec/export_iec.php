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
    <title>Find Companies by Category of Exporters</title>

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

                        // Fetch distinct categories of exporters from the database
                        $query = "SELECT DISTINCT Category_of_Exporters FROM iec";
                        $result = $conn->query($query);

                        // Fetch the categories and add them to the $categories array
                        while ($row = $result->fetch_assoc()) {
                            $categories[] = $row['Category_of_Exporters'];
                        }

                        // Default category (show all records)
                        $selectedButtonValue = isset($_GET["category"]) ? $_GET["category"] : '';

                        // Pagination variables for the table
                        $tableLimit = 10;
                        $tablePage = isset($_GET['table_page']) ? $_GET['table_page'] : 1;
                        $tableOffset = ($tablePage - 1) * $tableLimit;

                        $tableCurrentPage = isset($_GET['table_page']) ? intval($_GET['table_page']) : 1;

                        // Calculate previous and next page values for the table
                        $tablePrevPage = max(1, $tableCurrentPage - 1);
                        $tableNextPage = $tableCurrentPage + 1;

                        // Pagination variables for the category buttons
                        $categoryLimit = 30;
                        $categoryPage = isset($_GET['category_page']) ? $_GET['category_page'] : 1;
                        $categoryOffset = ($categoryPage - 1) * $categoryLimit;

                        // Calculate previous and next page values for the category buttons
                        $categoryPrevPage = max(1, $categoryPage - 1);
                        $categoryNextPage = $categoryPage + 1;

                        // Display the table
                        echo '<table class="table table-bordered">';
                        echo '<thead>';
                        echo '<tr>';
                        echo '<th>IEC</th>';
                        echo '<th>Firm Name</th>';
                        echo '<th>Address</th>';
                        echo '<th>Category of Exporters</th>';
                        echo '</tr>';
                        echo '</thead>';
                        echo '<tbody>';

                        // Your SQL query for the table
                        $tableSql = "SELECT IEC_Number AS 'IEC', Firm_Name  AS 'Firm Name', iec_Address AS 'Address' , Category_of_Exporters as 'Category of Exporters' FROM iec ";

                        if (!empty($selectedButtonValue)) {
                            $tableSql .= " WHERE Category_of_Exporters = '$selectedButtonValue'";
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
                            echo '<td>' . $row['Category of Exporters'] . '</td>';
                            echo '</tr>';
                        }

                        echo '</tbody>';
                        echo '</table>';

                        // Add Table Pagination
                        echo '<div>';
                        if ($tableCurrentPage > 1) {
                            echo '<a href="?table_page=' . $tablePrevPage . '&category=' . $selectedButtonValue . '" class="btn btn-primary">Previous</a>';
                        }
                        echo '<span style="margin-left:10px"></span>';
                        echo '<a href="?table_page=' . $tableNextPage . '&category=' . $selectedButtonValue . '" class="btn btn-primary">Next</a>';
                        echo '</div>';
                    ?>
                </div>
            </div>
            <div class="col-12" id='main-col'>
                <form method="get" id="category-form">
                <?php
                    // Display 30 categories per page
                    $visibleCategories = array_slice($categories, $categoryOffset, $categoryLimit);

                    // Generate buttons dynamically for categories
                    foreach ($visibleCategories as $category) {
                        echo '<button class="btn btn-primary category-button" style="margin: 5px; background-color:#645df9;color:#ffffff;" name="category" type="submit" value="' . $category . '">' . $category . '</button>';
                    }
                ?>
                </form>
                <br>
                <!-- Add category Pagination -->
                <div>
                    <?php
                        // Add category Pagination
                        if ($categoryPage > 1) {
                            echo '<a href="?category_page=' . $categoryPrevPage . '" class="btn btn-primary">Previous</a>';
                        }
                        echo '<span style="margin-left:10px"></span>';
                        echo '<a href="?category_page=' . $categoryNextPage . '" class="btn btn-primary">Next</a>';
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
