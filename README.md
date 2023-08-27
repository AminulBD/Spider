# Spider
Grab latest feed from search engine like Google, Bing, WordPress, etc.

## Installation
Install the package using composer by using this command below.

```bash
composer require aminulbd/spider
```

## Usage
Just import `\AminulBD\Spider\Spider` package and construct it with supported driver and call the `find` method like as below.

```php
// require '../vendor/autoload.php'; // if you are going with standalone mode.

use AminulBD\Spider\Spider;

$engine = 'wordpress'; // supported: google, bing and wordpress
$config = [
    'base_uri' => 'https://aminul.net/wp-json/wp/', // This is required for wordpress.
];

$spider = new Spider($engine, $config);

$finder = $spider->find(['q' => 'WordPress']);
$results = $finder->next(); // Here is your results. 

print_r($results);
```
