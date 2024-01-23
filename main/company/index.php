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
    <title>GST Numbers with Pagination</title>

</head>
<style></style>

<body>
     <?php include 'nav.php';?>
    <section id="about_us">
        <div class="container mt-3" style="background-color:white;margin-bottom:100px;">
            <h2>About Us</h2>
            <p>Welcome to our comprehensive company search platform, where we empower users to effortlessly explore and discover information about companies across various parameters. Our user-friendly interface allows you to search for companies based on specific criteria such as <b>state</b>, <b>pin code</b>, <b>city</b>, <b>Registrar of Companies (ROC)</b>, and <b>status</b>.</p>

            <p><b>Key Features:</b></p>

            <ol>
            <li><b>State-wise Search:</b> Locate companies based on their registered state, providing you with a convenient way to filter and find businesses in a particular region.</li>

            <li><b>Pin Code Search:</b> Narrow down your search by entering the postal code (pin code) associated with a company, ensuring precise and accurate results.</li>
            
            <li><b>City Search:</b> Explore companies within specific cities, helping you focus on businesses in your desired location.</li>

            <li><b>ROC Search:</b> Find companies registered under a particular Registrar of Companies (ROC), streamlining your search based on administrative divisions.</li>

            <li><b>Status-based Search:</b> Filter companies based on their current status, allowing you to identify active, inactive, or dissolved businesses.</li>

            <li><b>Newly Registered Companies:</b> Stay updated on the latest additions to the business landscape by accessing information on newly registered companies.</li>

            </ol>

            <p><b>How to Use:</b></p>

            <p>Simply input your desired search criteria into our intuitive search bar, and our platform will generate a comprehensive list of companies that match your specifications. Explore company profiles, view their current status, and access relevant details effortlessly.</p>

            <p>Explore about companiesin details on the service tabFrom state and city-wise searches to ROC and status-based filters, discover all companies. Search by month and find newly registered businesses.</p>
            <p>At our core, we strive to provide a seamless and user-friendly experience, ensuring that you have access to the information you need to make informed decisions.</p>

            <p>Thank you for choosing our platform for your company search needs. We are committed to continuously enhancing our services to offer you the best possible experience.</p>

            <p><i>Discover. Explore. Stay Informed.</i></p>
            <br>

        </div>
    </section>
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
            $query = "SELECT CIN, COMPANY_NAME, DATE_OF_REGISTRATION, REGISTERED_OFFICE_ADDRESS FROM technowire_data.CIN LIMIT $offset, $records_per_page";
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
                            $companyNameForURL = str_replace(' ', '-', $row['COMPANY_NAME']);
                            echo '<td><a href="cin_details.php?cin=' . urlencode($value) . '&company_name=' . urlencode($companyNameForURL) . '">' . htmlspecialchars($value) . '</a></td>';
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
    <?php include 'footer.php';?>
</body>
</html>
