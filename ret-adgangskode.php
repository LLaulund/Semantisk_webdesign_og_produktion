<?php
// Initialize the session
session_start();

// Check if the user is logged in, otherwise redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

// Include config file
require_once("../../inc_connect.php");

// Define variables and initialize with empty values
$ny_kode = $bekraeft_kode = "";
$ny_kode_err = $bekraeft_kode_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Validate new password
    if(empty(trim($_POST["ny_kode"]))){
        $ny_kode_err = "Venligst indtast ny adgangskode";
    } elseif(strlen(trim($_POST["ny_kode"])) < 6){
        $ny_kode_err = "Adgangskode skal minimum inhold 6 tegn.";
    } else{
        $ny_kode = trim($_POST["ny_kode"]);
    }

    // Validate confirm password
    if(empty(trim($_POST["bekraeft_kode"]))){
        $bekraeft_kode_err = "Bekræft adgangskode";
    } else{
        $bekraeft_kode = trim($_POST["bekraeft_kode"]);
        if(empty($ny_kode_err) && ($ny_kode != $bekraeft_kode)){
            $bekraeft_kode_err = "Adgangskoderne stemmer ikke overens";
        }
    }

    // Check input errors before updating the database
    if(empty($ny_kode_err) && empty($bekraeft_kode_err)){
        // Prepare an update statement
        $sql = "UPDATE SK_login SET kode = ? WHERE id = ?";

        if($stmt = mysqli_prepare($con, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "si", $param_password, $param_id);

            // Set parameters
            $param_password = password_hash($ny_kode, PASSWORD_DEFAULT);
            $param_id = $_SESSION["id"];

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Password updated successfully. Destroy the session, and redirect to login page
                session_destroy();
                header("location: login.php");
                exit();
            } else{
                echo "Der er sket en fejl, prøv igen!";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Close connection
    mysqli_close($con);
}
?>

<!DOCTYPE html>
<html lang="da" dir="ltr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>MusicWorld - Skift adgangskode</title>
    <link rel="stylesheet" type="text/css" href="adm_style.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  </head>
  <body>
    <div id="page-container">
      <div id="content-wrap">
    <header>
      <nav>
        <div>
          <?php include("adm_inc_menu.php"); ?>
        </div>
      </nav>
    </header>
    <main>
      <section class="login_form">
        <h2>Skift adgangskode</h2>
        <p>Udfyld formularen for at skifte adgangskode.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
          <div class="form-group">
            <label>Ny adgangskode</label>
            <input type="password" name="ny_kode" class="form-control <?php echo (!empty($ny_kode_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $ny_kode; ?>">
            <span class="invalid-feedback"><?php echo $ny_kode_err; ?></span>
          </div>
          <div class="form-group">
            <label>Bekræft adgangskode</label>
            <input type="password" name="bekraeft_kode" class="form-control <?php echo (!empty($bekraeft_kode_err)) ? 'is-invalid' : ''; ?>">
            <span class="invalid-feedback"><?php echo $bekraeft_kode_err; ?></span>
          </div>
          <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Submit">
            <a class="btn btn-link ml-2" href="velkom.php">Afbrud</a>
          </div>
        </form>
      </section>
    </main>
  </div>
  <footer id="footer">
    <?php include("footer.php");?>
  </footer>
</div>
<script>
  <?php include("navbar_script.js");?>
</script>
</body>
</html>
