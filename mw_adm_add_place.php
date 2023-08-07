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
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>MusicWorld - Tilføj Sted</title>
    <link rel="stylesheet" type="text/css" href="adm_style.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <?php
            // kræv indlæsning af forbindelse til databasen
            require_once("../../inc_connect.php");
        ?>
        <?php
            // tjek, om der er sendt data med (er der trykket på knappen "gem"?)
            if ($_POST['gem'] == "Gem") {
                // gem medsendte data som php-variabler
                $name = mysqli_real_escape_string($con, $_POST['name']);
                $hasMap = mysqli_real_escape_string($con, $_POST['hasMap']);
                $Place_contained_in_Place_id = mysqli_real_escape_string($con, $_POST['place_id']);
                // opbyg forespørsel til at indsætte databasen
                $query =
                    "INSERT INTO MW_Place (name, hasMap, Place_contained_in_Place_id)
                    VALUES ('$name', '$hasMap', '$Place_contained_in_Place_id')
                    ";
                // send forespørgsel til serveren og gem den i variablen $mysqli_more_results
                // echo query sørger for at forespørgsel sendes til skærmen for at teste at den virker
                $result = mysqli_query($con, $query);
                if ($result) {
                    echo "<p>Data indsat</p>";
                } else {
                    echo "<p>Data ikke indsat</p>";
                }
            }
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
      <main class="add_data_forms">
        <section class="form">
          <h1>Tilføj nye steder her</h1>
          <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <label for="name">Navn</label>
            <input type="text" id="name" name="name" />
            <br/><br/>
            <label for="hasMap">Kort</label>
            <input type="text" name="hasMap" id="hasMap" placeholder="Indsæt kort URL her..."/>
            <br/><br/>
            <label for="place_id"> Vælg overordnet sted, hvor dette sted ligger: </label>
            <br/><br/>
            <select id="place_id" name="place_id">
              <!-- Har sat value i option til null fordi PHP ellers prover at indsaette data i tabellen selvom den ikke skal-->
              <option value="">Vælg sted...</option>
                <?php
                  // hent steder fra tabellen og giv mulighed for at vælge overordnet sted
                  $query = "SELECT id, name FROM MW_Place ORDER BY name";
                  // Send forespørsel
                  $result = mysqli_query($con, $query);
                  // gennemløb alle rækker i resultatet
                  // While = er en loop konstruktion, dvs. så længe det er er i parenteset er sandt så fortsætter loopen
                  // mysqli assoc gennemgå alle resultater i en associative array
                  // bliver falsk når der ikke er flere rækker tilbage i.e når den har gennemløbet alle resultater
                  while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                <option value ="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                <?php
                }
                ?>
              </select>
              <br/><br/>
              <input type="submit" name="gem" value="Gem"/>
            </form>
          </section>
          <section class="oversigt">
          <h2>Oversigt over eksisterende steder</h2>
          <ul>
          <?php
            // hent steder fra tabellen og udskriv oversigt
            $query = "SELECT id, name FROM MW_Place ORDER BY name";
            // Send forespørsel
            $result = mysqli_query($con, $query);
            // gennemløb alle rækker i resultatet
            // While = er en loop konstruktion, dvs. så længe det er er i parenteset er sandt så fortsætter loopen
            // mysqli assoc gennemgå alle resultater i en associative array
            // bliver falsk når der ikke er flere rækker tilbage i.e når den har gennemløbet alle resultater
            while ($row = mysqli_fetch_assoc($result)) {
            ?>
            <li><?php echo $row['name']; ?>
              <a href="mw_adm_edit_place.php?id=<?php echo $row['id']; ?>">[ RET / SLET ]</a></li>
            <?php
            }
           ?>
           </ul>
           </section>
        </main>
        <footer>
          <?php include("footer.php"); ?>
        </footer>
        <script>
          <?php include("navbar_script.js");?>
        </script>
        <?php mysqli_close($con); ?>
      </body>
    </html>
