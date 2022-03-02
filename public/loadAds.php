<?php
// On autorise les requêtes Ajax pour toutes les sources
header('Access-Control-Allow-Origin: *');

// On vérifie qu'on utilise la méthode GET
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    // Ici on utilise la méthode GET
    // On se connecte à la base
    require_once('connect.php');

    // // On récupère les données dans la base

    $sql = "SELECT id, title, latitude, longitude, cover_image, slug, ( 6371 * acos( cos( radians(:lat) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(:lon) ) + sin( radians(:lat) ) * sin( radians( latitude ) ) ) ) AS distance FROM `ad` HAVING distance < :distance ORDER BY distance";

    $query = $db->prepare($sql);

    $query->bindValue(':lat', $_GET['latitude'], PDO::PARAM_STR);
    $query->bindValue(':lon', $_GET['longitude'], PDO::PARAM_STR);
    $query->bindValue(':distance', $_GET['distance'], PDO::PARAM_INT);
    $query->execute();
    $result = $query->fetchAll();


    // // On envoie le code de confirmation
    http_response_code(200);

    // // On envoie les données en json
    echo json_encode($result);

    // On se déconnecte de la base
    require_once('close.php');
} else {
    http_response_code(405);
    echo 'La méthode n\'est pas autorisée';
}
