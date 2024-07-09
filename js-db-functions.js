//JavaScript functions for accessing backend database

//Call to MostPopular.php for getting the list of 10 most popular games and populating the data in the HTML code
function showMostPopular() {
    var xmlhttpMostPopular = new XMLHttpRequest();

    xmlhttpMostPopular.onreadystatechange = function() {
        if(this.readyState == 4 && this.status == 200) {
            const mostPopular = JSON.parse(this.responseText);

            //loop through each game in the Most Popular results
            for(var i=0; i<mostPopular.length; i++) {
                var index = i+1;

                /******** Game Backgrounds ********/
                document.getElementById("GameBG" + index).style.background = "url('" + mostPopular[i]['BannerImage'] + "') center no-repeat";
                document.getElementById("GameBG" + index).style.backgroundSize =  "cover";
                /**********************************/

                /******** Game Titles ********/
                document.getElementById("GameName" + index).innerHTML = mostPopular[i]['GameName'];  //set vertical, closed accordion Game Name to name of current game in results
                //Set body of expanded accordion Game Name to current game from results
                var gameNameLink1 = "<a href=\"https://gameratingsapp.com/GamePage.html?id=" + mostPopular[i]['GameID'] + "\" class=\"ignore-link-format\"><h4 class=\"fw-bold textShadow\">" + mostPopular[i]['GameName'] + "</h4></a>";
                document.getElementById("GameNameLink" + index).innerHTML = gameNameLink1;
                /*****************************/

                /******** Description and link ********/
                 //get game description and link to game page formatted as desired in html
                var descriptionLink = "<a href=\"https://gameratingsapp.com/GamePage.html?id=" + mostPopular[i]['GameID'] + "\" class=\"ignore-link-format\"><p class=\"textShadow\">";
                descriptionLink += mostPopular[i]['Description'] + "</p></a>";
                document.getElementById("GameDescription" + index).innerHTML = descriptionLink;   //fill in html with current game's description
                /**************************************/

                /******** Boxart, Score and link ********/
                var boxScoreLink = "<a href=\"https://gameratingsapp.com/GamePage.html?id=" + mostPopular[i]['GameID'] + "\" class=\"ignore-link-format\">"; //set the box art and score to have link to current game's game page
                boxScoreLink += "<img src=\"" + mostPopular[i]['BoxArt'] + "\" class=\"img-fluid\">";  //set current game's box art
                boxScoreLink += "<div class=\"fitted-bar\" "; //set background bar for score

                //depending on the games current aggregated score, determine the background colour accordingly
                if(mostPopular[i]['CurrentScore'] >= 75.0)
                    boxScoreLink += "style=\"background: rgba(0, 190, 0, 1);\">";
                else if(mostPopular[i]['CurrentScore'] >= 60.0)
                    boxScoreLink += "style=\"background: rgba(255, 255, 0, 1);\">";
                else if (mostPopular[i]['CurrentScore'] > 0)
                    boxScoreLink += "style=\"background: rgba(255, 0, 0, 1);\">"
                else
                    boxScoreLink += "style=\"background: rgba(100, 100, 100, 1);\">";

                //style text of Current Score to be at bottom of parenting div and centered with a text shadow
                if(mostPopular[i]['CurrentScore'] > 0)
                    boxScoreLink += "<span class=\"text-center fw-bold textShadow bottom-centered\" style=\"font-size: 1.5em;\">" + mostPopular[i]['CurrentScore'].toFixed(2) + "%</span>";
                else
                    boxScoreLink += "<span class=\"text-center fw-bold textShadow bottom-centered\" style=\"font-size: 1.5em;\">N/A</span>";
                boxScoreLink += "</div></a>"
                document.getElementById("boxScoreLink" + index).innerHTML = boxScoreLink; //inject html with current game's page link, box art, and aggregated score to two decimal places with html formatting
                /****************************************/
            }
        }
    }
    xmlhttpMostPopular.open("GET", "MostPopular.php", true);
    xmlhttpMostPopular.send();
}

//Call to GameData.php to get all the necessary game data for the featured game and populates the data in the Featured Game section
async function getFeaturedGame() {
    var xmlhttpFeaturedGame = new XMLHttpRequest();

    //Get featured gameID based on the current date
    const oneDay = 24 * 60 * 60 * 1000;     //get one day in milliseconds
    const firstDate = new Date(1900,1,1);   //get a starting date to start counting days from
    const secondDate = new Date();          //get current date
    //get the difference between dates in number of days and get the modular of result using total number of games in the database to obtain gameID
    var gameID = Math.floor((secondDate - firstDate) / oneDay) % await getTotalNumGames();
        
    xmlhttpFeaturedGame.onreadystatechange = async function() {
        if(this.readyState == 4 && this.status == 200) {
            const gameData = JSON.parse(this.responseText);

            //set the box art for the Featured Game with link to the game's page
            document.getElementById("FeaturedGameBoxArt").innerHTML = "<a href=\"https://gameratingsapp.com/GamePage.html?id=" + gameData['GameID'] + "\"><img src=\"" + gameData['BoxArt'] + "\" class=\"img-fluid d-block m-auto\"></a>";
            //set the Game Title, Description, Developer, Publisher, and genre
            document.getElementById("FeaturedGameName").innerHTML = "<a href=\"https://gameratingsapp.com/GamePage.html?id=" + gameData['GameID'] + "\">" + gameData['GameName'] + "</a>";
            document.getElementById("FeaturedGameDescription").innerHTML = gameData['Description'];
            document.getElementById("FeaturedGameDeveloper").innerHTML = gameData['DeveloperName'];
            document.getElementById("FeaturedGamePublisher").innerHTML = gameData['PublisherName'];
            document.getElementById("FeaturedGameGenre").innerHTML = gameData['Genre'];

            //parse release date in desired format (YYYY-MM-DD)
            var parseReleaseDate = JSON.parse(JSON.stringify(gameData["ReleaseDate"]));
            var releaseDate = JSON.stringify(parseReleaseDate["date"]).split(" ");
            releaseDate = releaseDate[0].split("\"");
            document.getElementById("FeaturedGameReleaseDate").innerHTML = releaseDate[1];

            //Get the platform data for the featured game
            document.getElementById("FeaturedGamePlatforms").innerHTML = await getPlatformData(gameID);

            //Get current Score of game to two decimal places
            document.getElementById("FeaturedGameScore").innerHTML = gameData['CurrentScore'].toFixed(2);
        }               
    }
    xmlhttpFeaturedGame.open("GET", "GameData.php?q=" + gameID, true);
    xmlhttpFeaturedGame.send();
}

//call to GetTotalGames.php to get total number of games currently in database
function getTotalNumGames() {
    return new Promise((resolve) => {
        var xmlhttpTotalNumGames = new XMLHttpRequest();
        var numTotalGames = 0;
        
        xmlhttpTotalNumGames.onreadystatechange = function() {
            if(this.readyState == 4 && this.status == 200)  {
                numTotalGames = Number(this.responseText);

                resolve(numTotalGames); //return result
            }
        }
        xmlhttpTotalNumGames.open("GET", "GetTotalGames.php", true);
        xmlhttpTotalNumGames.send();
    });
}

//Call to PlatformsData.php to obtain the platforms the game is on given the gameID and populates the platforms field of the featured fame section
function getPlatformData(gameID) {
    return new Promise((resolve) => {
        var xmlhttpPlatforms = new XMLHttpRequest();

        xmlhttpPlatforms.onreadystatechange = function() {
            if(this.readyState == 4 && this.status == 200) {
                resolve(this.responseText);
            }
        };
        xmlhttpPlatforms.open("GET", "PlatformsData.php?q=" + gameID, true);
        xmlhttpPlatforms.send();
    });
}

//Call to MostRecentRelease.php to obtain list of the most recetnly released games in the database and populates the Most Recent Releases section of the homepage with formatted HTML and CSS
function mostRecentReleases() {
    var xmlhttpMostRecentReleases = new XMLHttpRequest();
    
    xmlhttpMostRecentReleases.onreadystatechange = function() {
        if(this.readyState == 4 && this.status == 200) {
            const mostRecentReleases = JSON.parse(this.responseText);

            
            //loop through each game in the Most Recent Releases results
            for(var i=0; i<mostRecentReleases.length; i++) {
                var index = i+1;

                //parse release date in desired format (YYYY-MM-DD)
                var parseReleaseDate = JSON.parse(JSON.stringify(mostRecentReleases[i]["ReleaseDate"]));
                var releaseDate = JSON.stringify(parseReleaseDate["date"]).split(" ")[0];
                releaseDate = releaseDate.substring(1).split("-");
                var months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                document.getElementById("RecentReleaseDate" + index).innerHTML = months[parseInt(releaseDate[1]-1)] + " " + releaseDate[2] + ", " + releaseDate[0];

                document.getElementById("RecentReleaseBoxArt" + index).innerHTML = "<a href=\"https://gameratingsapp.com/GamePage.html?id=" + mostRecentReleases[i]['GameID'] + "\"><img  class=\"img-fluid rounded\" src=\"" + mostRecentReleases[i]['BoxArt'] + "\" class=\"img-fluid d-block m-auto\"></a>";
            }
        }               
    }
    xmlhttpMostRecentReleases.open("GET", "MostRecentReleases.php", true);
    xmlhttpMostRecentReleases.send();
}

function populateDevDropdown() {
    var xmlhttpDevDropdown = new XMLHttpRequest();

    xmlhttpDevDropdown.onreadystatechange = function() {
        if(this.readyState == 4 && this.status == 200) {
            document.getElementById("developers").innerHTML = this.responseText;
        }
    };
    xmlhttpDevDropdown.open("GET", "AllDevs.php", true);
    xmlhttpDevDropdown.send();
}

function populatePubDropdown() {
    var xmlhttpPubDropdown = new XMLHttpRequest();

    xmlhttpPubDropdown.onreadystatechange = function() {
        if(this.readyState == 4 && this.status == 200) {
            document.getElementById("publishers").innerHTML = this.responseText;
        }
    };
    xmlhttpPubDropdown.open("GET", "AllPubs.php", true);
    xmlhttpPubDropdown.send();
}

function populatePlatforms() {
    var xmlhttpPlatformDropdown = new XMLHttpRequest();

    xmlhttpPlatformDropdown.onreadystatechange = function() {
        if(this.readyState == 4 && this.status == 200) {
            document.getElementById("platforms").innerHTML = this.responseText;
        }
    };
    xmlhttpPlatformDropdown.open("GET", "AllPlatforms.php", true);
    xmlhttpPlatformDropdown.send();
}

function populateYears() {
    var year = new Date().getFullYear();
    var html = "<select class=\"form-select\" id=\"Year\" name=\"Year\">";
        html += "<option value=\"\">- Release Year -</option>"

    while(year >= 1950) {
        html += "<option value=" + year + ">" + year + "</option>";
        year = year - 1;
    }

    html += "</select>";

    document.getElementById("year").innerHTML = html;
}

function showResults() {
    var xmlhttpGameResults = new XMLHttpRequest();
    var strings = window.location.search.split("=");

    var game = new window.URL(location.href).searchParams.get('Game');
    var dev = new window.URL(location.href).searchParams.get('Developer');
    var pub = new window.URL(location.href).searchParams.get('Publisher');
    var platform = new window.URL(location.href).searchParams.get('Platform');
    var year = new window.URL(location.href).searchParams.get('Year');

    xmlhttpGameResults.onreadystatechange = async function() {
        if(this.readyState == 4 && this.status == 200) {
            const searchResults = JSON.parse(this.responseText);

            var resultsContents = ""

            for(var i=0; i<searchResults.length; i++) {
                var odd = i%2;  //get if the gameID is even or odd to determine the styling

                //store html and css/bootstrap styling code for displaying search results depending on the gameID (gives table like listing to breakup results and make more readable)
                if(!odd)
                    resultsContents += "<div style=\"background-color: #343a40;\">";

                resultsContents += 
                        "<div class=\"container-md pt-4 pb-3\">" +
                            "<div class=\"row justify-content-center\">";

                if(!odd)
                    resultsContents += "<div class=\"col-lg-3 mb-2\">";
                else
                    resultsContents += "<div class=\"col-lg-3 mb-2 d-block d-lg-none\">";

                resultsContents +=
                                    "<a href=\"https://gameratingsapp.com/GamePage.html?id=" + searchResults[i]['GameID'] + "\"><img src=\"" + searchResults[i]['BoxArt'] + "\" class=\"img-fluid d-block m-auto\"></a>" +
                                "</div>";

                if(!odd)
                    resultsContents += "<div class=\"col-lg-9 px-4 px-lg-2\">";
                else
                    resultsContents += "<div class=\"col-lg-9 px-4 pe-lg-2\">"

                resultsContents +=
                                    "<div class=\"row\">" +
                                        "<h2><a href=\"https://gameratingsapp.com/GamePage.html?id=" + searchResults[i]['GameID'] + "\">" + searchResults[i]['GameName'] + "</a></h2>" +
                                    "</div>" +
                                    "<div class=\"row\">" +
                                        "<p>" + searchResults[i]['Description'] + "</p>" +
                                    "</div>" +
                                    "<div class=\"row justify-content-between\">" +
                                        "<div class=\"col-6 Justify-content-start\">" +
                                            "<tag class=\"fw-bold\">Developer: </tag>" + searchResults[i]['DeveloperName'] + "<br>" +
                                            "<tag class=\"fw-bold\">Publisher: </tag>" + searchResults[i]['PublisherName'] + "<br>" +
                                            "<tag class=\"fw-bold\">Genre: </tag>" + searchResults[i]['Genre'] + "<br>";

                //call function for game's platforms and wait for response
                var platforms = await getPlatformData(searchResults[i]['GameID']);
                resultsContents += "<tag class=\"fw-bold\">Platform: </tag>" + platforms + "<br>";


                //parse release date in desired format (YYYY-MM-DD)
                var parseReleaseDate = JSON.parse(JSON.stringify(searchResults[i]["ReleaseDate"]));
                var releaseDate = JSON.stringify(parseReleaseDate["date"]).split(" ");
                releaseDate = releaseDate[0].split("\"");

                //if game's score is 0, means no reviews, set it to N/A
                var gameScore = "";
                if(searchResults[i]['CurrentScore'] == 0.0)
                    gameScore = "N/A";
                else
                    gameScore = searchResults[i]['CurrentScore'].toFixed(2) + "%";

                resultsContents += 
                                            "<tag class=\"fw-bold\">Release Date: </tag>" + releaseDate[1] +
                                        "</div>" +
                                        "<div class=\"col-6 d-flex justify-content-center align-items-center\">" +
                                            "<h1 class=\"fw-bold\" style=\"transform: scale(2.0,2.0)\">" + gameScore + "</h1>" +
                                        "</div>" +
                                    "</div>" +
                                "</div>";

                if(!odd) {
                    resultsContents +=
                            "</div>";
                } else {
                    resultsContents += 
                            "<div class=\"col-lg-3 mb-2 d-none d-lg-block\">" +
                                "<a href=\"https://gameratingsapp.com/GamePage.html?id=" + searchResults[i]['GameID'] + "\"><img src=\"" + searchResults[i]['BoxArt'] + "\" class=\"img-fluid d-block m-auto\"></a>" +
                            "</div>"; 
                }
                resultsContents +=         
                        "</div>" +
                    "</div>";
            }

            document.getElementById("results").innerHTML = resultsContents;
        }
    };
    xmlhttpGameResults.open("GET", "search.php?Game=" + game + "&Developer=" + dev + "&Publisher=" 
                            + pub + "&Platform=" + platform + "&Year=" + year, true);
    xmlhttpGameResults.send();
}