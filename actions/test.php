<?php

require_once __DIR__ . '/vendor/autoload.php';

use OpenAI;

$yourApiKey = getenv('OPENAI_API_KEY');
$client = OpenAI::client($yourApiKey);

$prompt = $_GET['prompt'];

$response = $client->images()->create([
    'model' => 'dall-e-2',
    'prompt' => $prompt,
    'n' => 1,
    'size' => '256x256',
    'response_format' => 'b64_json',
]);

echo $response->data[0]->b64_json;

?>