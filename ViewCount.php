<?php

    //Connect to the SQL database
    $serverName = "****,port#";
    $connectionInfo = array("Database"=>"****", "Uid"=>"****", "PWD"=>"****");
    $connection = sqlsrv_connect( $serverName, $connectionInfo);

    $q = $_REQUEST["q"];

    //check to see if connection to database is made
    if($connection)
        echo "";
    else
        echo "No connection established<br>";

    //create SQL query to update the view count for the specified game page by incremented by 1
    $sqlquery = "UPDATE GamePage SET PageCount = PageCount + 1 WHERE GameID=" . $q;
    sqlsrv_query($connection, $sqlquery); //execute the SQL query

    sqlsrv_close($connection); //close the connection to the database

?>