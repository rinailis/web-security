<?php

// Подключение к базе данных
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "security";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Функция для валидации email
function validateEmail($email)
{
    // Проверка существования почты
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false;
    }
    return true;
}

// Функция для валидации пароля
function validatePassword($password)
{
    // Проверка на длину пароля
    if (strlen($password) < 8) {
        return false;
    }

    // Проверка на повторяющиеся символы
    if (preg_match('/(.)\1{2,}/', $password)) {
        return false;
    }

    // Проверка на содержание названия текущего месяца
    $currentMonth = strtolower(date('F'));
    if (strpos($password, $currentMonth) === false) {
        return false;
    }

    // Проверка на наличие минимум 2 спец. символов
    if (preg_match_all("/[!@#$%^&*()_+{}\[\]:;\"'<,>.|\/?]/", $password) < 2) {
        return false;
    }

    // Проверка на использование русской и английской раскладки
    if (!preg_match('/[а-яА-Яa-zA-Z]/u', $password)) {
        return false;
    }

    return true;
}

// Функция для валидации номера телефона
function validatePhoneNumber($phoneNumber)
{
    // Реализация маски и проверка кода страны
    // Допустим, формат номера телефона +1234567890
    if (!preg_match('/^\+\d{1}\(\d{3}\)-\d{3}-\d{2}-\d{2}$/', $phoneNumber)) {
        return false;
    }
    return true;
}

// Функция для валидации даты рождения
function validateDateOfBirth($dob)
{

    // Проверка на то, что дата рождения не в будущем и не старше 111 лет
    $today = new DateTime();
    $birthdate = new DateTime($dob);
    $age = $birthdate->diff($today)->y;

    if ($birthdate > $today || $age > 111) {
        return false;
    }

    return true;
}

// Функция для валидации гендера
function validateGender($gender)
{
    // Проверка на соответствие допустимых значений
    $validGenders = array('м', 'ж', 'д');
    if (!in_array($gender, $validGenders)) {
        return false;
    }
    return true;
}

// Функция для валидации username
function validateUsername($username, $conn)
{
    // Проверка на пустоту
    if (empty($username)) {
        return false;
    }

    // Проверка наличия цифр
    if (preg_match('/\d/', $username)) {
        return false;
    }

    // Проверка на уникальность в базе данных
    $stmt = $conn->prepare("SELECT * FROM user WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return false;
    }

    return true;
}

// Функция для валидации картинок
function validateImages($images, $conn)
{
    // Проверка наличия файлов
    if (!isset($images['name']) || empty($images['name'])) {
        return false;
    }

    // Проверка расширения файла
    $allowedExtensions = array('jpg', 'jpeg', 'png', 'gif');
    // foreach ($images['name'] as $imageName) {
    $extension = pathinfo($images['name'], PATHINFO_EXTENSION);
    if (!in_array(strtolower($extension), $allowedExtensions)) {
        return false;
    }
    // }

    // Проверка размера файла
    $maxSize = 10485760; // 10 MB
    // foreach ($images['size'] as $imageSize) {
    if ($images['size'] > $maxSize) {
        return false;
    }
    // }

    // Проверка имени файла
    // foreach ($images['name'] as $imageName) {
    if (strlen($images['name']) < 5 || strlen($images['name']) > 50) {
        return false;
    }
    // }

    // Проверка на уникальность в базе данных
    $stmt = $conn->prepare("SELECT * FROM user WHERE `image` = ?");
    $stmt->bind_param("s", $images['name']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return false;
    }

    return true;
}

// Проверка POST запроса и валидация данных
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $phoneNumber = $_POST['phone'];
    $dob = $_POST['birth'];
    $gender = $_POST['gender'];
    $username = $_POST['username'];
    $images = $_FILES['image'];

    // Валидация данных
    $errors = array();

    if (!validateEmail($email)) {
        $errors['email'] = "Invalid email";
    }

    if (!validatePassword($password)) {
        $errors['password'] = "Invalid password";
    }

    if (!validatePhoneNumber($phoneNumber)) {
        $errors['phone'] = "Invalid phone number".$phoneNumber;
    }

    if (!validateDateOfBirth($dob)) {
        $errors['dob'] = "Invalid date of birth";
    }

    if (!validateGender($gender)) {
        $errors['gender'] = "Invalid gender";
    }

    if (!validateUsername($username, $conn)) {
        $errors['username'] = "Invalid username";
    }

    if (!validateImages($images, $conn)) {
        $errors['images'] = "Invalid images";
    }

    // Возврат ошибок в формате JSON
    if (!empty($errors)) {
        http_response_code(400); // Устанавливаем код ответа 400 (Bad Request)
        echo json_encode($errors);
    } else {
        // Подготовленный запрос для вставки данных
        $stmt = $conn->prepare("INSERT INTO user (email, password, phone, birth, gender, username, image) VALUES (?, ?, ?, ?, ?, ?, ?)");

        // Привязка параметров
        $stmt->bind_param("sssssss", $email, $password, $phoneNumber, $dob, $gender, $username, $images['name']);

        // Выполнение запроса
        if ($stmt->execute()) {
            echo "New record inserted successfully";
        } else {
            echo "Error: " . $stmt->error;
        }
    }
}
