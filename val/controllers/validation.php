<?php
 http_response_code(400); // Устанавливаем код ответа 400 (Bad Request)
 echo json_encode('cscs');
 die ('oops!');
// Подключение к базе данных
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "myDB";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Функция для валидации email
function validateEmail($email) {
    // Проверка существования почты
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false;
    }
    return true;
}

// Функция для валидации пароля
function validatePassword($password) {
    // Проверка на длину пароля
    if (strlen($password) < 8) {
        return false;
    }

    // Проверка на повторяющиеся символы
    if (preg_match('/(.)\1{2,}/', $password)) {
        return false;
    }

    // Проверка на часто повторяющиеся комбинации
    if (preg_match('/(?=(.*?[a-zA-Z]{3,}))/i', $password)) {
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
function validatePhoneNumber($phoneNumber) {
    // Реализация маски и проверка кода страны
    // Допустим, формат номера телефона +1234567890
    if (!preg_match('/^\+\d{11}$/', $phoneNumber)) {
        return false;
    }
    return true;
}

// Функция для валидации даты рождения
function validateDateOfBirth($dob) {
    // Проверка формата даты
    $date = date_parse($dob);
    if (!$date || !checkdate($date['month'], $date['day'], $date['year'])) {
        return false;
    }

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
function validateGender($gender) {
    // Проверка на соответствие допустимых значений
    $validGenders = array('М', 'Ж');
    if (!in_array($gender, $validGenders)) {
        return false;
    }
    return true;
}

// Функция для валидации username
function validateUsername($username, $conn) {
    // Проверка на пустоту
    if (empty($username)) {
        return false;
    }

    // Проверка наличия цифр
    if (preg_match('/\d/', $username)) {
        return false;
    }

    // Проверка на уникальность в базе данных
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return false;
    }

    return true;
}

// Функция для валидации картинок
function validateImages($images) {
    // Проверка наличия файлов
    if (!isset($images['name']) || empty($images['name'])) {
        return false;
    }

    // Проверка расширения файла
    $allowedExtensions = array('jpg', 'jpeg', 'png', 'gif');
    foreach ($images['name'] as $imageName) {
        $extension = pathinfo($imageName, PATHINFO_EXTENSION);
        if (!in_array(strtolower($extension), $allowedExtensions)) {
            return false;
        }
    }

    // Проверка размера файла
    $maxSize = 10485760; // 10 MB
    foreach ($images['size'] as $imageSize) {
        if ($imageSize > $maxSize) {
            return false;
        }
    }

    // Проверка имени файла
    foreach ($images['name'] as $imageName) {
        if (strlen($imageName) < 5 || strlen($imageName) > 50) {
            return false;
        }
    }

    return true;
}

// // Проверка POST запроса и валидация данных
// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     $email = $_POST['email'];
//     $password = $_POST['password'];
//     $phoneNumber = $_POST['phone'];
//     $dob = $_POST['dob'];
//     $gender = $_POST['gender'];
//     $username = $_POST['username'];
//     $images = $_FILES['images'];

//     // Валидация данных
//     $errors = array();

//     if (!validateEmail($email)) {
//         $errors[] = "Invalid email";
//     }

//     if (!validatePassword($password)) {
//         $errors[] = "Invalid password";
//     }

//     if (!validatePhoneNumber($phoneNumber)) {
//         $errors[] = "Invalid phone number";
//     }

//     if (!validateDateOfBirth($dob)) {
//         $errors[] = "Invalid date of birth";
//     }

//     if (!validateGender($gender)) {
//         $errors[] = "Invalid gender";
//     }

//     if (!validateUsername($username, $conn)) {
//         $errors[] = "Invalid username";
//     }

//     if (!validateImages($images)) {
//         $errors[] = "Invalid images";
//     }

//     // Вывод ошибок или сохранение данных
//     if (!empty($errors)) {
//         // Обработка ошибок
//         foreach ($errors as $error) {
//             echo $error . "<br>";
//         }
//     } else {
//         // Сохранение данных в базе данных или другие действия
//         echo "Data is valid. Proceed with saving or further processing";
//     }
// }

// Проверка POST запроса и валидация данных
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $phoneNumber = $_POST['phone'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $username = $_POST['username'];
    $images = $_FILES['images'];

    // Валидация данных
    $errors = array();

    if (!validateEmail($email)) {
        $errors['email'] = "Invalid email";
    }

    if (!validatePassword($password)) {
        $errors['password'] = "Invalid password";
    }

    if (!validatePhoneNumber($phoneNumber)) {
        $errors['phone'] = "Invalid phone number";
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

    if (!validateImages($images)) {
        $errors['images'] = "Invalid images";
    }

    // Возврат ошибок в формате JSON
    if (!empty($errors)) {
        http_response_code(400); // Устанавливаем код ответа 400 (Bad Request)
        echo json_encode($errors);
    } else {
        // Если ошибок нет, продолжаем выполнение запроса или сохраняем данные
        echo "Data is valid. Proceed with saving or further processing";
    }
}
