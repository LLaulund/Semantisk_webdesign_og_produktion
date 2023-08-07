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
        <title>MusicWorld - Ret Musikgruppe</title>
        <link rel="stylesheet" type="text/css" href="adm_style.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <?php
            // kræv indlæsning af forbindelse til databasen
            require_once("../../inc_connect.php");
        ?>

        <?php
            // tjek, om der skal slettes
            if ($_POST['gem'] == "Slet") {
                // gem medsendte data som php-variabler
                $id = mysqli_real_escape_string($con, $_POST['id']);
                // udfør eventuel sletning eller opdatering i tilknyttede tabeller
                // eventuelle musikgruppe - musikalbum relationer
                
                  $query = "DELETE FROM MW_MusicAlbum_has_MusicGroup WHERE MusicGroup_id = '$id' ";
                  $result = mysqli_query($con, $query);
                

                $query = "DELETE FROM MW_Person WHERE MusicGroup_id = '$id'";
                $result = mysqli_query($con, $query);

                $query = "DELETE FROM MW_MusicGroup WHERE id = '$id'";
                $result = mysqli_query($con, $query);
            }

            // tjek, om der er sendt data med (er der trykket på knappen "gem"?)
            if ($_POST['gem'] == "Opdatér") {
                // gem medsendte data som php-variabler
                $id = mysqli_real_escape_string($con, $_POST['id']);
                $name = mysqli_real_escape_string($con, $_POST['name']);
                $Place_id = mysqli_real_escape_string($con, $_POST['Place_id']);
                $description = mysqli_real_escape_string($con, $_POST['description']);
                $foundingDate = mysqli_real_escape_string($con, $_POST['foundingDate']);
                $dissolutionDate = mysqli_real_escape_string($con, $_POST['dissolutionDate']);
                $image = mysqli_real_escape_string($con, $_POST['image']);
                $sameAs = mysqli_real_escape_string($con, $_POST['sameAs']);

                // opbyg forespørsel til at indsætte databasen
                $query = "UPDATE
                        MW_MusicGroup SET
                          name = '$name',
                          Place_id = '$Place_id',
                          description = '$description',
                          foundingDate = '$foundingDate',
                          dissolutionDate = '$dissolutionDate',
                          image = '$image',
                          sameAs = '$sameAs'
                        WHERE id = '$id'
                    ";
                // send forespørgsel til server, og gem resultat i $result
                $result = mysqli_query($con, $query);
                if ($result) {
                    echo "<p>Data opdateret</p>";
                } else {
                    echo "<p>Data ikke opdateret</p>";
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
            <?php
                // hent de parametre, der er sendt med i URL
                $id = mysqli_real_escape_string($con, $_GET['id']);
                // søg efter den række, der er valgt ved det medsendte id
                $query = "SELECT * FROM MW_MusicGroup WHERE id = '$id'";
                // send forespørgsel
                $result = mysqli_query($con, $query);
                // træk den række ud, der er fundet
                $row = mysqli_fetch_assoc($result);
            ?>
            <section class="form">
              <h1>MusicWorld: ret eller slet</h1>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">

                <input type="hidden" name="id" id="id" value="<?php echo $id; ?>" />

                <label for="name">Navn</label>
                <input type="text" name="name" id="name" value="<?php echo $row['name']; ?>" />
                <br/><br/>
                <label for="Place_id">Sted</label>
                <br/>
                <select id="Place_id" name="Place_id">
                    <option value="">Vælg sted</option>
                <?php
                    // hent steder fra tabellen, og giv mulighed for at vælge sted
                    $place_query = "SELECT * FROM MW_Place ORDER BY name";
                    // send forespørgsel
                    $place_result = mysqli_query($con, $place_query);
                    // gennemløb alle rækker i resultatet
                    while ($place_row = mysqli_fetch_assoc($place_result)) {
                        ?>
                        <option
                            value="<?php echo $place_row['id']; ?>"
                            <?php
                            // hvis denne option er den samme, som er gemt i databasen, skal den forvælges
                            if ($row['Place_id'] == $place_row['id']) {
                                echo " selected='selected'";
                            }
                            ?>
                          >
                            <?php echo $place_row['name']; ?>
                        </option>
                        <?php
                    }
                ?>
                </select>
                  <br/><br/>
                <label for="description">Beskrivelse</label>
                <textarea id="description" name="description" rows="5" cols="60"><?php echo $row['description']; ?></textarea>
              </br></br>

                <label for="foundingDate">Stiftelsesår</label>
                <input type="date" name="foundingDate" id="foundingDate" value="<?php echo $row['foundingDate']; ?>" />
                </br></br> 
                <label for="dissolutionDate">Opløsningsdato</label>
                <input type="date" name="dissolutionDate" id="dissolutionDate" value="<?php echo $row['dissolutionDate']; ?>" />
              </br></br>

                <label for="image">Foto (url)</label>
                <input type="text" name="image" id="image" value="<?php echo $row['image']; ?>" />
                <br/><br/>
                <label for="sameAs">IRI</label>
                <input type="text" name="sameAs" id="sameAs" value="<?php echo $row['sameAs']; ?>" />
                <br/><br/>
                <input type="submit" name="gem" id="opdater" value="Opdatér" />
            </form>


            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <input type="hidden" name="id" value="<?php echo $id; ?>" />
                <input type="submit" name="gem" id="slet" value="Slet" />
            </form>
          </section>
        </main>
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
      <footer>
        <?php include("footer.php");?>
      </footer>
      <script>
        <?php include("navbar_script.js");?>
      </script>
      <?php mysqli_close($con); ?>
    </body>
  </html>
