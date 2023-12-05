<?php

$host = "localhost";
$port = 3306;
$socket = "MySQL";
$user = "root";
$password = "bluellama1";
$dbname = "aixhibit";

$description = "default description";
$artistID = -1;
$image = array();

// update artists name column with new artist name
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $original_array = array();
    $update_array = $_POST['artists'];

    $conn = new mysqli($host, $user, $password, $dbname, $port, $socket)
        or die('Could not connect to the database server' . mysqli_connect_error());

    // get original names sorted by ID
    $stmt = $conn->prepare("SELECT * FROM artists ORDER BY ArtistID");
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        foreach ($rows as $row) {
            $original_array[] = $row["ArtistName"];
        }
    }


    // since both arrays are sorted by ID, we can compare them directly
    // if the names are different, update the name in the db
    for ($i = 0; $i < count($original_array); $i++) {
        if ($original_array[$i] !== $update_array[$i]) {
            $stmt = $conn->prepare("UPDATE artists SET ArtistName = ? WHERE ArtistID = ?");
            $ipp = $i + 1;
            $stmt->bind_param("si", $update_array[$i],  $ipp);
            $stmt->execute();

            echo "updated " . $original_array[$i] . " to " . $update_array[$i] . "<br>";
            echo "---------------------------------<br>";
            echo "<a href='update_form.php'>update</a><br>";
            echo "<a href='../search.html'>search</a><br>";
        }
    }

    if ($original_array === $update_array) {
        echo "no changes made";
        echo "<a href='update_form.php'>update</a><br>";
        echo "<a href='../search.html'>search</a><br>";
    }
    
    
    

}

?>