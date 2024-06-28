<?php

    //Connect to the SQL database locally
    $serverName = "desktop-ecie849\sqlexpress";
    $connectionInfo = array("Database"=>"GameRatings", "Uid"=>"", "PWD"=>"");
    $connection = sqlsrv_connect($serverName, $connectionInfo);

    
    //Connect to the SQL database Web Server (credentials hidden)
    //$serverName = "****,port#";
    //$connectionInfo = array("Database"=>"****", "Uid"=>"****", "PWD"=>"****");
    //$connection = sqlsrv_connect( $serverName, $connectionInfo);

    //check to see if connection to database is made
    if($connection)
        echo "";
    else
        echo "No connection established<br>";

    //create SQL query to obtain information from the database that is needed for the page's request
    $sqlquery = "SELECT TOP 10 * FROM GamePage ORDER BY PageCount DESC";
    $result = sqlsrv_query($connection, $sqlquery);

    $response = array();

    //Get all rows of results and store in array
    if($result)
    {
        while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
            $response[] = $row;
    }
    //if SQL fails, print out the error
    else
    {
        die(print_r(sqlsrv_errors(), true));
    }

    echo json_encode($response); //return the result of the SQL query

    sqlsrv_close($connection); //close the connection to the database
?>