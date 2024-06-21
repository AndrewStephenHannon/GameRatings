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

    $average = "N/A"; //instantiate the average variable
    
    //create SQL query for getting all reviews for the specified game
    $sqlGetReviews = "SELECT * FROM ReviewTable WHERE GameID=" . $q;
    $result = sqlsrv_query($connection, $sqlGetReviews); //execute SQL query

    $count = 0;
    $total = 0;

    if($result)
    {
        //if data is retrieved, go through data adding up the total value of review scores and counting the number of reviews
        While($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
            $total = $total + $row["Review Percentage"];
            $count = $count + 1;
        }

        //if count is greater than 0, then get average of review scores
        if($count > 0)
                $average = number_format((float)($total / $count), 2, '.', '');

        //if an average was obtained, update the game's average in the database to ensure the aggregated score is up to date
        if($average != "N/A") {
            $sqlUpdateAverage = "UPDATE GamePage SET CurrentScore = $average WHERE GameID=" . $q;
            sqlsrv_query($connection, $sqlUpdateAverage);
        }
    }
    //if SQL fails, print out the error
    else
    {
        die(print_r(sqlsrv_errors(), true));
    }

    echo $average . "%"; //return the result of the SQL query

    sqlsrv_close($connection); //close the connection to the database

?>