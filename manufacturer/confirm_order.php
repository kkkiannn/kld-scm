<?php
error_reporting(0);
require("../includes/config.php");
session_start();

if (isset($_SESSION['manufacturer_login'])) {
    $id = $_GET['id'];

    // Initialize arrays to hold order and available product data
    $orderQuantities = $availQuantities = [];

    // Query for available quantities in products table
    $queryAvailQuantity = "SELECT products.pro_id, products.quantity 
                           FROM products 
                           JOIN order_items ON products.pro_id = order_items.pro_id 
                           WHERE order_items.order_id = '$id' AND products.quantity IS NOT NULL";
    $resultAvailQuantity = mysqli_query($con, $queryAvailQuantity);

    // Query for ordered quantities in order_items table
    $queryOrderQuantity = "SELECT pro_id, quantity 
                           FROM order_items 
                           WHERE order_id = '$id'";
    $resultOrderQuantity = mysqli_query($con, $queryOrderQuantity);

    // Fetch available quantities and store them in an associative array
    while ($row = mysqli_fetch_assoc($resultAvailQuantity)) {
        $availQuantities[$row['pro_id']] = $row['quantity'];
    }

    // Fetch order quantities and store them in an associative array
    while ($row = mysqli_fetch_assoc($resultOrderQuantity)) {
        $orderQuantities[$row['pro_id']] = $row['quantity'];
    }

    $hasEnoughStock = true;

    // Check if available stock meets the ordered quantity
    foreach ($orderQuantities as $proId => $orderQty) {
        if (isset($availQuantities[$proId])) {
            $availQty = $availQuantities[$proId];
            $newQty = $availQty - $orderQty;

            if ($newQty >= 0) {
                // Update product quantity in database if stock is sufficient
                $updateQuery = "UPDATE products SET quantity = '$newQty' WHERE pro_id = '$proId'";
                mysqli_query($con, $updateQuery);
            } else {
                $hasEnoughStock = false;
                break;
            }
        } else {
            $hasEnoughStock = false;
            break;
        }
    }

    // Handle order confirmation or insufficient stock case
    if ($hasEnoughStock) {
        $queryConfirm = "UPDATE orders SET approved = 1 WHERE order_id = '$id'";
        if (mysqli_query($con, $queryConfirm)) {
            echo "<script>alert('Order has been confirmed');</script>";
        } else {
            echo "<script>alert('There was an issue approving the order.');</script>";
        }
    } else {
        echo "<script>alert('You do not have enough stock to approve this order');</script>";
    }

    // Redirect to view orders page
    header("refresh:0;url=view_orders.php");
} else {
    header('Location:../index.php');
}
?>
