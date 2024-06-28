//my JavaScript functions for accessing backend database

//Call to MostPopular.php for getting the list of 10 most popular games and populating the data in the HTML code
function showMostPopular() {
    var xmlhttpMostPopular = new XMLHttpRequest();

    xmlhttpMostPopular.onreadystatechange = function() {
        if(this.readyState == 4 && this.status == 200) {
            const mostPopular = JSON.parse(this.responseText);

            //loop through each game in the Most Popular results
            for(var i=0; i<mostPopular.length; i++) {
                var index = i+1;
                var gameNameFields = document.getElementsByClassName("GameName" + index);   //get the current game's name tags to be filled
                for (var j=0; j<gameNameFields.length; j++) {
                    gameNameFields[j].innerHTML = mostPopular[i]['Game Name'];  //fill in html with current game's Name
                }

                document.getElementById("Game" + index + "BoxArt").innerHTML =  "<img src=\"" + mostPopular[i]['BoxArt'] + "\" class=\"img-fluid d-block m-auto\">"; //fill in html with current game's box art
                document.getElementById("Game" + index + "Score").innerHTML = mostPopular[i]['CurrentScore'].toFixed(2); //fill in html with current game's aggregated score
            }
        }
    }
    xmlhttpMostPopular.open("GET", "MostPopular.php", true);
    xmlhttpMostPopular.send();
}

//call to GetTotalGames.php to get total number of games currently in database and pass to getFeaturedGame function
function getTotalNumGames() {
    var xmlhttpTotalNumGames = new XMLHttpRequest();
    var numTotalGames = 0;
    
    xmlhttpTotalNumGames.onreadystatechange = function() {
        if(this.readyState == 4 && this.status == 200)  {
            numTotalGames = Number(this.responseText);

            getFeaturedGame(numTotalGames); //send result to getFeaturedGames to use in calculating current featured game of the day
        }
    }
    xmlhttpTotalNumGames.open("GET", "GetTotalGames.php", true);
    xmlhttpTotalNumGames.send();
}

//Call to GameData.php to get all the necessary game data for the featured game and populates the data in the Featured Game section
function getFeaturedGame(numTotalGames) {
    var xmlhttpFeaturedGame = new XMLHttpRequest();

    //Get featured gameID based on the current date
    const oneDay = 24 * 60 * 60 * 1000;     //get one day in milliseconds
    const firstDate = new Date(1900,1,1);   //get a starting date to start counting days from
    const secondDate = new Date();          //get current date
    //get the difference between dates in number of days and get the modular of result using total number of games in the database to obtain gameID
    var gameID = Math.floor((secondDate - firstDate) / oneDay) % numTotalGames;
        
    xmlhttpFeaturedGame.onreadystatechange = function() {
        if(this.readyState == 4 && this.status == 200) {
            const gameData = JSON.parse(this.responseText);

            document.getElementById("FeaturedGameBoxArt").innerHTML = "<img src=\"" + gameData['BoxArt'] + "\" class=\"img-fluid d-block m-auto\">";
            document.getElementById("FeaturedGameName").innerHTML = gameData['Game Name'];
            document.getElementById("FeaturedGameDescription").innerHTML = gameData['Description'];
            document.getElementById("FeaturedGameDeveloper").innerHTML = gameData['Developer Name'];
            document.getElementById("FeaturedGamePublisher").innerHTML = gameData['Publisher Name'];
            document.getElementById("FeaturedGameGenre").innerHTML = gameData['Genre'];

            //parse release date im desired format (YYYY-MM-DD)
            var parseReleaseDate = JSON.parse(JSON.stringify(gameData["Release Date NA"]));
            var releaseDate = JSON.stringify(parseReleaseDate["date"]).split(" ");
            releaseDate = releaseDate[0].split("\"");
            document.getElementById("FeaturedGameReleaseDate").innerHTML = releaseDate[1];

            //Get the platform data for the featured game
            getPlatformData(gameID);

            document.getElementById("FeaturedGameScore").innerHTML = gameData['CurrentScore'].toFixed(2);
        }               
    }
    xmlhttpFeaturedGame.open("GET", "GameData.php?q=" + gameID, true);
    xmlhttpFeaturedGame.send();
}

//Call to PlatformsData.php to obtain the platforms the game is on given the gameID and populates the platforms field of the featured fame section
function getPlatformData(gameID) {
    var xmlhttpPlatforms = new XMLHttpRequest();

    xmlhttpPlatforms.onreadystatechange = function() {
        if(this.readyState == 4 && this.status == 200) {
            document.getElementById("FeaturedGamePlatforms").innerHTML = this.responseText;
        }
    };
    xmlhttpPlatforms.open("GET", "PlatformsData.php?q=" + gameID, false);
    xmlhttpPlatforms.send();
}

//Call to MostRecentRelease.php to obtain list of the most recetnly released games in the database and populates the Most Recent Releases section of the homepage with formatted HTML and CSS
function mostRecentReleases() {
    var xmlhttpMostRecentReleases = new XMLHttpRequest();
    var mostRecentReleasesTable = "<h5 style=\"width: 250; background-color: #C0C0C0; color: #303030; margin-bottom: 2px\">&nbspMost Recent Releases</h5><table style=\"width: 100%;\"; rules=\"none\"><colgroup><col span=\"1\" style=\"width: 5%\"><col span=\"1\" style=\"width: 75%\"><col span=\"1\" style=\"width: 20%\"></colgroup>";
        
    xmlhttpMostRecentReleases.onreadystatechange = function() {
        if(this.readyState == 4 && this.status == 200) {
            mostRecentReleasesTable += this.responseText;
            mostRecentReleasesTable += "</table>";

            document.getElementById("MostRecentReleases").innerHTML = mostRecentReleasesTable;
        }               
    }
    xmlhttpMostRecentReleases.open("GET", "MostRecentReleases.php", true);
    xmlhttpMostRecentReleases.send();
}