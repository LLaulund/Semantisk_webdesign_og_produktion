<?php
// Code fundet på: https://www.tutorialrepublic.com/php-tutorial/php-mysql-login-system.php
// Include config file
require_once("../../inc_connect.php");

// Define variables and initialize with empty values
$brugernavn = $kode = $confirm_kode = "";
$brugernavn_err = $kode_err = $confirm_kode_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Validate username
    if(empty(trim($_POST["brugernavn"]))){
        $brugernavn_err = "Venligst indtast brugernavn";
    } elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["brugernavn"]))){
        $brugernavn_err = "Brugernavn må kun indeholde bogstaver og tal.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM SK_login WHERE brugernavn = ?";

        if($stmt = mysqli_prepare($con, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_brugernavn);

            // Set parameters
            $param_brugernavn = trim($_POST["brugernavn"]);

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);

                if(mysqli_stmt_num_rows($stmt) == 1){
                    $brugernavn_err = "Brugernavn allerede optaget";
                } else{
                    $brugernavn = trim($_POST["brugernavn"]);
                }
            } else{
                echo "Der er opstået en fejl, prøv igen!";
            }
            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Validate password
    if(empty(trim($_POST["kode"]))){
        $kode_err = "Venligst indtast adgangskode";
    } elseif(strlen(trim($_POST["kode"])) < 6){
        $kode_err = "Adgangskode skal minimum indholde 6 tegn.";
    } else{
        $kode = trim($_POST["kode"]);
    }

    // Validate confirm password
    if(empty(trim($_POST["confirm_kode"]))){
        $confirm_kode_err = "Venligst bekræft adgangskode.";
    } else{
        $confirm_kode = trim($_POST["confirm_kode"]);
        if(empty($kode_err) && ($kode != $confirm_kode)){
            $confirm_kode_err = "Adgangskode er forkert.";
        }
    }

    // Check input errors before inserting in database
    if(empty($brugernavn_err) && empty($kode_err) && empty($confirm_kode_err)){

        // Prepare an insert statement
        $sql = "INSERT INTO SK_login (brugernavn, kode) VALUES (?, ?)";

        if($stmt = mysqli_prepare($con, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_brugernavn, $param_kode);

            // Set parameters
            $param_brugernavn = $brugernavn;
            $param_kode = password_hash($kode, PASSWORD_DEFAULT); // Creates a password hash

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: login.php");
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
    <title>MusicWorld - Admin opret</title>
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
        <h2>Opret dig her</h2>
        <p>Udfyld formularen for at oprette en bruger</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
          <div class="form-group">
            <label>Brugernavn</label>
            <input type="text" name="brugernavn" class="form-control <?php echo (!empty($brugernavn_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $brugernavn; ?>">
            <span class="invalid-feedback"><?php echo $brugernavn_err; ?></span>
          </div>
          <div class="form-group">
            <label>Adgangskode</label>
            <input type="password" name="kode" class="form-control <?php echo (!empty($kode_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $kode; ?>">
            <span class="invalid-feedback"><?php echo $kode_err; ?></span>
          </div>
          <div class="form-group">
            <label>Bekræft adgangskode</label>
            <input type="password" name="confirm_kode" class="form-control <?php echo (!empty($confirm_kode_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_kode; ?>">
            <span class="invalid-feedback"><?php echo $confirm_kode_err; ?></span>
          </div>
          <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Submit">
            <input type="reset" class="btn btn-secondary ml-2" value="Reset">
          </div>
          <p>Er du allerede oprettet som admin? <a href="login.php">Login her</a>.</p>
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
