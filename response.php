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

$description = "default description";
$artistID = -1;
$image = array();



if ($_SERVER["REQUEST_METHOD"] === "POST") {


    $query = $_POST['query'];
    //$query_type = $_POST['query_type']; //name, theme, genre

    $conn = new mysqli($host, $user, $password, $dbname, $port, $socket)
        or die('Could not connect to the database server' . mysqli_connect_error());

    $stmt = $conn->prepare("SELECT * FROM artists WHERE ArtistName = ?");
    $stmt->bind_param("s", $query);
    $stmt->execute();
    $result = $stmt->get_result();

    // check if name in database
    // if name in database, return description
    // else, generate description

    if ($result->num_rows > 0) {
        
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        foreach ($rows as $row) {
            $description = $row["Description"];
            $artistID = $row["ArtistID"];
        }
        // get artist images based on artist ID
        $stmt = $conn->prepare("SELECT * FROM artwork WHERE ArtistID = ?");
        $stmt->bind_param("i", $artistID);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            // get b64 data from image column
            $rows = $result->fetch_all(MYSQLI_ASSOC);
            //append to image array
            foreach ($rows as $row) {
                $image[] = $row["Image"];
            }
            
        }
        $stmt->close();

    } else {

        $description = $client->completions()->create([
            'model' => 'gpt-3.5-turbo-instruct',
            'prompt' => 'Construct a brief artists description (art style, method, medium, etc.) for the following artist: \n\nArtist Name: ' . $query . '\		n\nArtist Description:',
            'max_tokens' => 256,
            'temperature' => 0.7
        ]);

        $description = $description->choices[0]->text;
        

        $prompt = 'Artist Name: ' . $query . '\nArtist Description:' . $description . '\n Artwork by ' . $query . ':\n';
        //echo $prompt;
        $response = $client->images()->create([
            'model' => 'dall-e-2',
            'prompt' => $prompt,
            'n' => 3,
            'size' => '256x256',
            'response_format' => 'b64_json',
        ]);

        foreach ($response->data as $data) {
            $image[] = $data->b64_json;
        }

        // insert into database
        $artistID = rand(100000, 999999);

        $stmt = $conn->prepare("INSERT INTO artists (ArtistID, ArtistName, Description) VALUES (?, ?, ?)");

        $stmt->bind_param("iss", $artistID, $query, $description);
        $stmt->execute();
        
        foreach ($image as $img) {
            $artworkID = rand(100000, 999999);
            $artworkName = $query . " " . rand(100, 999);
            $stmt = $conn->prepare("INSERT INTO artwork (ArtworkID, ArtworkName, Image, ArtistID) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("isss", $artworkID, $artworkName, $img, $artistID);
            $stmt->execute();

        }

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

    <!--IMAGE_SECTION-->
    <!-- Artist Name and Description -->

    <div class="h-screen flex items-center justify-center">
        <div class="max-w-md mx-auto p-8 bg-white shadow-md">

            <!-- Large Bold Title Div -->
            <div class="text-3xl font-bold mb-4"><?php echo $query ?></div>

            <!-- Small Subtext Below Title -->
            <div class="text-sm text-gray-500"><?php echo $description ?></div>

            <!-- Content Goes Here -->

        </div>
        <!--IMAGES_DIV-->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 p-4">
            <div id="loader" class="hidden w-40 h-40 overflow-hidden">
                <img src="assets/loader.gif">
            </div>
            <!-- Check if Image is generated -->
            <?php if (!empty($image)) : ?>
                <!-- Display Images -->
                <?php foreach ($image as $img) : ?>
                    <img src="data:image/jpeg;base64,<?php echo $img; ?>" class="w-full h-full object-cover">
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <!--IMAGES_DIV_ENDS-->


    </div><!--IMAGE_SECTION_END-->



</body>

</html>