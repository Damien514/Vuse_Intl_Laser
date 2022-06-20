<?php

$v=trim(isset($_POST['v']) ? $_POST['v'] : (isset($_GET['v']) ? $_GET['v'] : '300'));

include './generate.design.test.epod2.php';


generateAudio('e11ad6beb620985a7f3f',$v,'debug');
//generateAudio('37980077f2e01fd6030b',$v,'debug');

// generateAudio('32803b52435db33c4fd5','debug','debug');

?>
