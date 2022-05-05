<?php

/**
 * Validate received GET parameters
 */

$expected = [
    'sip_in_callid',
    'length',
    'success',
    'failure',
];

foreach ($expected as $key)
    if (!array_key_exists($key, $_GET))
        die('Expected GET parameters not present!');

$id = $_GET['sip_in_callid'];
$length = $_GET['length'];

/**
 * Generate random challenge
 */

$challenge = [];
for ($i = 0; $i < $length; $i++)
    $challenge[] = rand(0, 9);

/**
 * Play welcome announcement
 */

if (!isset($_GET['no_prompt']))
    echo 'play:' . url() . '/prompt.flac' . PHP_EOL;

/**
 * Play challenge
 */

foreach ($challenge as $number)
    echo 'play:' . url() . '/' . $number . '.flac' . PHP_EOL;

/**
 * Load data file and save challenge
 */

$data = file_get_contents('../data.json');
$data = json_decode($data, true);

$data[hash('sha256', $id)] = implode('', $challenge);

$data = json_encode($data, JSON_PRETTY_PRINT);
file_put_contents('../data.json', $data);

/**
 * Redirect to verification
 */

echo 'uri:' . url() . '/verify.php?dtmf_count=' . $length .
    '&success=' . $_GET['success'] . '&failure=' . $_GET['failure'];

/**
 * Declare function to get URL
 */

function url()
{
    return 'https://' . $_SERVER['HTTP_HOST'];
}