# PSR 3 Logger
Lightweight and extremely customizable logger implementing the [PSR-3](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-3-logger-interface.md) standard.

## Requirements
Logger works with PHP >=7.1.

## Installation
Install the latest version with composer

```bash
$ composer require kod/logger
```

## Usage

Basic usage. Use default logger settings to log a message into 'php://stderr' in json format. Perfect for docker environment and works out of the box.

```php
<?php
use Kod\Logger;

$log = new Logger();
$log->debug('Debug message', [
    'client_login' => 'login@domain.com',
]);
```
Note the context data (`client_login` field) is simply merged into the default data structure.

```json
{
  "message": "Debug message",
  "level": "debug",
  "level_code": 7,
  "datetime": "2018-11-07T19:10:33.757+01:00",
  "client_login": "login@domain.com"
}
```
Here is a little bit more advanced setup for logging into a file. In this example we extend a default log data structure with fields that must be appended to every log. Those fields, if not overridden with context data, will have a default value.
```php
<?php
use Kod\Logger;

$log = new Logger([
    'message' => [
        // extend default log's data
        'fields' => [
            'client_name' => '',
            'client_login' => '',
            'request_uri' => $_SERVER['REQUEST_URI'],
            'client_ip' => $_SERVER['REMOTE_ADDR'],
        ],
    ],
    // distribution channels: places where logs must be written 
    'channels' => [
        [
            'handler' => [
                'path' => '/var/tmp/debug.log'
            ],
        ],
    ],
]);

$log->debug('Debug message', [
    'client_login' => 'login@domain.com',
]);
```
And here is our log message:
```json
{
    "message": "Debug message",
    "level": "debug",
    "level_code": 7,
    "datetime": "2018-11-07T19:10:33.757+01:00",
    "client_name": "",
    "client_login": "login@domain.com",
    "request_uri": "/",
    "client_ip": "127.0.0.1"
}
```

## Core concept

Logger operates with a Message object and log distribution channels. The message takes care of the log data: initializes date fields, (re)sets and filters the data according to defined configuration. The channel delivers this data to the destination (file, syslog)  in desired format (json, text). You may have as many channels as you wish but  only one data structure per logger.  

The default log data structure is quite minimalistic because it is supposed to be  extended through the configuration according to your business requirements.

|Field|Description
|:-------|:---
|message| message
|level|log level
|level_code|level code
|datetime|date

## Log levels
    
Logger is strictly compliant to PSR-3 specification and thus has following 8 log levels as they are defined in [RFC 5424](http://tools.ietf.org/html/rfc5424): _debug_, _info_, _notice_, _warning_, _error_, _critical_, _alert_, _emergency_

### Priority
  
It’s a common practice to restrict some levels from being logged by defining level priorities. Priorities in `Kod\Logger`, in descending order from maximum to minimum, are: 
* emergency
* alert
* critical
* error
* warning
* notice
* info
* debug

Debug level has the lowest priority and emergency level - the highest.  Level priority should not be confused with level code. 
Priority can be set with keywords  `levelPriorityMin` and/or `levelPriorityMax` globally for all channels or per channel .
    
### Custom levels

Custom log levels are not allowed. 

### Level codes

Level code is a numeric value associated to a level. It makes part of a base log data structure (`level_code` field).
By default they are set to php log levels constants. Surprisingly, those constants have different values on linux and windows systems.

|Constant       |Linux  |Windows    |Description|
|:---           |:---   |:---       |:---
|LOG_EMERG      |0      |1      |Emergency: system is unusable                
|LOG_ALERT      |1      |1      |Alert: action must be taken immediately      
|LOG_CRIT       |2      |1      |Critical: critical conditions                
|LOG_ERR        |3      |4      |Error: error conditions                      
|LOG_WARNING    |4      |4      |Warning: warning conditions                  
|LOG_NOTICE     |5      |5      |Notice: normal but significant condition     
|LOG_INFO       |6      |6      |Informational: informational messages        
|LOG_DEBUG      |7      |6      |Debug: debug-level messages                  

You can reset default codes by defining your custom codes with the keyword `levelCode` in the configuration.

## About

### Submitting bugs and feature requests

Bugs and feature requests are tracked on [GitHub](https://github.com/kderyabin/logger/issues)

### Author

Konstantin Deryabin - <kderyabin@orange.fr>

### License

Logger is licensed under the MIT License - see the `LICENSE` file for details

