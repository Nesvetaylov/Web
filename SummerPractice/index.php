<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <?php 
            $v = rand();
            echo "<link rel='stylesheet' type='text/css' href='style_of_site.css?v=$v' media='screen' />";
        ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Платный прием в поликлинике</title>
</head>

<body>
    <!-- Header -->
    <header class="header">
        <nav class="nav">
            <div class="logo">
                <a>Платный прием в поликлинике</a>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <div class="button-container">
            <a href="DOCTORS/index.php" class="button">Добавить врача</a>
            <a href="PATIENTS/patients.php" class="button">Добавить пациента</a>
            <a href="ADMISSION_OF_PATIENTS/admission_of_patients.php" class="button"> Записаться на прием</a>
            <a href="queries.php" class="button">QUERIES</a>
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <p>Несветайлов Владислав 24 группа</p>
    </footer>
</body>

</html>
