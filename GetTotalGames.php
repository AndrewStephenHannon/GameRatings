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
    //get number of games in database
    $sql_count_query = "SELECT COUNT(*) AS 'count' FROM Games";
    $count_result = sqlsrv_query($connection, $sql_count_query);
    if($count_result)
    {
        $num_games =  sqlsrv_fetch_array($count_result, SQLSRV_FETCH_ASSOC)['count'];
    }
    //if SQL fails, print out the error
    else
    {
        die(print_r(sqlsrv_errors(), true));
    }

    echo $num_games; //return the total number of games in the Games table of the database

    sqlsrv_close($connection); //close the connection to the database
?>