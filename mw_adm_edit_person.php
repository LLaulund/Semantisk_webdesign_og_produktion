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
        <title>MusicWorld - Rediger kunstner - </title>
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
                            
                // slet
                $query = "DELETE FROM MW_Person WHERE id = '$id'";
                $result = mysqli_query($con, $query);
            }

            // tjek, om der er sendt data med (er der trykket på knappen "gem"?)
            if ($_POST['gem'] == "Opdatér") {
                // gem medsendte data som php-variabler
                $id = mysqli_real_escape_string($con, $_POST['id']);
                $givenName = mysqli_real_escape_string($con, $_POST['givenName']);
                $familyName = mysqli_real_escape_string($con, $_POST['familyName']);
                $alternateName = mysqli_real_escape_string($con, $_POST['alternateName']);
                $birthDate = mysqli_real_escape_string($con, $_POST['birthDate']);
                $birthPlace_id = mysqli_real_escape_string($con, $_POST['birthPlace_id']);
                $deathDate = mysqli_real_escape_string($con, $_POST['deathDate']);
                $deathPlace_id = mysqli_real_escape_string($con, $_POST['deathPlace_id']);
                $gender = mysqli_real_escape_string($con, $_POST['gender']);
                $MusicGroup_id = mysqli_real_escape_string($con, $_POST['MusicGroup_id']);
                $image = mysqli_real_escape_string($con, $_POST['image']);
                $description = mysqli_real_escape_string($con, $_POST['description']);
                $sameAs = mysqli_real_escape_string($con, $_POST['sameAs']);
                // opbyg forespørsel til at indsætte databasen
                $query = "UPDATE
                        MW_Person SET
                            givenName = '$givenName',
                            familyName = '$familyName',
                            alternateName = '$alternateName',
                            birthDate = '$birthDate',
                            birthPlace_id = '$birthPlace_id',
                            deathDate = '$deathDate',
                            deathPlace_id = '$deathPlace_id',
                            gender = '$gender',
                            MusicGroup_id = '$MusicGroup_id',                        
                            image = '$image',
                            description = '$description',
                            sameAs = '$sameAs'
                        WHERE id = '$id'
                    ";
                 echo $query;
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
                $query = "SELECT * FROM MW_Person WHERE id = '$id'";
                // send forespørgsel
                $result = mysqli_query($con, $query);
                // træk den række ud, der er fundet
                $row = mysqli_fetch_assoc($result);
            ?>
            <section class="form">
              <h1>MusicWorld: Ret eller slet kunstner</h1>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">

                <input type="hidden" name="id" id="id" value="<?php echo $id; ?>" />

                <label for="givenName">Fornavn</label>
                <input type="text" name="givenName" id="givenName" value="<?php echo $row['givenName']; ?>" />

                <label for="familyName">Efternavn</label>
                <input type="text" name="familyName" id="familyName" value="<?php echo $row['familyName']; ?>" />

                <label for="alternateName">Kunstner navn</label>
                <input type="text" name="alternateName" id="alternateName" value="<?php echo $row['alternateName']; ?>" />

                <label for="birthDate">Fødselsdato</label>
                <input type="date" name="birthDate" id="birthDate" value="<?php echo $row['birthDate']; ?>" />

                <label for="birthPlace_id">Fødested</label>
                <br/>
                <select id="birthPlace_id" name="birthPlace_id">
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
                            if ($row['birthPlace_id'] == $place_row['id']) {
                                echo " selected='selected'";
                            }
                            ?>
                        >
                            <?php echo $place_row['name']; ?> - <?php echo $place_row['address']; ?>
                        </option>
                        <?php
                    }
                ?>
                </select>
                <br/><br/>

                <label for="deathDate">Dødsdato</label>
                <input type="date" name="deathDate" id="deathDate" value="<?php echo $row['deathDate']; ?>" />

                <label for="deathPlace_id">Dødssted</label>
                <br/>
                <select id="deathPlace_id" name="deathPlace_id">
                    <option value="">Vælg sted</option>
                <?php
                    // nulstil pointer i resultat fra databasen
                    mysqli_data_seek($place_result, 0);
                    // gennemløb alle rækker i resultatet
                    while ($place_row = mysqli_fetch_assoc($place_result)) {
                        ?>
                        <option
                            value="<?php echo $place_row['id']; ?>"
                            <?php
                            // hvis denne option er den samme, som er gemt i databasen, skal den forvælges
                            if ($row['deathPlace_id'] == $place_row['id']) {
                                echo " selected='selected'";
                            }
                            ?>
                        >
                            <?php echo $place_row['name']; ?> - <?php echo $place_row['address']; ?>
                        </option>
                        <?php
                    }
                ?>
                </select>
                <br/><br/>
                <label for="gender">Køn</label>
                <fieldset>
                M <input type="radio" id="gender" name="gender"
                <?php
                    if ($row['gender'] == 'm') {
                        echo " checked='checked' ";
                    }
                ?>
                value="m" />
                F <input type="radio" id="" name="gender"
                <?php
                    if ($row['gender'] == 'f') {
                        echo " checked='checked' ";
                    }
                ?>
                value="f" />
                </fieldset>
                <br/><br/>
                <label for="MusicGroup_id">Musik gruppe</label>
                <br/>
                <select id="MusicGroup_id" name="MusicGroup_id">
                    <option value="">Vælg musikgruppe</option>
                <?php
                    // hent fra tabellen, og giv mulighed for at vælge
                    $oc_query = "SELECT * FROM MW_MusicGroup ORDER BY name";
                    // echo $query;
                    // send forespørgsel
                    $oc_result = mysqli_query($con, $oc_query);
                    // gennemløb alle rækker i resultatet
                    while ($oc_row = mysqli_fetch_assoc($oc_result)) {
                        ?>
                        <option
                            value="<?php echo $oc_row['id']; ?>"
                            <?php if ($oc_row['id'] == $row['MusicGroup_id']) { echo " selected='selected' "; } ?>
                        >
                            <?php echo $oc_row['name']; ?>
                        </option>
                        <?php
                    }
                ?>
                </select>

                <br/><br/>

                <label for="image">Foto (url)</label>
                <input type="text" name="image" id="image" value="<?php echo $row['image']; ?>" />

                <label for="description">Beskrivelse</label>
                <textarea id="description" name="description" rows="5" cols="60"><?php echo $row['description']; ?></textarea>

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
                // hent personer fra tabellen, og udskriv oversigt
                $query = "SELECT id, givenName, familyName FROM MW_Person ORDER BY givenName";
                // send forespørgsel
                $result = mysqli_query($con, $query);
                // gennemløb alle rækker i resultatet
                while ($row = mysqli_fetch_assoc($result)) {
                    ?>

                    <li>
                        <?php echo $row['givenName']; ?> <?php echo $row['familyName']; ?>
                        <!-- link til at rette -->
                        <a href="mw_adm_edit_person.php?id=<?php echo $row['id']; ?>">[RET / SLET]</a>
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
