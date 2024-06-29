<?php

    //Connect to the SQL database locally
    $serverName = "desktop-ecie849\sqlexpress";
    $connectionInfo = array("Database"=>"GameRatings", "Uid"=>"", "PWD"=>"");
    $connection = sqlsrv_connect($serverName, $connectionInfo);


    //Connect to the SQL database
    //$serverName = "****,port#";
    //$connectionInfo = array("Database"=>"****", "Uid"=>"****", "PWD"=>"****");
    //$connection = sqlsrv_connect( $serverName, $connectionInfo);

    //check to see if connection to database is made
    if($connection)
        echo "";
    else
        echo "No connection established<br>";

    //create SQL query to obtain the 4 games that most recently released
    $sqlquery = "SELECT TOP 4 * FROM GamePage WHERE [Release Date NA] <= getdate() ORDER BY [Release Date NA] DESC";
    $result = sqlsrv_query($connection, $sqlquery);

    $response = array();

    //Check if query resulted in error
    if($result)
    {
        //Fetch each row of result and store in response array
        while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
            $response[] = $row;
    }
    //if SQL fails, print out the error
    else
    {
        die(print_r(sqlsrv_errors(), true));
    }

    echo json_encode($response); //return the result of the SQL query with html formatting

    sqlsrv_close($connection); //close the connection to the database
?>