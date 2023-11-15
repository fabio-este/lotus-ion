<?php

namespace Deployer;

use PhpParser\Comment;

require 'recipe/common.php';
require 'recipe/rsync.php';

date_default_timezone_set('Europe/Berlin');

/**
 * Configuration
 */
set('repository', 'git@git.rheingans.io:typo3/think-tank-owl-bric.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true);

// Project name
set('application', 'lotus-ion.com');

set('bin/php', static function () {
    if (has('bin')) {
        $bin = get('bin');
        if (is_array($bin) && isset($bin['php'])) {
            return $bin['php'];
        }
    }
    return locateBinaryPath('php');
});
set('bin/composer', static function () {
    if (has('bin')) {
        $bin = get('bin');
        if (is_array($bin) && isset($bin['composer'])) {
            return $bin['composer'];
        }
    }
    return locateBinaryPath('composer');
});

set('composer_options', '{{composer_action}} --prefer-dist --no-dev --no-progress --no-interaction --optimize-autoloader --no-suggest');

/**
 * Hosts
 */
inventory('servers.yml');

/**
 * TYPO3
 */
set('typo3_root', 'private');
set('typo3_web', 'public');

// Shared directories
set('shared_dirs', [
    '{{typo3_root}}/fileadmin',
    '{{typo3_root}}/typo3temp',
    '{{typo3_root}}/uploads',
    'config',
    'var'
]);

// Shared files
set('shared_files', [
    '{{typo3_root}}/typo3conf/LocalConfiguration.php',
    //'config/sites/thinktank-owl/config.yaml',
    //'config/sites/bric-owl/config.yaml',
    'public/robots.txt',
]);

// Writeable directories
set('writable_dirs', [
    '{{typo3_root}}/fileadmin',
    '{{typo3_root}}/typo3temp',
    '{{typo3_root}}/typo3conf',
    '{{typo3_root}}/uploads',
    'config',
    'var'
]);


set('writable_tty', true);
set('writable_mode', 'chmod');
set('writable_use_sudo', true);
set('writable_chmod_mode', '0775');
set('writable_chmod_recursive', true);

/**
 * TYPO3 specific tasks
 */

set('typo3cms_command', 'vendor/bin/typo3cms');

task('typo3:install:fixfolderstructure', function () {
    run('{{bin/php}} {{release_path}}/{{typo3cms_command}} install:fixfolderstructure');
})->desc('Fix Folder Structure');

task('typo3:install:extensionsetupifpossible', function () {
    run('{{bin/php}} {{release_path}}/{{typo3cms_command}} install:extensionsetupifpossible');
})->desc('Setup all active extensions');

task('typo3:cache:flush', function () {
    run('{{bin/php}} {{release_path}}/{{typo3cms_command}} cache:flush');
})->desc('Flush the cache');

task('typo3:database:updateschema', function () {
    run('{{bin/php}} {{release_path}}/{{typo3cms_command}} database:updateschema');
})->desc('update database schema');

/*task('typo3:language:update', function () {
    run('{{bin/php}} {{release_path}}/{{typo3cms_command}} language:update');
})->desc('Update all active languages');
*/

task('deploy:typo3', [
    'typo3:install:fixfolderstructure',
    'typo3:install:extensionsetupifpossible',
   // 'typo3:language:update',
]);

/**
 * Frontend
 */

set('bin/yarn', function () {
    return runLocally('which yarn');
});

task('yarn:install', function () {
    runLocally("{{bin/yarn}} install");
});
task('yarn:build', function () {
    runLocally("{{bin/yarn}} run build");
});

task('frontend', [
    'yarn:install',
    'yarn:build'
]);


$rsync = get('rsync');
$rsync['options'] = ['chmod=Dug=rwx,Do=rx,Fug=rw,Fo=r'];
set('rsync', $rsync);

set('rsync_src', __DIR__ . '/packages/site_package/Resources/Public');
set('rsync_dest', '{{release_path}}/packages/site_package/Resources/Public');

/**
 * Main task
 */
task('deploy', [
    'deploy:prepare',
    'deploy:lock',
    'deploy:release',
    'deploy:update_code',
    'frontend',
    'rsync',
    'deploy:vendors',
    'deploy:shared',
    'deploy:writable',
    'deploy:typo3',
    'deploy:symlink',
    'htaccess',
    'deploy:unlock',
    'cleanup',
])->desc('Deploy your project');

after('deploy', 'success');
after('deploy:failed', 'deploy:unlock');

/**
 * Parse the .htaccess file for the right environment
 */
task('htaccess', static function () {

    // read the stage
    $stage = NULL;
    if (input()->hasArgument('stage')) {
        $stage = input()->getArgument('stage');
    }

    if ($stage === NULL) {
        writeln('You need to specify a stage.');
        return;
    }

    $stageUC = strtoupper($stage);

    $filePath = 'htaccess.txt';
    // check if .htaccess exists
    if (!file_exists($filePath)) {
        writeln('.htaccess file not found');
        return;
    }

    // read file into array
    $htaccessContent = explode(PHP_EOL, file_get_contents($filePath));

    if (count($htaccessContent) > 0) {
        //iterate over each line
        foreach ($htaccessContent as $key => $line) {
            // check if the markup syntax is present in the line
            $match = preg_match_all('/#\[(.*)\]#/', $line);

            if ($match > 0) {
                $startPos = strpos($line, '#[');
                $endPos = strpos($line, ']#');
                // get the possible stages
                $stagesFromLine = substr($line, $startPos, $endPos);

                if (str_contains($stagesFromLine, $stageUC)) {
                    // if so, remove the comment
                    $htaccessContent[$key] = substr($line, 0, $startPos) . substr($line, $endPos + 2, strlen($line));
                } else {
                    // else, remove the line
                    unset($htaccessContent[$key]);
                }
            }
        }
    }

    $processedContent = implode(PHP_EOL, $htaccessContent);
    run('touch {{release_path}}/public/.htaccess');
    run('echo "' . base64_encode($processedContent) . '" | base64 --decode > {{release_path}}/public/.htaccess');

    writeln('Processing .htaccess for ' . $stageUC . ' done!');
});
