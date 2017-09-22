<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <title>AwesomeDataLists</title>
  </head>
  <body>
    <pre><?php

    require '../src/Data.php';
    require '../src/DataException.php';

    require 'print_table.php';

    use Cheich\AwesomeDataLists\Data as Data;

    try {
      //
      // Load array
      //
      $data = new Data(require 'dummy/data.php');

      //
      // Filter
      //
      echo '<h2><code>Data::filter()</code></h2>';
      echo '<h3>Filter even IDs</h3>';
      $data->filter(function($row) {
        if (($row['id'] % 2) == 1)
          return true;
        return false;
      });
      print_table($data);

      //
      // Walk
      //
      echo '<h2><code>Data::walk()</code></h2>';
      echo '<h3>Change case</h3>';
      $data->walk(function($row) {
        $row['firstname'] = strtoupper($row['firstname']);
        $row['lastname'] = strtolower($row['lastname']);
        return $row;
      });
      print_table($data);

      //
      // Sort
      //
      echo '<h2><code>Data::sort()</code></h2>';
      echo '<h3>Sort by last name then by first name</h3>';
      $data->sort(function($row1, $row2) {
        $result = 0;
        $result = strcmp($row1['lastname'], $row2['lastname']);

        if ($result == 0) {
          $result = strcmp($row1['firstname'], $row2['firstname']);
        }

        return $result;
      });
      print_table($data);
    } catch (DataException $e) {
      echo $e->getMessage();
    }

    ?></pre>

    <small>Dummy data generated with <a href="http://www.generatedata.com/">www.generatedata.com</a></small>
  </body>
</html>
