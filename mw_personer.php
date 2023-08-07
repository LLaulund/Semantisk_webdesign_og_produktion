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
      <section class="subnav">
        <h1>Releases</h1>
        <a href="#anchor_artist"><button>Kunstnere</button></a>
        <a href="#anchor_bands"><button>Bands</button></a>
      </section>

      <section>
        <h2 id="anchor_artist">Kunstnere</h2>
      </section>
      <section class="grid-container_artists">
        <?php
          // hent personer fra tabellen, og udskriv oversigt
          $query = "SELECT id, givenName, familyName, alternateName, description, image FROM MW_Person ORDER BY givenName";
          // send forespørgsel
          $result = mysqli_query($con, $query);
          // gennemløb alle rækker i resultatet
          while ($row = mysqli_fetch_assoc($result)) {
        ?>
        <div class="grid_item_artists">
          <h3>
            <a href="mw_person.php?id=<?php echo $row['id']; ?>"> 
              <?php echo $row['givenName']; ?> <?php echo $row['familyName']; ?> <?php if ($row['alternateName']== "") {} 
                else { echo "(" .$row['alternateName']. ")";} ?>
            </a>
          </h3>
          <figure>
            <a href="mw_person.php?id=<?php echo $row['id']; ?>"><img class="img-grid-item" src="<?php echo $row['image']; ?>" 
              alt="<?php echo $row['familyName']; ?>">
            </a>
            <figcaption><i><?php echo $row['givenName'];?> <?php echo $row['familyName']; ?></i></figcaption>
          </figure>
          
          <p><?php echo $row['description']; ?></p>
          <figure><a href="mw_person.php?id=<?php echo $row['id']; ?>"><button class="readMore"> Læs mere </button></a></figure>
        </div>
        <?php
          }
        ?>
      </section>

      <!-- I denne section starter vi en ny forespørgsel, da der tænkes at der oprettes en tabel med kunstnere og en med musikgrupper.
          - her oprettes nu en forespørgsel til tabellen MusicGroups -->

      <section>
        <h2 id="anchor_bands">Bands</h2>
      </section>
      <section class="grid-container_artists">
        <?php
          // hent musikgrupper fra tabellen, og udskriv oversigt
          $query = "SELECT id, name, image, description FROM MW_MusicGroup ORDER BY name";
          // send forespørgsel
          $result = mysqli_query($con, $query);
          // gennemløb alle rækker i resultatet
          while ($row = mysqli_fetch_assoc($result)) {
        ?>
        <div class="grid_item_artists">
          <h3><a href="mw_musicgroup.php?id=<?php echo $row['id']; ?>"> <?php echo $row['name']; ?></a></h3>
          <figure>
            <a href="mw_musicgroup.php?id=<?php echo $row['id']; ?>"><img class="img-grid-item" src="<?php echo $row['image']; ?>" 
              alt="<?php echo $row['name']; ?>">
            </a>
            <figcaption><i><?php echo $row['name'];?></i></figcaption>
          </figure>
          
          <p><?php echo $row['description']; ?></p>
          <figure><a href="mw_musicgroup.php?id=<?php echo $row['id']; ?>"> <button class="readMore"> Læs mere </button> </a></figure>
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
