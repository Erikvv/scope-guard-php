
```
composer require scope-guard/scope-guard dev-master
```

# ScopeGuard for PHP

This is a PHP implementation of the ScopeGuard idiom.

You can use this whenever you want to schedule code to run on the end of a scope. If you are already mainly using exceptions the readability of some pieces of code can improve greatly.

You can differentiate between code that should be executed
* always (onExit)
* when the scope is left early through an exception or early return (onFailure)
* when the scope is left sucessfully (onSuccess)

## Simplest example

This closes a connection on scope exit. 

```php
use ScopeGuard\Scope;

$connection = connect() 
$scope = new Scope;
$scope->onExit([$connection, 'disconnect']);

/* do stuff with $connection */

/* explicit or implicit return, ->disconnect() is called here */
return;
```

## OnFailure example

```php
use ScopeGuard\Scope;

$salesEntry = new SalesEntry;

$scope = new Scope;
$scope->onFailure(function use ($serviceDesk, $salesEntry) { $serviceDesk->alertRepresentative($salesEntry); }); 

/* more logic here */

// this makes sure any success handlers are called. 
// any other path leads to the failure handlers being called
$scope->markSuccessful(); 

// unset can manually trigger the handlers (taking into account the regular reference-counting semantics)
unset($scope);
```

Obviously your logic will be different depending on the structure of your application and the requirements.

Probably you will find onFailure the most useful for doing rollbacks. OnSuccess will generally not be used because your normal code is the success case, but it may help to group some statements more logically. OnExit can be used for something like cleanup which must be done regardless of what the eventual outcome of the script was.

It is perfectly fine to use multiple Scope object at the same time.

## Todo

More examples

Some might find the need to remove handlers or mark failure after marking success earlier.
