<?php

function print_table($data) {
  $data->rewind();
  echo '<table><tr><th>ID</th><th>Firstname</th><th>Lastname</th><th>Email</th></tr>';

  while ($data->valid()) {
    $row = $data->current();
    echo "<tr><td>{$row['id']}</td><td>{$row['firstname']}</td><td>{$row['lastname']}</td><td>{$row['email']}</td></tr>";
    $data->next();
  }
  echo '</table>';
}
