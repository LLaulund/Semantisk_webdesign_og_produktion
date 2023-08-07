<!DOCTYPE html>
<html lang="da" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>MusicWorld - Information</title>
    <link rel="stylesheet" href="stylev2.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  </head>
  <body class="outer-grid-container">
    <header>
      <nav>
        <div>
          <?php include("inc_menu.php"); ?>
        </div>
      </nav>
    </header>
    <main>
      <article>
        <h1 id="headline">About Musicworld</h1>
        <p>
          MusicWorld is a fictional website created for the exam at the University of southern Denmark.
          The Website contains information about artists and bands as well as songs and albums. The information about
          the artist, songs and bands is adapted from Wikipedia and the images taken from Wikipedia or Unsplash. </p>

        <h2>Webservice</h2>
        <p>As part of the exam, a webservice was created which can be accessed here.</p>
        <a href="musicworld_ws.php"><button class="readMore"> Webservice </button></a>
      </article>
    </main>
    <footer id="footer">
      <?php include("footer.php");?>
    </footer>
    <script>
      <?php include("navbar_script.js");?>
    </script>
  </body>
</html>
