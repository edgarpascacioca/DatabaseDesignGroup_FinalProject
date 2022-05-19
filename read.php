<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);
// Check existence of id parameter before processing further
if(isset($_GET["orderNumber"]) && !empty(trim($_GET["orderNumber"]))){
    // Include config file
    require_once "config.php";
    
    // Prepare a select statement
    $sql = "SELECT
    orderNumber,
    orderDate,
    orderLineNumber,
    productName,
    quantityOrdered,
    priceEach
FROM
    orders
INNER JOIN
    orderdetails USING (orderNumber)
INNER JOIN
    products USING (productCode)
WHERE
orderNumber=".$_GET['orderNumber']." AND productCode='".$_GET['productCode']."'";
    
    if($stmt = mysqli_prepare($link, $sql)){
//$stmt = mysqli_prepare($link, $sql);
$p1 = trim($_GET["orderNumber"]);
$p2 = trim($_GET["productCode"]);
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "is", $p1,$p2);
        
        // Set parameters
        
        
        // Attempt to execute the prepared statement
        //if(mysqli_stmt_execute($stmt)){
        mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
    
            if(mysqli_num_rows($result) == 1){
                /* Fetch result row as an associative array. Since the result set
                contains only one row, we don't need to use while loop */
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                
                // Retrieve individual field value
                $name = $row["orderNumber"];
                $address = $row["orderDate"];
                $salary = $row["orderLineNumber"];
            //} else{
                // URL doesn't contain valid id parameter. Redirect to error page
              //  header("location: error.php");
               // exit();
            //}
            
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }
    }
     
    // Close statement
    mysqli_stmt_close($stmt);
    
    // Close connection
    mysqli_close($link);
} else{
    // URL doesn't contain id parameter. Redirect to error page
    header("location: error.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Record</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        .wrapper{
            width: 500px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h1>View Record</h1>
                    </div>
                    <div class="form-group">
                        <label>OrderNumber</label>
                        <p class="form-control-static"><?php echo $row["orderNumber"]; ?></p>
                    </div>
                    <div class="form-group">
                        <label>OrderDate</label>
                        <p class="form-control-static"><?php echo $row["orderDate"]; ?></p>
                    </div>
                    <div class="form-group">
                        <label>OrderLineNumber</label>
                        <p class="form-control-static"><?php echo $row["orderLineNumber"]; ?></p>
                    </div>
                    <div class="form-group">
                        <label>productName</label>
                        <p class="form-control-static"><?php echo $row["productName"]; ?></p>
                    </div>
                    <div class="form-group">
                        <label>quantityOrdered</label>
                        <p class="form-control-static"><?php echo $row["quantityOrdered"]; ?></p>
                    </div>
                    <div class="form-group">
                        <label>priceEach</label>
                        <p class="form-control-static"><?php echo $row["priceEach"]; ?></p>
                    </div>
                    
                    <p><a href="index.php" class="btn btn-primary">Back</a></p>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>