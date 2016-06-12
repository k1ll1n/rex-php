Rex PHP

Simple framework for REST

Simple use!
Creating interfaces
```PHP
Builder::collector(array(
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
include 'App/rex/Builder.php';
include 'App/rex/BuildModel.php';
include 'App/routes/User.php';

use \rex\builder\Builder;
use \rex\builder\model\BuildModel;
use api\user\User;

Builder::collector(array(
    new BuildModel('GET', '/users/:groupId/user/:userId', new User())
));
```
```PHP
//Class User
namespace api\user;

include(__DIR__ . '/../rex/Route.php');
require __DIR__ . '/../rex/Request.php';

use rex\request\Request;
use rex\route\Route;

class User implements Route {
    public function handle(Request $request) {
        var_dump($request);
    }
}
```
Result
```
object(simplerest\request\Request)#4 (3) {
  ["params":"simplerest\request\Request":private]=>
  array(2) {
    ["groupId"]=>
    string(2) "12"
    ["userId"]=>
    string(1) "2"
  }
  ["query":"simplerest\request\Request":private]=>
  array(1) {
    ["foo"]=>
    string(3) "bar"
  }
  ["data":"simplerest\request\Request":private]=>
  array(0) {
  }
}
```
Retrieving data
```PHP
public function handle(Request $request) {
        $request->getParams();
        $request->getQuery();
        $request->getData();
}
```
Next, you treat them as you need.