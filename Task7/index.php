<?php

include('basic_auth.php');

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    try {
        $stmt = $db->prepare("SELECT application_id, name, phone, email, year, gender, biography FROM application");
        $stmt->execute();
        $values = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        print('Error : ' . $e->getMessage());
        exit();
    }
    if (!empty($_COOKIE['edit'])) {
        setcookie('edit', '', time() + 24 * 60 * 60);
    }
    include('errors.php');

    
    $_SESSION['token'] = bin2hex(random_bytes(32));
    $_SESSION['login'] = $validUser;

    include('dbshow.php');
    exit();
} else {
    if (!empty($_POST['token']) && hash_equals($_POST['token'], $_SESSION['token'])) {
        foreach ($_POST as $key => $value) {
            if (preg_match('/^clear(\d+)_x$/', $key, $matches)) {
                $app_id = $matches[1];
                setcookie('clear', $app_id, time() + 24 * 60 * 60);
                $stmt = $db->prepare("DELETE FROM application WHERE application_id = ?");
                $stmt->execute([$app_id]);
                $stmt = $db->prepare("DELETE FROM languages WHERE application_id = ?");
                $stmt->execute([$app_id]);
                $stmt = $db->prepare("DELETE FROM users WHERE application_id = ?");
                $stmt->execute([$app_id]);
            }
            if (preg_match('/^edit(\d+)_x$/', $key, $matches)) {
                $app_id = $matches[1];
                setcookie('edit', $app_id, time() + 24 * 60 * 60);
            }
            if (preg_match('/^save(\d+)_x$/', $key, $matches)) {
                setcookie('edit', '', time() + 24 * 60 * 60);
                $app_id = $matches[1];
                include('errors.php');
                $stmt = $db->prepare("SELECT name, phone, email, year, gender, biography FROM application WHERE application_id = ?");
                $stmt->execute([$app_id]);
                $old_dates = $stmt->fetchAll(PDO::FETCH_ASSOC);

                $stmt = $db->prepare("SELECT language_id FROM languages WHERE application_id = ?");
                $stmt->execute([$app_id]);
                $old_languages = $stmt->fetchAll(PDO::FETCH_COLUMN);
                if (array_diff($dates, $old_dates[0])) {
                    setcookie('updated', $app_id, time() + 24 * 60 * 60);
                    $stmt = $db->prepare("UPDATE application SET name = ?, phone = ?, email = ?, year = ?, gender = ?, biography = ? WHERE application_id = ?");
                    $stmt->execute([$dates['name'], $dates['phone'], $dates['email'], $dates['year'], $dates['gender'], $dates['biography'], $app_id]);
                }
                if (array_diff($languages, $old_languages) || count($languages) != count($old_languages)) {
                    setcookie('updated', $app_id, time() + 24 * 60 * 60);
                    try {
                        $db->beginTransaction();
                    
                        $stmt = $db->prepare("DELETE FROM languages WHERE application_id = ?");
                        $stmt->execute([$app_id]);
                    
                        $stmt = $db->prepare("INSERT INTO languages (application_id, language_id) VALUES (?, ?)");
                        foreach ($languages as $language_id) {
                            $stmt->execute([$app_id, $language_id]);
                        }
                        $db->commit();
                    } catch (PDOException $e) {
                        $db->rollBack();
                        echo "Ошибка: " . $e->getMessage();
                    }
                }
            }
        }
        
    } else {
        die('Ошибка CSRF: недопустимый токен');
    }
    header('Location: index.php');
}