Rex PHP simple framework for REST

Simple use!

Creating interfaces
```PHP
RexBuilder::collector(array(
    new BuildModel('HTTP_METHOD', 'PATH', YOUR_CLASS)
));
```

Treatment your_class
```PHP
class your_class implements Route {
    public function handle(Request $request) {
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
require 'App/rex/Builder.php';
require 'App/rex/BuildModel.php';
require 'App/routes/User.php';

Builder::collector(array(
    new BuildModel('GET', '/users/:groupId/user/:userId', new User())
));
```
```PHP
//Class User

require __DIR__ . '/../rex/Route.php';
require __DIR__ . '/../rex/Request.php';

class User implements Route {
    public function handle(Request $request) {
        var_dump($request);
    }
}
```
Result
```
object(Request)#6 (3) {
  ["params":"Request":private]=>
  array(2) {
    ["groupId"]=>
    string(2) "12"
    ["userId"]=>
    string(1) "2"
  }
  ["query":"Request":private]=>
  array(0) {
  }
  ["data":"Request":private]=>
  array(0) {
  }
}
```
Retrieving data
```PHP
public function handle(Request $request) {
        $request->getParamsArray();
        $request->getQueryArray();
        $request->getDataArray();
        $request->getParams('name_item_element');
        $request->getQuery('name_item_element');
        $request->getData('name_item_element');
}
```
Next, you treat them as you need.