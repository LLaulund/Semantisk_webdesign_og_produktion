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
        <h2 id="anchor_artist">Albummer</h2>
      </section>
      <section class="grid-container_artists">
        <?php
          // hent albummer fra tabellen, og udskriv oversigt
          $query = "SELECT id, name, description, image FROM MW_MusicAlbum ORDER BY name";
          // send forespørgsel
          $result = mysqli_query($con, $query);
          // gennemløb alle rækker i resultatet
          while ($row = mysqli_fetch_assoc($result)) {
        ?>
        <div class="grid_item_artists">
          <h3>
            <a href="mw_musicalbum.php?id=<?php echo $row['id']; ?>"> 
              <?php echo $row['name']; ?>
            </a>
          </h3>
          <figure>
            <a href="mw_musicalbum.php?id=<?php echo $row['id']; ?>"><img class="img-grid-item" src="<?php echo $row['image']; ?>"  
              alt="<?php echo $row['name']; ?>">
            </a>
            <figcaption><i><?php echo $row['name'];?></i></figcaption>
          </figure>
          
          <p><?php echo $row['description']; ?></p>
          <figure><a href="mw_musicalbum.php?id=<?php echo $row['id']; ?>"><button class="readMore"> Læs mere </button></a></figure>
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
