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
        <title>MusicWorld - Musikgruppe har album </title>
        <link rel="stylesheet" type="text/css" href="adm_style.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <?php
            // kræv indlæsning af forbindelse til databasen
            require_once("../../inc_connect.php");
        ?>

        <?php
            // tjek, om der er sendt data med (er der trykket på knappen "gem"?)
            if ($_POST['gem'] == "Gem") {
                // gem medsendte data som php-variabler
                $MusicAlbum_id = mysqli_real_escape_string($con, $_POST['MusicAlbum_id']);
                $MusicGroup_id = mysqli_real_escape_string($con, $_POST['MusicGroup_id']);
                // opbyg forespørsel til at indsætte databasen
                $query = "
                    INSERT INTO MW_MusicAlbum_has_MusicGroup (
                        MusicAlbum_id,
                        MusicGroup_id
                        )
                    VALUES (
                        '$MusicAlbum_id',
                        '$MusicGroup_id'
                        )
                    ";
                // send forespørgsel til server, og gem resultat i $result
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
      <div id="page-container">
        <div id="content-wrap">
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
          <h1>Tilføj Album til Musikgruppe</h1>
          <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <label for="MusicGroup_id">Musikgruppe</label>
            <select id="MusicGroup_id" name="MusicGroup_id">
                <option value="">Vælg Musikgruppe</option>
            <?php
                // hent steder fra tabellen, og giv mulighed for at vælge sted
                $query = "SELECT id, name FROM MW_MusicGroup ORDER BY name";
                // send forespørgsel
                $result = mysqli_query($con, $query);
                // gennemløb alle rækker i resultatet
                while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                    <option value="<?php echo $row['id']; ?>">
                        <?php echo $row['name']; ?>
                    </option>
                    <?php
                }
            ?>
            </select>

            <label for="MusicAlbum_id">har album</label>
            <select id="MusicAlbum_id" name="MusicAlbum_id">
                <option value="">Vælg Album</option>
            <?php
                // hent steder fra tabellen, og giv mulighed for at vælge sted
                $query = "SELECT id, name FROM MW_MusicAlbum ORDER BY name";
                // send forespørgsel
                $result = mysqli_query($con, $query);
                // gennemløb alle rækker i resultatet
                while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                    <option value="<?php echo $row['id']; ?>">
                        <?php echo $row['name']; ?>
                    </option>
                    <?php
                }
            ?>
            </select>
            <br/><br/>
            <input type="submit" name="gem" value="Gem" />
        </form>
      </section>
      <section class="oversigt">
            <h2>Oversigt</h2>
            <ul>
              <?php
                      // hent steder fra tabellen, og udskriv oversigt
                      $query =
                      "SELECT
                          MW_MusicGroup.name AS Musikgruppe,
                          MW_MusicAlbum.name AS Album
                      FROM
                          MW_MusicAlbum_has_MusicGroup
                      LEFT JOIN MW_MusicGroup
                      ON MW_MusicGroup.id = MusicGroup_id
                      LEFT JOIN MW_MusicAlbum
                      ON MW_MusicAlbum.id = MusicAlbum_id
                      
                      ";
                      // Jeg har brugt alias på musigroup.name og musicalbum.name for at kunne vise dem i li tag forneden
                      // send forespørgsel
                      $result = mysqli_query($con, $query);
                      // gennemløb alle rækker i resultatet
                      while ($row = mysqli_fetch_assoc($result)) {
                          ?>

                          <li><?php echo $row['Musikgruppe']; ?> har album <?php echo $row['Album']; ?></li>

                          <?php
                }
        ?>
            </ul>

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
  <?php mysqli_close($con); ?>
</body>
</html>
