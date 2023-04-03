<?php
$db = new SQLite3('shopping.db');

$id = $_POST['id'];
$results = $db->query("SELECT amount FROM shopping_list WHERE id = $id");
$row = $results->fetchArray();
$amount = $row['amount'];

if ($amount > 1) {
    $amount--;
    $db->exec("UPDATE shopping_list SET amount = $amount WHERE id = $id");
} else {
    $db->exec("DELETE FROM shopping_list WHERE id = $id");
}

$db->close();
echo $amount;
