<?php

//         - MoeFM HTML5 Project Subject -
//      ~ MoeFM High-Quality Audio Checker ~
//             (c) 2014 864907600cc
//                License: GPLv3
//                     V1.0

// -- 如何使用？
//    请使用 GET 方式将萌否电台的音频文件位址（无论音质，可不含 http 前缀）传入 url 参数中。
//    eg. ?url=http://nyan.90g.org/9/f/6d/ab2941597177f67434cb0a007c35f759_320.mp3
//        ?url=nyan.90g.org/e/6/57/c6d3aab94862090b6517708c2bdea56f.mp3
// -- 返回数据的格式？
//    提交后返回数据为 JSON 格式，无论错误码如何均返回 200 OK。参考数据如下：
//    { // 这是返回正常的数据
//        "request": "http://nyan.90g.org/9/f/6d/ab2941597177f67434cb0a007c35f759_320.mp3", // 提交请求的 URL 地址
//        "response": {
//            "64":{ // 64Kbps 音质的数据，Object 数据格式下同
//                "exist": 1, // 是否存在该码率文件，1 为存在，0 为不存在
//                "url": "http://nyan.90g.org/9/f/6d/ab2941597177f67434cb0a007c35f759.mp3" // 该码率的理论文件位址（即使文件不存在）
//            },
//            "128":{ ... }, // 128Kbps 音质的数据
//            "192":{ ... }, // 192Kbps 音质的数据
//            "320":{ ... }, // 320Kbps 音质的数据
//        },
//        "error": 0 // 错误码，0 为返回正常，其他为返回出错
//    }
//
//    { // 这是返回异常的数据（目前仅有判断 URL 是否正常之功能）
//        "request": "http://moefm.ccloli.com",
//        "error": 1,
//        "error_msg": "URL\u0020\u4e0d\u5339\u914d"
//    }
// -- 

header('Access-Control-Allow-Origin: *');
header('Content-Type:text/plain; charset: utf-8');


/* Source Code of get_head() came from http://blog.sina.com.cn/s/blog_5f54f0be0102uvxu.html */
function get_head($sUrl){
	//$time = time();
	$oCurl = curl_init();
	// 设置请求头, 有时候需要,有时候不用,看请求网址是否有对应的要求
	//$header[] = "Content-type: application/x-www-form-urlencoded";
	$user_agent = "MoeFM HTML5 Project Spider (Checking whether high quality audio is exist or not)";
	//"Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.124 Safari/537.36"; 
	//"Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/33.0.1750.146 Safari/537.36";

	curl_setopt($oCurl, CURLOPT_URL, $sUrl);
	//curl_setopt($oCurl, CURLOPT_HTTPHEADER, $header);
	// 返回 response_header, 该选项非常重要,如果不为 true, 只会获得响应的正文
	curl_setopt($oCurl, CURLOPT_HEADER, true);
	// 是否不需要响应的正文,为了节省带宽及时间,在只需要响应头的情况下可以不要正文
	curl_setopt($oCurl, CURLOPT_NOBODY, true);
	// 使用上面定义的 ua
	curl_setopt($oCurl, CURLOPT_USERAGENT,$user_agent);
	curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1 );
	// 不用 POST 方式请求, 意思就是通过 GET 请求
	curl_setopt($oCurl, CURLOPT_POST, false);

	$sContent = curl_exec($oCurl);
	// 获得响应结果里的：头大小
	//$headerSize = curl_getinfo($oCurl, CURLINFO_HEADER_SIZE);
	// 根据头大小去获取头信息内容
	//$header = substr($sContent, 0, $headerSize);
	    
	curl_close($oCurl);

	//echo (time() - $time);

	//return $header;
	return $sContent;
}

if(!empty($_GET['url'])&&preg_match('/(?:http:\/\/)?(nyan\.(?:90g|moefou)\.org\/[0-9a-f\/]+)(?:(?:_.+)?\.mp3)/', $_GET['url'], $front_url)){ 
	$url_64 = 'http://'.$front_url[1].'.mp3';
	$exist_64 = 0;
	$url_128 = 'http://'.$front_url[1].'_128.mp3';
	$exist_128 = 0;
	$url_192 = 'http://'.$front_url[1].'_192.mp3';
	$exist_192 = 0;
	$url_320 = 'http://'.$front_url[1].'_320.mp3';
	$exist_320 = 0;
	if(strstr(get_head($url_64), 'HTTP/1.1 200 OK'))$exist_64 = 1;
	if(strstr(get_head($url_128), 'HTTP/1.1 200 OK'))$exist_128 = 1;
	if(strstr(get_head($url_192), 'HTTP/1.1 200 OK'))$exist_192 = 1;
	if(strstr(get_head($url_320), 'HTTP/1.1 200 OK'))$exist_320 = 1;
	echo '
{
	"request": "'.$_GET['url'].'",
	"response": {
		"64":{
			"exist": '.$exist_64.',
			"url": "'.$url_64.'"
		},
		"128":{
			"exist": '.$exist_128.',
			"url": "'.$url_128.'"
		},
		"192":{
			"exist": '.$exist_192.',
			"url": "'.$url_192.'"
		},
		"320":{
			"exist": '.$exist_320.',
			"url": "'.$url_320.'"
		}
	},
	"error": 0
}';
}
else{
	echo '
{
	"request": "'.$_GET['url'].'",
	"error": 1,
	"error_msg": "URL\u0020\u4e0d\u5339\u914d"
}';
}

?>