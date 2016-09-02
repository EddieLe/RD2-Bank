<?php

require_once 'MyPDO.php';
ini_set('display_errors', true);

function trsMoney()
{
    if ($_POST['money'] < 0) {
        echo "<script> alert('輸入小於0');</script>";
        header("refresh:0, url=Bank.php");
        exit;
    }
    session_start();
    $mypod = new MyPDO();
    $pdo = $mypod->pdoConnect;

    $pdo->beginTransaction();

    if ($_POST['trs'] == 'out') {

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
            $stmt->execute([':take' => $_POST['money'], ':account' => $_SESSION['account']]);

            $cmd = "INSERT INTO `detail`(`name`, `total`, `take`, `result`) VALUES (:account, :total, :take, :result)";
            $stmt = $pdo->prepare($cmd);
            $stmt->execute([
                ':take' => $_POST['money'],
                ':total' => $newTotal,
                ':account' => $_SESSION['account'],
                ':result' => $newTotal - $_POST['money']
            ]);

            $pdo->commit();
            header("location: Bank.php");
        } catch (Exception $e) {
            $pdo->rollback();
            echo 'Caught exception: ', $e->getMessage();
        }
    } else {
        try {
            $cmd = "SELECT `count` FROM `accounts` WHERE `name` = :account FOR UPDATE";
            $stmt = $pdo->prepare($cmd);
            $stmt->execute([':account' => $_SESSION['account']]);

            $row = $stmt->fetchall(PDO::FETCH_ASSOC);
            $newTotal = $row[0]['count'];

            $cmd = "UPDATE `accounts` SET `count` = `count` + :save WHERE `name` = :account";
            $stmt = $pdo->prepare($cmd);
            $stmt->execute([':save' => $_POST['money'], ':account' => $_SESSION['account']]);

            $cmd = "INSERT INTO `detail`(`name`, `total`, `save`, `result`) VALUES (:account, :total, :save, :result)";
            $stmt = $pdo->prepare($cmd);
            $stmt->execute([
                ':save' => $_POST['money'],
                ':total' => $newTotal,
                ':account' => $_SESSION['account'],
                ':result' => $newTotal + $_POST['money']
            ]);

            $pdo->commit();
            header("location: Bank.php");
        } catch (Exception $e) {
            $pdo->rollback();
            echo 'Caught exception: ', $e->getMessage();
        }
    }
}
trsMoney();