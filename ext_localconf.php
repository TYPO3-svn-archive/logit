<?php

$TYPO3_CONF_VARS['SC_OPTIONS']['t3lib/class.t3lib_div.php']['devLog'][] = 'EXT:logit/class.tx_logit.php:tx_logit->>doLog';
$TYPO3_CONF_VARS['SYS']['enable_DLOG'] = 1;

?>