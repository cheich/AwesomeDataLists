<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <title>AwesomeDataLists - DataStructure</title>
  </head>
  <body>
    <pre><?php

    require '../autoload.php';
    require 'print_table.php';

    use AwesomeDataLists\Data as Data;
    use AwesomeDataLists\DataType as DataType;

    try {
      //
      // CSV
      //
      echo '<h2>Decode CSV</h2>';
      $datatype = new DataType(DataType::CSV);
      $data = $datatype->decode(file_get_contents('dummy/data.csv'));
      print_table($data);

      //
      // JSON
      //
      echo '<h2>Decode JSON</h2>';
      $datatype = new DataType(DataType::JSON);
      $data = $datatype->decode(file_get_contents('dummy/data.json'));
      print_table($data);

      //
      // XML
      //
      echo '<h2>Decode XML</h2>';
      $datatype = new DataType(DataType::XML);
      $data = $datatype->decode(file_get_contents('dummy/data.xml'));
      print_table($data);
    } catch (DataException $e) {
      echo $e->getMessage();
    }

    ?></pre>

    <small>Dummy data generated with <a href="http://www.generatedata.com/">www.generatedata.com</a></small>
  </body>
</html>
