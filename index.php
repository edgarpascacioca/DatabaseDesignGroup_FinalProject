<?php require("config.php"); ?>
<html>
<head>
	<title>Database Design Group - Final Project - Employees List</title>
	<style>
		table, th, td {
  			border: 1px solid black;
		}
	</style>
</head>
<body>
	<h1>Employees Details</h1>
	<a href="create.php">Add New Employee</a>
	<table >
		<tr>
			<th>#</th>
			<th>Name</th>
			<th>Address</th>
			<th>Salary</th>
			<th>Action</th>
		</tr>
		<?php
		$employees = listEmployees();
		if ($employees->num_rows > 0) {
	  // output data of each row
	  while($row = $employees->fetch_assoc()) {
	  	?>
	  	<tr>
			<td><?php echo $row["id"]; ?></td>
			<td><?php echo $row["name"]; ?></td>
			<td><?php echo $row["address"]; ?></td>
			<td><?php echo $row["salary"]; ?></td>
			<td>
				<a href="read.php?id=<?php echo $row["id"] ?>">View</a>
				<a href="update.php?id=<?php echo $row["id"] ?>">Edit</a>
				<a href="delete.php?id=<?php echo $row["id"] ?>">Delete</a>
			</td>
		</tr>
	    
	    <?php
	  }
	} else {
	  echo "0 results";
	}
	 ?>
		
	</table>
</body>
</html>