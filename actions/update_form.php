<?php

require_once __DIR__ . '/vendor/autoload.php';

use OpenAI;

$yourApiKey = getenv('OPENAI_API_KEY');
$client = OpenAI::client($yourApiKey);

$host = "localhost";
$port = 3306;
$socket = "MySQL";
$user = "root";
$password = "bluellama1";
$dbname = "aixhibit";

// get all items from db table artists, and store in array
$artists = array();

function return_data_from_sql($query, $conn)
{
    $stmt = $conn->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        return $rows;
    }
    else {
        return null;
    }
}


$conn = new mysqli($host, $user, $password, $dbname, $port, $socket)
    or die('Could not connect to the database server' . mysqli_connect_error());


$rows = return_data_from_sql("SELECT * FROM artists ORDER BY ArtistID", $conn);

if ($rows != null) {
    foreach ($rows as $row) {
        $artists[] = $row["ArtistName"];
    }
}

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>AIxhibit</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" type="text/css" href="frontend/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.0.slim.min.js" integrity="sha256-tG5mcZUtJsZvyKAxYLVXrmjKBVLd6VpVccqz/r4ypFE=" crossorigin="anonymous"></script>

    <link rel="apple-touch-icon" sizes="180x180" href="frontend/images/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="frontend/images/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="frontend/images/favicon/favicon-16x16.png">
    <link rel="manifest" href="frontend/images/favicon/site.webmanifest">
    <link rel="mask-icon" href="frontend/images/favicon/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">


</head>

<body>

   <!-- flex rows to hold all artists names in rows, names are editable, form submits the updated name array -->



    <div class="flex flex-col items-center justify-center min-h-screen py-2">
        <div class="flex flex-col justify-center items-center w-full text-center">
            <h1 class="text-4xl font-bold">AIxhibit</h1>
            <p class="text-lg">AI-generated art</p>
        </div>
        <div class="flex flex-col justify-center items-center w-full text-center">
            <h1 class="text-2xl font-bold">Update Artists</h1>
            <p class="text-lg">Edit the names of the artists in the database</p>
        </div>
        <div class="flex flex-col justify-center items-center w-full text-center">
            <form action="update.php" method="post">
                <div class="flex flex-row flex-wrap justify-center items-center w-full text-center" id="artistsArray">
                    <?php
                    foreach ($artists as $artist) {
                        echo "<input type='text' name='artists[]' value='$artist' class='border-2 border-gray-300 rounded-md p-2 m-2'>";
                    }
                    ?>
                </div>
                <div class="flex flex-col justify-center items-center w-full text-center">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>

        
        
    </script>



</body>

</html>
