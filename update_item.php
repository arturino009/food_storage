<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];
    $name = $_POST["name"];
    $amount = $_POST["amount"];
    $expiration = $_POST["expiration"];

    // Perform validation on input
    if (!isset($id) || !isset($name) || !isset($amount) || !isset($expiration)) {
        echo "Invalid input";
        exit;
    }

    // Connect to database
    $db = new SQLite3('shopping.db');

    // Prepare update statement
    $stmt = $db->prepare('UPDATE shopping_list SET name=:name, amount=:amount, expiration=:expiration WHERE id=:id');
    $stmt->bindValue(':name', $name, SQLITE3_TEXT);
    $stmt->bindValue(':amount', $amount, SQLITE3_INTEGER);
    $stmt->bindValue(':expiration', $expiration, SQLITE3_TEXT);
    $stmt->bindValue(':id', $id, SQLITE3_INTEGER);

    // Execute update statement
    $result = $stmt->execute();

    if ($result) {
        echo "Item updated successfully";
    } else {
        echo "Error updating item";
    }

    // Close database connection
    $db->close();
} else {
    echo "Invalid request method";
}
