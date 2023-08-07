<?php
// Code fundet på: https://www.tutorialrepublic.com/php-tutorial/php-mysql-login-system.php
// Initialize the session
session_start();

// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: velkom.php");
    exit;
}

// Include config file
require_once("../../inc_connect.php");

// Define variables and initialize with empty values
$brugernavn = $kode = "";
$brugernavn_err = $kode_err = $login_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Check if username is empty
    if(empty(trim($_POST["brugernavn"]))){
        $brugernavn_err = "Venligst indtast brugernavn.";
    } else{
        $brugernavn = trim($_POST["brugernavn"]);
    }

    // Check if password is empty
    if(empty(trim($_POST["kode"]))){
        $kode_err = "Venligst indtast adgangskode";
    } else{
        $kode = trim($_POST["kode"]);
    }

    // Validate credentials
    if(empty($brugernavn_err) && empty($kode_err)){
        // Prepare a select statement
        $sql = "SELECT id, brugernavn, kode FROM SK_login WHERE brugernavn = ?";

        if($stmt = mysqli_prepare($con, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_brugernavn);

            // Set parameters
            $param_brugernavn = $brugernavn;

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);

                // Check if username exists, if yes then verify password
                if(mysqli_stmt_num_rows($stmt) == 1){
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $brugernavn, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($kode, $hashed_password)){
                            // Password is correct, so start a new session
                            session_start();

                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["brugernavn"] = $brugernavn;

                            // Redirect user to welcome page
                            header("location: velkom.php");
                        } else{
                            // Password is not valid, display a generic error message
                            $login_err = "Ugyldigt brugernavn eller adgangskode";
                        }
                    }
                } else{
                    // Username doesn't exist, display a generic error message
                    $login_err = "Ugyldigt brugernavn eller adgangskode.";
                }
            } else{
                echo "Der er opstået en fejl, prøv igen!";
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
    <title>MusicWorld - Admin login</title>
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
        <h2>Admin login</h2>
        <p>Venligst indtast dine login oplysninger</p>
        <?php
        if(!empty($login_err)){
            echo '<div class="alert alert-danger">' . $login_err . '</div>';
        }
        ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Brugernavn</label>
                <input type="text" name="brugernavn" class="form-control <?php echo (!empty($brugernavn_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $brugernavn; ?>">
                <span class="invalid-feedback"><?php echo $brugernavn_err; ?></span>
            </div>
            <div class="form-group">
                <label>Adgangskode</label>
                <input type="password" name="kode" class="form-control <?php echo (!empty($kode_err)) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback"><?php echo $kode_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Login">
            </div>
            <p>Er du ikke oprettet som admin? <a href="opret.php">Opret dig her</a>.</p>
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
