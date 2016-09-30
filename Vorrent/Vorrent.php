<?php
	$name = preg_replace('/\s+/','+',$_POST['name']);

	$checker = explode('+',$name);

	if(isset($_POST['720p'])) {
		array_push($checker, "720p");
	}
	if(isset($_POST['1080p'])) {
		array_push($checker, "1080p");
	}
	if(isset($_POST['CAM'])) {
		array_push($checker, "CAM");
	}
	if(isset($_POST['160kbps'])) {
		array_push($checker, "160kbps");
	}
	if(isset($_POST['320kbps'])) {
		array_push($checker, "320kbps");
	}

	print_r($checker);
	echo "Search parameter : ".$name."<br><br>";
	$search_url = sprintf("http://www.1377x.to/srch?search=%s",$name);
	$doc = new DOMDocument();
	$html = file_get_contents($search_url);
	$doc->loadHTML($html);
	$html_save = $doc->saveHTML();
	$detail_link = '';

	echo "Search Links :";
	foreach($doc->getElementsByTagName('a') as $link) {
		$str_link = $link->getAttribute('href');
		$check = 1;
		foreach($checker as $check) {
			if(strpos(strtolower($str_link),strtolower($check)) !== false) {
				$check = 1;
			}
			else {
				$check = 0;
				break;
			}
		}
		if($check == 1) {
			echo $str_link;
			$detail_link = $str_link;
			break;
		}
	}
	echo $detail_link;

	echo "<br><br>Found a torrent link : ".$detail_link;
	$link = sprintf("http://www.1377x.to%s",$detail_link);
	
	echo "<br><br> Generating SHA Checksum";
	$html = file_get_contents($link);
	$doc->loadHTML($html);
	$html_save = $doc->saveHTML();

	echo "<br><br>Getting Magnet Link";
	$magnet_link = $doc->getElementById('magnetdl')->getAttribute('href');
	echo $magnet_link;
	chdir('/home/jeet');

	echo "<br><br> Starting transmission-cli and adding torrent";
	$cmd = sprintf('nohup transmission-cli -w /home/jeet/Downloads/ -f /home/jeet/Documents/php_work/Vorrent/Vorrent_close.sh %s &', $magnet_link);

	echo "<br><br>Downloading... <br> <pre>".shell_exec($cmd)."</pre>";
	
	echo "<br><br>Downloaded to /home/jeet/Downloads";
?>	