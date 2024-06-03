<?php

$stmt = $db->prepare("SELECT count(application_id) from languages where language_id = 1;");
$stmt->execute();
$lang1 = $stmt->fetchColumn();
$stmt = $db->prepare("SELECT count(application_id) from languages where language_id = 2;");
$stmt->execute();
$lang2 = $stmt->fetchColumn();
$stmt = $db->prepare("SELECT count(application_id) from languages where language_id = 3;");
$stmt->execute();
$lang3 = $stmt->fetchColumn();
$stmt = $db->prepare("SELECT count(application_id) from languages where language_id = 4;");
$stmt->execute();
$lang4 = $stmt->fetchColumn();
$stmt = $db->prepare("SELECT count(application_id) from languages where language_id = 5;");
$stmt->execute();
$lang5 = $stmt->fetchColumn();
$stmt = $db->prepare("SELECT count(application_id) from languages where language_id = 6;");
$stmt->execute();
$lang6 = $stmt->fetchColumn();
$stmt = $db->prepare("SELECT count(application_id) from languages where language_id = 7;");
$stmt->execute();
$lang7 = $stmt->fetchColumn();
$stmt = $db->prepare("SELECT count(application_id) from languages where language_id = 8;");
$stmt->execute();
$lang8 = $stmt->fetchColumn();
$stmt = $db->prepare("SELECT count(application_id) from languages where language_id = 9;");
$stmt->execute();
$lang9 = $stmt->fetchColumn();
$stmt = $db->prepare("SELECT count(application_id) from languages where language_id = 10;");
$stmt->execute();
$lang10 = $stmt->fetchColumn();
$stmt = $db->prepare("SELECT count(application_id) from languages where language_id = 11;");
$stmt->execute();
$lang11 = $stmt->fetchColumn();
echo "
    <table>
        <tr> 
            <th>язык программирования</th>
            <th>кол-во</th>
        </tr>
        <tr>
            <td>Pascal</td>
            <td>"; echo (empty($lang1) ? '0' : $lang1); echo "</td>
        </tr>
        <tr>
            <td>C</td>
            <td>"; echo (empty($lang2) ? '0' : $lang2); echo "</td>
        </tr>
        <tr>
            <td>C++</td>
            <td>"; echo (empty($lang3) ? '0' : $lang3); echo "</td>
        </tr>
        <tr>
            <td>JavaScript</td>
            <td>"; echo (empty($lang4) ? '0' : $lang4); echo "</td>
        </tr>
        <tr>
            <td>PHP</td>
            <td>"; echo (empty($lang5) ? '0' : $lang5); echo "</td>
        </tr>
        <tr>
            <td>Python</td>
            <td>"; echo (empty($lang6) ? '0' : $lang6); echo "</td>
        </tr>
        <tr>
            <td>Java</td>
            <td>"; echo (empty($lang7) ? '0' : $lang7); echo "</td>
        </tr>
        <tr>
            <td>Haskel</td>
            <td>"; echo (empty($lang8) ? '0' : $lang8); echo "</td>
        </tr>
        <tr>
            <td>Clojure</td>
            <td>"; echo (empty($lang9) ? '0' : $lang9); echo "</td>
        </tr>
        <tr>
            <td>Prolog</td>
            <td>"; echo (empty($lang10) ? '0' : $lang10); echo "</td>
        </tr>
        <tr>
            <td>Scala</td>
            <td>"; echo (empty($lang11) ? '0' : $lang11); echo "</td>
        </tr>
    </table>
";