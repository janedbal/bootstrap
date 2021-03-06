<?php

/**
 * Test: Nette\Configurator and createContainer.
 */

use Nette\Configurator;
use Tester\Assert;


require __DIR__ . '/../bootstrap.php';


date_default_timezone_set('America/Los_Angeles');

$configurator = new Configurator;
$configurator->setTempDirectory(TEMP_DIR);
$configurator->addParameters([
	'wwwDir' => 'overwritten', // overwrites default value
	'foo2' => '%foo%',         // uses parameter from config file
	'foo3' => '%foo%',         // will be overwritten by config file
]);
$container = $configurator->addConfig('files/configurator.basic.neon', 'production')
	->createContainer();

Assert::same('overwritten', $container->parameters['wwwDir']);
Assert::same('hello world', $container->parameters['foo']);
Assert::same('hello world', $container->parameters['foo2']);
Assert::same('overwritten', $container->parameters['foo3']);
Assert::same('hello', $container->parameters['bar']);
Assert::same('hello world', constant('BAR'));
Assert::same('Europe/Prague', date_default_timezone_get());

Assert::same([
	'dsn' => 'sqlite2::memory:',
	'user' => 'dbuser',
	'password' => 'secret',
], $container->parameters['database']);
