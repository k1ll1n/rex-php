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
    public function handle(RexRequest $request) {
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

require_once 'rex/utils/RexClassLoader.php';

class User implements RexHandlerInterface {
    
    public function handle(RexRequest $request) {
        var_dump($request);
        var_dump($request->getQuery('foo'));
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
string(3) "bar"
```
Retrieving data
```PHP
public function handle(Request $request) {
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
}
```
Next, you treat them as you need.