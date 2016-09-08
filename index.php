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

        if ($rawJson === FALSE) {
            echo '<div class="error"><h2>Oh Dear</h2><p>I had some problems contacting youtube for video information and I am now sulking. I probably hit my limit for youtube API requests. You can <a href="https://www.youtube.com/">go to the real youtube</a> if you want to but be careful - it can be addictive.</p></div>';
            exit;
        }

        $decodedJson = json_decode($rawJson);
        $formattedResults = new stdClass();
        $count = 0;

        foreach($decodedJson->items as $item) {
            $formattedResults->$count = [
                'id' => $item->id,
                'title' => $item->snippet->title,
            ];
            $count++;
        }

        $initialVideoCount = 1;
        echo '<ol id="video-list" class="list">';
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
