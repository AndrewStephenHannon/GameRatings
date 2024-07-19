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
    //$sqlquery = "SELECT TOP 1 * FROM Platforms;";
    $sqlquery = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'Platforms' AND COLUMN_NAME != 'GameID' ORDER BY COLUMN_NAME ASC";
    $result = sqlsrv_query($connection, $sqlquery);

    $response = "<select class=\"form-select\" id=\"Platform\" name=\"Platform\">";
    $response .= "<option value=\"\">- Select Platform -</option>";

    //if the SQL query gets data, read through the data getting all different platforms in the database and format it for the html page
    //if SQL fails, print out the error
    if($result)
    {
        while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
            $response .= "<option value='" . $row["COLUMN_NAME"] . "'>" . $row["COLUMN_NAME"] . "</option>";
        }
    }
    else
    {
        die(print_r(sqlsrv_errors(), true));
    }

    $response .= "</select>";

    echo $response; //return the result of the SQL query with html formatting

    sqlsrv_close($connection); //close the connection to the database
?>