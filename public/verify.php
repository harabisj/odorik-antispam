<?php

/**
 * Validate received GET parameters
 */

$expected = [
    'sip_in_callid',
    'dtmf',
    'success',
    'failure',
];

foreach ($expected as $key)
    if (!array_key_exists($key, $_GET))
        die('Expected GET parameters not present!');

$id = $_GET['sip_in_callid'];
$dtmf = $_GET['dtmf'];

/**
 * Load data file and delete the challenge from it
 */

$hash = hash('sha256', $id);

$data = file_get_contents('../data.json');
$data = json_decode($data, true);

$challenge = $data[$hash];

unset($data[$hash]);

$data = json_encode($data, JSON_PRETTY_PRINT);
file_put_contents('../data.json', $data);

/**
 * Validate the challenge
 */

$command = ($challenge == $dtmf) ? $_GET['success'] : $_GET['failure'];
echo str_replace('_', ':', $command);