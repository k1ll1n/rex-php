Rex PHP simple framework for REST

Simple use!

Creating interfaces
```PHP
RexBuilder::collector([
	'HTTP_METHOD|PATH' => YourClass::class
]);
```

Treatment YourClass
```PHP
class YourClass implements RexHandlerInterface {
    public function handle(RexResponse $response, RexRequest $request) {
        //your code
    }
}
```

Example
```
//test url
//http://your-domen.dev/users/12/user/2?foo=bar
```
```PHP
//index.php
use rex\RexBuilder;

require_once 'rex/utils/RexClassLoader.php';
require_once 'App/routes/User.php';

RexBuilder::collector([
	'GET|/users/:groupId/user/:userId' => User::class
]);
```
```PHP
//Class User

use rex\RexHandlerInterface;
use rex\RexRequest;

class User implements RexHandlerInterface {
    
    public function handle(RexResponse $response, RexRequest $request) {
        $response->show($request, true); //with debug mode
        $response->show($request->getQuery('foo')); //without debug mode
    }
}
```
Result
```
object(rex\RexRequest)#3 (3) {
  ["params":"rex\RexRequest":private]=>
  array(2) {
    ["groupId"]=>
    string(2) "12"
    ["userId"]=>
    string(1) "2"
  }
  ["query":"rex\RexRequest":private]=>
  array(1) {
    ["foo"]=>
    string(3) "bar"
  }
  ["data":"rex\RexRequest":private]=>
  array(0) {
  }
}

bar
```
Retrieving data
```PHP
public function handle(Request $request) {

        /*REQUEST*/
        $request->params();
        $request->params('name_item_element');
        $request->queryParams();
        $request->queryParams('name_item_element');
        $request->data();
        $request->data('name_item_element');
        $request->body();
        $request->contentType();
        $request->headers();
        $request->headers('name_item_element');
        /*END*/
        
        /*RESPONSE*/
        $response->show('Your response');
        $response->show('Your response', dubug_flag(bool));
        {
            echo 'Your response';             //No need to use echo
            $response->show('Your response'); //need use method show()
        }
        $response->setSession('name', 'value');
        $response->unsetSession('name');
        $response->destroySession();
        $response->getSession();
        $response->getSession('name_item_element');
        /*END*/
}
```
Next, you treat them as you need.