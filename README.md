# AwesomeDataLists

Simple data handler

## Install

### Composer

#### Command Line

```
composer require cheich/awesome-data-lists:dev-master
```

#### composer.json

```
{
  "require": {
    "cheich/awesome-data-lists": "dev-master"
  }
}
```

## Requirements

- PHP >= 5.3.0

## Features

- Filter, walk and sort with closures
- Import/Export data from/to
  - CSV
  - JSON
  - XML
  - PHP Array

## Examples

### Fetch data from database

```php
try {
  $dbh = new PDO('sqlite:dummy/data.sqlite');
  $sth = $dbh->prepare("SELECT * FROM users");
  $sth->execute();
  $data = new Data($sth->fetchAll());
} catch (DataException $e) {
  echo $e->getMessage();
} catch (PDOException $e) {
  echo $e->getMessage();
}
```

### Import data from file

```php
try {
  $csv = new DataTypeCSV();
  $data = $csv->decode(file_get_contents('path/to/data.csv'));
} catch (DataException $e) {
  echo $e->getMessage();
}
```

### Import data from string

```php
try {
  $json = new DataTypeJSON();
  $data = $json->decode('[{"id": 1, "name": "Peter"}]');
} catch (DataException $e) {
  echo $e->getMessage();
}
```

### Export data

```php
try {
  $xml = new DataTypeXML();
  $data = new Data([
    [
      'id' => 1,
      'name' => 'Peter'
    ]
  ]);
  echo $xml->encode($data);
} catch (DataException $e) {
  echo $e->getMessage();
}
```
