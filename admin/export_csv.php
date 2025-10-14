<?php
require_once("checkLogin.php");
require('../connection.php');

// Set headers to force download
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=articles_export_' . date('Y-m-d') . '.csv');

// Open PHP output stream
$output = fopen('php://output', 'w');

// Output column headings
fputcsv($output, ['ID', 'Article', 'Size', 'Scheme', 'Timestamp']);

// Fetch all records
$query = "SELECT * FROM dumpv2 ORDER BY id ASC";
$result = mysqli_query($mysql, $query);

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        fputcsv($output, [
            $row['id'],
            $row['article'],
            $row['size'],
            $row['scheme'],
            $row['time']
        ]);
    }
}

fclose($output);
exit;
