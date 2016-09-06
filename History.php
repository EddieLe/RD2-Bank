<?php
require_once 'MyPDO.php';
session_start();

function history()
{
    $myPdo = new MyPDO();
    $pdo = $myPdo->pdoConnect;
    $sql = "SELECT * FROM `gameResult` WHERE `account` = :account";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':account' => $_SESSION['account']]);
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }
    return $data;
}

function detail()
{
    $myPdo = new MyPDO();
    $pdo = $myPdo->pdoConnect;
    $sql = "SELECT * FROM `Result` WHERE `id` = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $_POST['id']]);
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $detail[] = $row;
    }

    return $detail;
}
?>
<html>
<head>
    <title>下注歷史紀錄</title>
</head>
<body>

<table width="1000" border="1">
    <tr>
        <td>注單編號</td>
        <td>開獎期數</td>
        <td>中獎人</td>
        <td>數字一</td>
        <td>數字二</td>
        <td>數字三</td>
        <td>數字四</td>
        <td>數字五</td>
        <td>下注金額</td>
        <td colspan="3">開獎結果</td>
        <td>時間</td>
    </tr>
    <?php $data = history();?>
    <?php for ($i = 0; $i < count($data); $i++) :?>
        <tr>
            <td><?php echo $data[$i]['id']; ?></td>
            <td><?php echo $data[$i]['number']; ?></td>
            <td><?php echo $data[$i]['account']; ?></td>
            <td><?php echo $data[$i]['one']; ?></td>
            <td><?php echo $data[$i]['two']; ?></td>
            <td><?php echo $data[$i]['three']; ?></td>
            <td><?php echo $data[$i]['four']; ?></td>
            <td><?php echo $data[$i]['five']; ?></td>
            <td><?php echo $data[$i]['pay']; ?></td>
            <td Width="60"><?php echo $data[$i]['result']; ?></td>
            <td Width="60"><?php echo $data[$i]['result1']; ?></td>
            <td Width="60"><?php echo $data[$i]['result2']; ?></td>
            <td><?php echo $data[$i]['date']; ?></td>
        </tr>
    <?php endfor; ?>
</table>
<form method="post" action="">
    開獎期數：<input type="text" name="id" value="">
    <input type="submit" name="sub" value="查詢">
    <table width="1000" border="1">
        <tr>
            <td>開獎期數</td>
            <td>數字一</td>
            <td>數字二</td>
            <td>數字三</td>
            <td>數字四</td>
            <td>數字五</td>
            <td>時間</td>
        </tr>
        <?php $data = detail();?>
        <?php for ($i = 0; $i < count($data); $i++) :?>
            <tr>
                <td><?php echo $data[$i]['id']; ?></td>
                <td><?php echo $data[$i]['one']; ?></td>
                <td><?php echo $data[$i]['two']; ?></td>
                <td><?php echo $data[$i]['three']; ?></td>
                <td><?php echo $data[$i]['four']; ?></td>
                <td><?php echo $data[$i]['five']; ?></td>
                <td><?php echo $data[$i]['date']; ?></td>
            </tr>
        <?php endfor; ?>
    </table>
</form>
<a href ="Game.php">上一頁</a>
</body>
</html>
