<?php

namespace ScopeGuard;

class Test extends \PHPUnit_Framework_TestCase
{
	public function testFailure()
	{
		$onExit    = 'untouched';
		$onFailure = 'untouched';
		$onSuccess = 'untouched';

		$scope = new Scope;
		$scope->onExit(function() use (&$onExit) { $onExit = 'touched'; });
		$scope->onFailure(function() use (&$onFailure) { $onFailure = 'touched'; });
		$scope->onSuccess(function() use (&$onSuccess) { $onSuccess = 'touched'; });

		$this->assertEquals($onExit,    'untouched');
		$this->assertEquals($onFailure, 'untouched');
		$this->assertEquals($onSuccess, 'untouched');

		unset($scope);

		$this->assertEquals($onExit,    'touched');
		$this->assertEquals($onFailure, 'touched');
		$this->assertEquals($onSuccess, 'untouched');
	}

	public function testSuccess()
	{
		$onExit    = 'untouched';
		$onFailure = 'untouched';
		$onSuccess = 'untouched';

		$scope = new Scope;
		$scope->onExit(function() use (&$onExit) { $onExit = 'touched'; });
		$scope->onFailure(function() use (&$onFailure) { $onFailure = 'touched'; });
		$scope->onSuccess(function() use (&$onSuccess) { $onSuccess = 'touched'; });

		$this->assertEquals($onExit,    'untouched');
		$this->assertEquals($onFailure, 'untouched');
		$this->assertEquals($onSuccess, 'untouched');

		$scope->markSuccessful();

		unset($scope);

		$this->assertEquals($onExit,    'touched');
		$this->assertEquals($onFailure, 'untouched');
		$this->assertEquals($onSuccess, 'touched');
	}
}
