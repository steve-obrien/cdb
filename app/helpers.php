<?php

// if (!function_exists('dp')) {
// function dp(...$args)
// {
// 	$url = 'http://localhost:3003/log'; // Make sure the endpoint matches the Node.js server

// 	$data = json_encode($args);

// 	$ch = curl_init($url);

// 	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// 	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
// 		'Content-Type: application/json'
// 	));
// 	curl_setopt($ch, CURLOPT_POST, true);
// 	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

// 	$result = curl_exec($ch);

// 	if (curl_errno($ch)) {
// 		error_log('Error logging data to debug server: ' . curl_error($ch));
// 	} else {
// 		error_log('Data logged successfully: ' . $result);
// 	}

// 	curl_close($ch);
// }
// }