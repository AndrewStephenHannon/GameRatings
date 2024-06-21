<html>
    <head>
            <h1>Test PHP</h1>
    </head>

    <body>
        <?php 
            $serverName = "10.0.0.121,1433";
            $connectionInfo = array("Database"=>"GameRatings", "Uid"=>"user", "PWD"=>"admin123");
            $connection = sqlsrv_connect($serverName, $connectionInfo);

            if($connection)
                echo "CONNECTED!<br>";
            else
                die(print_r(sqlsrv_errors(), true));

            $sqlquery = "SELECT * FROM GamePage";
            $result = sqlsrv_query($connection, $sqlquery);

            $response = "";

            if($result)
            {
                while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
                    $response .= "Title: " . $row["Game Name"]. "<br>";
                }
            }
            else
            {
                die(print_r(sqlsrv_errors(), true));
            }

            echo $response;

            sqlsrv_close($connection);
            
            /*$serverName = "desktop-ecie849\sqlexpress";
            $connectionInfo = array( "Database"=>"GameRatings");
            $connection = sqlsrv_connect( $serverName, $connectionInfo);

            if($connection)
                echo "CONNECTED!<br>";
            else
                echo "No connection established<br>";

            $sqlquery = "SELECT * FROM GamePage";
            $result = sqlsrv_query($connection, $sqlquery);

            $response = "";

            if($result)
            {
                while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
                    $response .= "Title: " . $row["Game Name"]. "<br>";
                }
            }
            else
            {
                die(print_r(sqlsrv_errors(), true));
            }

            echo $response;

            sqlsrv_close($connection);*/
        ?>
    </body>

</html>