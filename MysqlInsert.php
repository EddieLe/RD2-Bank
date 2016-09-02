<?php
require_once 'MyPDO.php';

function create()
{
    if (!preg_match('/^[A-Za-z0-9]+$/', $_POST['account']) || !preg_match('/^[A-Za-z0-9]+$/', $_POST['password'])) {
        echo "<script> alert('輸入不合法');</script>";
        header("refresh:0, url=SignIn.php");
        exit;
    }
    $myPdo = new MyPDO();
    $pdo = $myPdo->pdoConnect;

    $sql = "SELECT * FROM `accounts` WHERE `name` = :account";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':account' => $_POST['account']]);
    if ($stmt->rowCount() > 0) {
        echo "<script> alert('帳號重複');</script>";
        header("refresh:0, url=index.php");
        exit;
    }
    $sql = "INSERT INTO `accounts`(`name`, `password`) VALUES (:account, :password)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':account' => $_POST['account'], ':password' => md5($_POST['password'])]);
}
create();
header("location:SignIn.php");



