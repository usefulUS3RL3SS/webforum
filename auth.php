<?php
$servername = "localhost";
$dbname = 'auth';

$conn = new mysqli('127.0.0.1', 'root', '');


if (!$conn) {
    die("Connection failed: " . $conn->connect_error);
}

$resultSelectDB = mysqli_select_db($conn, $dbname);


function registerUser($username, $email, $group, $password) {
    global $conn;

    // Хеширование пароля перед сохранением в базу данных
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Подготовка SQL-запроса
    $stmt = $conn->prepare("INSERT INTO users (username, email, group, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $email, $group, $hashedPassword);

    // Выполнение запроса
    $result = $stmt->execute();

    // Закрытие запроса
    $stmt->close();

    return $result;
}

// Функция для авторизации пользователя
function loginUser($email, $password) {
    global $conn;

    // Подготовка SQL-запроса
    $stmt = $conn->prepare("SELECT email, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);

    // Выполнение запроса
    $stmt->execute();

    // Получение результата
    $result = $stmt->get_result();

    // Закрытие запроса
    $stmt->close();

    // Проверка существования пользователя
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Проверка пароля
        if (password_verify($password, $row['password'])) {
            return true; // Пользователь авторизован
        }
    }

    return false; // Неверный логин или пароль
}




// Обработка запроса на регистрацию
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["Sign_up"])) {
    $newUsername = $_POST["new_username"];
    $newEmail = $_POST["new_email"];
    $newGroup = $_POST["new_group"];
    $newPassword = $_POST["new_password"];

    // Проверка наличия значений
    if (!empty($newUsername) && !empty($newEmail) && !empty($newGroup) && !empty($newPassword)) {
        // Регистрация пользователя
        if (registerUser($newUsername, $newEmail, $newGroup, $newPassword)) {
            echo "<script type='text/javascript'>alert('Пользователь успешно зарегистрирован.');</script>";
        } else {
            echo "<script type='text/javascript'>alert('Ошибка регистрации. Пользователь с таким именем уже существует.');</script>";
        }
    } else {
        echo "<script type='text/javascript'>alert('Введите логин и пароль для регистрации.');</script>";
    }
}



// Обработка запроса на авторизацию
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["Log_in"])) {
    $existingEmail = $_POST["existing_email"];
    $existingPassword = $_POST["existing_password"];

    // Проверка наличия обоих значений
    if (!empty($existingEmail) && !empty($existingPassword)) {
        // Авторизация пользователя
        if (loginUser($existingEmail, $existingPassword)) {
            echo "<script type='text/javascript'>alert('Пользователь успешно авторизован.');</script>";
        } else {
            echo "<script type='text/javascript'>alert('Неверный логин и пароль.');</script>";
        }
    } else {
        echo "<script type='text/javascript'>alert('Введите логин и пароль.');</script>";
    }
}
?>