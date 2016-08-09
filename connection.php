<?php
  // 1- create a database connection
  define("DB_SERVER","localhost");
  define("DB_USER","root");
  define("DB_PASSWORD","");
  define("DB_NAME","cv");
  $connection = mysqli_connect(DB_SERVER,DB_USER,DB_PASSWORD,DB_NAME);

  // check databse connection
  if (mysqli_connect_errno()) {
    die("connection failed ". mysqli_connect_error() . mysqli_connect_errno());
  }else {
    echo "";
  }
?>
