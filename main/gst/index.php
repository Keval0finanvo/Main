<?php
// Start the session
session_start();

include 'config.php';

function handleQueryError($result)
{
    global $conn;
    if (!$result) {
        die("Query failed: " . $conn->error);
    }
}

function sanitizeIntInput($input)
{
    return isset($input) ? intval($input) : 0;
}

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

//To get statecode and statename
$stateName = isset($_SESSION['stateName']) ? htmlspecialchars($_SESSION['stateName']) : "";
$stateCode = isset($_SESSION['stateCode']) ? $_SESSION['stateCode'] : "";

$sql = "SELECT stateCode, stj from gstin_bipin.gst_detail";
$result = $conn->query($sql);
handleQueryError($result);


?>

<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Search GST number</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <?php echo '<meta name="stateName" content="' . $stateName . '" />'; ?>
    <?php echo '<meta name="stateCode" content="' . $stateCode . '" />'; ?>
    
    <link rel="stylesheet" type="text/css" href="stylesheet.css">

</head>

    <body>
    <?php include 'nav.php';?>

    <section id="about_us">
        <div class="container mt-3" style="background-color:white; margin-bottom:10px;">
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

            <li><b>Month and Year-wise Search:</b> Dive into historical data by searching for companies based on their registration month and year. This feature enables users to track the growth and establishment of businesses over time.</li>
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
    <br><br><br><br>
    <?php include 'footer.php';?>
    </body>
</html>
