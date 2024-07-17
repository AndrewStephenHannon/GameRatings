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

    //create SQL query to obtain reviews data for specified game from the database
    $sqlquery = "SELECT * FROM Reviews WHERE GameID=" . $q;
    $result = sqlsrv_query($connection, $sqlquery); //execute the SQL query

    $response = array();

    //if the SQL query gets data, read through the data, get developer name and format it for the html page
    //if SQL fails, print out the error
    if($result)
    {
        //Fetch each row of result and store in response array
        while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
            $response[] = $row;
    }
    else
    {
        die(print_r(sqlsrv_errors(), true));
    }

    echo json_encode($response); //return the result of the SQL query

    sqlsrv_close($connection); //close the connection to the database
?>