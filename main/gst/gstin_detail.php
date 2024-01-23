<?php
session_start();

// Function to sanitize user input
function sanitizeInput($input) {
    return htmlspecialchars(trim($input));
}

// Include the database connection configuration
include 'config.php';

// Extract GSTIN from the URL
$gstinFromUrl = isset($_GET['gstin']) ? sanitizeInput($_GET['gstin']) : '';

// Get the referring URL
$referrer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php';

// Prepare and execute the SQL query for GSTIN
$sql = "SELECT gstin AS 'GST', lgnm AS 'Company Name', nba AS 'Nature_of_Business', mandatedeInvoice AS 'E_invoice_Applicable', aggreTurnOverFY AS 'Aggregate_Turnover_FY', lgnm AS 'Legal_Name', dty AS 'Taxpayer_Type', aggreTurnOver AS 'Aggregate_Turnover_FY', gtiFY AS 'Gross_Total_income_FY', rgdt AS 'Registration_Date', ctb AS 'Type_of_Company', sts AS 'Status', tradeNam AS 'Trade_Name', isFieldVisitConducted AS 'FieldVisit_Conducted', ctj AS 'Commissionerate', percentTaxInCash AS 'Percent_Tax_paid_in_Cash', gti AS 'Gross_Total_Income', stj AS 'State_Jurisdiction', mbr AS 'Members', update_time AS 'Last_Updated_at' from gstin_bipin.gst_detail WHERE (gstin != '' AND gstin = ?) OR (pan != '' AND pan = ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $gstinFromUrl, $gstinFromUrl);
$stmt->execute();
$result = $stmt->get_result();

// Output data in the specified HTML format
$output = '<div class="mt-3"><h2>Result</h2><div class="row">';

$rows = $result->fetch_all(MYSQLI_ASSOC);

$aboutUs = '';
foreach ($rows as $row) {
    $aboutUs .= "<p>Welcome to {$row['Company Name']}, a {$row['Type_of_Company']} based in {$row['State_Jurisdiction']}, India. Established on {$row['Registration_Date']}, we are actively involved in {$row['Nature_of_Business']}.</p>";
    $aboutUs .= "<p>Our legal name is {$row['Legal_Name']}, and our GSTIN is {$row['GST']}. We operate with a taxpayer type of {$row['Taxpayer_Type']} and fall under the status of {$row['Status']}.</p>";
    $aboutUs .= "<p>Our aggregate turnover for the fiscal year {$row['Aggregate_Turnover_FY']} is {$row['Aggregate_Turnover_FY']}.</p>";
    $aboutUs .= "<p>At present, our E-invoice applicability is marked as {$row['E_invoice_Applicable']}. We conduct our operations under the trade name {$row['Trade_Name']}.</p>";
    $aboutUs .= "<p>Our business activities include office/sale office, retail business, and wholesale business. We are a proud member of the esteemed group consisting of {$row['Members']}.</p>";
    $aboutUs .= "<p>For tax purposes, we are in the jurisdiction of {$row['Commissionerate']}, {$row['State_Jurisdiction']}, {$row['FieldVisit_Conducted']}.</p>";
    $aboutUs .= "<p>As of the last update on {$row['Last_Updated_at']}, we have paid {$row['Percent_Tax_paid_in_Cash']} of our taxes in cash.</p>";
    $aboutUs .= "<p>Our gross total income for the fiscal year 2019-2020 is {$row['Gross_Total_Income']}.</p>";
}

foreach ($rows as $row) {
    foreach ($row as $key => $value) {
        $output .= '<div class="col-md-6"><strong>' . htmlspecialchars($key) . '</strong></div>';
        $output .= '<div class="col-md-6">' . htmlspecialchars($value) . '</div>';
    }
}

$output .= '</div></div>';

// Close the database connection
$stmt->close();
?>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>GST Lookup</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Include Bootstrap dependencies -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-2104302062002302" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="stylesheet.css">
</head>
<body>
<style>
    p{
      font-family: Arial, Helvetica, sans-serif;

    font-size: medium;
    font-style: normal;
    }
</style>

<?php include 'nav.php';?>

<div class="container mt-3">
    <div class="card" style="padding:10px">

    <h2>GSTIN Lookup</h2>
    <!-- Output data here -->
    <?php

    echo $aboutUs;
    if (isset($output) && $result->num_rows > 0) {
        echo $output;
    // Hyperlink button
    $encodedCompanyName = str_replace(' ', '-', $row['Company Name']);

    // Hyperlink button
    $profileUrl = "https://finanvo.in/gst/profile/" . urlencode($gstinFromUrl) . "/" . urlencode($encodedCompanyName) . "/";
    echo '<a href="' . htmlspecialchars($profileUrl) . '" class="btn btn-success mt-3" style="width:250px" target="_blank">View More details on ...</a>';
    } else {
        echo '<p>No data found.</p>';
    }
    ?>
    <br>
    <a href="<?= htmlspecialchars($referrer) ?>" class="btn btn-primary"  style="width:250px">Go Back</a>
    <br>
</div>
 <br> <br> <br>
<?php include 'footer.php';?>

    </div>
</body>
</html>
