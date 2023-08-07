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
                MW_MusicGroup.*,
                MW_Place.name AS place,
                MW_MusicAlbum_has_MusicGroup.*,
                MW_MusicAlbum.id, MW_MusicAlbum.name AS albumName,
                MW_MusicRecording.id, MW_MusicRecording.audio
                FROM MW_MusicGroup
                LEFT JOIN MW_Place
                ON MW_MusicGroup.Place_id = MW_Place.id
                LEFT JOIN MW_MusicAlbum_has_MusicGroup
                ON MW_MusicGroup.id = MW_MusicAlbum_has_MusicGroup.MusicGroup_id
                LEFT JOIN MW_MusicAlbum
                ON MW_MusicAlbum_has_MusicGroup.MusicAlbum_id = MW_MusicAlbum.id
                LEFT JOIN MW_MusicRecording
                ON MW_MusicRecording.MusicAlbum_id = MW_MusicAlbum.id
                WHERE MW_MusicGroup.id = '$id';
            ";
            // send forespørgsel
            $result = mysqli_query($con, $query);
            // hent oplysninger fra resultatet
            $row = mysqli_fetch_assoc($result);
            $antal = mysqli_num_rows($result);
            
        ?>
        <title>MusicWorld - <?php echo $row['name']; ?></title>             

        <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "webpage",
            "name": "MusicWorld: <?php echo $row['name']; ?>",
            "mainEntity": {
                "@type": "MusicGroup",
                "name": "<?php echo $row['name']; ?>",
                "sameas": "<?php echo $row['sameAs']; ?>",
                "image": [
                  {
                    "@type": "ImageObject",
                      "url": "<?php echo $row['image']; ?>"
                  }
                ],
                "description": "<?php echo $row['description']; ?>",
                "foundingDate": "<?php echo $row['foundingDate']; ?>",
                <?php if ( $row['dissolutionDate'] == '0000-00-00') { } else echo '"dissolutionDate":"' .$row['dissolutionDate'].'",'; ?>

                

                "foundingLocation": [
                  {
                    "@type": "Place",
                      "name": "<?php echo $row['place']; ?>"
                  }
                ],
                
                "album":[ 
                                                   
                  <?php
                  $i = 1; // tæller
                  
                  while ($row= mysqli_fetch_assoc($result)) {
                    $i++; // læg 1 til $i
                    if ($id2 == $row['MusicAlbum_id']) {
                     
                    } else {
                      echo 
                        
                      '{                                  
                        "@type": "MusicAlbum",
                        "name": "'.$row['albumName'] . '"
                      }';
                      
                      if ($antal > $i) {
                            echo ",";
                        }
                    } // end else
                    $id2 = $row['MusicAlbum_id'];
                  } // end while 
                ?>                       
                                   
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
           <!-- reset mærkør i resultatsættet, så vi igen starte fra plads 0 -->
            <?php mysqli_data_seek($result,0);
                $row=mysqli_fetch_assoc($result);
            ?>
          <section>
            <h1 id="headline"><?php echo $row['name']; ?> </h1>
            <figure id="musikere">
              <img src="<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>">
              <figcaption><i><?php echo $row['name'];?></i> </figcaption>
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
                <h1 id="headline"><?php echo $row['name']; ?> </h1>
                <figure id="thumb">
                  <img src="<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>">
                  <figcaption><i><?php echo $row['name'];?></i> </figcaption>
                </figure>
                <h2> Information </h2>
                <b>Stiftet</b> <?php echo $row['foundingDate'];?> i <?php echo $row['place'];?>
                <br/><br/>
                <?php
                    if ( $row['dissolutionDate'] == "0000-00-00"){
                        
                    } else {
                        echo "<b>Opløst: </b>" .$row['dissolutionDate']. "<br/><br/>";
                    }
                ?>
               
               
                
                <b>Beskrivelse:</b> <?php echo $row['description'];?>
                <br/><br/>
                
                
                <?php
                  // hvis der er et album, skal følgende udskrives
                  if ($row['albumName'] != NULL) {
                      ?>
                  <h2>Albummer</h2>

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
          <h3 id="anchor_artist">Andre bands</h3>
          <?php
            // hent musikgrupper fra tabellen, og udskriv oversigt
            $query = "SELECT id, name, image FROM MW_MusicGroup ORDER BY name";
            // send forespørgsel
            $result = mysqli_query($con, $query);
            // gennemløb alle rækker i resultatet
            while ($row = mysqli_fetch_assoc($result)) {
            ?>
            <figure class="gallery">
              <a href="mw_musicgroup.php?id=<?php echo $row['id']; ?>"><img src="<?php echo $row['image']; ?>"  
                alt="<?php echo $row['name']; ?>" id="thumb">
              </a>
              <figcaption><i><?php echo $row['name'];?> </i></figcaption>
            </figure>
          <?php
            }// end while
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
