<?php

    require_once('db.php');

    $address = $_POST['address'];
    $description = $_POST['description'];
    $categoryId = $_POST['category'];
    $max_price = $_POST['max_price'];

    $imgSize = $_FILES['uploadFile']['size'];

    $uploadDir = '../img/orders/';
    $uploadFile = $uploadDir . basename($_FILES['uploadFile']['name']);

    $fileExtension = pathinfo($uploadFile, PATHINFO_EXTENSION);
    
    $allowedExtension = ['jpg','jpeg','png','bmp'];
    $allowedSize = 2097152;

    if ($imgSize < $allowedSize) {
        if (in_array($fileExtension, $allowedExtension)) {
            if (move_uploaded_file($_FILES['uploadFile']['tmp_name'], $uploadFile)) {
                $sql = 'INSERT INTO `orders`(`user_id`, `address`, `category_id`, `description`, `max_price`, `before_image`) VALUES (:u, :a, :c, :d, :m, :b)';
                $query = $connect -> prepare($sql);
                $result = $query->execute(["u" => $_SESSION['id'], "a" => $address, "c" => $categoryId, "d" => $description, "m" => $max_price, "b" => $uploadFile]);
                if ($result) {
                    echo 'Добавление заявки успешно';
                } else {
                    echo 'Запрос прошел неудачно';
                }
            } else {
                echo 'Возможная атака с помощью файловой загрузки!';
            }
        } else {
            echo 'Неверное расширение файла';
        }        
    } else {
        echo 'Файл слишком большой';
    }
    
?>