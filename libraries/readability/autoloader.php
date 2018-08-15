<?php

$mapping = array(
	'Nerd_Wp_Lib\Utils\PF_ReadabilityForNerd'      => __DIR__ . '/Utils/PF_ReadabilityForNerd.php',
	'Nerd_Wp_Lib\Utils\ReadabilityForNerd'         => __DIR__ . '/Utils/ReadabilityForNerd.php',
	'Nerd_Wp_Lib\Utils\RetrieveHttpContentForNerd' => __DIR__ . '/Utils/RetrieveHttpContentForNerd.php',
	'Nerd_Wp_Lib\Utils\HTMLCheckerForNerd'         => __DIR__ . '/Utils/HTMLCheckerForNerd.php',
	'Nerd_Wp_Lib\Utils\OpenGraphForNerd'           => __DIR__ . '/Utils/OpenGraphForNerd.php',
);

spl_autoload_register(function ($class) use ($mapping) {
	if (isset($mapping[$class])) {
		require $mapping[$class];
	}
}, true);
