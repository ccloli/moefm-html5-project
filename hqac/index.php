<?php

/* 
 * MoeFM High-Quality Audio Checker
 * @description  MoeFM HTML5 Project Subject
 * @version      1.2
 * @author       864907600cc (ccloli)
 */

header('Access-Control-Allow-Origin: *');
header('Content-Type: text/plain; charset: utf-8');

function check_url_exist($url){
	$curl = curl_init();
	$user_agent = 'MoeFM HQAC Spider/1.2';

	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_HEADER, true);
	curl_setopt($curl, CURLOPT_NOBODY, true);
	curl_setopt($curl, CURLOPT_USERAGENT, $user_agent);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_POST, false);

	curl_exec($curl);
	$http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	curl_close($curl);

	return $http_code == 200;
}

$url = $_GET['url'];
$callback = $_GET['callback'];

if (!empty($url) && preg_match('/(?:http:\/\/)?(nyan\.(?:90g|moefou)\.org\/[0-9a-f\/]+)(?:_\d+)?\.mp3/', $url, $front_url)) {
	$url_64 = 'http://' . $front_url[1] . '.mp3';
	$exist_64 = 0;
	$url_128 = 'http://' . $front_url[1] . '_128.mp3';
	$exist_128 = 0;
	$url_192 = 'http://' . $front_url[1] . '_192.mp3';
	$exist_192 = 0;
	$url_320 = 'http://' . $front_url[1] . '_320.mp3';
	$exist_320 = 0;

	if (check_url_exist($url_64)) $exist_64 = 1;
	if (check_url_exist($url_128)) $exist_128 = 1;
	if (check_url_exist($url_192)) $exist_192 = 1;
	if (check_url_exist($url_320)) $exist_320 = 1;
	$data = array(
		'request' => $_GET['url'],
		'response' => array(
			'64' => array(
				'exist' => $exist_64,
				'url' => $url_64
			),
			'128' => array(
				'exist' => $exist_128,
				'url' => $url_128
			),
			'192' => array(
				'exist' => $exist_192,
				'url' => $url_192
			),
			'320' => array(
				'exist' => $exist_320,
				'url' => $url_320
			)
		),
		'error' => 0,
		'error_msg' => '获取成功'
	);
	$res = json_encode($data);
	if (!empty($callback)) $res = $callback . '(' . $res . ')';
	echo $res;
}
else {
	$data = array(
		'request' => $_GET['url'],
		'error' => 1,
		'error_msg' => 'URL 不匹配'
	);
	$res = json_encode($data);
	if (!empty($callback)) $res = $callback . '(' . $res . ')';
	echo $res;
}