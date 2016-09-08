<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>10k tube</title>
    <link rel="stylesheet" href="css/10ktube.css">
</head>

<body>
    <header>
        <h1><img class="logo" src="images/10ktube-logo.svg" alt="10k tube logo"></h1>
        <h2>The current most popular videos on youtube</h2>
    </header>

    <?php

        $httpHost = $_SERVER['HTTP_HOST'];
        $httpRequest = $_SERVER['REQUEST_URI'];
        $request = "https://www.googleapis.com/youtube/v3/videos/?chart=mostPopular&maxResults=50&part=id,snippet&key=AIzaSyAqC1qAEVYz-iTZbS1uPGLcI2Vrk3lHX2k";
        $requestOptions = array(
            'http'=>array(
                'method'=>"GET",
                'header'=>"Referer: http://" . $httpHost . $httpRequest
            )
        );
        $requestStream = stream_context_create($requestOptions);
        $rawJson = @file_get_contents($request, false, $requestStream);
        $formattedResults = new stdClass();
        $count = 0;

        // handle the possibility that the youtube api might reach it's limits or go down - fall back to local data
        if ($rawJson === FALSE) {
            $rawJson = @file_get_contents("data/php-generated-data.json");
            $decodedJson = json_decode($rawJson);
            foreach($decodedJson as $item) {
                $formattedResults->$count = [
                    'id' => $item->id,
                    'title' => $item->title,
                ];
                $count++;
            }
            echo '<div class="error"><p>I had some problems contacting youtube for current video information and I am forced to use the last set of videos that I can remember. Sorry about that!</div>';
        } else {
            $decodedJson = json_decode($rawJson);
            foreach($decodedJson->items as $item) {
                $formattedResults->$count = [
                    'id' => $item->id,
                    'title' => $item->snippet->title,
                ];
                $count++;
            }
        }

        // generates a local backup in case youtube api is down or limit is reached
        // file_put_contents("data/php-generated-data.json", json_encode($formattedResults));

        echo '<ol id="video-list" class="list">';
        $initialVideoCount = 1;
        foreach($formattedResults as $video) {
            echo '<li class="list__vid"><a href="https://www.youtube.com/watch?v=' . $video['id'] . '" data-id="' . $video['id'] . '" onclick="return openModal(\'' . $video['id'] . '\')"><h3>' . $video['title'] . '</h3></a></li>';
            if ($initialVideoCount == 10) { break; }
            $initialVideoCount++;
        }
        echo '</ol>';
    ?>

    </div>

    <div class="player-wrap" id="player-container">
        <div class="player-wrap__toolbar">
            <img class="logo logo--small" src="images/10ktube-logo.svg" alt="10k tube logo">
            <button class="player-wrap__close" id="focused-element" data-close>&lsaquo; back to video list</button>
        </div>
        <div id="player"></div>
    </div>

    <footer id="footer">
        <h3>That's all for now folks!</h3>
        <p>You can <a href="https://www.youtube.com/">watch more videos on youtube</a> if you want to but be careful - it's addictive.</p>
        <p>This site was built for <a href="https://a-k-apart.com/">10k apart</a> - a compelling web experience that can be delivered in 10kB and works without JavaScript. Built by <a href="http://www.lendmeyourear.net/">Lee Jordan</a>.</p>
    </footer>

    <script src="js/10ktube.js"></script>
</body>
</html>
