<?php
defined('TYPO3') or die('Access denied.');
call_user_func(function()
{
    $GLOBALS['TCA']['tx_bootstrappackage_timeline_item']['columns']['date']['config']['required'] = 0;
});

