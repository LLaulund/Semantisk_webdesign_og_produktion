<!DOCTYPE html>
<html lang="da" dir="ltr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>MusicWorld - Homepage</title>
    <link rel="stylesheet" type="text/css" href="stylev2.css" />
    <!-- Link for Fontawesome ikoner -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <?php
      require_once("../../inc_connect.php");
    ?>
  </head>
  <body>
    <nav>
      <div>
        <?php include("inc_menu.php");?>
      </div>
    </nav>
    <main>
      <!-- Carousel code below retrieved from: https://www.geeksforgeeks.org/how-to-make-a-carousel-using-css/  -->
      <section id="carousel">
        <div class="container">
          <div class="content">
            <!-- The content which is placed at the center of the carousel -->
            <div class="carousel-content">
              <h1 class="carousel-heading">MusicWorld</h1>
            </div>
            <div class="slideshow">
              <!-- carousel wrapper which contains all images -->
              <div class="slideshow-wrapper">
                <div class="slide">
                  <!-- Photo by Austin Neill on Unsplash: https://unsplash.com/photos/hgO1wFPXl3I -->
                  <img class="slide-img"src="concert.jpg" alt="Image of a concert">
                </div>
                <div class="slide">
                  <!-- Photo by Chris Ainsworth on Unsplash: https://unsplash.com/photos/7WfcHibcR3Y -->
                  <img class="slide-img" src="concert2.jpg" alt="Image of a concert">
                </div>
                <div class="slide">
                  <!-- Photo by Nainoa Shizuru on Unsplash: https://unsplash.com/photos/NcdG9mK3PBY -->
                  <img class="slide-img" src="concert5.jpg" alt="Image of a concert">
                </div>
                <div class="slide">
                  <!-- Photo by Yvette de Wit on Unsplash: https://unsplash.com/photos/NYrVisodQ2M -->
                  <img class="slide-img" src="concert6.jpg" alt="Image of a concert">
                </div>
              </div>
            </div>
            <p><i>All photos found on Unsplash.com (credit & links in source code)</i></p>
          </div>
        </div>
      </section>
      <section class="grid-container">
        <article class="grid-item">
           <h1>MusicWorld</h1>
          <h2><i>Dit sted for musik</i></h2>
          <p>This website is an exam project done at the University of Southern Denmark.
          </p>
          <p>Vi er MusicWorld dit sted for musik. Her vil du finde informationer og updates
            over dine yndlings musikere og bands, events og meget mere. Oplev musik verdenen
            idag.
          </p>
        </article>
        <section class="grid-item">
          <figure>
            <!-- Photo by Daniel Wirtz on Unsplash: https://unsplash.com/photos/jmmTlAPtNw8 -->
            <img src="concert3.jpg" href ="#" alt="Image of an artist playing at a concert">
            <figcaption><i>Photo by <a href="https://unsplash.com/@danielwirtz?utm_source=unsplash&utm_medium=referral&utm_content=creditCopyText">Daniel Wirtz</a> on <a href="https://unsplash.com/?utm_source=unsplash&utm_medium=referral&utm_content=creditCopyText">Unsplash</a></i></figcaption>
            <br/><br/>
            <a href="mw_musicalbums.php"><button>Albums</button></a>
          </figure>
        </section>
        <section class="grid-item">
          <figure>
            <!-- Photo by Geo Chierchia on Unsplash: https://unsplash.com/photos/o-9-fSSiCT0 -->
            <img src="concert4.jpg" href ="#" alt="Image of an artist playing at a concert">
            <figcaption><i>Photo by <a href="https://unsplash.com/@geochierchia?utm_source=unsplash&utm_medium=referral&utm_content=creditCopyText">Geo Chierchia</a> on <a href="https://unsplash.com/?utm_source=unsplash&utm_medium=referral&utm_content=creditCopyText">Unsplash</a></i></figcaption>
            <br/><br/>
            <a href="mw_musicrecordings.php"><button>Musik</button></a>
          </figure>
        </section>
      </section>
    </main>
    <footer>
      <?php include("footer.php");?>
    </footer>
    <script>
      <?php include("navbar_script.js");?>
    </script>
  </body>
</html>
