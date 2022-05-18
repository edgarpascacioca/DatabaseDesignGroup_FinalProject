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
		<tr>
			<td>1</td>
			<td>Peter</td>
			<td>Address</td>
			<td>1234</td>
			<td>
				<a href="read.php">View</a>
				<a href="update.php">Edit</a>
				<a href="delete.php">Delete</a>
			</td>
		</tr>
	</table>
</body>
</html>