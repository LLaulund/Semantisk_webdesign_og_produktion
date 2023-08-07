<?php
// Code fundet på: https://www.tutorialrepublic.com/php-tutorial/php-mysql-login-system.php
// Initialize the session
session_start();

//Check if the user is logged in, if not then redirect him to login page
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
    <title>MusicWorld - Tilføj Album</title>
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
                $query = "
                    INSERT INTO MW_MusicAlbum (
                        name,
                        numTracks,
                        image,
                        sameAs,
                        datePublished,
                        MusicAlbumProductionType_id,
                        MusicAlbumReleaseType_id,
                        genre_id,
                        Organization_id, 
                        description)
                    VALUES (
                        '$name', 
                        '$numTracks',
                        '$image',
                        '$sameAs',
                        '$datePublished',
                        '$MusicAlbumProductionType_id',
                        '$MusicAlbumReleaseType_id',
                        '$genre_id',
                        '$Organization_id',
                        '$description')
                    ";
                    
                    // send forspørgslen
                    $result = mysqli_query($con, $query);
                    
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
          <h1>Tilføj album her</h1>
          
          <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <label for="name">Navn</label>
            <input type="text" id="name" name="name" />
            
            <label for="numTracks">Antal sange</label>
            <input type="text" id="numTracks" name="numTracks" />
            
            <label for="image">Billede url</label>
            <input type="text" id="image" name="image" />

            <label for="sameAs">Album IRI</label>
            <input type="text" id="sameAs" name="sameAs" />

            <label for="datePublished">Publikationsdato</label>
            <input type="date" id="datePublished" name="datePublished" />

            <label for="description">Beskrivelse</label>
            <textarea id="description" name="description" rows="5" cols="60"></textarea>

            <label for="MusicAlbumProductionType_id">Albummets produktionstype</label><br/>
              <select id="MusicAlbumProductionType_id" name="MusicAlbumProductionType_id">
                  <option value="">Vælg produktionstype</option>
              <?php
                  // hent fra tabellen, og giv mulighed for at vælge
                  $query = "SELECT * FROM MW_MusicAlbumProductionType ORDER BY albumProductionType";
                  // send forespørgsel
                  $result = mysqli_query($con, $query);
                  // gennemløb alle rækker i resultatet
                  while ($row = mysqli_fetch_assoc($result)) {
                      ?>
                      <option value="<?php echo $row['id']; ?>">
                          <?php echo $row['albumProductionType']; ?>
                      </option>
                      <?php
                  }
              ?>
              </select>
              
              <br/><br/>
              <label for="MusicAlbumReleaseType_id">Albummets udgivelsestype</label><br/>
              <select id="MusicAlbumReleaseType_id" name="MusicAlbumReleaseType_id">
                  <option value="">Vælg udgivelsestype</option>
              <?php
                  // hent fra tabellen, og giv mulighed for at vælge
                  $query = "SELECT * FROM MW_MusicAlbumReleaseType ORDER BY albumReleaseType";
                  // send forespørgsel
                  $result = mysqli_query($con, $query);
                  // gennemløb alle rækker i resultatet
                  while ($row = mysqli_fetch_assoc($result)) {
                      ?>
                      <option value="<?php echo $row['id']; ?>">
                          <?php echo $row['albumReleaseType']; ?>
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
                  // hent fra tabellen, og giv mulighed for at vælge
                  $query = "SELECT * FROM MW_genre";
                  // send forespørgsel
                  $result = mysqli_query($con, $query);
                  // gennemløb alle rækker i resultatet
                  while ($row = mysqli_fetch_assoc($result)) {
                      ?>
                      <option value="<?php echo $row['id']; ?>">
                          <?php echo $row['text']; ?>
                      </option>
                      <?php
                  }
              ?>
              </select>
              <br/><br/>
              <label for="Organization_id">Pladeselsskab</label><br/>
              <select id="Organization_id" name="Organization_id">
                  <option value="">Vælg pladeselsskab</option>
              <?php
                  // hent fra tabellen, og giv mulighed for at vælge
                  $query = "SELECT * FROM MW_Organization ORDER BY name";
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


            <input type="submit" name="gem" value="Gem"/>
          </form>
        </section>

        <section class="oversigt">
            <h2>Oversigt</h2>
            <ul>
        <?php
                // hent steder fra tabellen, og udskriv oversigt
                $query = "SELECT id, name FROM MW_MusicAlbum ORDER BY name";
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
