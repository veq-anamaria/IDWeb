<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$brand = $tara = $cantitate = "";
$brand_err = $tara_err = $cantitate_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate name
    $input_brand = trim($_POST["brand"]);
    if(empty($input_brand)){
        $brand_err = "Please enter a name.";
    } elseif(!filter_var($input_brand, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $brand_err = "Va rog introduceti brandul.";
    } else{
        $brand = $input_brand;
    }

    // Validate address
    $input_tara = trim($_POST["tara"]);
    if(empty($input_tara)){
        $tara_err = "Va rog introduceti tara.";
    } else{
        $tara = $input_tara;
    }

    // Validate salary
    $input_cantitate = trim($_POST["cantitate"]);
    if(empty($input_cantitate)){
        $cantitate_err = "Va rog introduceti cantitatea.";
    } elseif(!ctype_digit($input_cantitate)){
        $cantitate_err = "Va rog introduceti numere pozitive.";
    } else{
        $cantitate = $input_cantitate;
    }

    // Check input errors before inserting in database
    if(empty($brand_err) && empty($tara_err) && empty($cantitate_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO lab (brand, tara, cantitate) VALUES (?, ?, ?)";

        $link =5;
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            $param_cantitate =5;
            mysqli_stmt_bind_param($stmt, "sss", $param_brand, $param_tara, $param_cantitate);

            // Set parameters
            $param_brand = $brand;
            $param_tara = $tara;
            $param_cantitate = $cantitate;

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records created successfully. Redirect to landing page
                header("location: brand.php");
                exit();
            } else{
                echo "Hopa! Ceva n-a mers bine. Vă rugăm să încercați din nou mai târziu.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }

    // Close connection
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Creeaza o inregistrare</title>
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
                <h2 class="mt-5">Create Record</h2>
                <p>Please fill this form and submit to add employee record to the database.</p>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="form-group">
                        <label>Brand</label>
                        <input  name="brand" class="form-control <?php echo (!empty($brand_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $brand; ?>">
                        <span class="invalid-feedback"><?php echo $brand_err;?></span>
                    </div>
                    <div class="form-group">
                        <label>Tara</label>
                        <textarea name="tara" class="form-control <?php echo (!empty($tara_err)) ? 'is-invalid' : ''; ?>"><?php echo $tara; ?></textarea>
                        <span class="invalid-feedback"><?php echo $tara_err;?></span>
                    </div>
                    <div class="form-group">
                        <label>Cantitate</label>
                        <input type="text" name="cantitate" class="form-control <?php echo (!empty($cantitate_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $cantitate; ?>">
                        <span class="invalid-feedback"><?php echo $cantitate_err;?></span>
                    </div>
                    <input type="submit" class="btn btn-primary" value="Submit">
                    <a href="index.php" class="btn btn-secondary ml-2">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>