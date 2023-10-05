<?php

$conn = new mysqli(getenv('AZURE_MYSQL_HOST'), getenv('AZURE_MYSQL_USERNAME'), getenv('AZURE_MYSQL_PASSWORD'), getenv('AZURE_MYSQL_DBNAME'));

// Check connection
/*if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}*/

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notes WG</title>
    <script>
        function search() {
            const urlParams = new URLSearchParams(window.location.search);
            urlParams.set('search', document.getElementById("search").value);
            window.location.search = urlParams;
        }
    </script>
</head>
<body>
    <h1>Notes WG</h1>
    <input placeholder="Key words" id="search" />
    <button onclick="search();">Search</button>
    <p>Get Param (search):<?= $_GET['search'] ?></p>
    <?= [getenv('AZURE_MYSQL_HOST'), getenv('AZURE_MYSQL_USERNAME'), getenv('AZURE_MYSQL_PASSWORD'), getenv('AZURE_MYSQL_DBNAME')] ?>
</body>
</html>