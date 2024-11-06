<?php
require("../includes/config.php");
session_start();

$currentDate = date('Y-m-d');

if (isset($_SESSION['manufacturer_login'])) {
    $order_id = $_GET['id'];

    // Query to fetch distributor information
    $querySelectDistributor = "SELECT dist_id, dist_name FROM distributor";
    $resultDistributor = mysqli_query($con, $querySelectDistributor);

    // Query to fetch order items and product details
    $querySelectOrderItems = "
        SELECT products.pro_name, products.pro_price, order_items.quantity AS q 
        FROM order_items 
        JOIN products ON order_items.pro_id = products.pro_id 
        WHERE order_items.order_id = '$order_id'";
    $resultSelectOrderItems = mysqli_query($con, $querySelectOrderItems);

    // Query to fetch order date and status
    $querySelectOrder = "SELECT date, status FROM orders WHERE order_id = '$order_id'";
    $resultSelectOrder = mysqli_query($con, $querySelectOrder);
    $rowSelectOrder = mysqli_fetch_assoc($resultSelectOrder);

    // Query to fetch the next invoice ID
    $querySelectInvoiceId = "SELECT `AUTO_INCREMENT` 
                             FROM INFORMATION_SCHEMA.TABLES 
                             WHERE TABLE_SCHEMA = 'scm_new' AND TABLE_NAME = 'invoice'";
    $resultSelectInvoiceId = mysqli_query($con, $querySelectInvoiceId);
    $rowSelectInvoiceId = mysqli_fetch_assoc($resultSelectInvoiceId);

    // Ensure that the result is valid before accessing AUTO_INCREMENT
    $invoiceId = isset($rowSelectInvoiceId['AUTO_INCREMENT']) ? $rowSelectInvoiceId['AUTO_INCREMENT'] : 'N/A';
} else {
    header('Location: ../index.php');
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Orders</title>
    <link rel="stylesheet" href="../includes/main_style.css">
</head>
<body>
    <?php
    include("../includes/header.inc.php");
    include("../includes/nav_manufacturer.inc.php");
    include("../includes/aside_manufacturer.inc.php");
    ?>
    <section>
        <h1>Invoice Summary</h1>
        <table class="table_infoFormat">
            <tr>
                <td>Invoice No:</td>
                <td><?php echo htmlspecialchars($invoiceId); ?></td>
            </tr>
            <tr>
                <td>Invoice Date:</td>
                <td><?php echo date('d-m-Y'); ?></td>
            </tr>
            <tr>
                <td>Order No:</td>
                <td><?php echo htmlspecialchars($order_id); ?></td>
            </tr>
            <tr>
                <td>Order Date:</td>
                <td><?php echo isset($rowSelectOrder['date']) ? date("d-m-Y", strtotime($rowSelectOrder['date'])) : 'N/A'; ?></td>
            </tr>
        </table>
        <form action="insert_invoice.php" method="POST" class="form">
            <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order_id); ?>" />
            <table class="table_invoiceFormat">
                <tr>
                    <th>Products</th>
                    <th>Unit Price</th>
                    <th>Quantity</th>
                    <th>Amount</th>
                </tr>
                <?php 
                $grandTotal = 0;
                while ($rowSelectOrderItems = mysqli_fetch_assoc($resultSelectOrderItems)) { 
                    $amount = $rowSelectOrderItems['q'] * $rowSelectOrderItems['pro_price'];
                    $grandTotal += $amount;
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($rowSelectOrderItems['pro_name']); ?></td>
                    <td><?php echo htmlspecialchars($rowSelectOrderItems['pro_price']); ?></td>
                    <td><?php echo htmlspecialchars($rowSelectOrderItems['q']); ?></td>
                    <td><?php echo $amount; ?></td>
                </tr>
                <?php } ?>
                <tr style="height:40px;vertical-align:bottom;">
                    <td colspan="3" style="text-align:right;">Grand Total:</td>
                    <td><?php echo $grandTotal; ?></td>
                </tr>
            </table>
            <br/>
            Ship via: &nbsp;&nbsp;&nbsp;&nbsp;
            <select name="distributor">
                <option value="" disabled selected>--- Select Distributor ---</option>
                <?php while ($rowSelectDistributor = mysqli_fetch_assoc($resultDistributor)) { ?>
                <option value="<?php echo htmlspecialchars($rowSelectDistributor['dist_id']); ?>">
                    <?php echo htmlspecialchars($rowSelectDistributor['dist_name']); ?>
                </option>
                <?php } ?>
            </select> 
            <br/><br/>
            Comments: <textarea maxlength="400" name="txtComment" rows="5" cols="30"></textarea>
            <br/>
            <input type="submit" value="Generate Invoice" class="submit_button" />
            <span class="error_message">
            <?php
                if (isset($_SESSION['error'])) {
                    echo htmlspecialchars($_SESSION['error']);
                    unset($_SESSION['error']);
                }
            ?>
            </span>
        </form>
    </section>
    <?php
    include("../includes/footer.inc.php");
    ?>
</body>
</html>
