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

if(isset($_GET['sql'])) {
    $result = $conn->query($_GET['sql']);

    if(is_bool($result)) {
        $sqlRes = $result ? "Sql-query done" : "Sql-query failed";
    } else {
        while ($row = $result->fetch_assoc()) {
            $sqlRes .= json_encode($row) . "<br />";
        }
    }
}

if(isset($_GET['new_note'])){
    $conn->query("INSERT INTO notes VALUES ('{$_GET['new_note']}')");
}

if(isset($_GET['del_note_text'])) {
    $conn->query("DELETE FROM notes WHERE note = '{$_GET['del_note_text']}'");
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notes WG</title>
    <script>
        function search() {
            const urlParams = new URLSearchParams();
            urlParams.set('search', document.getElementById("search").value);
            window.location.search = urlParams;
        }

        function runSql() {
            const urlParams = new URLSearchParams();
            urlParams.set('sql', document.getElementById("sql").value);
            window.location.search = urlParams;
        }

        function resetGetParams() {
            const urlParams = new URLSearchParams();
            window.location.search = urlParams;
        }

        function createNote() {
            const urlParams = new URLSearchParams();
            urlParams.set('new_note', document.getElementById("new_note").value);
            window.location.search = urlParams;
        }

        function deleteNode(text) {
            const urlParams = new URLSearchParams();
            urlParams.set('del_note_text', text);
            window.location.search = urlParams;
        }
    </script>
</head>
<body>
    <h1>Notes WG</h1>
    <h2>Create Note</h2>
    <textarea id="new_note" rows="4" cols="50" placeholder="node_text"></textarea><br />
    <button onclick="createNote()">Create</button>

    <h2>View Notes</h2>
    <input placeholder="Key words" id="search" />
    <button onclick="search();">Search</button>
    <button onclick="resetGetParams();">Reset Search</button>
    <?= isset($_GET['search']) ? "<p>You searched for: {$_GET['search']}</p>" : "" ?>
    <ul>
        <?php 
            if(isset($_GET['search'])) {
                $result = $conn->query("SELECT * FROM notes WHERE note LIKE '%{$_GET['search']}%'");
            } else {
                $result = $conn->query("SELECT * FROM notes");
            }
            while ($row = $result->fetch_assoc()) {
                echo "<li>{$row['note']}<button onclick=\"deleteNode('{$row['note']}')\">x</button></li>";
            }
        ?>
    </ul>
    <?php if($sqlRes || isset($_GET['showSql'])) { ?>
        <h2>SQL Queries</h2>
        <input placeholder="SQL Command" id="sql" />
        <button onclick="runSql();">Run</button>
        <?php
            if($sqlRes) {
                echo "<h3>Results</h3>";
                echo $sqlRes;
            }
        ?>
    <?php } ?>
</body>
</html>