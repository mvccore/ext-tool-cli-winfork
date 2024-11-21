<?php

/**
 * MvcCore
 *
 * This source file is subject to the BSD 3 License
 * For the full copyright and license information, please view
 * the LICENSE.md file that are distributed with this source code.
 *
 * @copyright	Copyright (c) 2016 Tom Flidr (https://github.com/mvccore)
 * @license		https://mvccore.github.io/docs/mvccore/5.0.0/LICENSE.md
 */

/**
 * Remove previous npm code and install fresh content.
 * @return void
 */
call_user_func(function () {
	$packageDir = str_replace('\\', '/', realpath(dirname(__DIR__, 6)));
	$composerClsLoader = 'Composer\\Autoload\\ClassLoader';
	$composerClsLoaderType = new \ReflectionClass($composerClsLoader);
	$appRoot = str_replace('\\', '/', dirname($composerClsLoaderType->getFileName(), 3));
	$app = \MvcCore\Application::GetInstance();
	$srcDir = implode('/', [
		$packageDir,
		'bin',
		'Release'
	]);
	$cliDir = str_replace('~/', $appRoot . '/', $app->GetPathCli(FALSE));
	$binFileName = 'Fork.exe';
	$srcFullPath = $srcDir . '/' . $binFileName;
	$targetFullPath = $cliDir . '/' . $binFileName;
	// If there are any previous version - remove it:
	if (file_exists($targetFullPath)) {
		$removed = unlink($targetFullPath);
		if (!$removed) throw new \Exception(
			"There was not possible to remove previous version: `{$targetFullPath}`."
		);
	}
	if (!is_dir($cliDir)) {
		$created = mkdir($cliDir, 0777, TRUE);
		if (!$created) throw new \Exception(
			"There was not possible to create target dir: `{$cliDir}`."
		);
	}
	$modified = chmod($cliDir, 0777);
	if (!$modified) throw new \Exception(
		"There was not possible to set executable privileges on target dir: `{$cliDir}`."
	);
	$copied = copy($srcFullPath, $targetFullPath);
	if (!$copied) throw new \Exception(
		"There was not possible to copy target executable: `{$targetFullPath}`."
	);
	$modified = chmod($targetFullPath, 0777);
	if (!$modified) throw new \Exception(
		"There was not possible to set executable privileges on target executable: `{$targetFullPath}`."
	);
});