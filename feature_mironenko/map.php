<?php
//$lng=$_POST['lng'];
//$ltd=$_POST['ltd'];
//$radius=;
$cg=curl_init('https://digitalapi.auspost.com.au/locations/v2/points/geo/151.20929550000005/-33.8688197?radius=50&limit=10000');
curl_setopt($cg, CURLOPT_RETURNTRANSFER, true);
curl_setopt($cg, CURLOPT_HTTPHEADER, array(
'Content-Type: application/json', 'charset=utf-8',
'AUTH-KEY: 0588d91e-3d09-4edb-9563-91f4f9709ac6'
 )
 );
 $ret1 = curl_exec($cg);
 $res=json_decode($ret1);
 curl_close;
 $str='';
 $i=0;
 $arr=array();
 foreach($res->points as $item)
 {
	 if(!in_array($item->address->postcode,$arr)) 
	 {$arr[]=$item->address->postcode;
		$i++;
	 }
	
//	 $str.=$item->address->postcode.', ';
// $i++;
 }
 print implode(', ',$arr);
 print '<br>'.$i;