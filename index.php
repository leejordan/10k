<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>10k tube</title>
    <link rel="stylesheet" href="css/10ktube.css">
</head>

<body>
    <h1>10k tube</h1>
    <h2>The most popular youtube videos of all time</h2>
    <ul class="list">

        <?
            $string = file_get_contents("data/mostpopular.json");
            $json = json_decode($string, true);

            foreach (array_slice($json['items'], 0, 20) as $video) {
                echo '<li class="list__vid" data-img="' . $video['snippet']['thumbnails']['default']['url'] . '">';
                echo '<h3>' . ucfirst(strtolower($video['snippet']['title'])) . '</h3>';
                echo '<p>' . ucfirst(strtolower(substr($video['snippet']['description'],0, strpos(wordwrap($video['snippet']['description'], 250), "\n")))) . '</p>';
                echo '<a href="https://www.youtube.com/watch?v=' . $video['id'] . '" data-id="' . $video['id'] . '">watch video</a>';
                echo "</li>";
            }
        ?>

    </ul>

    <script src="js/10ktube.js"></script>
</body>
</html>
