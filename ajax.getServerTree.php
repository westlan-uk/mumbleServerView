<?php

require_once 'classes/ServerViewer.php';

header('Access-Control-Allow-Origin: *') ;
header('Content-Type: application/json');

try {	
	$serverId = intval($_GET['serverId']) or die('Please provide the serverId parameter');
	$serverTree = ServerViewer::getServer($serverId);
} catch (Exception $e) {
	echo json_encode(array('error' => $e->getMessage()));
	exit;
}


echo json_encode($serverTree);

