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

    try {
      //
      // Get data from database
      //
      $dbh = new PDO('sqlite:dummy/data.sqlite');
      $sth = $dbh->prepare("SELECT * FROM users");
      $sth->execute();

      echo '<h2>Data received from database</h2>';
      $data = new Data($sth->fetchAll());
      print_table($data);
    } catch (DataException $e) {
      echo $e->getMessage();
    } catch (PDOException $e) {
      echo $e->getMessage();
    }

    ?></pre>

    <small>Dummy data generated with <a href="http://www.generatedata.com/">www.generatedata.com</a></small>
  </body>
</html>
