<?php
date_default_timezone_set('Asia/Jakarta');
require_once("sdata-modules.php");
/**
 * @Author: Eka Syahwan
 * @Date:   2017-12-11 17:01:26
 * @Last Modified by:   xdamns
 * @Last Modified time: 2018-08-17 15:13:34
*/
##############################################################################################################
$config['deviceCode'] 		= '866709036100153';
$config['tk'] 				= 'ACGbGoeHXYhMeLUqft0EDp2BMuhnGDoW8ntxdHRodw';
$config['token'] 			= 'c57cB0Bb0nquiLCAuDFLnPCpPGLH7RacDlelilZwgaSaF2idvaXo098yA0ldPzioglio2ajkFPdTvXk';
$config['uuid'] 			= 'ebf6ca4a3e194a6ea2206d845c9e94f0';
$config['sign'] 			= '8aff252f543bd0eae7b5852ae596b0c8';
$config['android_id'] 		= '4f7ce7a69e955e1e';
##############################################################################################################
for ($x=0; $x <1; $x++) { 
	$url 	= array(); 
	for ($cid=0; $cid <20; $cid++) { 
		for ($page=0; $page <10; $page++) { 
			$url[] = array(
				'url' 	=> 'http://api.beritaqu.net/content/getList?cid='.$cid.'&page='.$page,
				'note' 	=> 'optional', 
			);
		}
		$ambilBerita = $sdata->sdata($url); unset($url);unset($header);
		foreach ($ambilBerita as $key => $value) {
			$jdata = json_decode($value[respons],true);
			foreach ($jdata[data][data] as $key => $dataArtikel) {
				$artikel[] = $dataArtikel[id];
			}
		}
		$artikel = array_unique($artikel);
		echo "[+] Mengambil data artikel (CID : ".$cid.") ==> ".count(array_unique($artikel))."\r\n";
	}
	while (TRUE) {
		$timeIn60Minutes = time() + 60*60;
		$rnd 	= array_rand($artikel); 
		$id 	= $artikel[$rnd];
		$url[] = array(
			'url' 	=> 'http://api.beritaqu.net/timing/read',
			'note' 	=> $rnd, 
		);
		$header[] = array(
			'post' => 'OSVersion=8.0.0&android_channel=google&android_id='.$config['android_id'].'&content_id='.$id.'&content_type=1&deviceCode='.$config['deviceCode'].'&device_brand=samsung&device_ip=114.124.239.'.rand(0,255).'&device_version=SM-A730F&dtu=001&lat=&lon=&network=wifi&pack_channel=google&time='.$timeIn30Minutes.'&tk='.$config['tk'].'&token='.$config['token'].'&uuid='.$config['uuid'].'&version=10047&versionName=1.4.7&sign='.$config['sign'], 
		);
		$respons = $sdata->sdata($url , $header); 
		unset($url);unset($header);
		foreach ($respons as $key => $value) {
			$rjson = json_decode($value[respons],true);
			echo "[+][".$id." (Live : ".count($artikel).")] Message : ".$rjson['message']." | Poin : ".$rjson['data']['amount']." | Read Second : ".$rjson['data']['current_read_second']."\r\n";
			{
				unset($artikel[$value[data][note]]);
			}
		}
		if(count($artikel) == 0){
			sleep(60);
			break;
		}
		sleep(5);
	}
	$x++;
}
