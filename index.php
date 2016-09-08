<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>10k tube the current most popular videos on youtube in under 10k</title>
    <link rel="stylesheet" href="css/10ktube.min.css">
</head>

<body>
    <header>
        <h1><img class="logo" src="images/10ktube-logo.svg" alt="10k tube"></h1>
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

        // generates a local backup in case youtube api is down or limit is reached
        // file_put_contents("data/php-generated-data.json", json_encode((object)$rawJson));

        // handle the possibility that the youtube api might reach it's limits or go down - fall back to local data
        if ($rawJson === FALSE) {
            $rawJson = @file_get_contents("data/php-generated-data.json");
            echo '<div class="error"><p>I had some problems contacting youtube for current video information and I am forced to use the last set of videos that I can remember. Sorry about that!</div>';
        }

        $formattedResultsForHtml = new stdClass();
        $formattedResultsForJson = new stdClass();
        $initialVideoMax = 10;

        $decodedJson = json_decode($rawJson);
        $decodedJsonItems = isset($decodedJson->scalar) ? json_decode($decodedJson->scalar) : $decodedJson;

        $count = 0;
        foreach($decodedJsonItems->items as $item) {
            $count++;
            $formattedResultsForHtml->$count = [
                'id' => $item->id,
                'title' => $item->snippet->title,
            ];
            if ($count == $initialVideoMax) {
                break;
            }
        }
        $count = -1;
        foreach($decodedJsonItems->items as $item) {
            $count++;
            if ($count < $initialVideoMax) { continue; }
            $index = $count - $initialVideoMax;
            $formattedResultsForJson->$index = [
                'id' => $item->id,
                'title' => $item->snippet->title,
            ];
        }

        // output the initial 10 video thumbnails to page
        $initialVideoCount = 0;
        echo '<ol id="video-list" class="list">';
        foreach($formattedResultsForHtml as &$video) {
            echo '<li class="list__vid"><a href="https://www.youtube.com/watch?v=' . $video['id'] . '" data-id="' . $video['id'] . '" onclick="return openModal(\'' . $video['id'] . '\')"><h3>' . $video['title'] . '</h3></a></li>';
            if ($initialVideoCount == $initialVideoMax) { break; }
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

    <?php
        // output the json data blob
        echo '<script>var videoData = ' . json_encode($formattedResultsForJson) . ';</script>'
    ?>
    <script src="js/10ktube.min.js"></script>
</body>
</html>
