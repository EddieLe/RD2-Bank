<?php
require_once 'MyPDO.php';
session_start();

if (!isset($_SESSION['account'])) {
    header("location:SignIn.php");
}
function money()
{
    session_start();
    $myPdo = new MyPDO();
    $pdo = $myPdo->pdoConnect;
    $sql = "SELECT * FROM `accounts` WHERE `name` = :account";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':account' => $_SESSION['account']]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    echo $row['count'];
}

function detail()
{
    session_start();
    $myPdo = new MyPDO();
    $pdo = $myPdo->pdoConnect;
    $sql = "SELECT * FROM `detail` WHERE `name` = :account";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':account' => $_SESSION['account']]);
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }

    return $data;
}

function logout()
{
    session_destroy();
}

if (isset($_POST['logout'])) {
    logout();
    header("location:SignIn.php");
}
?>

<html>
    <head>
        <title>Bank</title>
    </head>
    <body>
    <div>帳號：<?php echo $_SESSION['account'] ?></div>
    <div>目前餘額：<?php money(); ?></div>
        <form action="MysqlTrs.php" method="post">
            轉帳選擇: <select name="trs">
                        　<option value="in">轉入</option>
                        　<option value="out">轉出</option>
                    </select>
            輸入金額: <input type="text" name="money" value="" />
            <input type="submit" value="確認" />
        </form>
    <?php if ($_SESSION['account'] == 'root') {?>
        <form action="Open.php" method="post">
            <input type="submit" name="open" value="開獎頁">
        </form>
    <?php } else {?>
        <form action="Game.php" method="post">
            <input type="submit" name="logout" value="三字遊戲" />
        </form>
    <?php } ?>
        <form action="" method="post">
            <input type="submit" name="logout" value="登出" />
        </form>
    <table width="300" border="1">
        <tr>
            <th>金額</th>
            <th>提款</th>
            <th>存款</th>
            <th>下注</th>
            <th>贏額</th>
            <th>餘額</th>
        </tr>
        <?php $data = detail();?>
        <?php for ($i = 0; $i < count($data); $i++): ?>
            <tr>
                <th><?php echo $data[$i]['total'] ?></th>
                <th><?php echo $data[$i]['take'] ?></th>
                <th><?php echo $data[$i]['save'] ?></th>
                <th><?php echo $data[$i]['play'] ?></th>
                <th><?php echo $data[$i]['win'] ?></th>
                <th><?php echo $data[$i]['result'] ?></th>
            </tr>
        <?php endfor; ?>
    </table>

    </body>
</html>