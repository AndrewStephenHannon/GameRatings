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

    //create SQL query to obtain reviews data for specified game from the database
    $sqlquery = "SELECT * FROM ReviewTable WHERE GameID=" . $q;
    $result = sqlsrv_query($connection, $sqlquery); //execute the SQL query

    $response = "";

    //if the SQL query gets data, read through the data, get developer name and format it for the html page
    //if SQL fails, print out the error
    if($result)
    {
        //format table entries of reviews for the game's page
        While($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
            $response .= "<tr>";
            $response .= "<td>" . $row["Publication Name"] . "</td>";
            $response .= "<td><a href=\"" . $row["Review Link"] . "\" target=\"blank\">Review</a></td>";
            $response .= "<td>" . $row["Publication Date"] . "</td>";
            $response .= "<td style=\"text-align: right\">" . $row["Review Score"] . "</td>";
            $response .= "<td style=\"text-align: right\">" . $row["Review Percentage"] . "%</td>";
            $response .= "</tr>";
        }
    }
    else
    {
        die(print_r(sqlsrv_errors(), true));
    }

    echo $response; //return the result of the SQL query with html formatting

    sqlsrv_close($connection); //close the connection to the database
?>