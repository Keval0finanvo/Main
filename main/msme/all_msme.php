<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="stylesheet.css">
    <title>Find All MSME</title>
    
</head>
<body>
    <?php include 'nav.php';?>
    <div class="container">
        <?php
            include 'config.php';

            // Number of records per page
            $records_per_page = 20;

            // Get the current page number from the query string
            $page = isset($_GET['page']) ? intval($_GET['page']) : 1;

            // Calculate the offset for the query
            $offset = ($page - 1) * $records_per_page;

            // SQL Query
            $query = "SELECT udyam_number AS 'Udyam Number', enterpris_name AS 'Enterprise Name', city AS 'City' FROM msme_nic LIMIT $offset, $records_per_page";

            $result = $conn->query($query);

            // Display the result in a table
            if ($result && $result->num_rows > 0) {
                echo '<div class="mt-3"><h2>All companies</h2><table class="table table-bordered table-striped">';
                $headerPrinted = false;

                while ($row = $result->fetch_assoc()) {
                    if (!$headerPrinted) {
                        echo '<tr>';
                        foreach ($row as $key => $value) {
                            echo '<th class="table-dark">' . htmlspecialchars($key) . '</th>';
                        }
                        echo '</tr>';
                        $headerPrinted = true;
                    }

                    // Output table rows with hyperlinks
                    echo '<tr>';
                    foreach ($row as $key => $value) {
                        if ($key === 'CIN') {
                            echo '<td><a href="cin_details.php?cin=' . urlencode($value) . '">' . htmlspecialchars($value) . '</a></td>';
                        } else {
                            echo '<td>' . htmlspecialchars($value) . '</td>';
                        }
                    }
                    echo '</tr>';
                }

                echo '</table>';

                // Pagination
                echo "<br>";
                echo "<div id='pagination'>";
                echo "<nav aria-label='Page navigation example'>";
                echo "<ul class='pagination justify-content-center'>";

                // Previous button
                if ($page > 1) {
                    echo "<li class='page-item'><a class='page-link' style='background-color:#645df9;color:#ffffff' href='?page=" . ($page - 1) . "'>Previous</a></li>";
                } else {
                    echo "<li class='page-item disabled'><span class='page-link'>Previous</span></li>";
                }

                // Next button
                echo "<li class='page-item'><a class='page-link'  style='background-color:#645df9;color:#ffffff' href='?page=" . ($page + 1) . "'>Next</a></li>";

                echo "</ul>";
                echo "</nav>";
                echo "</div>";
            } else {
                // No records found, display a message
                echo '<h3>No records available</h3>';
            }

            // Close the database connection
            $conn->close();
        ?>
    </div>
     <br> <br> <br>
    <?php include 'footer.php';?>
</body>
</html>
