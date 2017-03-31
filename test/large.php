<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <title>AwesomeDataLists</title>
  </head>
  <body>
    <?php

    require '../autoload.php';
    require 'print_table.php';

    use AwesomeDataLists\Data as Data;

    @ini_set('memory_limit','512M');

    try {
      //
      // Load and build array
      //
      $loops = 1000;
      $array_data = require 'dummy/data.php';
      $array = array();
      $starttime_build = microtime(true);
      $starttime_all = microtime(true);

      for ($i = 0; $i < $loops; $i++) {
        foreach ($array_data as $dataset) {
          $array[] = $dataset;
        }
      }

      $starttime_constructor = microtime(true);
      $data = new Data($array);
      $interval_build = microtime(true) - $starttime_build;
      $interval_constructor = microtime(true) - $starttime_constructor;
      echo "<h1>Run through {$data->count()} rows</h1>";
      echo "<table><tr><th>Operation</th><th>Time</th></tr>";
      echo "<tr><td>Build array</td><td>{$interval_build}s</td></tr></p>";
      echo "<tr><td><code>Data::constructor()</code></td><td>{$interval_constructor}s</td></tr></p>";

      //
      // Standard loop
      //
      $starttime = microtime(true);
      while ($data->valid()) {
        $data->next();
      }
      $data->rewind();
      $interval = microtime(true) - $starttime;
      echo "<tr><td>Standard loop</td><td>{$interval}s</td></tr></p>";

      //
      // Count
      //
      $starttime = microtime(true);
      $data->count();
      $interval = microtime(true) - $starttime;
      echo "<tr><td><code>Data::count()</code></td><td>{$interval}s</td></tr></p>";

      //
      // Walk
      //
      $starttime = microtime(true);
      $data->walk(function($row) { return $row; });
      $interval = microtime(true) - $starttime;
      echo "<tr><td><code>Data::walk()</code></td><td>{$interval}s</td></tr></p>";

      //
      // Sort
      //
      $starttime = microtime(true);
      $data->sort(function($row) { return 0; });
      $interval = microtime(true) - $starttime;
      echo "<tr><td><code>Data::sort()</code></td><td>{$interval}s</td></tr></p>";

      //
      // Filter
      //
      $starttime = microtime(true);
      $data->filter(function($row) { return true; });
      $interval = microtime(true) - $starttime;
      echo "<tr><td><code>Data::filter()</code></td><td>{$interval}s</td></tr></p>";

      //
      // Serialize
      //
      $starttime = microtime(true);
      $serialized = $data->serialize();
      $interval = microtime(true) - $starttime;
      echo "<tr><td><code>Data::serialize()</code></td><td>{$interval}s</td></tr></p>";

      //
      // Unserialize
      //
      $starttime = microtime(true);
      $data->unserialize($serialized);
      $interval = microtime(true) - $starttime;
      echo "<tr><td><code>Data::unserialize()</code></td><td>{$interval}s</td></tr></p>";

      //
      // Columns
      //
      $starttime = microtime(true);
      $data->columns();
      $interval = microtime(true) - $starttime;
      echo "<tr><td><code>Data::columns()</code></td><td>{$interval}s</td></tr></p>";

      //
      // Columns count
      //
      $starttime = microtime(true);
      $data->columnCount();
      $interval = microtime(true) - $starttime;
      echo "<tr><td><code>Data::columnCount()</code></td><td>{$interval}s</td></tr></p>";

      //
      // All
      //
      $interval = microtime(true) - $starttime_all;
      echo "<tr><th>Sum</th><th>{$interval}s</th></tr></p>";
    } catch (DataException $e) {
      echo $e->getMessage();
    }

    ?>

    </table>
  </body>
</html>
