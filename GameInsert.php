<?php
require_once 'MyPDO.php';
session_start();

function gameInsert()
{
    $numArray = array($_POST['one'], $_POST['two'], $_POST['three'], $_POST['four'], $_POST['five']);
    $myPdo = new MyPDO();
    $pdo = $myPdo->pdoConnect;

    $sql = "SELECT `count` FROM `accounts` WHERE `name` = :account";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':account' => $_SESSION['account']]);
    $row = $stmt->fetchall(PDO::FETCH_ASSOC);

    if ($row[0]['count'] < $_POST['pay']) {
        echo "<script> alert('沒有足夠金額');</script>";
        header("refresh:0, url=Bank.php");
        exit;
    }

    if ($_POST['pay'] < 0) {
        echo "<script> alert('輸入要大於零'); </script>";
        header("refresh:0, url=Game.php");
        exit;
    }
    if (count(array_unique($numArray)) != count($numArray)) {
        echo "<script> alert('輸入重複數字'); </script>";
        header("refresh:0, url=Game.php");
        exit;
    }
//    echo $_POST['one'];
//    echo $_POST['two'];
//    echo $_POST['three'];
//    echo $_POST['four'];
//    echo $_POST['five'];
//    echo $_SESSION['account'];
//    echo $_POST['pay'];
//    exit;

    $sql = "INSERT INTO `gameResult`(`one`, `two`, `three`, `four`, `five`, `pay`, `account`) 
      VALUES (:one, :two, :three, :four, :five, :pay, :account)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':one' => $_POST['one'],
        ':two' => $_POST['two'],
        ':three' => $_POST['three'],
        ':four' => $_POST['four'],
        ':five' => $_POST['five'],
        ':pay' => $_POST['pay'],
        ':account' => $_SESSION['account']
        ]);
    echo "<script> alert('下注成功'); </script>";
    header("refresh:0, url=Game.php");
    exit;
}
gameInsert();
header("location:Game.php");