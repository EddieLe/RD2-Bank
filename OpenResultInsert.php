<?php
require_once 'MyPDO.php';
session_start();

function resultInsert()
{
    $myPdo = new MyPDO();
    $pdo = $myPdo->pdoConnect;

    $sql = "INSERT INTO `Result`(`one`, `two`, `three`, `four`, `five`,`starttime`, `endtime`) 
      VALUES (:one, :two, :three, :four, :five, :starttime, :endtime)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':one' => $_POST['one'],
        ':two' => $_POST['two'],
        ':three' => $_POST['three'],
        ':four' => $_POST['four'],
        ':five' => $_POST['five'],
        ':starttime' => $_POST['start'],
        ':endtime' => $_POST['end']
    ]);
}
resultInsert();
header("location:Open.php");