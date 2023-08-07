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
<html lang="da">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>MusicWorld - Ret eller slet pladeselsskab</title>
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

                // FJERN REFERENCE i andre tabeller
                $query = "UPDATE MW_MusicAlbum SET Organization_id = '0' WHERE Organization_id = '$id'";
                $result = mysqli_query($con, $query);

                // SLET: så skal dette museum slettes
                $query = "DELETE FROM MW_Organization WHERE id = '$id'";
                $result = mysqli_query($con, $query);

            }

            // tjek, om der er sendt data med (er der trykket på knappen "gem"?)
            if ($_POST['gem'] == "Gem") {
                // gem medsendte data som php-variabler
                $id = mysqli_real_escape_string($con, $_POST['id']);
                $name = mysqli_real_escape_string($con, $_POST['name']);
                $Place_id = mysqli_real_escape_string($con, $_POST['Place_id']);
                $foundingDate = mysqli_real_escape_string($con, $_POST['foundingDate']);
                $description = mysqli_real_escape_string($con, $_POST['description']);
                $sameAs = mysqli_real_escape_string($con, $_POST['sameAs']);
                // opbyg forespørsel til at indsætte databasen
                $query = "UPDATE
                          MW_Organization SET
                          name = '$name',
                          Place_id = '$Place_id',
                          foundingDate = '$foundingDate',
                          description = '$description',
                          sameAs = '$sameAs'
                        WHERE id = '$id'";
                echo $query;
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
        <?php
                // hent de parametre, der er sendt med i URL
                if (isset($_GET['id'])) {
                    $id = mysqli_real_escape_string($con, $_GET['id']);
                } else {
                    $id = mysqli_real_escape_string($con, $_POST['id']);
                }
                // søg efter den række, der er valgt ved det medsendte id
                $query = "SELECT * FROM MW_Organization WHERE id = '$id'";
                // send forespørgsel
                $result = mysqli_query($con, $query);
                // træk den række ud, der er fundet
                $row = mysqli_fetch_assoc($result);
            ?>
            <section class="form">
              <h1>MusicWorld: ret eller slet</h1>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <input type="hidden" name="id" value="<?php echo $row['id']; ?>" />

                <label for="name">Navn</label>
                <input type="text" name="name" id="name" value="<?php echo $row['name']; ?>" />
                <br/><br/>
                <label for="foundingDate">Stiftelsesdato</label>
                <input type="date" id="foundingDate" name="foundingDate" value="<?php echo $row['foundingDate']; ?>" />
                <br/><br/>
                <label for="description">Beskrivelse</label>
                <textarea id="description" name="description" rows="5" cols="60"><?php echo $row['description']; ?></textarea>
                <br/><br/>
                <label for="Place_id">Sted</label><br/>
                <select id="Place_id" name="Place_id">
                    <option>Vælg sted...</option>
                <?php
                    // hent steder fra tabellen, og giv mulighed for at vælge adresse
                    $p_query = "SELECT * FROM MW_Place ORDER BY name";
                    // send forespørgsel
                    $p_result = mysqli_query($con, $p_query);
                    // gennemløb alle rækker i resultatet
                    while ($p_row = mysqli_fetch_assoc($p_result)) {
                        ?>
                        <option
                            value="<?php echo $p_row['id']; ?>"
                            <?php
                                // hvis vi har fat i det sted, som er registreret som overordnet sted, skal det forvælges
                                if ($p_row['id']==$row['Place_id']) { echo " selected='selected' "; } ?>

                            >
                            <?php echo $p_row['name']; ?>
                        </option>
                        <?php
                    }
                ?>
                </select>
                <br/><br/>
                <label for="sameAs">IRI</label>
                <textarea id="sameAs" name="sameAs"><?php echo $row['sameAs']; ?></textarea>
                <br/><br/>
                <input type="submit" name="gem" id="opdater" value="Gem" />
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
                $query = "SELECT id, name FROM MW_Organization ORDER BY name";
                // send forespørgsel
                $result = mysqli_query($con, $query);
                // gennemløb alle rækker i resultatet
                while ($row = mysqli_fetch_assoc($result)) {
                    ?>

                    <li><?php echo $row['name']; ?>
                      <a href="mw_adm_edit_organization.php?id=<?php echo $row['id']; ?>">[ RET / SLET ]</a></li>

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
