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
	$packageDir = realpath(dirname(__DIR__, 6));
	$composerClsLoader = 'Composer\\Autoload\\ClassLoader';
	$composerClsLoaderType = new \ReflectionClass($composerClsLoader);
	$appRoot = dirname($composerClsLoaderType->getFileName(), 3);
	$app = \MvcCore\Application::GetInstance();
	$srcDir = implode(DIRECTORY_SEPARATOR, [
		$packageDir,
		'bin',
		'Release'
	]);
	$cliDir = implode(DIRECTORY_SEPARATOR, [
		$appRoot,
		$app->GetAppDir(),
		$app->GetCliDir()
	]);
	$binFileName = 'Fork.exe';
	$srcFullPath = $srcDir . DIRECTORY_SEPARATOR . $binFileName;
	$targetFullPath = $cliDir . DIRECTORY_SEPARATOR . $binFileName;
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