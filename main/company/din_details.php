<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Companies Director details</title>
    <link rel="stylesheet" type="text/css" href="stylesheet.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</head>
<script>
    function goBack() {
        window.history.back();
    }
</script>
<body style='background-color:#f0f2f8;'>

<?php include 'nav.php'; ?>

<?php
include 'config.php';

function generateTable($result, $displayHeaders = false)
{
    $tableHTML = '<table class="table table-bordered table-striped">';

    $headerPrinted = false;

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            if (!$headerPrinted) {
                $tableHTML .= '<thead class="table-dark"><tr>';
                foreach ($row as $key => $value) {
                    $tableHTML .= '<th scope="col">' . htmlspecialchars($key) . '</th>';
                }
                $tableHTML .= '</tr></thead><tbody>';
                $headerPrinted = true;
            }

            $tableHTML .= '<tr>';
            foreach ($row as $key => $value) {
                if ($key === 'CIN') {
                    $cinLink = '<a href="cin_details.php?cin=' . urlencode($value) . '">' . htmlspecialchars($value) . '</a>';
                    $tableHTML .= '<td>' . $cinLink . '</td>';
                } else {
                    $tableHTML .= '<td>' . htmlspecialchars($value) . '</td>';
                }
            }
            $tableHTML .= '</tr>';
        }
    } else {
        $tableHTML .= '<tbody><tr><td colspan="2">No Records Found</td></tr>';
    }

    $tableHTML .= '</tbody></table>';
    return $tableHTML;
}

// Get the DIN value from the query parameter (with basic input validation)
$din = isset($_GET['din']) ? $_GET['din'] : '';
$din = $conn->real_escape_string($din);

// Run the SQL query to get DIN details
$dinQuery = "SELECT DIN, NAME, DATE_JOIN, DESIGNATION FROM technowire_data.CIN_DIN WHERE DIN = ?";
$dinStmt = $conn->prepare($dinQuery);
$dinStmt->bind_param('s', $din);
$dinStmt->execute();
$dinResult = $dinStmt->get_result();

$referrer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'company.php';
error_reporting(E_ERROR | E_PARSE);


// Close the database connection
$conn->close();
?>


<div class="container mt-3">
    <div class="row">
        <div class="col-12">
            <h2>DIN Details</h2>
            <?php echo generateTable($dinResult, true); ?>
        </div>

        <div class="col-md-12">
            <br>
            <?php
                $directorNameForURL = str_replace(' ', '-', $row['NAME']);
                echo '<a href="https://finanvo.in/director/profile/' . urlencode($din) . '/' . urlencode($directorNameForURL) . '" class="btn btn-success" style="width:300px;height:40px;">Know More</a>';
            ?>
        </div>
    </div>
</div>

<script async src="https://pagead2.googlesyndication.com/pagead/js?client=ca-pub-2104302062002302" crossorigin="anonymous"></script>
<br> <br> <br> <br>

<?php include 'footer.php'; ?>

</body>
</html>
