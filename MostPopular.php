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
    $game_index = 1;

    //Parse the necessary data from the results for each game returned and format for the html table
    if($result)
    {
        While($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
            $response .= "<div class=\"accordion-item\">";
            $response .= "<h2 class=\"accordion-header\" id=\"heading-" . $game_index . "\">";
            if($game_index > 1)
                $response .= "<button class=\"accordion-button collapsed\" type=\"button\" data-bs-toggle=\"collapse\" data-bs-target=\"#Game-" . $game_index . "\">";
            else
                $response .= "<button class=\"accordion-button\" type=\"button\" data-bs-toggle=\"collapse\" data-bs-target=\"#Game-" . $game_index . "\">";
            $response .= $row["Game Name"];
            $response .= "</button>";
            $response .= "</h2>";
            if($game_index > 1)
                $response .= "<div id=\"Game-" . $game_index . "\" class=\"accordion-collapse collapse\" data-bs-parent=\"#games\">";
            else
                $response .= "<div id=\"Game-" . $game_index . "\" class=\"accordion-collapse collapse show\" data-bs-parent=\"#games\">";
            $response .= "<div class=\"accordion-body\">";
            if($row["CurrentScore"] == null)
            $response .= "<div class=\"row p-1 justify-content-around bg-secondary rounded shadow\"><div class=\"col-lg-4 col-6\"><img class=\"img-fluid\" src=\"" . $row["BoxArt"] . "\"></div><div class=\"col-lg-6 col-4 d-flex align-items-center justify-content-center text-white fw-bold\"><p class=\"text-center\">" . $row["Description"] . "</p></div><div class=\"col-2 d-flex align-items-center justify-content-center\"><p class=\"text-white fs-2 fw-bolder text-center \">" . $row["CurrentScore"] . "%</p></div></div>";
            else if($row["CurrentScore"] >= 75)
                $response .= "<div class=\"row p-1 justify-content-around bg-success rounded shadow\"><div class=\"col-lg-4 col-6\"><img class=\"img-fluid\" src=\"" . $row["BoxArt"] . "\"></div><div class=\"col-lg-6 col-4 d-flex align-items-center justify-content-center text-white fw-bold\"><p class=\"text-center\">" . $row["Description"] . "</p></div><div class=\"col-2 d-flex align-items-center justify-content-center\"><p class=\"text-white fs-2 fw-bolder text-center \">" . $row["CurrentScore"] . "%</p></div></div>";
            else if($row["CurrentScore"] >= 60)
                $response .= "<div class=\"row p-1 justify-content-around bg-warning rounded shadow\"><div class=\"col-lg-4 col-6\"><img class=\"img-fluid\" src=\"" . $row["BoxArt"] . "\"></div><div class=\"col-lg-6 col-4 d-flex align-items-center justify-content-center text-white fw-bold\"><p class=\"text-center\">" . $row["Description"] . "</p></div><div class=\"col-2 d-flex align-items-center justify-content-center\"><p class=\"text-white fs-2 fw-bolder text-center \">" . $row["CurrentScore"] . "%</p></div></div>";
            else
                $response .= "<div class=\"row p-1 justify-content-around bg-danger rounded shadow\"><div class=\"col-lg-4 col-6\"><img class=\"img-fluid\" src=\"" . $row["BoxArt"] . "\"></div><div class=\"col-lg-6 col-4 d-flex align-items-center justify-content-center text-white fw-bold\"><p class=\"text-center\">" . $row["Description"] . "</p></div><div class=\"col-2 d-flex align-items-center justify-content-center\"><p class=\"text-white fs-2 fw-bolder text-center \">" . $row["CurrentScore"] . "%</p></div></div>";
            $response .= "</div>";
            $response .= "</div>";
            $response .= "</div>";

            $game_index = $game_index + 1;
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