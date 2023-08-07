<!DOCTYPE html>
<html lang="da" dir="ltr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" type="text/css" href="stylev2.css" />
    <!-- Link for Fontawesome ikoner -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <?php
    // kræv indlæsning af forbindelse til databasen
    require_once("../../inc_connect.php")
    ?>
        <?php
            // hent medsendt id
            $id = $_GET['id'];
            // opbyg forespørgsel
                       
            $query =
            "SELECT
                MW_Person.*,
                MW_Place.name AS birthPlace,
                Place2.name AS deathPlace,
                MW_MusicGroup.name AS musicgroup,
                MW_MusicGroup.id AS musicgroup_id,
                MW_MusicAlbum_has_MusicGroup.*,
                MW_MusicAlbum.id, MW_MusicAlbum.name AS albumName,
                MW_MusicRecording.id, MW_MusicRecording.audio
                FROM MW_Person
                LEFT JOIN MW_MusicGroup
                ON MW_Person.musicgroup_id = MW_MusicGroup.id
                LEFT JOIN MW_Place
                ON MW_Person.birthPlace_id = MW_Place.id
                LEFT JOIN MW_Place AS Place2
                ON MW_Person.deathPlace_id = Place2.id
                LEFT JOIN MW_MusicAlbum_has_MusicGroup
                ON MW_Person.MusicGroup_id = MW_MusicAlbum_has_MusicGroup.MusicGroup_id
                LEFT JOIN MW_MusicAlbum
                ON MW_MusicAlbum_has_MusicGroup.MusicAlbum_id = MW_MusicAlbum.id
                LEFT JOIN MW_MusicRecording
                ON MW_MusicRecording.MusicAlbum_id = MW_MusicAlbum.id
                WHERE MW_Person.id = '$id';
            ";
            // send forespørgsel
            $result = mysqli_query($con, $query);
            // hent oplysninger fra resultatet
            $row = mysqli_fetch_assoc($result);
            
        ?>
        <title>MusicWorld - <?php echo $row['givenName']; ?> <?php echo $row['familyName']; ?></title>
        
        
        <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "webpage",
            "name": "MusicWorld: <?php echo $row['givenName']; ?> <?php echo $row['familyName']; ?>",
            "mainEntity": {
                "@type": "Person",
                "sameas": "<?php echo $row['sameAs']; ?>",
                "image": [
                  {
                    "@type": "ImageObject",
                      "url": "<?php echo $row['image']; ?>"
                  }
                ],
                "givenName": "<?php echo $row['givenName']; ?>",
                "familyName": "<?php echo $row['familyName']; ?>",
                "alternateName": "<?php echo $row['alternateName']; ?>",
                "birthPlace": [
                  {
                    "@type": "Place",
                    "name": "<?php echo $row['birthPlace']; ?>"
                  }
                ],
                "birthDate": "<?php echo $row['birthDate']; ?>",
                "deathPlace": [
                  {
                    "@type": "Place",
                    "name": "<?php echo $row['deathPlace']; ?>"
                  }
                ],
                <?php if ( $row['deathDate'] == '0000-00-00') {} else echo '"deathDate": "'. $row['deathDate'].'",'; ?>
                "gender": "<?php if ( $row['gender'] == "m") {echo "Mand"; } else {echo "Kvinde";}?>",
                "description": "<?php echo $row['description']; ?>",

                "memberOf": [
                  {
                    "@type": "MusicGroup",
                    "name": "<?php echo $row['musicgroup']; ?>"
                  }
                ]
                                 
              }
        }
        </script>
    </head>
    <body>
      <nav>
        <div>
          <?php include("inc_menu.php"); ?>
        </div>
      </nav>
        <main>
          <section>
            <h1 id="headline"><?php echo $row['givenName']; ?> <?php echo $row['familyName']; ?></h1>
            <figure id="musikere">
              <img src="<?php echo $row['image']; ?>" alt="<?php echo $row['familyName']; ?>">
              <figcaption><i><?php echo $row['givenName'];?></i> <i><?php echo $row['familyName']; ?></i></figcaption>
            </figure>
          </section>
          <section class="grid-container_artistpage">
            <article class="grid-item_artistpage">
              <div class="responsive_iframe_container">
                <iframe class="responsive_iframe" src="<?php echo $row['audio']; ?>"></iframe>
              </div>
              <p id="description">Beskrivelse: <?php echo nl2br($row['description']); ?> </p>
            </article>
            <article class="grid-item_artistpage">
              <div id="infobox">
                <h1 id="headline"><?php echo $row['givenName']; ?> <?php echo $row['familyName']; ?></h1>
                <figure id="thumb">
                  <img src="<?php echo $row['image']; ?>" alt="<?php echo $row['familyName']; ?>">
                  <figcaption><i><?php echo $row['givenName'];?></i> <i><?php echo $row['familyName']; ?></i></figcaption>
                </figure>
                <h2> Personlig information </h2>
                <b>Født:</b> <?php echo $row['birthDate'];?> i <?php echo $row['birthPlace'];?>
                <br/><br/>
                <?php
                    if ( $row['deathDate'] == "0000-00-00"){
                        
                    } else {
                        echo "<b>Død: </b>" .$row['deathDate']. " i " .$row['deathPlace']. "<br/><br/>";
                    }
                ?>
               
               <?php
                    if ( $row['musicgroup'] != NULL){
                        echo "<b>Medlem af gruppe: </b>" .$row['musicgroup']. "<br/><br/>";
                   
                    }
                ?>
               
                <?php
                    if ( $row['alternateName'] != NULL){
                        echo "<b>Kunstner navn: </b>" .$row['alternateName']. "<br/><br/>";
                   
                    }
                ?>
                
                <b>Køn:</b> <?php if ( $row['gender'] == "m") {echo "Mand"; } else {echo "Kvinde";}?>
                <br/><br/>
                <b>Beskrivelse:</b> <?php echo $row['description'];?>
                
                
                <?php
                  // hvis der er album, skal følgende udskrives
                  if ($row['albumName'] != NULL) {
                      ?>
                  <h2>Album</h2>
                  <b><a href="mw_musicalbum.php?id=<?php echo $row['MusicAlbum_id']; ?>"><?php echo $row['albumName'];?></a></b><br/><br/>
                  <?php
                   $id2 = $row['MusicAlbum_id'];
                    while ($row = mysqli_fetch_assoc($result)) {
                      
                      if ($id2 == $row['MusicAlbum_id']) {
                        
                      } else {
                        echo '<b>  <a href="mw_musicalbum.php?id='. $row['MusicAlbum_id'] .'">'.$row['albumName'] .  '</a></b><br/><br/>';
                      }
                      $id2 = $row['MusicAlbum_id'];
                  }
                    ?>
                <?php
                  } // end if ($row['albumName'] != NULL)
                ?>
                

              </div>
            </article>
          </section>
          <section class="follow_artist">
            <h2>Follow the artist on social media</h2>
            <a href="#" class="fa fa-facebook"></a>
            <a href="#" class="fa fa-twitter"></a>
            <a href="#" class="fa fa-youtube"></a>
            <a href="#" class="fa fa-instagram"></a>
          </section>
        </main>
        <aside>
          <h3 id="anchor_artist">Andre kunstnere</h3>
          <?php
            // hent personer fra tabellen, og udskriv oversigt
            $query = "SELECT id, givenName, familyName, image FROM MW_Person ORDER BY givenName";
            // send forespørgsel
            $result = mysqli_query($con, $query);
            // gennemløb alle rækker i resultatet
            while ($row = mysqli_fetch_assoc($result)) {
            ?>
            <figure class="gallery">
              <a href="mw_person.php?id=<?php echo $row['id']; ?>"><img src="<?php echo $row['image']; ?>"  alt="<?php echo $row['familyName']; ?>" id="thumb"></a>
              <figcaption><i><?php echo $row['givenName'];?> <?php echo $row['familyName']; ?></i></figcaption>
            </figure>
          <?php
            }
          ?>
        </aside>
        <footer>
          <?php include("footer.php");?>
        </footer>
        <script>
          <?php include("navbar_script.js");?>
        </script>
        <?php mysqli_close($con); ?>
    </body>
</html>
