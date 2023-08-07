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
                MW_MusicAlbum.*,
                MW_genre.*,
                MW_MusicAlbumReleaseType.*, 
                MW_MusicAlbumProductionType.*, 
                MW_MusicRecording.id AS musikstykkeId, MW_MusicRecording.name AS musikstykke, MW_MusicRecording.audio,
                MW_Organization.id, MW_Organization.name AS pladeselsskab,
                MW_MusicGroup.name AS musicgroup,
                MW_MusicGroup.id AS musicgroup_id,
                MW_MusicAlbum_has_MusicGroup.*
                FROM MW_MusicAlbum
                LEFT JOIN MW_genre
                ON MW_MusicAlbum.genre_id = MW_genre.id
                LEFT JOIN MW_MusicAlbumReleaseType
                ON MW_MusicAlbum.MusicAlbumReleaseType_id = MW_MusicAlbumReleaseType.id
                LEFT JOIN MW_MusicAlbumProductionType
                ON MW_MusicAlbum.MusicAlbumProductionType_id = MW_MusicAlbumProductionType.id
                LEFT JOIN MW_MusicRecording
                ON MW_MusicRecording.MusicAlbum_id = MW_MusicAlbum.id
                LEFT JOIN MW_Organization
                ON MW_MusicAlbum.Organization_id = MW_Organization.id
                LEFT JOIN MW_MusicAlbum_has_MusicGroup
                ON MW_MusicAlbum.id = MW_MusicAlbum_has_MusicGroup.MusicAlbum_id
                LEFT JOIN MW_MusicGroup
                ON MW_MusicAlbum_has_MusicGroup.MusicGroup_id = MW_MusicGroup.id
                WHERE MW_MusicAlbum.id = '$id';
            ";
            // send forespørgsel
            $result = mysqli_query($con, $query);
            // hent oplysninger fra resultatet
            $row = mysqli_fetch_assoc($result);
            $antal = mysqli_num_rows($result);                  
        ?>

        <title>MusicWorld - <?php echo $row['name']; ?> </title>
        
        
        <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "webpage",
            "name": "MusicWorld: <?php echo $row['name']; ?>",
            "mainEntity": {
                "@type": "MusicAlbum",
                "name": "<?php echo $row['name']; ?>",
                "sameas": "<?php echo $row['sameAs']; ?>",
                "image": [
                  {
                    "@type": "ImageObject",
                      "url": "<?php echo $row['image']; ?>"
                  }
                ],
                "numTracks": "<?php echo $row['numTracks']; ?>",
                "albumReleaseType": "<?php echo $row['albumReleaseType']; ?>",
                "albumProductionType": "<?php echo $row['albumProductionType']; ?>",
                <?php if ( $row['datePublished'] == "0000-00-00"){} else {echo '"datePublished": "'.$row['datePublished']. '",';} ?>
                "description": "<?php echo $row['description']; ?>",
                "genre": "<?php echo $row['text']; ?>",
                "producer": [
                  {
                    "@type": "Organization",
                      "name": "<?php echo $row['pladeselsskab']; ?>"
                  }
                ],
                "byArtist": [
                  {
                    "@type": "MusicGroup",
                      "name": "<?php echo $row['musicgroup']; ?>"
                  }],
                
                  "track": {
                    "@type": "ItemList",
                    "itemListElement": [
                      {
                        "@type":"ListItem",        
                          "item": {
                            "@type": "MusicRecording",
                            "name": "<?php echo $row['musikstykke']; ?>"

                          }
                      },
                      
                        <?php
                        $i = 1; // tæller
                        
                        while ($row = mysqli_fetch_assoc($result)) {
                          $i++; // læg 1 til $i
                            echo 
                            
                            '{
                              "@type": "ListItem",        
                              "item": {
                                "@type": "MusicRecording",
                                "name": "'.$row['musikstykke'] . '"}
					                }';
                          
                          
                          
                          if ($antal > $i) {
                                echo ",";
                            }
                        } // end while 
                        ?>                       
                    ],
                    "numberOfItems": "<?php echo $antalalbums; ?>"
                  }

                
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
            <h1 id="headline"><?php echo $row['name']; ?></h1>
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
                    
                    <b>Kunstner/Band:</b> <?php echo $row['musicgroup'];?>
                    <br/><br/>
                    <b>Pladeselskab:</b> <?php echo $row['pladeselsskab'];?>
                    <br/><br/>
                    <b>Antal spor:</b> <?php echo $row['numTracks'];?> 
                    <br/><br/>
                    <?php
                        if ( $row['datePublished'] == "0000-00-00"){                           
                        } else {
                            echo "<b>Udgivet: </b>" .$row['datePublished']. "<br/><br/>";
                        }
                    ?>
                                
                    <b>Produktionstype:</b> <?php echo $row['albumProductionType'];?>
                    <br/><br/>
                    <b>Udgivelsestype:</b> <?php echo $row['albumReleaseType'];?>
                    <br/><br/>
                    <b>Genre:</b> <?php echo $row['text'];?> 
                    <br/><br/>
                    <b>Beskrivelse:</b> <?php echo $row['description'];?>
                    
                    <?php
                    // hvis der er et musikstykke, skal følgende udskrives
                    if ($row['musikstykke'] != NULL) {
                        ?>
                    <h2>Sange på Albummet</h2>
                        <b>
                        <a href="mw_musicrecording.php?id=<?php echo $row['musikstykkeId']; ?>">
                        <?php echo $row['musikstykke']; ?> 
                        </a></b>
                        <br/><br/>
                        <?php
                        // gennemløb alle rækker i resultatet (første række ER allerede hentet og udskrevet)
                        
                        while ($row = mysqli_fetch_assoc($result)) {
                        // udskriv musikstykke
                        echo '<b>  <a href="mw_musicrecording.php?id='. $row['musikstykkeId'] .'">'.$row['musikstykke'] .  
                        '</a></b><br/><br/>';
                        }                     
                        ?>
                    <?php
                    } // end if ($row['musikstykke'] != NULL)
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
          <h3 id="anchor_artist">Andre Album</h3>
          <?php
            // hent Albummer fra tabellen, og udskriv oversigt
            $query = "SELECT id, name, image FROM MW_MusicAlbum ORDER BY name";
            // send forespørgsel
            $result = mysqli_query($con, $query);
            // gennemløb alle rækker i resultatet
            
            while ($row = mysqli_fetch_assoc($result)) {
            ?>
            <figure class="gallery">
              <a href="mw_musicalbum.php?id=<?php echo $row['id']; ?>"><img src="<?php echo $row['image']; ?>"  
                alt="<?php echo $row['name']; ?>" id="thumb">
              </a>
              <figcaption><i><?php echo $row['name'];?> </i></figcaption>
            </figure>
          <?php
            } // end while
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
