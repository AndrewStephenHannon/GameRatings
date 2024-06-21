<?php

    //Connect to the SQL database
    $serverName = "****,port#";
    $connectionInfo = array("Database"=>"****", "Uid"=>"****", "PWD"=>"****");
    $connection = sqlsrv_connect( $serverName, $connectionInfo);

    //get game ID from html query
    $q = $_REQUEST["q"];

    //check to see if connection to database is made
    if($connection)
        echo "";
    else
        echo "No connection established<br>";

    //create SQL query to obtain game from html queried game ID
    $sqlquery = "SELECT TOP 1 * FROM GamePage WHERE GameID=" . $q . ";";
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

    echo json_encode($row); //return the game data in json format

    sqlsrv_close($connection); //close the connection to the database
?>