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

    //create SQL query to obtain game's platform information
    $sqlquery = "SELECT TOP 1 * FROM Platforms WHERE GameID=" . $q . ";";
    $result = sqlsrv_query($connection, $sqlquery); //execute SQL query

    $response = "";

    //if the SQL query gets data, read through the data to get the list of platforms for the game and format it for the html page
    //if SQL fails, print out the error
    if($result)
    {
        $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);

        foreach($row as $key => $value)
        {
            if($value && $key != "GameID")
                $response .= $key . ", ";
        }
    }
    else
    {
        die(print_r(sqlsrv_errors(), true));
    }

    echo substr($response, 0, -2); //return the result of the SQL query removing the last space and comma created when building the string of platfortms

    sqlsrv_close($connection); //close the connection to the database
?>