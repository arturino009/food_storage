<?php
$db = new SQLite3('shopping.db');
$results = $db->query('SELECT * FROM shopping_list');

$tableData = array();

while ($row = $results->fetchArray()) {
    $tableData[] = $row;
}

$db->close();
echo json_encode($tableData);
