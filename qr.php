<?php
header("Content-type:image/png");
$width=180;
$height=180;
$data=$_GET['data'];
echo qrcode($width,$height,$data);

function qrcode($width,$height,$string){
	$post_data=array();
	$post_data['cht']='qr';
	$post_data['chs']=$width."x".$height;
	$post_data['chl']=$string;
	$post_data['choe']="UTF-8";
	$url="http://chart.apis.google.com/chart";
	$data_Array=array();
	foreach($post_data as $key=>$value){
		$data_Array[]=$key.'='.$value;
	}
	$data=implode("&",$data_Array);
	$ch=curl_init();
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_URL, $url);    
	curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$result=curl_exec($ch);
	//echo "<img src =\"data:image/png;base64,".base64_encode($result)."\" >"; 注意，不写header的写法  
	return $result;
}
?>