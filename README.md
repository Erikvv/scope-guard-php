
# ScopeGuard for PHP, easy exception-safe transactions

This is a PHP implementation of the ScopeGuard idiom.

You can use this whenever you want to schedule code to run on the end of a scope. If you are mainly using exceptions the readability of some pieces of code can improve greatly.

You can differentiate between code that should be executed
* always (onExit)
* when an exception is thrown (onFailure)
* when the scope is left sucessfully (onSuccess)

```
$scope = new ScopeGuard;

$scope->onFailure(function($ex) { error_log($ex); }); 

$salesEntry = $repository->addSalesEntry($data);
$scope->onSuccess(function use ($repository, $salesEntry) { $repository->markFinalized($salesEntry); });

// normal control flow is still usefull
try {
	$paymentProcessing->execute($salesEntry->paymentInfo);
}
catch (FraudException $ex) {
	$fbi->alert($salesEntry);
}

// if we fail after this point, we want to alert a human agent to fix the situation
$scope->onFailure(function() { $serviceDesk->alert($salesEntry); });

$warehouse->sendPackage($salesEntry->order);
$mailServer->sendConfirmation(salesEntry);

// this makes sure the success handlers are called. 
// any other path leads to the failure handlers being called
$scope->markSuccessfull(); 
```

Obviously your logic will be different depending on the structure of your application and the requirements.

Probably you will find onFailure the most useful for doing rollbacks. OnSuccess will generally not be used because your normal code is the success case, but it may help to group some statements more logically. OnExit can be used for something like cleanup which must be done regardless of what the eventual outcome of the script was.
