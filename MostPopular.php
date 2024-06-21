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

    $response = "";
    $count = 1;

    //Parse the necessary data from the results for each game returned and format for the html table
    if($result)
    {
        While($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
            //change every second entry's background colour to make table look more readable
            if($count%2==0)
                $response .= "<tr style=\"background-color:#E0E0E0\">";
            else
                $response .= "<tr style=\"padding-right: 5px;\">";
            $response .= "<td>" . $count . ". </td>";
            $response .= "<td><img src=\"" . $row["BoxArt"] . "\" width=\"auto\" height=\"50\"></td>";
            $response .= "<td><a href=\"https://gameratingsapp.com/GamePage.html?id=" . $row["GameID"] .  "\">" . $row["Game Name"] . "</a></td>";
            if($row["CurrentScore"] > 0)
                $response .= "<td style=\"text-align: left\">" . number_format($row["CurrentScore"], 2) . "%</td>";
            else
                $response .= "<td style=\"text-align: right\">N/A</td>";
            $response .= "</tr>";

            $count = $count + 1;
        }
    }
    //if SQL fails, print out the error
    else
    {
        die(print_r(sqlsrv_errors(), true));
    }

    echo $response; //return the result of the SQL query with html formatting

    sqlsrv_close($connection); //close the connection to the database
?>