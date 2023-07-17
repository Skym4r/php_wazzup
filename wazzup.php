
<?php

// Авторизация в Wazzup
$api_key = 'api_key'; // Ваш API-ключ Wazzup
$api_url = 'api_url'; // URL API Wazzup

// Данные нового пользователя
$new_user_data = array(array(
    'id' => '1234',
    'name' => 'name', // Имя нового пользователя
    'phone' => 'phone', // Телефон нового пользователя
));

// Отправка запроса на создание нового пользователя
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $api_url); // URL для создания нового пользователя
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($new_user_data));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json',
    'Authorization: Bearer '.$api_key
));

$result = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// Проверка результата запроса
if ($http_code == 201) {
    // Новый пользователь успешно создан
    $user_data = json_decode($result, true);
    $user_id = $user_data['id']; // Идентификатор нового пользователя
    echo 'Новый пользователь создан. ID пользователя: ' . $user_id;
} else {
    // Произошла ошибка при создании пользователя
    echo 'Ошибка создания пользователя: ' . $result;
}

?>