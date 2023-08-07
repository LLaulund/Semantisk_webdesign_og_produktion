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
        <title>MusicWorld - Rediger kunstner - </title>
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



                // eventuelle Musicrecording og album relationer
                $query = "DELETE FROM MW_MusicRecording WHERE MusicAlbum_id = '$id'";
                $result = mysqli_query($con, $query);

                // eventuelle musicgrupper
                // slet værk-person-relationer, inden værket slettes - dvs. vi skal bruge værkets id før vi sletter
                $query = "SELECT MusicGroup_id FROM MW_MusicAlbum_has_MusicGroup WHERE MusicAlbum_id = '$id'";
                $result = mysqli_query($con, $query);
                while ($row=mysqli_fetch_assoc($result)) {
                    $work_id = $row['MusicGroup_id'];
                    $query = "DELETE FROM MW_MusicAlbum_has_MusicGroup WHERE MusicGroup_id = '$work_id'AND MusicAlbum_id = '$id' ";
                    $result = mysqli_query($con, $query);
                }




                // slet
                $query = "DELETE FROM MW_MusicAlbum WHERE id = '$id'";
                $result = mysqli_query($con, $query);
            }

            // tjek, om der er sendt data med (er der trykket på knappen "gem"?)
            if ($_POST['gem'] == "Opdatér") {
                // gem medsendte data som php-variabler
                $id = mysqli_real_escape_string($con, $_POST['id']);
                $name = mysqli_real_escape_string($con, $_POST['name']);
                $numTracks = mysqli_real_escape_string($con, $_POST['numTracks']);
                $image = mysqli_real_escape_string($con, $_POST['image']);
                $sameAs = mysqli_real_escape_string($con, $_POST['sameAs']);
                $datePublished = mysqli_real_escape_string($con, $_POST['datePublished']);
                $MusicAlbumProductionType_id = mysqli_real_escape_string($con, $_POST['MusicAlbumProductionType_id']);
                $MusicAlbumReleaseType_id = mysqli_real_escape_string($con, $_POST['MusicAlbumReleaseType_id']);
                $genre_id = mysqli_real_escape_string($con, $_POST['genre_id']);
                $Organization_id = mysqli_real_escape_string($con, $_POST['Organization_id']);
                $description = mysqli_real_escape_string($con, $_POST['description']);
                // opbyg forespørsel til at indsætte databasen
                $query = "UPDATE
                        MW_MusicAlbum SET
                            name = '$name',
                            numTracks = '$numTracks',
                            image = '$image',
                            sameAs = '$sameAs',
                            datePublished ='$datePublished',
                            MusicAlbumProductionType_id = '$MusicAlbumProductionType_id',
                            MusicAlbumReleaseType_id = '$MusicAlbumReleaseType_id',
                            genre_id = '$genre_id',
                            Organization_id ='$Organization_id',
                            description = '$description'
                        WHERE id = '$id'
                    ";
                 //echo $query;
                // send forespørgsel til server, og gem resultat i $result
                $result = mysqli_query($con, $query);
                if ($result) {
                    echo "<p>Data opdateret</p>";
                } else {
                    echo "<p>Data ikke opdateret</p>";
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

                $id = mysqli_real_escape_string($con, $_GET['id']);
                // søg efter den række, der er valgt ved det medsendte id
                $query = "SELECT * FROM MW_MusicAlbum WHERE id = '$id'";
                // send forespørgsel
                $result = mysqli_query($con, $query);
                // træk den række ud, der er fundet
                $row = mysqli_fetch_assoc($result);
            ?>
            <section class="form">
              <h1>MusicWorld: ret eller slet musik album</h1>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">

                <input type="hidden" name="id" id="id" value="<?php echo $id; ?>" />

                <label for="name">Navn</label>
                <input type="text" id="name" name="name" value="<?php echo $row['name']; ?>"/>

                <label for="numTracks">Antal sange</label>
                <input type="text" id="numTracks" name="numTracks" value="<?php echo $row['numTracks']; ?>"/>

                <label for="image">Billede url</label>
                <input type="text" id="image" name="image" value="<?php echo $row['image']; ?>"/>

                <label for="sameAs">Album IRI</label>
                <input type="text" id="sameAs" name="sameAs" value="<?php echo $row['sameAs']; ?>"/>

                <label for="datePublished">Publikationsdato</label>
                <input type="date" id="datePublished" name="datePublished" value="<?php echo $row['datePublished']; ?>"/>

                <label for="description">Beskrivelse</label>
                <textarea id="description" name="description" rows="5" cols="60"><?php echo $row['description']; ?></textarea>

                <label for="MusicAlbumProductionType_id">Albummets produktionstype</label> <br/>
                <select id="MusicAlbumProductionType_id" name="MusicAlbumProductionType_id">
                    <option value="">Vælg produktionstype</option>
                <?php
                    // hent steder fra tabellen, og giv mulighed for at vælge sted
                    $place_query = "SELECT * FROM MW_MusicAlbumProductionType ORDER BY albumProductionType";
                    // send forespørgsel
                    $place_result = mysqli_query($con, $place_query);
                    // gennemløb alle rækker i resultatet
                    while ($place_row = mysqli_fetch_assoc($place_result)) {
                        ?>
                        <option
                            value="<?php echo $place_row['id']; ?>"
                            <?php
                            // hvis denne option er den samme, som er gemt i databasen, skal den forvælges
                            if ($row['MusicAlbumProductionType_id'] == $place_row['id']) {
                                echo " selected='selected'";
                            }
                            ?>
                        >
                            <?php echo $place_row['albumProductionType']; ?>
                        </option>
                        <?php
                    }
                ?>
                </select>
                <br/><br/>
                <label for="MusicAlbumReleaseType_id">Albummets udgivelsestype</label> <br/>
                <select id="MusicAlbumReleaseType_id" name="MusicAlbumReleaseType_id">
                    <option value="">Vælg udgivelsestype</option>
                <?php
                    // hent steder fra tabellen, og giv mulighed for at vælge sted
                    $place_query = "SELECT * FROM MW_MusicAlbumReleaseType ORDER BY albumReleaseType";
                    // send forespørgsel
                    $place_result = mysqli_query($con, $place_query);
                    // gennemløb alle rækker i resultatet
                    while ($place_row = mysqli_fetch_assoc($place_result)) {
                        ?>
                        <option
                            value="<?php echo $place_row['id']; ?>"
                            <?php
                            // hvis denne option er den samme, som er gemt i databasen, skal den forvælges
                            if ($row['MusicAlbumReleaseType_id'] == $place_row['id']) {
                                echo " selected='selected'";
                            }
                            ?>
                        >
                            <?php echo $place_row['albumReleaseType']; ?>
                        </option>
                        <?php
                    }
                ?>
                </select>
                <br/><br/>
                <label for="genre_id">Albummets genre</label><br/>
                <select id="genre_id" name="genre_id">
                    <option value="">Vælg genre</option>
                <?php
                    // hent steder fra tabellen, og giv mulighed for at vælge sted
                    $place_query = "SELECT * FROM MW_genre";
                    // send forespørgsel
                    $place_result = mysqli_query($con, $place_query);
                    // gennemløb alle rækker i resultatet
                    while ($place_row = mysqli_fetch_assoc($place_result)) {
                        ?>
                        <option
                            value="<?php echo $place_row['id']; ?>"
                            <?php
                            // hvis denne option er den samme, som er gemt i databasen, skal den forvælges
                            if ($row['genre_id'] == $place_row['id']) {
                                echo " selected='selected'";
                            }
                            ?>
                        >
                            <?php echo $place_row['text']; ?>
                        </option>
                        <?php
                    }
                ?>
                </select>
                <br/><br/>
                <label for="Organization_id">Albummets Pladeselskab</label><br/>
                <select id="Organization_id" name="Organization_id">
                    <option value="">Vælg Pladeselskab</option>
                <?php
                    // hent steder fra tabellen, og giv mulighed for at vælge sted
                    $place_query = "SELECT * FROM MW_Organization ORDER BY name";
                    // send forespørgsel
                    $place_result = mysqli_query($con, $place_query);
                    // gennemløb alle rækker i resultatet
                    while ($place_row = mysqli_fetch_assoc($place_result)) {
                        ?>
                        <option
                            value="<?php echo $place_row['id']; ?>"
                            <?php
                            // hvis denne option er den samme, som er gemt i databasen, skal den forvælges
                            if ($row['Organization_id'] == $place_row['id']) {
                                echo " selected='selected'";
                            }
                            ?>
                        >
                            <?php echo $place_row['name']; ?>
                        </option>
                        <?php
                    }
                ?>
                </select>
                <br/><br/>

                <input type="submit" name="gem" id="opdater" value="Opdatér" />
            </form>


            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <input type="hidden" name="id" value="<?php echo $id; ?>" />
                <input type="submit" name="gem" id="slet" value="Slet" />
            </form>
          </section>
        </main>
          <section class="oversigt">
            <h2>Oversigt</h2>
            <ul>
        <?php
                // hent steder fra tabellen, og udskriv oversigt
                $query = "SELECT id, name  FROM MW_MusicAlbum ORDER BY name";
                // send forespørgsel
                $result = mysqli_query($con, $query);
                // gennemløb alle rækker i resultatet
                while ($row = mysqli_fetch_assoc($result)) {
                    ?>

                    <li>
                        <?php echo $row['name']; ?>
                        <!-- link til at rette -->
                        <a href="mw_adm_edit_musicalbum.php?id=<?php echo $row['id']; ?>">[RET / SLET]</a>
                    </li>

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
