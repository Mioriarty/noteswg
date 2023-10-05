<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$conn = new mysqli(getenv('AZURE_MYSQL_HOST'), getenv('AZURE_MYSQL_USERNAME'), getenv('AZURE_MYSQL_PASSWORD'), getenv('AZURE_MYSQL_DBNAME'));

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sqlRes = "";

if($_GET['sql']) {
    $result = $conn->query($_GET['sql']);

    if(is_bool($result)) {
        $sqlRes = $result ? "Sql-query done" : "Sql-query failed";
    } else {
        while ($row = $result->fetch_assoc()) {
            $sqlRes .= json_encode($row) . "<br />";
        }
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notes WG</title>
    <script>
        function updatePage() {
            const urlParams = new URLSearchParams();
            urlParams.set('search', document.getElementById("search").value);
            urlParams.set('sql', document.getElementById("sql").value);
            window.location.search = urlParams;
        }
    </script>
</head>
<body>
    <h1>Notes WG</h1>
    <h2>Create Note</h2>
    <textarea id="now_node" rows="4" cols="50" placeholder="node_text" />
    <button onclick="createNote()">Create</button>

    <h2>View Notes</h2>
    <input placeholder="Key words" id="search" />
    <button onclick="search();">Search</button>
    <button onclick="resetGetParams();">Reset Search</button>
    <ul>
        <?php 
            if(isset($_GET['search'])) {
                $result = $conn->query("SELECT * FROM notes WHERE note LIKE '%{$_GET['search']}%'");
            } else {
                $result = $conn->query("SELECT * FROM notes");
            }
            while ($row = $result->fetch_assoc()) {
                echo "<li>{$row['note']}</li>";
            }
        ?>
    </ul>


    <input placeholder="Key words" id="search" />
    <input placeholder="SQL Command" id="sql" />
    <button onclick="updatePage();">Run</button>
    <p>Get Param (search): <?= $_GET['search'] ?? "None" ?></p>
    <?php
        if($sqlRes) {
            echo "<h2>SQL-Results</h2>";
            echo $sqlRes;
        }
    ?>
</body>
</html>