<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$orderno = $productcode = 0;
$productCode =  $priceEach =  $quantityOrdered =  $orderNumber = $orderLineNumber = $orderDate = "";
$orderNumber_err = $orderDate_err = $orderLineNumber_err = $productName_err = $quantityOrdered_err = $priceEach_err =  "";
 
// Processing form data when form is submitted
if(isset($_POST["orderNumber"]) && !empty($_POST["orderNumber"])){
    // Get hidden input value
    $orderno = $_POST["orderNumber"];
    $productcode = $_POST["productCode"];
    // Validate name
    $input_order_date = trim($_POST["orderDate"]);
    if(empty($input_order_date)){
        $orderDate = "Please enter a date.";
    } elseif(!filter_var($input_order_date, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/")))){
        $orderDate_err = "Please enter a valid date.";
    } else{
        $orderDate = $input_order_date;
    }
    
    // Validate address address
    $input_order_line_number = trim($_POST["orderLineNumber"]);
    if(!ctype_digit($input_order_line_number)){
        $orderLineNumber_err = "Please enter a proper Order Line Number.";     
    } else{
        $orderLineNumber = $input_order_line_number;
    }

    // Validate salary
    $input_quantity_ordered = trim($_POST["quantityOrdered"]);
    if(empty($input_quantity_ordered)){
        $quantityOrdered = "Please enter the salary amount.";     
    } elseif(!ctype_digit($input_quantity_ordered)){
        $quantityOrdered_err = "Please enter a positive integer value.";
    } else{
        $quantityOrdered = $input_quantity_ordered;
    }

    // Validate salary
    $input_price_each = trim($_POST["priceEach"]);
    if(empty($input_price_each)){
        $priceEach = "Please enter the salary amount.";     
    } elseif(floatval($input_price_each) <= 0.0){
        $priceEach_err = "Please enter a positive integer value.";
    } else{
        $priceEach = $input_price_each;
    }
    
        // Prepare an update statement
        $sql = "UPDATE orders
                INNER JOIN
                    orderdetails USING (orderNumber)
                INNER JOIN
                    products USING (productCode)
                SET 
                    orderDate = ? , orderLineNumber = ? , quantityOrdered = ? , priceEach = ?
                WHERE 
                    orderNumber = ? AND productCode = ?";
         
        if($stmt = mysqli_prepare($link, $sql)){
            
            // Set parameters
            $param_order_date = $orderDate;
            $param_order_line_number = $orderLineNumber;
            $param_quantity_ordered = $quantityOrdered;
            $param_price_each = $priceEach;
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssssss", $param_order_date, $param_order_line_number, 
            $param_quantity_ordered, $param_price_each, $orderno, $productcode);
            
            
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records updated successfully. Redirect to landing page
                header("location: index.php");
                exit();
            } else{
                echo "Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    
    // Close connection
    mysqli_close($link);
} else{
    // Check existence of id parameter before processing further
    if(isset($_GET["orderNumber"]) && !empty(trim($_GET["orderNumber"]))){
        // Get URL parameter
        $orderno =  trim($_GET["orderNumber"]);
        $productcode = trim($_GET["productCode"]);
        
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
                orderNumber = ? AND productCode = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            
            $p1 = trim($_GET["orderNumber"]);
            $p2 = trim($_GET["productCode"]);
            $productcode = $p2;
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "is", $p1, $p2);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
    
                if(mysqli_num_rows($result) == 1){
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    
                    // Retrieve individual field value
                    $orderNumber = $row['orderNumber'];
                    $orderLineNumber = $row['orderLineNumber'];
                    $orderDate = $row['orderDate'];
                    $quantityOrdered = $row['quantityOrdered'];
                    $priceEach = $row['priceEach'];
                    
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
    }  
    else{
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
                    <div class="wrapper">
    
                    <div class="form-group">
                        <label>Order Number</label>
                        <p class="form-control-static"><?php echo $row['orderNumber']; ?></p>
                    </div>
                    
                    <div class="form-group">
                        <label>Product Name</label>
                        <p class="form-control-static"><?php echo $row['productName']; ?></p>
                    </div>


                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                        <div class="form-group <?php echo (!empty($orderDate_err)) ? 'has-error' : ''; ?>">
                            <label>Order Date</label>
                            <input type="text" name="orderDate" class="form-control" value="<?php echo $orderDate; ?>">
                            <span class="help-block"><?php echo $orderDate_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($orderLineNumber_err)) ? 'has-error' : ''; ?>">
                            <label>Order Line Number</label>
                            <input name="orderLineNumber" class="form-control" value = "<?php echo $orderLineNumber; ?> ">
                            <span class="help-block"><?php echo $orderLineNumber_err;?></span>
                        </div>
                        <div class="form-group <?php echo (!empty($quantityOrdered_err)) ? 'has-error' : ''; ?>">
                            <label>Quantity Ordered</label>
                            <input type="number" name="quantityOrdered" class="form-control" value="<?php echo $quantityOrdered; ?>">
                            <span class="help-block"><?php echo $quantityOrdered_err;?></span>
                        </div>

                        <div class="form-group <?php echo (!empty($priceEach_err)) ? 'has-error' : ''; ?>">
                            <label>Price Each</label>
                            <input type="text" name="priceEach" class="form-control" value="<?php echo $priceEach; ?>">
                            <span class="help-block"><?php echo $priceEach_err;?></span>
                        </div>

                        <input type="hidden" name="orderNumber" value="<?php echo $orderno; ?>"/>
                        <input type="hidden" name="productCode" value="<?php echo $productcode; ?>"/>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>