# Food Vendor Search CLI

## Overview
A console application to search a EBNF file of food vendors for menu items available on a given day, time, location and a headcount.

## Requirements

- PHP >= 7.1.3
- BCMath PHP Extension
- Ctype PHP Extension
- JSON PHP Extension
- Mbstring PHP Extension
- OpenSSL PHP Extension
- PDO PHP Extension
- Tokenizer PHP Extension
- XML PHP Extension
- composer

## Install

You can use the following “composer” command to install the necessary PHP modules.   
`composer install`

## Execution

The console application takes five input parameters:

```
filename - input file with the vendors data
day      - delivery day (dd/mm/yy)
time     - delivery time in 24h format (hh:mm)
location - delivery location (postcode without spaces, e.g. NW43QB)
covers   - number of people to feed (2)
```

Example:

`php foodVendorSearchCli --filename='tests/unit/resources/example-input.txt' --day='11/11/18' --time='11:00' --location='NW43QB' --covers='20'`


## Testing 

You can run Codeception tests by running the command:  
unix system:  
`php vendor/bin/codecept run unit`  
windows system:  
`vendor/bin/codecept.bat run unit`
