<?php
require_once __DIR__ . '/parsing.php';


// *************************************************
// ================ REQUESTS =======================
// *************************************************

if (isset($_REQUEST['in'])) {
    
	if (strtolower($_REQUEST['in']) == 'ukraine') {
        setcookie('load_in', $_REQUEST['in'], time()+ 3600*24*30);
		include __DIR__ . '/action.ukraine.php';
        
	} elseif (strtolower($_REQUEST['in']) == 'world') {
        setcookie('load_in', $_REQUEST['in'], time()+ 3600*24*30);
		include __DIR__ . '/action.world.php';
	}
}