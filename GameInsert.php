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

    $pdo->beginTransaction();

    $sql = "SELECT `count` FROM `accounts` WHERE `name` = :account";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':account' => $_SESSION['account']]);
    $row = $stmt->fetchall(PDO::FETCH_ASSOC);

    if ($row[0]['count'] < $_POST['money'] ) {
        echo "<script> alert('沒有足夠金額');</script>";
        header("refresh:0, url=Bank.php");
        exit;
    }

    try {
        $sql = "SELECT `count` FROM `accounts` WHERE `name` = :account FOR UPDATE";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':account' => $_SESSION['account']]);

        //取出當下total
        $row = $stmt->fetchall(PDO::FETCH_ASSOC);
        $newTotal = $row[0]['count'];

        $cmd = "UPDATE `accounts` SET `count` = `count` - :take WHERE `name` = :account";
        $stmt = $pdo->prepare($cmd);
        $stmt->execute([':take' => $_POST['pay'], ':account' => $_SESSION['account']]);

        $cmd = "INSERT INTO `detail`(`name`, `total`, `play`, `result`) VALUES (:account, :total, :play, :result)";
        $stmt = $pdo->prepare($cmd);
        $stmt->execute([
            ':play' => $_POST['pay'],
            ':total' => $newTotal,
            ':account' => $_SESSION['account'],
            ':result' => $newTotal - $_POST['pay']
        ]);

        $pdo->commit();
    } catch (Exception $e) {
        $pdo->rollback();
        echo 'Caught exception: ', $e->getMessage();
    }


    $sql = "INSERT INTO `gameResult`(`one`, `two`, `three`, `four`, `five`, `pay`, `result`, `account`, `result1`, `result2`) 
      VALUES (:one, :two, :three, :four, :five, :pay, '無', :account, '無', '無')";
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