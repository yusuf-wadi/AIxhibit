<?php

require_once __DIR__ . '/vendor/autoload.php';

use OpenAI;

$yourApiKey = getenv('OPENAI_API_KEY');
$client = OpenAI::client($yourApiKey);

$query = $_POST['query'];
//$query_type = $_POST['query_type']; //name, theme, genre

if ($query_type == 'name'){

	// check if name in database
	// if name in database, return description
	// else, generate description


	$description = $client->completions()->create([
    		'model' => 'gpt-3.5-turbo-instruct',
    		'prompt' => 'Construct an artists description for the following artist: \n\nArtist Name: ' . $query . '\		n\nArtist Description:',
    		'max_tokens' => 250,
    		'temperature' => 0.7
	]);

	$prompt = 'Artist Name: ' . $query . '\nArtist Description:' . $description->choices[0]->text;

}



?>
