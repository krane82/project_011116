<?php
$lng=$_POST['lng'];
$ltd=$_POST['ltd'];
$radius=$_POST['radius'];
if(!is_numeric($radius)) die('radius is not valid');
$link='https://digitalapi.auspost.com.au/locations/v2/points/geo/'.$lng.'/'.$ltd.'?radius='.$radius.'&limit=10000';
$cg=curl_init($link);
curl_setopt($cg, CURLOPT_RETURNTRANSFER, true);
curl_setopt($cg, CURLOPT_HTTPHEADER, array(
'Content-Type: application/json', 'charset=utf-8',
'AUTH-KEY: 0588d91e-3d09-4edb-9563-91f4f9709ac6'
 )
 );
 $ret1 = curl_exec($cg);
 $res=json_decode($ret1);
 curl_close;
 $arr=array();
 foreach($res->points as $item)
 {
	 if(!in_array($item->address->postcode,$arr)) 
	 {$arr[]=$item->address->postcode;
	 }
 }
 print implode(',',$arr);