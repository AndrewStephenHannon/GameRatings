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

    //Parse the necessary data from the results for each game returned and format for the html table
    if($result)
    {
        while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
            $response[] = $row;
        /*While($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
            $response .= "<div class=\"accordion-item\">";  //create accordion item
            $response .= "<h2 class=\"accordion-header\" id=\"heading-" . $game_index . "\">";

            //create accordion button and link to the appropriate game info
            if($game_index > 1)
                $response .= "<button class=\"accordion-button collapsed bg-dark text-white fw-bold fs-5 d-block text-center\" type=\"button\" data-bs-toggle=\"collapse\" data-bs-target=\"#Game-" . $game_index . "\">";
            else
                $response .= "<button class=\"accordion-button bg-dark text-white fw-bold fs-5 d-block text-center\" type=\"button\" data-bs-toggle=\"collapse\" data-bs-target=\"#Game-" . $game_index . "\">";
            $response .= $row["Game Name"];
            $response .= "</button>";
            $response .= "</h2>";

            //link game entry to parent accordion object to have collapse when another game is opened
            if($game_index > 1)
                $response .= "<div id=\"Game-" . $game_index . "\" class=\"accordion-collapse collapse\" data-bs-parent=\"#games\">";
            else
                $response .= "<div id=\"Game-" . $game_index . "\" class=\"accordion-collapse collapse show\" data-bs-parent=\"#games\">";

            //set link to game page if clicked anywhere in accordion body and style game info depending on the current average score the game has
            if($row["CurrentScore"] == null)
                $response .= "<div class=\"accordion-body bg-secondary \"><a class=\"text-decoration-none\" href=\"https://gameratingsapp.com/GamePage.html?id=" . $row["GameID"] .  "\">";
            else if($row["CurrentScore"] >= 75)
                $response .= "<div class=\"accordion-body bg-success \"><a class=\"text-decoration-none\" href=\"https://gameratingsapp.com/GamePage.html?id=" . $row["GameID"] .  "\">";
            else if($row["CurrentScore"] >= 60)
                $response .= "<div class=\"accordion-body bg-warning \"><a class=\"text-decoration-none\" href=\"https://gameratingsapp.com/GamePage.html?id=" . $row["GameID"] .  "\">";
            else
                $response .= "<div class=\"accordion-body bg-danger \"><a class=\"text-decoration-none\" href=\"https://gameratingsapp.com/GamePage.html?id=" . $row["GameID"] .  "\">";

            //remaining styling for game info
            $response .= "<div class=\"row p-1 justify-content-center\">";
            $response .= "<div class=\"col-lg-4\"><img class=\"img-fluid d-block m-auto\" src=\"" . $row["BoxArt"] . "\"></div><div class=\"col-lg-6 d-flex align-items-center text-white fw-bold\"><p class=\"text-center\">" . $row["Description"] . "</p></div><div class=\"col-2 d-flex align-items-center justify-content-center\"><p class=\"text-white fs-2 fw-bolder text-center\">" . $row["CurrentScore"] . "%</p></div></div>";
            $response .= "</div>";
            $response .= "</a></div>";
            $response .= "</div>";

            $game_index = $game_index + 1;
        }*/
    }
    //if SQL fails, print out the error
    else
    {
        die(print_r(sqlsrv_errors(), true));
    }

    echo json_encode($response); //return the result of the SQL query

    sqlsrv_close($connection); //close the connection to the database
?>