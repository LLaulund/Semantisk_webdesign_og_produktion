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
    <title>MusicWorld - Tilføj kunstner </title>
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
                $query = "
                    INSERT INTO MW_Person (
                        givenName,
                        familyName,
                        alternateName,
                        birthDate,
                        birthPlace_id,
                        deathDate,
                        deathPlace_id,
                        gender,
                        MusicGroup_id,
                        image,
                        description,
                        sameAs
                        )
                    VALUES (
                        '$givenName',
                        '$familyName',
                        '$alternateName',
                        '$birthDate',
                        '$birthPlace_id',
                        '$deathDate',
                        '$deathPlace_id',
                        '$gender',
                        '$MusicGroup_id',
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
          <h1>Tilføj nye kunstnere her</h1>
          <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <label for="givenName">Fornavn</label>
            <input type="text" name="givenName" id="givenName"/>

            <label for="familyName">Efternavn</label>
            <input type="text" name="familyName" id="familyName"/>

            <label for="alternateName">Kunstner navn</label>
            <input type="text" name="alternateName" id="alternateName"/>

            <label for="birthDate">Fødselsdato</label>
            <input type="date" name="birthDate" id="birthDate"/>

            <label for="birthPlace_id">Fødested</label> <br/>
            <select id="birthPlace_id" name="birthPlace_id">
                <option value="">Vælg fødested</option>
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
            <label for="deathDate">Dødsdato</label>
            <input type="date" name="deathDate" id="deathDate" />

            <label for="deathPlace_id">Dødssted</label><br/>
            <select id="deathPlace_id" name="deathPlace_id">
                <option value="">Vælg dødssted</option>
            <?php
                // nulstil pointer i resultat fra databasen
                mysqli_data_seek($place_result, 0);
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
            <label for="gender">Køn</label>
            <fieldset>
            M <input type="radio" id="gender" name="gender" checked="checked" value="m" />
            F <input type="radio" id="" name="gender" value="f" />
            </fieldset>
            <br/>

            <label for="MusicGroup_id">Musikgruppe</label><br/>
              <select id="MusicGroup_id" name="MusicGroup_id">
                  <option value="">Vælg musikgruppe</option>
              <?php
                  // hent fra tabellen, og giv mulighed for at vælge
                  $query = "SELECT * FROM MW_MusicGroup ORDER BY name";
                  // send forespørgsel
                  $result = mysqli_query($con, $query);
                  // gennemløb alle rækker i resultatet
                  while ($row = mysqli_fetch_assoc($result)) {
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

              <label for="sameAs">Tilføj IRI</label><br />
              <input type="text" name="sameAs" id="sameAs" /><br />


            <label for="description">Beskrivelse</label><br />
            <textarea id="description" name="description" rows="5" cols="60"></textarea><br />

            <input type="submit" name="gem" value="Gem" />
        </form>
      </section>
      <section class="oversigt">
            <h2>Oversigt</h2>
            <ul>
        <?php
                // hent steder fra tabellen, og udskriv oversigt
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
