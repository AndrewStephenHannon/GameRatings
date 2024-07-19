<?php

    //Connect to the SQL database locally
    $serverName = "desktop-ecie849\sqlexpress";
    $connectionInfo = array("Database"=>"GameRatings", "Uid"=>"", "PWD"=>"");
    $connection = sqlsrv_connect($serverName, $connectionInfo);

    
    //Connect to the SQL database Web Server (credentials hidden)
    //$serverName = "****,port#";
    //$connectionInfo = array("Database"=>"****", "Uid"=>"****", "PWD"=>"****");
    //$connection = sqlsrv_connect( $serverName, $connectionInfo);

    //get game ID and average score from html query
    $gameID = $_REQUEST["gameid"];
    $average = $_REQUEST["average"];

    //check to see if connection to database is made
    if($connection)
        echo "";
    else
        echo "No connection established<br>";
    
    //create SQL query for updating the average aggregated score of a game
    $sqlUpdateAverage = "UPDATE Games SET CurrentScore = $average WHERE GameID=" . $gameID;
    sqlsrv_query($connection, $sqlUpdateAverage); //execute the SQL query

    sqlsrv_close($connection); //close the connection to the database

?>