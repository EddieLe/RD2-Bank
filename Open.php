<?php
require_once 'MyPDO.php';
session_start();

if (!isset($_SESSION['account'])) {
    header("location:SignIn.php");
}

function result()
{
    for ($i = 0; $i < 10; $i++) {
        $rand[] = $i ;
    }
    shuffle($rand);
    return $rand;
}

function detail()
{
    session_start();
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
detail()
?>
<html>
    <head>
        <title>開獎頁</title>
    </head>
    <body>
    <?php $data = result();?>
        <form method="post" action="OpenResultInsert.php">
            <input type="date" name="start" value="" placeholder="2014-09-18" required>
            <input type="date" name="end" value="" placeholder="2014-09-18" required>
            數字一: <input type="text" size="3" name="one" value="<?php echo $data[0];?>" readonly="readonly" required pattern="[0-9]{1}"/>
            數字二：<input type="text" size="3" name="two" value="<?php echo $data[1];?>" readonly="readonly" required pattern="[0-9]{1}"/>
            數字三：<input type="text" size="3" name="three" value="<?php echo $data[2];?>" readonly="readonly" required pattern="[0-9]{1}"/>
            數字四：<input type="text" size="3" name="four" value="<?php echo $data[3];?>" readonly="readonly" required pattern="[0-9]{1}"/>
            數字五：<input type="text" size="3" name="five" value="<?php echo $data[4];?>" readonly="readonly" required pattern="[0-9]{1}"/>
            <input type="submit" value="確認" />
        </form>
        <a href ="Game.php">上一頁</a>
    </body>
</html>
