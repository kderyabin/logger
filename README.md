# PSR 3 Logger implementaion

Lightweight and extremely customizable logger which implement the [PSR-3](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-3-logger-interface.md) standard.

## Requirements

- Logger works with PHP 7.1 or above.

## Installation

Install the latest version with

```bash
$ composer require kod/logger
```

## Usage

Out of the box
```php
<?php
use Kod\Logger;

// Init logger with default settings
$log = new Logger();

// log some message
$log->info('Info message');
```
This will log to 'php://stderr' default fields in json format.
```json
{
    "message": "Info message",
    "level": "info",
    "level_code": 6,
    "datetime": "2018-11-02T10:01:34.594+01:00"
}
```
Certainly you want some more data to be logged to fulfill your business or security requirements.
You can do it by configuring the message section of the logger configuration.
Message section allows you to: 
* customize a log data structure (`fields` section)
* set/reset a field value (`setters` section)
* declare date fields having different formats (`dates` section)
* apply filters on data (`filters` section)

### Extend data structure

You can extend default fields with `fields` keyword. Those fields are written with every log.
```php
<?php
use Kod\Logger;
// Init logger
$log = new Logger([
    'message' => [
        // extend default log fields
        'fields' => [
            'login' => '',
            // fields with default values
            'request_uri' => $_SERVER['REQUEST_URI'],
            'user_agent' => $_SERVER['HTTP_USER_AGENT'],
            'client_ip' => $_SERVER['REMOTE_ADDR'],
        ]
    ],

// Success case
$login = 'login@domain.com'; // in real world the $login comes from the user input
$log->info('Login success', [
    'login' => $login,
]);
// Error case
$login = '';
$exception = new Exception('Not an email');
$log->error('Login failed: invalid login', [
    // this will be appended to defined fields 
    'trace' => (string)$exception
]);
```
This will output :

```json
{
    "message": "Login success",
    "level": "info",
    "level_code": 6,
    "datetime": "2018-11-02T15:50:42.747+00:00",
    "login": "login@domain.com",
    "request_uri": "/login",
    "user_agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:64.0) Gecko/20100101 Firefox/64.0",
    "client_ip": "127.0.0.1"
}
{
    "message": "Login failed: invalid login",
    "level": "error",
    "level_code": 4,
    "datetime": "2018-11-02T15:50:42.748+00:00",
    "login": "",
    "request_uri": "/login",
    "user_agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:64.0) Gecko/20100101 Firefox/64.0",
    "client_ip": "127.0.0.1",
    "trace": "Exception: Not an email in D:\\projects\\github\\logger\\sample\\init.php:51\nStack trace:\n#0 {main}"
}
```
Notice the extra field `trace` is simply appended to defined fields.

### Set/Reset a field value

This can be accomplished with `setters` keyword. A setter is attached to a field and allows to modify its value. 
It may be an anonymous function or a callable accepting 1 argument. 

```php
<?php
use Kod\Logger;

// Init logger
$log = new Logger([
    'message' => [
        // extend default log fields
        'fields' => [
            'login' => '',
            'mobile' => ''
        ],
        'setters' => [
            // encrypt the login because the security team wants it
            'login' => ['MyClass', 'encrypt'],
            // obfuscate a mobile number
            'mobile' => function($value){
                return  substr_replace($value,  '****', 2, -3);
            }
        ]
    ],
]);

$log->info('Info message', [
    'login' => 'login@domain.com',
    'mobile' => '6699999999999',
]);
``` 
The output :
```json
{
    "message": "Info message",
    "level": "info",
    "level_code": 6,
    "datetime": "2018-11-02T17:20:06.442+00:00",
    "login": "G9C+gECCa55z/XIMF4Fixg==",
    "mobile": "66****999"
}
```
### Filters
Filter are conceptually different from setters. 
A filter operates on all fields and its main purpose is to prepare the data for logging. 
For instance to remove fields with empty values and thus do not log them.
Filter like setter may be an anonymous function or a callable accepting 1 argument (an array).

```php
<?php
use Kod\Logger;

// Init logger
$log = new Logger([
    'message' => [
        'fields' => [
            'login' => 'NA',
            'mobile' => 'NA'
        ],
        'filters' => [
            // remove empty fields and fields with default values
            function($fields){
                return array_filter($fields, function($value){
                    return !empty($value) && $value !== 'NA';
                });
            }
        ]
    ],
]);
// write a log
$log->info('Info message', [
    'login' => '',
]);
```
The result :
```json
{
    "message": "Info message",
    "level": "info",
    "level_code": 6,
    "datetime": "2018-11-02T17:51:23.023+00:00"
}
```
### Date fields
Date fields can be defined with `dates` keywords. 
This part of message configuration says to logger which fields are the dates and the format to use for their initialization.
For date formatting see [php date function](http://php.net/manual/en/function.date.php) and 
[predefined date constants](http://php.net/manual/en/class.datetimeinterface.php#datetime.constants.types).<br/>
By default the `datetime` field is set to `DATE_RFC3339_EXTENDED` constant but you may change in 
`dates` section and optionally configure other fields. <br/>
Date fields must be also declared in `fields` section.

```php
<?php
use Kod\Logger;

// Init logger
$log = new Logger([
    'message' => [
        'fields' => [
            // declare date fields
            'datetime' => '',
            'timezone' => '',
            'offset' => '',
            'timestamp' => '',
        ],
        'dates' => [
            'datetime' => 'H:i:s m/d/y',
            // Timezone abbreviation
            'timezone' => 'T',
            // Difference to Greenwich time (GMT) with colon between hours and minutes
            'offset' => 'P',
            // Seconds since the Unix Epoch with microseconds
            'timestamp' => 'U.u'
        ]
    ],
]);
$login = '';
$log->info('Info message');
```
The result: 

```json
{
    "message": "Info message",
    "level": "info",
    "level_code": 6,
    "datetime": "19:39:50 11/02/18",
    "timezone": "CET",
    "offset": "+01:00",
    "timestamp": "1541183990.867800"
}
``` 
### Log 
## About


### Submitting bugs and feature requests

Bugs and feature requests are tracked on [GitHub](https://github.com/kderyabin/logger/issues)

### Author

Konstantin Deryabin - <kderyabin@orange.fr> <br />

### License

Logger is licensed under the MIT License - see the `LICENSE` file for details

