<?php
    // kræv indlæsning af forbindelse til databasen
    require_once("../../inc_connect.php");
?>
<?php
        // hent alt om kunstnere fra tabellen
        $query = "SELECT
            MW_Person.*,
            MW_Place.name AS birthPlace,
            Place2.name AS deathPlace,
            MW_MusicGroup.name AS Musicgroup
            FROM MW_Person
            LEFT JOIN MW_Place
            ON MW_Place.id = MW_Person.birthPlace_id
            LEFT JOIN MW_Place AS Place2
            ON MW_Person.deathPlace_id = Place2.id
            LEFT JOIN MW_MusicGroup
            ON MW_Person.musicgroup_id = MW_MusicGroup.id
            ORDER BY givenName
        ";
        // send forespørgsel
        $result = mysqli_query($con, $query);

        //opret et PHP array
        $output = array();
        // gennemløb alle rækker i resultatet og indsæt direkte i array
        while ($row = mysqli_fetch_assoc($result)) {
            $output[] = $row;
        }
        header('Content-type: application/json');
        echo json_encode(array($output));

mysqli_close($con);
?>
