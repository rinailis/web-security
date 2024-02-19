<?php

$file_get_contents = file_get_contents("./settings_ip.json");
$settings_ip = json_decode($file_get_contents, true);
$allowed_ips = $settings_ip['allowed'];
$forbidden_ips = $settings_ip['forbidden'];

$user_ip = $_SERVER['REMOTE_ADDR'];

if (in_array($user_ip, $allowed_ips) && !in_array($user_ip, $forbidden_ips)) {
    echo "Доступ разрешен для IP: $user_ip";
} else {
    echo "Доступ запрещен для IP: $user_ip";
}
