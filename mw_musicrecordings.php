<!DOCTYPE html>
<html lang="da" dir="ltr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>MusicWorld - Kunstnere</title>
    <link rel="stylesheet" type="text/css" href="stylev2.css" />
    <!-- Link for Fontawesome ikoner -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <?php
    // kræv indlæsning af forbindelse til databasen
    require_once("../../inc_connect.php")
    ?>
  </head>
  <body>
    <header>
      <nav>
        <div>
          <?php include("inc_menu.php"); ?>
        </div>
      </nav>
    </header>
    <main class="outer-grid-container">
      <!-- Code for anchor link retrieved from: https://www.w3docs.com/snippets/html/how-to-create-an-anchor-link-to-jump-to-a-specific-part-of-a-page.html -->
      <section>
        <h2 id="anchor_artist">Sange</h2>
      </section>
      <section class="grid-container_artists">
        <?php
          // hent musikstykker fra tabellen, samt albumnavn for tabellen MW_Album, og udskriv oversigt
          $query = "SELECT 
          MW_MusicRecording.id,
          MW_MusicRecording.name AS musikstykke,
          MW_MusicRecording.audio,
          MW_MusicRecording.copyrightYear,
          MW_MusicRecording.MusicAlbum_id,
          MW_MusicAlbum.name AS album
          FROM MW_MusicRecording
          LEFT JOIN MW_MusicAlbum
          ON MW_MusicRecording.MusicAlbum_id = MW_MusicAlbum.id               
          ORDER BY 'musikstykke';
        ";

          // send forespørgsel
          $result = mysqli_query($con, $query);
          // gennemløb alle rækker i resultatet
          while ($row = mysqli_fetch_assoc($result)) {
        ?>
        <div class="grid_item_music">
          <div class="responsive_iframe_container">
            <iframe class="responsive_iframe" src="<?php echo $row['audio']; ?>"></iframe>
          </div>
          <h3 id="musik_titel">
            <a href="mw_musicrecording.php?id=<?php echo $row['id']; ?>"> 
              <?php echo $row['musikstykke']; ?>
            </a>
          </h3>
          <h4>Album: <?php echo $row['album'];?></h4>
          <h4><?php echo $row['copyrightYear'];?></h4>
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
