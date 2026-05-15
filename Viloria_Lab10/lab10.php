<?php
    // =========================================================
    // TODO 1: SECURE DATABASE CONNECTION (XAMPP / MySQL)
    // =========================================================
    // 1. Connect to MySQL using mysqli_connect($host, $user, $password, $dbname)
    $host = "localhost";
    $user = "pizza_admin";
    $password = "pizzaIsLife";
    $dbname = "PIZZADB";



    $conn = new mysqli ($host, $user, $password, $dbname); // Replace this with your actual connection code

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // =========================================================
    // TODO 2: HANDLE POST REQUESTS (ALL CRUD OPERATIONS)
    // =========================================================
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // You can refresh the page by using header("Location: " . $_SERVER['PHP_SELF']); exit; after each operation to see changes immediately.
        
        // ---  PIZZA ADMIN ---
        if (isset($_POST['add_pizza'])) {
            // TODO: Write INSERT query for Pizzas 
            $sql = "INSERT INTO pizzas (name, price) VALUES ('".$_POST['name'] . "', '".$_POST['price']."')";

            if ($conn->query($sql) === TRUE) {
                echo "New record created successfully";
            } else {
                echo "Error inserting record: : " . $conn->error;
            }
      

        }
        if (isset($_POST['update_pizza'])) {
            // TODO: Write UPDATE query to change pizza price
            $sql = "UPDATE pizzas SET price = '".$_POST['new_price']. "' WHERE id = '".$_POST['item_id']."'";

                if ($conn->query($sql) === TRUE) {
                    echo "Record updated successfully";
                } else {
                echo "Error updating record: " . $conn->error;
                }
        }
        if (isset($_POST['delete_pizza'])) {
            // TODO: Write DELETE query to remove a pizza
            $sql = "DELETE FROM pizzas WHERE id = '".$_POST['item_id']."'";

                if ($conn->query($sql) === TRUE) {
                    echo "Record deleted successfully";
                } else {
                echo "Error deleting record: " . $conn->error;
                }

        }

        // ---  TOPPINGS ADMIN ---
        if (isset($_POST['add_topping'])) {
            // TODO: Write INSERT query for Toppings
            $sql = "INSERT INTO toppings (name, price) VALUES ('".$_POST['name'] . "', '".$_POST['price']."')";

            if ($conn->query($sql) === TRUE) {
                echo "New record created successfully";
            } else {
                echo "Error inserting record: : " . $conn->error;
            }
      
        }
        if (isset($_POST['update_topping'])) {
            // TODO: Write UPDATE query to change topping price
            $sql = "UPDATE toppings SET price = '".$_POST['new_price']. "' WHERE id = '".$_POST['item_id']."'";

                if ($conn->query($sql) === TRUE) {
                    echo "Record updated successfully";
                } else {
                echo "Error updating record: " . $conn->error;
                }

        }
        if (isset($_POST['delete_topping'])) {
            // TODO: Write DELETE query to remove a topping
             $sql = "DELETE FROM toppings WHERE id = '".$_POST['item_id']."'";

                if ($conn->query($sql) === TRUE) {
                    echo "Record deleted successfully";
                } else {
                echo "Error deleting record: " . $conn->error;
                }

        }

        // --- 🛒 ORDERING SYSTEM ---
        if (isset($_POST['create_order'])) {
            // TODO: 
            // 1. Fetch the selected Pizza's price from the database using mysqli_query
            // 2. Loop through selected Toppings, fetch their prices, and calculate total topping cost
            // 3. Calculate Grand Total: (Pizza Price + Toppings Total) * Quantity
            // 4. INSERT the final order into the 'orders' table

        // Fetch pizza price for the DB.
            $pizza_id = intval($_POST['pizza']);
            $pizza_query = "SELECT price FROM pizzas WHERE id = $pizza_id";
            $pizza_result = $conn->query($pizza_query);
            $pizza_data = $pizza_result->fetch_assoc();
            $pizza_price = $pizza_data['price'];

            //caculate toppings
            $toppings_total_cost = 0;
            if (isset($_POST['toppings']) && is_array($_POST['toppings'])) {

                foreach ($_POST['toppings'] as $topping_id) {

                    $topping_id = intval($topping_id);
                    $topping_query ="SELECT price FROM toppings WHERE id = $topping_id";
                    $topping_result = $conn->query($topping_query);

                    if ($topping_result->num_rows > 0) {
                        $topping_data = $topping_result->fetch_assoc();
                        $toppings_total_cost += $topping_data['price'];
                    }
                }
            }

            //Grand total:
            $quantity = intval($_POST['qty']);
            $grand_total = ($pizza_price + $toppings_total_cost) * $quantity;

            // Insert order in the DB
            $customer_name = $conn->real_escape_string($_POST['customer']);
            $insert_order_query = "INSERT INTO orders (customer, pizza, toppings, qty, total, status) VALUES ('$customer_name', '" . $pizza_name . "', '" . implode(',', $_POST['toppings']) . "', $quantity, $grand_total, 'Pending')";


                if ($conn->query($insert_order_query) === TRUE) {
                    echo "New order created successfully";
                } else {
                echo "Error inserting order: " . $conn->error;
                }


        }

        // --- 📋 MANAGE ORDERS ---
        if (isset($_POST['update_status'])) {
            // TODO: Write UPDATE query to change order status to 'Completed'
            $sql = "UPDATE orders SET status = 'Completed' WHERE id = '".$_POST['item_id']."'";

                  if ($conn->query($sql) === TRUE) {

                    echo "New order status updated successfully";
                } else {

                echo "Error updating record: : " . $conn->error;
                }
        }
        if (isset($_POST['delete_order'])) {
            // TODO: Write DELETE query to remove an order

            $sql = "DELETE FROM orders WHERE id = '".$_POST['item_id']."'";

            if ($conn->query($sql) === TRUE) {

                    echo "Order deleted successfully";
                } else {

                echo "Error deleting record: " . $conn->error;
                }
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>🍕 Pizza Master Dashboard</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; background: linear-gradient(135deg, #FF6B6B 0%, #FFA500 100%); min-height: 100vh; padding: 40px 20px; color: #333;}
        .container { max-width: 1200px; margin: 0 auto; }
        header { text-align: center; color: white; margin-bottom: 40px; text-shadow: 2px 2px 4px rgba(0,0,0,0.3); }
        h1 { font-size: 3em; margin-bottom: 10px; }
        
        .grid-layout { display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 30px;}
        .full-width { grid-column: 1 / -1; }
        @media(max-width: 800px) { .grid-layout { grid-template-columns: 1fr; } }
        
        .card { background: white; border-radius: 15px; padding: 30px; box-shadow: 0 10px 20px rgba(0,0,0,0.2); }
        .card h2 { color: #FF6B6B; border-bottom: 3px solid #FFA500; padding-bottom: 10px; margin-bottom: 20px; }
        
        .form-group { display: flex; gap: 10px; margin-bottom: 20px; align-items: flex-end; }
        .form-stack { display: flex; flex-direction: column; gap: 8px; margin-bottom: 15px; }
        input[type="text"], input[type="number"] { padding: 10px; border: 2px solid #FF6B6B; border-radius: 8px; width: 100%; }
        
        .radio-group, .checkbox-group { display: flex; flex-direction: column; gap: 10px; }
        .selection-item { display: flex; align-items: center; padding: 10px; border-radius: 8px; cursor: pointer; background: #fff5f5;}
        .selection-item:hover { background-color: #ffe8e8; }
        .selection-item input { margin-right: 10px; width: 18px; height: 18px; accent-color: #FF6B6B; }
        .price { color: #FFA500; font-weight: bold; }
        
        button { padding: 10px 15px; background: #FF6B6B; color: white; border: none; border-radius: 8px; font-weight: bold; cursor: pointer; }
        button:hover { background: #FFA500; }
        .btn-large { width: 100%; padding: 15px; font-size: 1.1em; }
        .btn-update { background: #4CAF50; padding: 6px 12px; font-size: 0.9em; }
        .btn-delete { background: #f44336; padding: 6px 12px; font-size: 0.9em; }
        
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ecf0f1; }
        th { background-color: #FFF5E6; color: #FF6B6B; }
        .price-input { width: 90px !important; padding: 6px !important; margin-right: 5px; border: 1px solid #ccc !important;}
        
        .badge { padding: 5px 10px; border-radius: 20px; font-size: 0.8em; font-weight: bold; color: white; }
        .bg-pending { background-color: #FFA500; }
        .bg-completed { background-color: #4CAF50; }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>🍕 Pizza Master Dashboard</h1>
            <p>Admin Menu Management & Live Ordering System</p>
        </header>

        <div class="grid-layout">
            
            <div class="card">
                <h2>⚙️ Manage Pizzas</h2>
                <form method="post" class="form-group">
                    <div style="flex: 2;"><input type="text" name="name" placeholder="New Pizza Name" required></div>
                    <div style="flex: 1;"><input type="number" name="price" step="0.01" min="0" placeholder="Price" required></div>
                    <button type="submit" name="add_pizza">Add</button>
                </form>
                <table>
                    <tbody>
                        <?php
                            // TODO 3: Read from 'pizzas' table using mysqli_query and mysqli_fetch_assoc
                            // Remember to use htmlspecialchars() for security!

                            $query = "SELECT id, name, price FROM pizzas";
                            $result = $conn->query($query);

                            // check if there are results then loop it.

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $id = $row['id'];
                                    $name = htmlspecialchars($row['name']);
                                    $price = htmlspecialchars($row['price']);

                                    //update

                                    echo "<tr>";
                                    echo "<td><strong>{$name}</strong></td>";
                                
                                    echo "<td>
                                        <form method='post' style='display:flex;'>
                                            <input type='hidden' name='item_id' value='{$id}'>
                                            <input type='number' name='new_price' value='{$price}' step='0.01' class='price-input' required>
                                            <button type='submit' name='update_pizza' class='btn-update'>Save</button>
                                        </form>
                                    </td>";

                                 //delete

                                    echo "<td>
                                        <form method='post' onsubmit='return confirm(\"Are you sure you want to delete this pizza?\");'>
                                            <input type='hidden' name='item_id' value='{$id}'>
                                            <button type='submit' name='delete_pizza' class='btn-delete'>✖</button>
                                        </form>
                                    </td>";
                                    echo "</tr>";

                                }
                            } else {
                                echo "<tr><td colspan='3' style='text-align:center;'><em>No pizzas found in the menu.</td></tr>";
                            }
                        
                            /* Example of how the generated HTML should look:
                            <tr>
                                <td><strong>Safe Pizza Name</strong></td>
                                <td>
                                    <form method='post' style='display:flex;'>
                                        <input type='hidden' name='item_id' value='1'>
                                        <input type='number' name='new_price' value='150.00' step='0.01' class='price-input' required>
                                        <button type='submit' name='update_pizza' class='btn-update'>Save</button>
                                    </form>
                                </td>
                                <td>
                                    <form method='post'>
                                        <input type='hidden' name='item_id' value='1'>
                                        <button type='submit' name='delete_pizza' class='btn-delete'>✖</button>
                                    </form>
                                </td>
                            </tr>
                            */
                        ?>
                    </tbody>
                </table>
            </div>

            <div class="card">
                <h2>⚙️ Manage Toppings</h2>
                <form method="post" class="form-group">
                    <div style="flex: 2;"><input type="text" name="name" placeholder="New Topping Name" required></div>
                    <div style="flex: 1;"><input type="number" name="price" step="0.01" min="0" placeholder="Price" required></div>
                    <button type="submit" name="add_topping">Add</button>
                </form>
                <table>
                    <tbody>
                        <?php
                            // TODO 4: Read from 'toppings' table and generate rows dynamically
                            //should also contain the a form with input to update price and a delete button similar to pizzas

                            // fetch toppings from the DB
                            $topping_query = "SELECT id, name, price FROM toppings";
                            $topping_results = $conn->query($topping_query);


                            //check if the query returns results
                              if ($topping_results->num_rows > 0) {
                                while ($topping_row = $topping_results->fetch_assoc()) {
                                $topping_id = $topping_row['id'];
                                $topping_name = htmlspecialchars($topping_row['name']);
                                $topping_price = htmlspecialchars($topping_row['price']);

                                //update

                                echo "<tr>";
                                echo "<td><strong>{$topping_name}</strong></td>";
                                
                                echo "<td>
                                    <form method='post' style='display:flex;'>
                                        <input type='hidden' name='item_id' value='{$topping_id}'>
                                        <input type='number' name='new_price' value='{$topping_price}' step='0.01' class='price-input' required>
                                        <button type='submit' name='update_topping' class='btn-update'>Save</button>
                                    </form>
                                </td>";

                            //delete

                                echo "<td>
                                    <form method='post' onsubmit='return confirm(\"Are you sure you want to delete this topping?\");'>
                                        <input type='hidden' name='item_id' value='{$topping_id}'>
                                        <button type='submit' name='delete_topping' class='btn-delete'>✖</button>
                                    </form>
                                </td>";
                            echo "</tr>";

                            }
                        } else {
                            echo "<tr><td colspan='3' style='text-align:center;'><em>No toppings found in the menu.</em></td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card" style="max-width: 800px; margin: 0 auto 30px auto;">
            <h2>🛒 Place New Order</h2>
            <form method="post">
                <div class="form-stack">
                    <label><strong>Customer Name</strong></label>
                    <input type="text" name="customer" required>
                </div>

                <div class="grid-layout" style="gap: 20px; margin-bottom: 0;">
                    
                    <div class="form-stack">
                        <label><strong>Select Pizza</strong></label>
                        <div class="radio-group">
                            <?php 
                                // TODO 5: Fetch Pizzas from DB to generate radio buttons
                               
                                  // fetch pizzas from the DB
                            $pizza_query = "SELECT id, name, price FROM pizzas";
                            $pizza_results = $conn->query($pizza_query);


                            //check if the query returns results
                              if ($pizza_results->num_rows > 0) {
                                while ($pizza_row = $pizza_results->fetch_assoc()) {
                                $pizza_id = $pizza_row['id'];
                                $pizza_name = htmlspecialchars($pizza_row['name']);
                                $pizza_price = htmlspecialchars($pizza_row['price']);

                                //wrap the input inside the label

                                echo "<label class='selection-item'>";
                                echo "<input type='radio' name='pizza' value='{$pizza_id}' required>";
                                echo "<span>{$pizza_name} <span class='price'>₱{$pizza_price}</span></span>";
                                echo "</label>";
                                }
                              } else {
                                echo "<label class='selection-item'><em> No pizzas available.</em></label>";
                              }

                            ?>
                        </div>
                    </div>

                    <div class="form-stack">
                        <label><strong>Select Toppings</strong></label>
                        <div class="checkbox-group">
                            <?php 
                                // TODO 6: Fetch Toppings from DB to generate checkboxes
                                $topping_query = "SELECT id, name, price FROM toppings";
                                $topping_option_results = $conn->query($topping_query);


                            //check if the query returns results
                              if ($topping_option_results->num_rows > 0) {
                                while ($topping = $topping_option_results->fetch_assoc()) {
                                $topping_id = $topping['id'];
                                $topping_name = htmlspecialchars($topping['name']);
                                $topping_price = htmlspecialchars($topping['price']);

                                //wrap the input inside the label

                                echo "<label class='selection-item'>";
                                echo "<input type='checkbox' name='toppings[]' value='{$topping_id}'>";
                                echo "<span>{$topping_name} (+₱{$topping_price})</span>";
                                echo "</label>";
                                }
                              } else {
                                echo "<label class='selection-item'><em> No toppings available.</em></label>";
                              }
                               
                            ?>
                        </div>
                    </div>
                </div>

                <div class="form-stack" style="margin-top: 15px;">
                    <label><strong>Quantity</strong></label>
                    <input type="number" name="qty" min="1" value="1" required>
                </div>

                <button type="submit" name="create_order" class="btn-large">🚀 Submit Order</button>
            </form>
        </div>

        <div class="card full-width">
            <h2>📋 Live Kitchen Orders</h2>
            <div style="overflow-x: auto;">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th><th>Customer</th><th>Order Details</th><th>Total</th><th>Status</th><th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            // TODO 7: Read from 'orders' table and display live kitchen orders
                            // If status is Pending, show the Checkmark (✔) button. Otherwise, hide it.

                            $order_query = "SELECT * FROM orders ORDER BY id DESC";
                            $order_results = $conn->query($order_query);

                            if ($order_results->num_rows > 0) {
                                while ($order = $order_results->fetch_assoc()) {
                                    $order_id = $order['id'];
                                    $order_customer = htmlspecialchars($order['customer']);
                                    $order_pizza = htmlspecialchars($order['pizza']);
                                    $order_toppings = $order['toppings'] ? explode(',', htmlspecialchars($order['toppings'])) : [];
                                    $order_quantity = htmlspecialchars($order['qty']);
                                    $order_total = htmlspecialchars($order['total']);
                                    $order_status = htmlspecialchars($order['status']);
                                  
                                    echo "<tr>";
                                    echo "<td>{$order_id}</td>";
                                    echo "<td>{$order_customer}</td>";
                                    echo "<td>
                                            <strong>Pizza:</strong> {$order_pizza}<br>
                                            <strong>Toppings:</strong> " . ($order_toppings ? implode(', ', $order_toppings) : 'None') . "<br>
                                            <strong>Quantity:</strong> {$order_quantity}<br>
                                            </td>";
                                    echo "<td>₱{$order_total}</td>";
                                    
                                    // Status column
                                    echo "<td>";
                                    if (strtolower($order_status) === 'pending') {
                                        echo "<span class='badge bg-pending'>{$order_status}</span>";
                                    } else {
                                        echo "<span class='badge bg-completed'>{$order_status}</span>";
                                    }
                                    echo "</td>";
                                    
                                    // Actions column
                                    echo "<td>";
                                    if (strtolower($order_status) === 'pending') {
                                        echo "<form method='post' style='display:inline;'>
                                                <input type='hidden' name='item_id' value='{$order_id}'>
                                                <button type='submit' name='update_status' class='btn-update' title='Mark as Completed'>✔</button>
                                            </form>";
                                    }
                                    echo "<form method='post' style='display:inline; margin-left:5px;' onsubmit='return confirm(\"Are you sure you want to delete this order?\");'>
                                            <input type='hidden' name='item_id' value='{$order_id}'>
                                            <button type='submit' name='delete_order' class='btn-delete'>✖</button>
                                        </form>";
                                    echo "</td>";
                                    echo "</tr>";
                                    }
                                } else {
                                        echo "<tr><td colspan='6' style='text-align:center;'><em>No orders in the system</em></td></tr>";
                                }
                            
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
</body>
<?php $conn->close(); ?>
</html>
</html>