<?php

    //Connect to the SQL database locally
    $serverName = "desktop-ecie849\sqlexpress";
    $connectionInfo = array("Database"=>"GameRatings", "Uid"=>"", "PWD"=>"");
    $connection = sqlsrv_connect($serverName, $connectionInfo);

    
    //Connect to the SQL database Web Server (credentials hidden)
    //$serverName = "****,port#";
    //$connectionInfo = array("Database"=>"****", "Uid"=>"****", "PWD"=>"****");
    //$connection = sqlsrv_connect( $serverName, $connectionInfo);

    //get platform name from html query
    $q = $_REQUEST["q"];

    if($connection)
        echo "";
    else
        echo "No connection established<br>";

    //create the SQL query when the platform variable is empty. Will get all games regardless of platform ordered by highest score
    if(empty($q))
        $sqlquery = "SELECT * FROM Games ORDER BY CurrentScore DESC";
    //create the SQL query to get all games on specified platform ordered by highest score
    else {
        $sqlquery = "SELECT Games.GameID, Games.[GameName], Games.CurrentScore
        FROM Games INNER JOIN Platforms ON Games.GameID = Platforms.GameID WHERE [" . $q .  "]= 1
        ORDER BY Games.CurrentScore Desc";
    }
    $result = sqlsrv_query($connection, $sqlquery); //execute the query

    $response = "";

    //if the SQL query gets data, read through the data to get the game IDs, names, and scores for all found games and format it for the html page
    //if SQL fails, print out the error
    if($result)
    {
        $num_rows = sqlsrv_num_rows($result);
        $data_array = array_fill(0, $num_rows, "");
        $index = 0;

        while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
            $response .= "<a href=\"https://gameratingsapp.com/GamePage.html?id=" . $row["GameID"] .  "\">" . $row["GameName"] . "</a> " . $row["CurrentScore"] . "<br>";
        }
    }
    else
    {
        die(print_r(sqlsrv_errors(), true));
    }

    echo $response; //return the result of the SQL query with html formatting

    sqlsrv_close($connection); //close the connection to the database
?>