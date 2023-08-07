<?php
// Code fundet på: https://www.tutorialrepublic.com/php-tutorial/php-mysql-login-system.php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="da" dir="ltr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>MusicWorld - Tilføj Musikgruppe </title>
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
                $foundingDate = mysqli_real_escape_string($con, $_POST['foundingDate']);
                $dissolutionDate = mysqli_real_escape_string($con, $_POST['dissolutionDate']);
                $Place_id = mysqli_real_escape_string($con, $_POST['Place_id']);
                $image = mysqli_real_escape_string($con, $_POST['image']);
                $description = mysqli_real_escape_string($con, $_POST['description']);
                $sameAs = mysqli_real_escape_string($con, $_POST['sameAS']);
                // opbyg forespørsel til at indsætte databasen
                $query = "
                    INSERT INTO MW_MusicGroup (
                        name,
                        foundingDate,
                        dissolutionDate,
                        Place_id,
                        image,
                        description,
                        sameAs
                        )
                    VALUES (
                        '$name',
                        '$foundingDate',
                        '$dissolutionDate',
                        '$Place_id',                        
                        '$image',
                        '$description',
                        '$sameAs'
                        )
                    ";
                // send forespørgsel til server, og gem resultat i $result
                $result = mysqli_query($con, $query);
                if ($result) {
                    echo "<p>Data indsat</p>";
                } else {
                    echo "<p>Data ikke indsat</p>";
                }
            }
        ?>
    </head>
    <body>
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
          <h1>Tilføj nye musikgrupper her</h1>
          <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            
            <label for="name">Navn</label>
            <input type="text" name="name" id="name"/>
            <br/><br/>
            
            <label for="foundingDate">Stiftelsesdato</label>
            <input type="date" name="foundingDate" id="foundingDate"/>
            <br/><br/>
            <label for="dissolutionDate">Opløsningsdato</label>
            <input type="date" name="dissolutionDate" id="dissolutionDate" />
            <br/><br/>
            <label for="Place_id">Sted</label> <br/>
            <select id="Place_id" name="Place_id">
                <option value="">Vælg sted</option>
            <?php
                // hent steder fra tabellen, og giv mulighed for at vælge sted
                $query = "SELECT * FROM MW_Place ORDER BY name";
                // send forespørgsel
                $place_result = mysqli_query($con, $query);
                // gennemløb alle rækker i resultatet
                while ($row = mysqli_fetch_assoc($place_result)) {
                    ?>
                    <option value="<?php echo $row['id']; ?>">
                        <?php echo $row['name']; ?>
                    </option>
                    <?php
                }
            ?>
            </select>
            <br/><br/>          
                                  
            
            <label for="image">Foto (url)</label><br />
            <input type="text" name="image" id="image" /><br />
            <br/><br/>
            <label for="sameAs">Tilføj IRI</label><br />
            <input type="text" name="sameAs" id="sameAs" /><br />
            <br/><br/>

            <label for="description">Beskrivelse</label><br />
            <textarea id="description" name="description" rows="5" cols="60"></textarea><br />
            <br/><br/>
            <input type="submit" name="gem" value="Gem" />
        </form>
      </section>
      <section class="oversigt">
            <h2>Oversigt</h2>
            <ul>
        <?php
                // hent steder fra tabellen, og udskriv oversigt
                $query = "SELECT id, name FROM MW_MusicGroup ORDER BY name";
                // send forespørgsel
                $result = mysqli_query($con, $query);
                // gennemløb alle rækker i resultatet
                while ($row = mysqli_fetch_assoc($result)) {
                    ?>

                    <li>
                        <?php echo $row['name']; ?>
                        <!-- link til at rette -->
                        <a href="mw_adm_edit_musicgroup.php?id=<?php echo $row['id']; ?>">[RET / SLET]</a>
                    </li>

                    <?php
                }
        ?>
            </ul>
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
