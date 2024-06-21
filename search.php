<html>
    <body>
        <?php 

            //Connect to the SQL database locally
            $serverName = "desktop-ecie849\sqlexpress";
            $connectionInfo = array("Database"=>"GameRatings", "Uid"=>"", "PWD"=>"");
            $connection = sqlsrv_connect($serverName, $connectionInfo);

            
            //Connect to the SQL database Web Server (credentials hidden)
            //$serverName = "****,port#";
            //$connectionInfo = array("Database"=>"****", "Uid"=>"****", "PWD"=>"****");
            //$connection = sqlsrv_connect( $serverName, $connectionInfo);

            //get the search filters from the html page and store them in variables
            $game = $_REQUEST["Game"];
            $dev = $_REQUEST["Developer"];
            $pub = $_REQUEST["Publisher"];
            $plat = $_REQUEST["Platform"];
            $year = $_REQUEST["Year"];

            //check to see if connection to database is made
            if($connection)
                echo "";
            else
                echo "No connection established<br>";

            //if the platform wasn't selected on the search page
            if(empty($plat))
            {
                //start SQL query to get any and all games that match the search filters
                $sqlquery = "SELECT * FROM GamePage 
                        INNER JOIN DeveloperPage ON GamePage.DevID = DeveloperPage.DevID 
                        INNER JOIN PublisherPage ON GamePage.PubID = PublisherPage.PubID 
                        WHERE GamePage.[Game Name] LIKE '%" . $game . "%'" . 
                        " AND DeveloperPage.[Developer Name] LIKE '%" . $dev . "%'" .
                        " AND PublisherPage.[Publisher Name] LIKE '%" . $pub . "%'";
                
                //if the year is specified in the search query, check if it matches the year in the games' release date field
                if(!empty($year))
                    $sqlquery .= " AND YEAR(GamePage.[Release Date NA]) = " . $year;

                //close off the SQL query by ordering the results by highest review average
                $sqlquery .= "ORDER BY GamePage.CurrentScore Desc";
            }
            //if a platform was specified
            else{
                /*
                start SQL query to get any and all games that match the search filters (note: difference in this SQL query 
                from above to make sure the value of the matched platform in the database is 1 as this indicates
                "true" or that the game is on this platform (0 would be mean it is not on this platform))
                */
                $sqlquery = "SELECT * FROM GamePage 
                        INNER JOIN DeveloperPage ON GamePage.DevID = DeveloperPage.DevID 
                        INNER JOIN PublisherPage ON GamePage.PubID = PublisherPage.PubID 
                        INNER JOIN PlatformsTable ON GamePage.GameID = PlatformsTable.GameID
                        WHERE GamePage.[Game Name] LIKE '%" . $game . "%'" . 
                        " AND DeveloperPage.[Developer Name] LIKE '%" . $dev . "%'" .
                        " AND PublisherPage.[Publisher Name] LIKE '%" . $pub . "%'" . 
                        " AND [" . $plat . "] = 1";

                //if the year is specified in the search query, check if it matches the year in the games' release date field
                if(!empty($year))
                    $sqlquery .= " AND YEAR(GamePage.[Release Date NA]) = " . $year;
    
                //close off the SQL query by ordering the results by highest review average
                $sqlquery .= "ORDER BY GamePage.CurrentScore Desc";
            }
            
            //execute the SQL query
            $result = sqlsrv_query($connection, $sqlquery);

            //format the results in a table
            $response = "<table style=\"width: 100%\"; rules=\"none\"><colgroup><col span=\"1\" style=\"width: 10%\"><col span=\"1\" style=\"width: 80%\"><col span=\"1\" style=\"width: 10%\"></colgroup>";
            $count = 1;

            //if the results return 1 or more database entries, parse the necessary data from the results for each game returned and format for the html table
            if($result)
            {
                while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
                    //change every second entry's background colour to make table look more readable
                    if($count%2==0)
                        $response .= "<tr style=\"background-color:#E0E0E0\">";
                    else
                        $response .= "<tr style=\"padding-right: 5px;\">";
                        $response .= "<td><img src=\"" . $row["BoxArt"] . "\" width=\"auto\" height=\"100\"></td>";
                        $response .= "<td><font size=\"+1.5\"><b><a href=\"https://gameratingsapp.com/GamePage.html?id=" . $row["GameID"] .  "\">" . $row["Game Name"] . "</a></b></font></td>";
                    if($row["CurrentScore"] > 0)
                        $response .= "<td style=\"text-align: left\"><font size=\"+2\"><b>" . number_format($row["CurrentScore"], 2) . "%</b></font></td>";
                    else
                        $response .= "<td style=\"text-align: left\"><font size=\"+2\"><b>N/A</b></font></td>";
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
    </body>
</html>

