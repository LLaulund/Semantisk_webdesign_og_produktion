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
        <title>MusicWorld - Ret sted</title>
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
                // hvis dette sted er registreret som museums adresse
                $query = "UPDATE MW_Organization SET Place_id = '0' WHERE Place_id = '$id'";
                $result = mysqli_query($con, $query);
                // hvis dette sted er overordnet sted for et andet sted
                $query = "UPDATE MW_Place SET Place_contained_in_Place_id = '0' WHERE Place_contained_in_Place_id = '$id'";
                $result = mysqli_query($con, $query);
                // hvis dette sted er motiv for et værk
                $query = "UPDATE MW_MusicGroup SET Place_id = '0' WHERE Place_id = '$id'";
                $result = mysqli_query($con, $query);
                // hvis dette sted er fødested, dødssted eller homeLocation for en person
                $query = "UPDATE MW_Person SET birthPlace_id = '0' WHERE birthPlace_id = '$id'";
                $result = mysqli_query($con, $query);
                $query = "UPDATE MW_Person SET deathPlace_id = '0' WHERE deathPlace_id = '$id'";
                $result = mysqli_query($con, $query);

                // SLET: nu kan dette sted slettes
                $query = "DELETE FROM MW_Place WHERE id = '$id'";
                $result = mysqli_query($con, $query);

            }

            // tjek, om der er sendt data med (er der trykket på knappen "gem"?)
            if ($_POST['gem'] == "Gem") {
                $id = mysqli_real_escape_string($con, $_POST['id']);
                // gem medsendte data som php-variabler
                $name = mysqli_real_escape_string($con, $_POST['name']);
                $hasMap = mysqli_real_escape_string($con, $_POST['hasMap']);
                $Place_contained_in_Place_id = mysqli_real_escape_string($con, $_POST['Place_contained_in_Place_id']);
                // opbyg forespørsel til at indsætte databasen
                $query = "UPDATE
                            MW_Place SET
                            name = '$name',
                            hasMap = '$hasMap',
                            Place_contained_in_Place_id = '$Place_contained_in_Place_id'
                            WHERE id = '$id'";
                // echo $query;
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
                $query = "SELECT * FROM MW_Place WHERE id = '$id'";
                // send forespørgsel
                $result = mysqli_query($con, $query);
                // træk den række ud, der er fundet
                $row = mysqli_fetch_assoc($result);
            ?>
            <section class="form">
              <h1>MusicWorld: ret eller slet </h1>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <input type="hidden" name="id" value="<?php echo $row['id']; ?>" />

                <label for="name">Navn</label>
                <input type="text" name="name" id="name" value="<?php echo $row['name']; ?>" />
                <br/><br/>
                <label for="hasMap">Kort-url</label>
                <input type="text" name="hasMap" id="hasMap" value="<?php echo $row['hasMap']; ?>" />
                <br/><br/>
                <label for="Place_contained_in_Place_id">Stedet ligger i: </label>
                <select id="Place_contained_in_Place_id" name="Place_contained_in_Place_id">
                    <option>Vælg sted...</option>
                <?php
                    // hent steder fra tabellen, og giv mulighed for at vælge overordnet sted
                    $p_query = "SELECT id, name FROM MW_Place WHERE id <> '$id' ORDER BY name";
                    // send forespørgsel
                    $p_result = mysqli_query($con, $p_query);
                    // gennemløb alle rækker i resultatet
                    while ($p_row = mysqli_fetch_assoc($p_result)) {
                        ?>
                        <option
                            value="<?php echo $p_row['id']; ?>"
                            <?php
                                // hvis vi har fat i det sted, som er registreret som overordnet sted, skal det forvælges
                                if ($p_row['id']==$row['Place_contained_in_Place_id']) { echo " selected='selected' "; } ?>
                            >
                            <?php echo $p_row['name']; ?>
                        </option>
                        <?php
                    }
                ?>
                </select>
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
                $query = "SELECT id, name FROM MW_Place ORDER BY name";
                // send forespørgsel
                $result = mysqli_query($con, $query);
                // gennemløb alle rækker i resultatet
                while ($row = mysqli_fetch_assoc($result)) {
                    ?>

                    <li><?php echo $row['name']; ?>
                      <a href="mw_adm_edit_place.php?id=<?php echo $row['id']; ?>">[ RET / SLET ]</a></li>

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
