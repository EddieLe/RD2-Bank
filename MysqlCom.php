<?php
require_once 'MyPDO.php';

function comparison()
{
    $myPdo = new MyPDO();
    $pdo = $myPdo->pdoConnect;
    $sql = "SELECT * FROM `accounts` WHERE `name` = :account";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':account' => $_POST['account']]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($stmt->rowCount() > 0) {
        if ($row['password'] == md5($_POST['password'])) {

            session_start();
            $_SESSION['account'] = $_POST['account'];
            header("location: Bank.php");
            exit;

        } else {
            echo "<script> alert('密罵錯誤');</script>";
            header("refresh:0, url=SignIn.php");
            exit;
        }
    } else {
        echo "<script> alert('沒有此帳號');</script>";
        header("refresh:0, url=SignIn.php");
    }
}
comparison();
