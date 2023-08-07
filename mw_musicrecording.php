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
            require_once("../../inc_connect.php");
        ?>
        <?php
            // hent medsendt id
            $id = $_GET['id'];
            // opbyg forespørgsel
            $query =
            "SELECT 
            MW_MusicRecording.*,
            MW_MusicAlbum.name AS album,
            MW_MusicAlbum.id,
            MW_MusicAlbum_has_MusicGroup.*,
            MW_MusicGroup.id, 
            MW_MusicGroup.name AS musikgruppe
            FROM MW_MusicRecording
            LEFT JOIN MW_MusicAlbum
            ON MW_MusicRecording.MusicAlbum_id = MW_MusicAlbum.id 
            LEFT JOIN MW_MusicAlbum_has_MusicGroup
            ON MW_MusicAlbum.id = MW_MusicAlbum_has_MusicGroup.MusicAlbum_id
            LEFT JOIN MW_MusicGroup
            ON MW_MusicAlbum_has_MusicGroup.MusicGroup_id = MW_MusicGroup.id
            
            WHERE MW_MusicRecording.id = '$id'
            ";
            // send forespørgsel
            $result = mysqli_query($con, $query);
            // hent oplysninger fra resultatet
            $row = mysqli_fetch_assoc($result);
        ?>
        <title>MusicWorld - <?php echo $row['name']; ?></title>
        <script type="application/ld+json">
          {
            "@context": "https://schema.org",
            "@type": "webpage",
            "name": "MusicWorld: <?php echo $row['name']; ?>",
            "mainEntity": {
                "@type": "MusicRecording",
                "name": "<?php echo $row['name']; ?>",
                "sameas": "<?php echo $row['sameAs']; ?>",
                <?php if ($row['image'] != ""){ echo '"image": [ { "@type": "ImageObject","url": "'.$row['image'].'"}],' ;}?>
                "duration": "<?php echo $row['duration']; ?>",
                <?php if($row['aggregateRating'] != "0") { echo '"aggregateRating": { "@type":"AggregateRating","ratingValue":' .$row['aggregateRating']. '"},' ;}?>
                <?php if ( $row['datePublished'] == "0000-00-00"){} else {echo '"datePublished": "'.$row['datePublished']. '",';} ?>
                "description": "<?php echo $row['description']; ?>",
                "audio":[ 
                  {"@type": "AudioObject",
                    "contentUrl":"<?php echo $row['audio']; ?>"
                  } ],
                "inAlbum": [
                  {
                    "@type": "MusicAlbum",
                      "name": "<?php echo $row['album']; ?>"
                  } ],

                "byArtist": [
                  {
                    "@type": "MusicGroup",
                      "name": "<?php echo $row['musikgruppe']; ?>"
                  }]
              }
          }
        </script>
        
    </head>
    <body>
      <header>
        <nav>
          <div>
            <?php include("inc_menu.php"); ?>
          </div>
        </nav>
      </header>
      <main>
        <h1 id="headline"><?php echo $row['name']; ?> (<?php echo $row['copyrightYear']; ?>)</h1>
        <div class="testframe">
          <div class="responsive_iframe_container">
            <iframe class="responsive_iframe" src="<?php echo $row['audio']; ?>"></iframe>
          </div>
        </div>
        <section id="facts">
          <article>
            <p><?php echo $row['description']; ?></p>
            <h2>Om musikstykket</h2>
            
              <b>Nummer:</b> <?php echo ucfirst($row['name']); ?> 
              fra ablummet 
              "<a href="mw_musicalbum.php?id=<?php echo $row['MusicAlbum_id']; ?>">
                  <?php echo $row['album']; ?> 
                </a>"
               af 
               <a href="mw_musicgroup.php?id=<?php echo $row['MusicGroup_id']; ?>">
                  <?php echo $row['musikgruppe']; ?> 
                </a>
            
              <br/><br/>
              <b>Længde:</b> <?php echo $row['duration']; ?> minutter
              <br/><br/>
              <b>Copyright år:</b> <?php echo $row['copyrightYear']; ?>
              <br/><br/>
              <?php
                    if ( $row['datePublished'] == "0000-00-00"){
                    } else {
                        echo "<b>Udgivet: </b>" .$row['datePublished']. "<br/><br/>";
                    }
                ?>
               
               <?php
                    if ( $row['aggregateRating'] != "0"){
                        echo "<b>Bedømmelse (Mellem 1 og 10): </b>" .$row['aggregateRating']. "<br/><br/>";
                      }
                ?>                          

            </article>
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
