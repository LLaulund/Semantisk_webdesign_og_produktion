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
        <title>MusicWorld - Tilføj Musikstykke</title>
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
                $name = mysqli_real_escape_string($con, $_POST['name']);
                $MusicAlbum_id = mysqli_real_escape_string($con, $_POST['MusicAlbum_id']);
                $duration = mysqli_real_escape_string($con, $_POST['duration']);
                $aggregateRating = mysqli_real_escape_string($con, $_POST['aggregateRating']);
                $copyrightYear = mysqli_real_escape_string($con, $_POST['copyrightYear']);
                $datePublished = mysqli_real_escape_string($con, $_POST['datePublished']);
                $description = mysqli_real_escape_string($con, $_POST['description']);
                $audio = mysqli_real_escape_string($con, $_POST['audio']);
                $image = mysqli_real_escape_string($con, $_POST['image']);
                $sameAs = mysqli_real_escape_string($con, $_POST['sameAs']);
                // opbyg forespørsel til at indsætte databasen
                $query = " INSERT INTO MW_MusicRecording (
                        name,
                        MusicAlbum_id,
                        duration,
                        aggregateRating,
                        copyrightYear,
                        datePublished,
                        description,
                        audio,
                        image,
                        sameAs
                        )
                    VALUES (
                        '$name',
                        '$MusicAlbum_id',
                        '$duration',
                        '$aggregateRating',
                        '$copyrightYear',
                        '$datePublished',
                        '$description',
                        '$audio',
                        '$image',
                        '$sameAs'
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
          <h1>Tilføj nye musikstykker</h1>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">

            <label for="name">Titel</label>
            <input type="text" name="name" id="name" />

            <label for="MusicAlbum_id">I album</label><br/>
            <select id="MusicAlbum_id" name="MusicAlbum_id">
                <option value="">Vælg Album</option>
            <?php
                // hent data fra tabellen, og giv mulighed for at vælge
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

            <label for="duration">Længde</label>
            <input type="text" name="duration" id="duration" />
            <br/><br/>

            <label for="aggregateRating">Vurdering (Mellem 1 og 10)</label><br/>
            <input type="text" name="aggregateRating" id="aggregateRating" />
            <br/><br/>

            <label for="copyrightYear">Copyright år (4 cifre)</label><br/>
            <input type="text" name="copyrightYear" id="copyrightYear" />
            <br/><br/>

            <label for="datePublished">Udgivelses dato (4 cifre)</label><br/>
            <input type="date" name="datePublished" id="datePublished" />
            <br/><br/>

            <label for="description">Beskrivelse</label>
            <textarea id="description" name="description" rows="5" cols="60"></textarea>
            <br/><br/>

            <label for="audio">Musik URL</label><br/>
            <input type="text" name="audio" id="audio" />
            <br/><br/>

            <label for="image">Foto (url)</label><br/>
            <input type="text" name="image" id="image" />

            <label for="sameAs">Indsæt IRI</label><br/>
            <input type="text" name="sameAs" id="sameAs" />
            <br/><br/>

            <input type="submit" name="gem" value="Gem" />
        </form>
      </section>
      <section class="oversigt">
            <h2>Oversigt</h2>
            <ul>
        <?php
                // hent steder fra tabellen, og udskriv oversigt
                $query = "SELECT id, name FROM MW_MusicRecording ORDER BY name";
                // send forespørgsel
                $result = mysqli_query($con, $query);
                // gennemløb alle rækker i resultatet
                while ($row = mysqli_fetch_assoc($result)) {
                    ?>

                    <li><?php echo $row['name']; ?>
                      <a href="mw_adm_edit_musicrecording.php?id=<?php echo $row['id']; ?>">[ RET / SLET ]</a></li>

                    <?php
                }
        ?>
            </ul>
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
