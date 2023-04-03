<!DOCTYPE html>
<html>

<head>
    <style>
        /* Red color for expired items */
        .table-danger td {
            background-color: #f8d7da;
        }

        /* Orange color for items expiring within the next 3 days */
        .table-warning td {
            background-color: #fff3cd;
        }
    </style>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Food Storage</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

</head>

<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <h2 class="text-center">Food Storage</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Amount</th>
                            <th>Expiration</th>
                            <th>Decrease</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $db = new SQLite3('shopping.db');
                        $results = $db->query('SELECT * FROM shopping_list ORDER BY CASE WHEN expiration IS "" THEN 1 ELSE 0 END, expiration ASC');
                        while ($row = $results->fetchArray()) {
                            $id = $row['id'];
                            $name = $row['name'];
                            $amount = $row['amount'];
                            $expiration = $row['expiration'];

                            // Check if item has expired or will expire within the next 3 days
                            $today = date('Y-m-d');
                            $expiration_date = date_create($expiration);
                            $diff = date_diff(date_create($today), $expiration_date);
                            $days_until_expiration = $diff->format("%R%a");

                            // Add CSS classes based on expiration date
                            $row_class = '';
                            if ($days_until_expiration < 0) {
                                $row_class = 'table-danger';
                            } else if ($days_until_expiration >= 0 && $days_until_expiration <= 3) {
                                $row_class = 'table-warning';
                            }

                            echo "  <tr class='{$row_class}'>
                                        <td>{$name}</td>
                                        <td>{$amount}</td>
                                        <td>{$expiration}</td>
                                        <td>
                                            <button type='button' class='btn btn-primary decrease-btn' onclick='decreaseItem({$id});'>-</button>
                                        </td>
                                    </tr>";
                        }
                        $db->close();
                        ?>

                    </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <h3>Add Item</h3>
                <form id="add-item-form" onsubmit="addItem(); return false;">
                    <input type="text" id="name" name="name" placeholder="Name" required>
                    <input type="number" id="amount" name="amount" placeholder="1" required>
                    <input type="date" id="expiration" name="expiration">
                    <button type="submit" class="btn btn-primary">Add Item</button>
                </form>
            </div>
        </div>
        <script>
            // Function to add item to database
            function addItem() {
                // Get form data
                var name = document.getElementById("name").value;
                var amount = document.getElementById("amount").value;
                var expiration = document.getElementById("expiration").value;

                // Send form data to add_item.php via AJAX
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "add_item.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.send("name=" + name + "&amount=" + amount + "&expiration=" + expiration);
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            // Refresh the table
                            document.location.reload();
                        } else {
                            console.error('Error adding item');
                        }
                    }
                };
            }

            // Function to decrease item amount in database
            function decreaseItem(id) {

                // Send form data to decrease_item.php via AJAX
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "decrease_item.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.send("id=" + id);
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            // Refresh the table
                            document.location.reload();
                        } else {
                            console.error('Error decreasing item');
                        }
                    }
                };
            }
        </script>
    </div>
</body>

</html>