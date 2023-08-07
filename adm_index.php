<?php
// Code fundet på: https://www.tutorialrepublic.com/php-tutorial/php-mysql-login-system.php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="da" dir="ltr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>MusicWorld - Administration</title>
    <link rel="stylesheet" type="text/css" href="adm_style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <?php
      // kræv indlæsning af forbindelse til databasen
      require_once("../../inc_connect.php");
      //bestem hvilken tidszone kommentar funktionen vil bruge
      date_default_timezone_set('Europe/Copenhagen');
    ?>
  </head>
  <body>
    <header>
      <nav>
        <div>
          <?php include("adm_inc_menu.php"); ?>
        </div>
      </nav>
    </header>
    <aside>
      <div>
        <?php include("adm_dashboard_menu.php"); ?>
      </div>
    </aside>
      <!-- For at oprette kommentarfunktionen har jeg fulgt Youtube tutorial:
        - https://www.youtube.com/watch?v=1LmPeHX7RRo
        - https://www.youtube.com/watch?v=kWOuUkLtQZw
        - https://www.youtube.com/watch?v=4pPGOF5MI4U
        Har redigeret på koden og indsat egen form
      -->
      <?php
        if (isset($_POST['kommenter'])) {
        if ($_POST['kommenter'] == "Kommenter") {
        $bruger_id = mysqli_real_escape_string($con, $_POST['bruger_id']);
        $dato = mysqli_real_escape_string($con, $_POST['dato']);
          //brugt date funktionen for at få fat i tidspunktet for kommentar
          // fundet på: https://hibbard.eu/how-to-insert-the-current-date-into-a-datetime-field-in-a-mysql-database-using-php/
          $dato = date("Y-m-d H:i:s");
          $besked = mysqli_real_escape_string($con, $_POST['besked']);
          $query =
            "INSERT INTO kommentar (bruger_id, dato, besked)
             VALUES ('$bruger_id', '$dato','$besked')
            ";
            $result = mysqli_query($con, $query);
            if ($result) {
              echo "<p>Data indsat</p>";
            } else {
              echo "<p>Data ikke indsat</p>";
            }
          }
          }
          ?>
      <main>
        <article id="velkommen">
          <h1>MusicWorld Administration</h1>
          <h2>Velkommen <?php echo ($_SESSION["brugernavn"]);?>!</h2>
          <h3>Husk at notere dine ændringer i loggen forneden.</h3>
        </article>
        <section>
          <form class="form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <label for="description">Indsæt dine opdateringer her</label>
            <!-- Check om denne echo funktion i value af bruger id virker!!-->
            <input type="hidden" name="bruger_id" value="<?php echo ($_SESSION["brugernavn"]);?>"/>
            <input type='hidden' name='dato' value="getdate()"/>
            <textarea id="besked" name="besked" rows="5" cols="60"></textarea>
            <input type="submit" name="kommenter" value="Kommenter"/>
          </form>
          <?php
          // hent steder fra tabellen og udskriv oversigt
          $query = "SELECT * FROM kommentar ORDER BY id desc";
          $result = mysqli_query($con, $query);
          // gennemløb alle rækker i resultatet
          // While = er en loop konstruktion, dvs. så længe det er er i parenteset er sandt så fortsætter loopen
          // mysqli assoc gennemgå alle resultater i en associative array
          // bliver falsk når der ikke er flere rækker tilbage i.e når den har gennemløbet alle resultater
          while ($row = mysqli_fetch_assoc($result)) {
            ?>
          <div class="loggen">
            <ul>
              <p><b>Dato:</b> <?php echo $row['dato']; ?> </p>
              <p><b>Bruger:</b> <?php echo $row['bruger_id']; ?> </p>
              <p><b>Kommentar:</b> <?php echo $row['besked']; ?> </p>
            </ul>
          </div>
          <?php
          }
          ?>
        </section>
      </main>
      <footer>
        <?php include("footer.php");?>
      </footer>
    <script>
      <?php include("navbar_script.js");?>
    </script>
    <?php mysqli_close($con); ?>
  </body>
</html>
