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
      // Load array
      //
      $data = new Data(require 'dummy/data.php');

      //
      // CSV
      //
      echo '<h2>Encode CSV</h2>';
      $datatype = new DataType(DataType::CSV);
      echo $datatype->encode($data);

      //
      // JSON
      //
      echo '<h2>Encode JSON</h2>';
      $datatype = new DataType(DataType::JSON, JSON_PRETTY_PRINT);
      echo $datatype->encode($data);

      //
      // XML
      //
      echo '<h2>Encode XML</h2>';
      $datatype = new DataType(DataType::XML);
      echo htmlspecialchars($datatype->encode($data));

      echo '<h2>Encode multi dimensional XML</h2>';
      $datatype = new DataType(DataType::XML);
      echo htmlspecialchars($datatype->encode(new Data([
        [
          'id' => 1,
          'child' => [
            'first' => '1A child',
            'second' => '1B child',
            'third' => '1C child',
          ]
        ],
        [
          'id' => 2,
          '@attributes' => [
            'class' => 'myclass',
          ],
          'child' => [
            'first' => '2A child',
            'second' => '2B child',
            'third' => '2C child',
          ]
        ],
      ])), ['formatOutput' => true]);
    } catch (DataException $e) {
      echo $e->getMessage();
    }

    ?></pre>

    <small>Dummy data generated with <a href="http://www.generatedata.com/">www.generatedata.com</a></small>
  </body>
</html>