<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$orderNumber = $orderDate = $orderLineNumber = $productName = $quantityOrdered = $priceEach = "";
$orderNumber_err = $orderDate_err = $orderLineNumber_err = $productName_err = $quantityOrdered_err = $priceEach_err = "";
 
// Processing form data when form is submitted
if(isset($_POST["orderNumber"]) && !empty($_POST["orderNumber"])){
    // Get hidden input value
    $orderNumber = $_POST["orderNumber"];
    $productCode = $_POST["productCode"];
    
    // Validate Order Date
    $input_orderDate = trim($_POST["orderDate"]);
    if(empty($input_orderDate)){
        $orderDate_err = "Please enter an Order Date.";
    } else{
        $orderDate = $input_orderDate;
    }
    
    // Validate Order Line Number
    $input_orderLineNumber = trim($_POST["orderLineNumber"]);
    if(empty($input_orderLineNumber)){
        $orderLineNumber_err = "Please enter an Order line Number.";     
    } else{
        $orderLineNumber = $input_orderLineNumber;
    }
    
    // Validate Quantity Ordered
    $input_quantityOrdered = trim($_POST["quantityOrdered"]);
    if(empty($input_quantityOrdered)){
        $quantityOrdered_err = "Please enter the Quantity Ordered.";     
    } else{
        $quantityOrdered = $input_quantityOrdered;
    }

    // Validate Price Each
    $input_priceEach = trim($_POST["priceEach"]);
    if(empty($input_priceEach)){
        $priceEach_err = "Please enter the Price Each.";     
    } else{
        $priceEach = $input_priceEach;
    }
    
    // Check input errors before inserting in database
    if(empty($orderDate_err) && empty($orderLineNumber_err) && empty($quantityOrdered_err)  && empty($priceEach_err)){
        // Prepare an update statement
        $sql = "UPDATE orders 
        INNER JOIN
            orderdetails USING (orderNumber)
        INNER JOIN
            products USING (productCode)
        SET orderDate = '".$orderDate."', orderLineNumber=".$orderLineNumber.", quantityOrdered=".$quantityOrdered.", priceEach=".$priceEach." WHERE orderNumber=".$orderNumber." AND productCode='".$productCode."'";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "isiii", $param_orderNumber, $param_orderDate, $param_orderLineNumber, $param_quantityOrdered, $param_priceEach);
            
            // Set parameters
            $param_orderNumber = $orderNumber;
            $param_orderDate = $orderDate;
            $param_orderLineNumber = $orderLineNumber; 
            $param_quantityOrdered = $quantityOrdered;
            $param_priceEach = $priceEach;
           
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records updated successfully. Redirect to landing page
                header("location: index.php");
                exit();
            } else{
                echo htmlspecialchars($mysqli->error);
                echo "<h1>Something went wrong. Please try again later.</h1>";
            }
        }
         echo htmlspecialchars($link->error);
                echo "<h1>Something went wrong. Please try again later.</h1>";
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close connection
    mysqli_close($link);
} else{
    // Check existence of id parameter before processing further
    if(isset($_GET["orderNumber"]) && !empty(trim($_GET["orderNumber"]))){
        // Get URL parameter
        $id =  trim($_GET["orderNumber"]);
        
        // Prepare a select statement
        $sql = "SELECT
    orderNumber,
    orderDate,
    orderLineNumber,
    productName,
    quantityOrdered,
    priceEach,
    productCode
FROM
    orders
INNER JOIN
    orderdetails USING (orderNumber)
INNER JOIN
    products USING (productCode)
WHERE
orderNumber=".$_GET['orderNumber']." AND productCode='".$_GET['productCode']."'";
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_id);
            
            // Set parameters
            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
    
                if(mysqli_num_rows($result) == 1){
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    
                    // Retrieve individual field value
                    $orderNumber = $row["orderNumber"];
                    $orderDate = $row["orderDate"];
                    $orderLineNumber = $row["orderLineNumber"];
                    $quantityOrdered = $row["quantityOrdered"];
                    $priceEach = $row["priceEach"];
                    $productCode = $row["productCode"];
                    $productName = $row["productName"];
                } else{
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: error.php");
                    exit();
                }
                
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
        
        // Close connection
        mysqli_close($link);
    }  else{
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Record</title>
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
                        <h2>Update Record</h2>
                    </div>
                    <p>Please edit the input values and submit to update the record.</p>
                    <form action="update.php" method="post">
                        <input type="hidden" name="orderNumber" class="form-control" value="<?php echo $orderNumber; ?>">
                        <input type="hidden" name="productCode" class="form-control" value="<?php echo $productCode; ?>">
                        
                        <div class="form-group">
                            <label>Product Name</label>
                            <span class="help-block"><?php echo $productName;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($orderNumber_err)) ? 'has-error' : ''; ?>">
                            <label>Order Number</label>
                            <span class="help-block"><?php echo $orderNumber;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($name_err)) ? 'has-error' : ''; ?>">
                            <label>Order Date</label>
                            <input type="text" name="orderDate" class="form-control" value="<?php echo $orderDate; ?>">
                            <span class="help-block"><?php echo $orderDate_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($address_err)) ? 'has-error' : ''; ?>">
                            <label>Order Line Number</label>
                            <textarea name="orderLineNumber" class="form-control"><?php echo $orderLineNumber; ?></textarea>
                            <span class="help-block"><?php echo $orderLineNumber_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($salary_err)) ? 'has-error' : ''; ?>">
                            <label>Quantity Ordered</label>
                            <input type="text" name="quantityOrdered" class="form-control" value="<?php echo $quantityOrdered; ?>">
                            <span class="help-block"><?php echo $quantityOrdered_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($salary_err)) ? 'has-error' : ''; ?>">
                            <label>Price Each</label>
                            <input type="text" name="priceEach" class="form-control" value="<?php echo $priceEach; ?>">
                            <span class="help-block"><?php echo $priceEach_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>