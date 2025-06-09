<?php
session_start();
require_once 'db.php';

session_start();

function checkAuth() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: /clinic/login.php');
        exit();
    }
}

function login($access_code) {
    $db = DB::getInstance()->getConnection();
    
    $stmt = $db->prepare("SELECT * FROM users WHERE access_code = ?");
    $stmt->bind_param("s", $access_code);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['user_name'] = $user['full_name'];
        return true;
    }
    
    return false;
}

function logout() {
    session_unset();
    session_destroy();
}

function getUserRole() {
    return $_SESSION['user_role'] ?? null;
}
