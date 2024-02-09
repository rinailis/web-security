 # Отчет

### Пратическая №1
создаем базу данных MySQL с помощью phpMyAdmin  
добавляем таблицу для пользователей  
создаем обычного юзера и админа для теста  

![image](https://github.com/rinailis/web-security/assets/131038016/2f38652a-30d7-4715-a2f7-1760f1b9b6d6)

подключаемся к базе данных и объявляем сессию
```php
$connect = mysqli_connect('localhost', 'root', '', 'security');
session_start();
```
создаём форму авторизации
```php
<form method="post" action="/">
    <input type="text" name="login" placeholder="Логин">
    <input type="password" name="password" placeholder="Пароль">
    <input type="submit" name="send"></input>
</form>
```

![image](https://github.com/rinailis/web-security/assets/131038016/88df1bb1-3157-4143-a088-fc6f2fe705fc)

ищем строки с таким логином и паролем в бд, получаем его данные
```php
$str = "SELECT * FROM `users` WHERE `login`='$login' AND `password`='$password'";
    $query = mysqli_query($connect, $str);
    $fetch = mysqli_fetch_array($query);
    $check = mysqli_num_rows($query);
```
проверяем есть ли такой пользователь и в зависимости от роли перенаправляем пользователя на страницу админки или пользователя
```php
if ($check == 1) {
        $_SESSION['role'] = $fetch['role'];
    } else {
        echo "Неверный логин или пароль";
    }
```

на странице пользователя и админа делаем проверку на роль
1. админ(роль 2)
```php
if ($_SESSION['role'] != 2) {
    header('Location:/');
}
```

![image](https://github.com/rinailis/web-security/assets/131038016/3b08dead-ab5b-4d2d-929b-29639cbf5402)

2. пользователь(любая роль)
```php
if (!$_SESSION['role']) {
    header('Location:/');
}
```

![image](https://github.com/rinailis/web-security/assets/131038016/307ed8bd-07ed-4495-9013-e29fb4ac98b7)
