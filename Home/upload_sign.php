<?php 
	$result = array();
	$imagedata = base64_decode($_POST['img_data']);
	$file_name = "canvas_sign.png";
	//Location to where you want to created sign image
	//$file_name = './'.$filename.'.png';
	file_put_contents($file_name,$imagedata);
	$result['status'] = 1;
	$result['file_name'] = $file_name;
	echo json_encode($result);
?>

