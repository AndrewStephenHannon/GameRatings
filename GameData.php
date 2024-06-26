<?php

    //Connect to the SQL database locally
    $serverName = "desktop-ecie849\sqlexpress";
    $connectionInfo = array("Database"=>"GameRatings", "Uid"=>"", "PWD"=>"");
    $connection = sqlsrv_connect($serverName, $connectionInfo);

    
    //Connect to the SQL database Web Server (credentials hidden)
    //$serverName = "****,port#";
    //$connectionInfo = array("Database"=>"****", "Uid"=>"****", "PWD"=>"****");
    //$connection = sqlsrv_connect( $serverName, $connectionInfo);

    //get game ID from html query
    $q = $_REQUEST["q"];

    //check to see if connection to database is made
    if($connection)
        echo "";
    else
        echo "No connection established<br>";

    //create SQL query to obtain all Game Data (info on game from Game Table joined with the Game's platform
    //data on the platform table and getting Dev and Pub names from the Developer and Publisher tables)
    //$sqlquery = "SELECT DeveloperPage.[Developer Name], PublisherPage.[Publisher Name], *
    //            FROM DeveloperPage, PublisherPage, GamePage INNER JOIN PlatformsTable ON GamePage.GameID = PlatformsTable.GameID 
    //            WHERE GamePage.GameID=" . $q . " AND GamePage.DevID = DeveloperPage.DevID AND GamePage.PubID = PublisherPage.PubID";
    $sqlquery = "SELECT DeveloperPage.[Developer Name], PublisherPage.[Publisher Name], * FROM DeveloperPage, PublisherPage, GamePage 
                WHERE GamePage.GameID=" . $q . " AND GamePage.DevID = DeveloperPage.DevID AND GamePage.PubID = PublisherPage.PubID";
    $result = sqlsrv_query($connection, $sqlquery); //execute SQL query

    //if the SQL query gets data,store the data in variable to be formatted in json
    //if SQL fails, print out the error
    if($result)
    {
        $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
    }
    else
    {
        die(print_r(sqlsrv_errors(), true));
    }

    echo json_encode($row); //return the game data results in json format

    sqlsrv_close($connection); //close the connection to the database
?>