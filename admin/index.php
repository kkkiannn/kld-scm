<?php
include("../includes/config.php");
session_start();
if (isset($_SESSION['admin_login'])) {
	if ($_SESSION['admin_login'] == true) {
		//select last 5 retailers
		$query_selectRetailer = "SELECT * FROM retailer,area WHERE retailer.area_id=area.area_id ORDER BY retailer_id DESC LIMIT 5";
		$result_selectRetailer = mysqli_query($con, $query_selectRetailer);
		//select last 5 manufacturers
		$query_selectManufacturer = "SELECT * FROM manufacturer ORDER BY man_id DESC LIMIT 5";
		$result_selectManufacturer = mysqli_query($con, $query_selectManufacturer);
		//select last 5 products
		$query_selectProducts = "SELECT * FROM products,categories,unit WHERE products.pro_cat=categories.cat_id AND products.unit=unit.id ORDER BY pro_id DESC LIMIT 5";
		$result_selectProducts = mysqli_query($con, $query_selectProducts);
	} else {
		header('Location:../index.php');
	}
} else {
	header('Location:../index.php');
}
?>
<!DOCTYPE html>
<html>

<head>
	<title> Admin: Home </title>
	<link rel="stylesheet" href="../includes/main_style.css">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body class="bg-light">
	<?php
	include("../includes/header.inc.php");
	include("../includes/nav_admin.inc.php");
	include("../includes/aside_admin.inc.php");
	?>
	<section class="container my-4">
		<h1 class="text-center mb-4">Welcome Admin</h1>

		<div class="mb-5">
			<h2 class="h4 mb-3">Recently Added Retailers</h2>
			<div class="table-responsive">
				<table class="table table-bordered table-striped">
					<thead class="table-dark">
						<tr>
							<th>#</th>
							<th>Username</th>
							<th>Zip Code</th>
							<th>Phone</th>
							<th>Email</th>
							<th>Address</th>
						</tr>
					</thead>
					<tbody>
						<?php $i = 1;
						while ($row_selectRetailer = mysqli_fetch_array($result_selectRetailer)) { ?>
							<tr>
								<td> <?php echo $i; ?> </td>
								<td> <?php echo $row_selectRetailer['username']; ?> </td>
								<td> <?php echo $row_selectRetailer['area_code']; ?> </td>
								<td> <?php echo $row_selectRetailer['phone']; ?> </td>
								<td> <?php echo $row_selectRetailer['email']; ?> </td>
								<td> <?php echo $row_selectRetailer['address']; ?> </td>
							</tr>
						<?php $i++;
						} ?>
					</tbody>
				</table>
			</div>
		</div>

		<article class="mb-5">
			<h2 class="h4 mb-3">Recently Added Manufacturers</h2>
			<div class="table-responsive">
				<table class="table table-bordered table-striped">
					<thead class="table-dark">
						<tr>
							<th>#</th>
							<th>Name</th>
							<th>Email</th>
							<th>Phone</th>
							<th>Username</th>
						</tr>
					</thead>
					<tbody>
						<?php $i = 1;
						while ($row_selectManufacturer = mysqli_fetch_array($result_selectManufacturer)) { ?>
							<tr>
								<td> <?php echo $i; ?> </td>
								<td> <?php echo $row_selectManufacturer['man_name']; ?> </td>
								<td> <?php echo $row_selectManufacturer['man_email']; ?> </td>
								<td> <?php echo $row_selectManufacturer['man_phone']; ?> </td>
								<td> <?php echo $row_selectManufacturer['username']; ?> </td>
							</tr>
						<?php $i++;
						} ?>
					</tbody>
				</table>
			</div>
		</article>

		<article>
			<h2 class="h4 mb-3">Recently Added Products</h2>
			<div class="table-responsive">
				<table class="table table-bordered table-striped">
					<thead class="table-dark">
						<tr>
							<th>Code</th>
							<th>Name</th>
							<th>Price</th>
							<th>Unit</th>
							<th>Category</th>
							<th>Quantity</th>
						</tr>
					</thead>
					<tbody>
						<?php $i = 1;
						while ($row_selectProducts = mysqli_fetch_array($result_selectProducts)) { ?>
							<tr>
								<td> <?php echo $row_selectProducts['pro_id']; ?> </td>
								<td> <?php echo $row_selectProducts['pro_name']; ?> </td>
								<td> <?php echo $row_selectProducts['pro_price']; ?> </td>
								<td> <?php echo $row_selectProducts['unit_name']; ?> </td>
								<td> <?php echo $row_selectProducts['cat_name']; ?> </td>
								<td> <?php echo $row_selectProducts['quantity'] ?? "N/A"; ?> </td>
							</tr>
						<?php $i++;
						} ?>
					</tbody>
				</table>
			</div>
		</article>
	</section>
	<?php
	include("../includes/footer.inc.php");
	?>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>
