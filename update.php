<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$brand = $tara = $cantitate = "";
$brand_err = $tara_err = $cantitate_err = "";

// Processing form data when form is submitted
if(isset($_POST["id"]) && !empty($_POST["id"])){
    // Get hidden input value
    $id = $_POST["id"];

    // Validate name
    $input_brand = trim($_POST["brand"]);
    if(empty($input_brand)){
        $brand_err = "Please enter a name.";
    } elseif(!filter_var($input_brand, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $brand_err = "Please enter a valid brand.";
    } else{
        $brand = $input_brand;
    }

    // Validate address
    $input_tara = trim($_POST["tara"]);
    if(empty($input_tara)){
        $tara_err = "Please enter an contry.";
    } else{
        $tara = $input_tara;
    }

    // Validate salary
    $input_cantitate = trim($_POST["cantitate"]);
    if(empty($input_cantitate)){
        $cantitate_err = "Please enter the stock.";
    } elseif(!ctype_digit($input_cantitate)){
        $cantitate_err = "Please enter a positive integer value.";
    } else{
        $cantitate = $input_cantitate;
    }

    // Check input errors before inserting in database
    if(empty($brand_err) && empty($tara_err) && empty($cantitate_err)){
        // Prepare an update statement
        $sql = "UPDATE lab SET brand=?, tara=?, cantitate=? WHERE id=?";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssi", $param_brand, $param_tara, $param_cantitate, $param_id);

            // Set parameters
            $param_brand = $brand;
            $param_tara = $tara;
            $param_cantitate = $cantitate;
            $param_id = $id;

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records updated successfully. Redirect to landing page
                header("location: brand.php");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }

    // Close connection
    mysqli_close($link);
} else{
    // Check existence of id parameter before processing further
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        // Get URL parameter
        $id =  trim($_GET["id"]);

        // Prepare a select statement
        $sql = "SELECT * FROM lab WHERE id = ?";
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
                    $brand = $row["brand"];
                    $tara = $row["tara"];
                    $cantitate = $row["cantitate"];
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
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper{
            width: 600px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <h2 class="mt-5">Update Register</h2>
                <p>Please edit the input values and submit to update the register.</p>
                <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                    <div class="form-group">
                        <label>Brand</label>
                        <input type="text" name="brand" class="form-control <?php echo (!empty($brand_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $brand; ?>">
                        <span class="invalid-feedback"><?php echo $brand_err;?></span>
                    </div>
                    <div class="form-group">
                        <label>Tara</label>
                        <textarea name="tara" class="form-control <?php echo (!empty($tara_err)) ? 'is-invalid' : ''; ?>"><?php echo $tara; ?></textarea>
                        <span class="invalid-feedback"><?php echo $tara_err;?></span>
                    </div>
                    <div class="form-group">
                        <label>Salary</label>
                        <input type="text" name="cantitate" class="form-control <?php echo (!empty($cantitate_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $cantitate; ?>">
                        <span class="invalid-feedback"><?php echo $cantitate_err;?></span>
                    </div>
                    <input type="hidden" name="id" value="<?php echo $id; ?>"/>
                    <input type="submit" class="btn btn-primary" value="Submit">
                    <a href="index.php" class="btn btn-secondary ml-2">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>