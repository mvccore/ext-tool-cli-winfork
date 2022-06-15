# MvcCore - Extension - Tool - CLI - Windows Fork

[![Latest Stable Version](https://img.shields.io/badge/Stable-v5.0.0-brightgreen.svg?style=plastic)](https://github.com/mvccore/ext-tool-cli-winfork/releases)
[![License](https://img.shields.io/badge/License-BSD%203-brightgreen.svg?style=plastic)](https://mvccore.github.io/docs/mvccore/5.0.0/LICENSE.md)
![PHP Version](https://img.shields.io/badge/PHP->=5.4-brightgreen.svg?style=plastic)

Windows .NET Framework 4 utility for CLI calls via PHP `shell_exec()` or `system()` to fork new process in background.

This extension only copy precompiled binary `Fork.exe` into application directory `./App/Cli` after installation via Composer.

## Installation
```shell
composer require mvccore/ext-tool-cli-winfork
```

## Usage

Run PHP background job on Windows / Unix with MvcCore framework:
```php
<?php

// standard web request process:

include_once('vendor/autoload.php');

// prepare paths and params:
$app = \MvcCore\Application::GetInstance();
$req = $app->GetRequest();
$indexScriptAbsPath = $req->GetDocumentRoot() . $req->GetScriptName();
$phpParams = "-d max_execution_time=0 -d memory_limit=-1";
$bgProcessParams = "controller=bg-process action=calculate";
$cliDirFullPath = implode('/', [
	$req->GetAppRoot(),
	$app->GetAppDir(),
	$app->GetCliDir(),
]);

// prepare bg command:
$cmd = "php {$phpParams} {$indexScriptAbsPath} {$bgProcessParams}";
if (substr(mb_strtolower(PHP_OS), 0, 3) === 'win') {
	// Fork.exe automatically finds php.exe, php.bat or php.cmd in %PATH%
	$cmd = "Fork.exe {$cmd}";
} else {
	// Unix system needs to has php executable in $PATH environment variable
	// for user running web request scripts (eg: www, apache):
	$cmd = 'bash -c "exec nohup setsid ' . $cmd . ' > /dev/null 2>&1 &"';
}

// start second bg process:
$cwdBefore = getcwd();
chdir($cliDirFullPath);
system($cmd);
chdir($cwdBefore);

// continue in the standard web request process without 
// waiting for the background process execution end...

```
