<?php
defined('TYPO3') or die('Access denied.');

 $GLOBALS['TCA']['sys_file_reference']['columns']['crop']['config']['ratios'] = array(
    '1.7777777777777777' => '16:9',
    '0.5625' => '16:9',
    '1.3333333333333333' => '4:3',
    '0.75' => '3:4',
    '1' => '1:1',
    'NaN' => 'Free',
);
