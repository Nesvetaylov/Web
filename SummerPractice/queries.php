<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <?php 
            $v = rand();
            echo "<link rel='stylesheet' type='text/css' href='style_of_site.css?v=$v' media='screen' />";
        ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Queries</title>
</head>

<body>
    <main class="main-content">
        <div class="query-container">
            <a href="QUERIES/first_query.php" class="button">1 запрос</a><br>
            <a href="QUERIES/second_query.php" class="button">2 запрос</a>
            <a href="QUERIES/third_query.php" class="button">3 запрос</a>
            <a href="QUERIES/four_query.php" class="button">4 запрос</a>
            <a href="QUERIES/five_query.php" class="button">5 запрос</a>
            <a href="QUERIES/six_query.php" class="button">6 запрос</a>
            <a href="QUERIES/seven_query.php" class="button">7 запрос</a>
        </div>
    </main>
</body>

</html>