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
<html lang="da">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>MusicWorld - Ret eller slet musikstykke</title>
        <link rel="stylesheet" type="text/css" href="adm_style.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <?php
            // kræv indlæsning af forbindelse til databasen
            require_once("../../inc_connect.php");
        ?>

        <?php
            // tjek, om der skal slettes
            if ($_POST['gem'] == "Slet") {
                // gem medsendte data som php-variabler
                $id = mysqli_real_escape_string($con, $_POST['id']);
                // udfør eventuel sletning eller opdatering i tilknyttede tabeller

                // SLET: så skal dette mustikstykke slettes
                $query = "DELETE FROM MW_MusicRecording WHERE id = '$id'";
                $result = mysqli_query($con, $query);

            }

            // tjek, om der er sendt data med (er der trykket på knappen "gem"?)
            if ($_POST['gem'] == "Gem") {
                // gem medsendte data som php-variabler
                $id = mysqli_real_escape_string($con, $_POST['id']);
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
                $query = "UPDATE MW_MusicRecording SET
                            name = '$name',
                            MusicAlbum_id ='$MusicAlbum_id',
                            duration = '$duration',
                            aggregateRating = '$aggregateRating',
                            copyrightYear = '$copyrightYear',
                            datePublished = '$datePublished',
                            description = '$description',
                            audio = '$audio',
                            image = '$image',
                            sameAs = '$sameAs'
                        WHERE id = '$id'
                    ";
                //echo $query;
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
        <?php
                // hent de parametre, der er sendt med i URL
                if (isset($_GET['id'])) {
                    $id = mysqli_real_escape_string($con, $_GET['id']);
                } else {
                    $id = mysqli_real_escape_string($con, $_POST['id']);
                }
                // søg efter den række, der er valgt ved det medsendte id
                $query = "SELECT * FROM MW_MusicRecording WHERE id = '$id'";
                // send forespørgsel
                $result = mysqli_query($con, $query);
                // træk den række ud, der er fundet
                $row = mysqli_fetch_assoc($result);
            ?>
            <section class="form">
              <h1>MusicWorld: ret eller slet musikstykke</h1>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <input type="hidden" name="id" value="<?php echo $row['id']; ?>" />

                <label for="name">Titel</label>
                <input type="text" name="name" id="name" value="<?php echo $row['name']; ?>" />

                <br/><br/>
                <label for="MusicAlbum_id">I Album</label>
                <br/>
                <select id="MusicAlbum_id" name="MusicAlbum_id">
                    <option value="">Vælg album/option>
                <?php
                    // hent data fra tabellen, og giv mulighed for at vælge
                    $p_query = "SELECT id, name FROM MW_MusicAlbum ORDER BY name";
                    // send forespørgsel
                    $p_result = mysqli_query($con, $p_query);
                    // gennemløb alle rækker i resultatet
                    while ($p_row = mysqli_fetch_assoc($p_result)) {
                        ?>
                        <option value="<?php echo $p_row['id']; ?>"
                        <?php
                                // hvis vi har fat i det album id, der er lig med album id'et fra dette musikstykke så set denne til selected
                                if ($p_row['id']==$row['MusicAlbum_id']) { echo " selected='selected' "; } ?>

                        >
                            <?php echo $p_row['name']; ?>
                        </option>
                        <?php
                    }
                ?>
                </select>
            <br/><br/>
            <label for="duration">Længde</label>
            <input type="text" name="duration" id="duration" value="<?php echo $row['duration']; ?>"/>
            <br/><br/>

            <label for="aggregateRating">Vurdering (Mellem 1 og 10)</label><br/>
            <input type="text" name="aggregateRating" id="aggregateRating" value="<?php echo $row['aggregateRating']; ?>" />
            <br/><br/>

            <label for="copyrightYear">Copyright År (4 cifre)</label><br/>
            <input type="text" name="copyrightYear" id="copyrightYear" value="<?php echo $row['copyrightYear']; ?>" />
            <br/><br/>

            <label for="datePublished">Udgivelses dato (4 cifre)</label><br/>
            <input type="date" name="datePublished" id="datePublished" value="<?php echo $row['datePublished']; ?>"/>
            <br/><br/>

            <label for="description">Beskrivelse</label>
            <textarea id="description" name="description" rows="5" cols="60"><?php echo $row['description']; ?></textarea>
            <br/><br/>

            <label for="audio">Musik URL</label><br/>
            <input type="text" name="audio" id="audio" value="<?php echo $row['audio']; ?>"/>
            <br/><br/>

            <label for="image">Foto (url)</label><br/>
            <input type="text" name="image" id="image" value="<?php echo $row['image']; ?>" />
            <br/><br/>
            <label for="sameAs">Indsæt IRI</label><br/>
            <input type="text" name="sameAs" id="sameAs" value="<?php echo $row['sameAs']; ?>" />
            <br/><br/>

              

                

                <input type="submit" name="gem" id="opdater" value="Gem" />
            </form>


            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <input type="hidden" name="id" id="opdater" value="<?php echo $id; ?>" />
                <input type="submit" name="gem" id="slet" value="Slet" />
            </form>
          </section>
        </main>
        <section class="oversigt">
            <h2>Oversigt</h2>
            <ul>
        <?php
                // hent musikstykker fra tabellen, og udskriv oversigt
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
        <footer>
          <?php include("footer.php");?>
        </footer>
        <script>
          <?php include("navbar_script.js");?>
        </script>
        <?php mysqli_close($con); ?>
      </body>
    </html>
