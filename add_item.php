<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $amount = $_POST['amount'];
    $expiration = $_POST['expiration'];

    $db = new SQLite3('shopping.db');
    $stmt = $db->prepare('INSERT INTO shopping_list (name, amount, expiration) VALUES (:name, :amount, :expiration)');
    $stmt->bindValue(':name', $name, SQLITE3_TEXT);
    $stmt->bindValue(':amount', $amount, SQLITE3_INTEGER);
    $stmt->bindValue(':expiration', $expiration, SQLITE3_TEXT);
    $stmt->execute();
    $db->close();

    echo 'success';
} else {
    header('HTTP/1.1 400 Bad Request');
    echo 'error';
}
