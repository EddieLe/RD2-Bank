<?php
require_once 'MyPDO.php';
ini_set('display_errors', true);
session_start();

if (!isset($_SESSION['account'])) {
    header("location:SignIn.php");
}

function back($pay, $account)
{
    try {
        $myPdo = new MyPDO();
        $pdo = $myPdo->pdoConnect;
        $pdo->beginTransaction();

        $cmd = "SELECT `count` FROM `accounts` WHERE `name` = :account FOR UPDATE";
        $stmt = $pdo->prepare($cmd);
        $stmt->execute([':account' => $account]);

        $row = $stmt->fetchall(PDO::FETCH_ASSOC);
        $newTotal = $row[0]['count'];

        $cmd = "UPDATE `accounts` SET `count` = `count` + :win WHERE `name` = :name";
        $stmt = $pdo->prepare($cmd);
        $stmt->execute([':win' => $pay, ':name' => $account]);

        $cmd = "INSERT INTO `detail`(`name`, `total`, `result`, `win`) VALUES (:account, :total, :result, :win)";
        $stmt = $pdo->prepare($cmd);
        $stmt->execute([
            ':total' => $newTotal,
            ':account' => $account,
            ':result' => $newTotal + $pay,
            ':win' => $pay
        ]);

        $pdo->commit();

    } catch (Exception $e) {
        $pdo->rollback();
        echo 'Caught exception: ', $e->getMessage();
    }
}

function comparison()
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
    $number = $pdo->lastInsertId();

    $sql = "SELECT * FROM `gameResult`";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }

    for ($i = 0; $i < count($data); $i++) {
        $time[] = strtotime($data[$i]['date']);

        if ($time[$i] >= strtotime($_POST['start'])  &&  $time[$i] <= (strtotime($_POST['end'])+86399)) {
            $result[] = $data[$i];
        }
    }

    $oneResult = array($_POST['one'], $_POST['two'], $_POST['three']);
    $twoResult = array($_POST['two'], $_POST['three'], $_POST['four']);
    $threeResult = array($_POST['three'], $_POST['four'], $_POST['five']);

    for ($i = 0; $i < count($result); $i++) {

        if (in_array($result[$i]['one'],$oneResult) && in_array($result[$i]['two'],$oneResult) && in_array($result[$i]['three'],$oneResult)) {
            $sql = "UPDATE `gameResult` SET `result`= '中前三', `number` = :numbers WHERE `id` = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['id' => $result[$i]['id'], ':numbers' => $number]);
            back($result[$i]['pay'], $result[$i]['account']);
//            $totalResult[] = $result[$i];
        }
        if (in_array($result[$i]['two'],$twoResult) && in_array($result[$i]['three'],$twoResult) && in_array($result[$i]['four'],$twoResult)) {
            $sql = "UPDATE `gameResult` SET `result1`= '中中三', `number` = :numbers WHERE `id` = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['id' => $result[$i]['id'], ':numbers' => $number]);
            back($result[$i]['pay'],$result[$i]['account']);
//            $totalResult[] = $result[$i];
        }
        if (in_array($result[$i]['three'],$threeResult) && in_array($result[$i]['four'],$threeResult) && in_array($result[$i]['five'],$threeResult)) {
            $sql = "UPDATE `gameResult` SET `result2`= '中後三', `number` = :numbers WHERE `id` = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['id' => $result[$i]['id'], ':numbers' => $number]);
            back($result[$i]['pay'],$result[$i]['account']);
//            $totalResult[] = $result[$i];
        }
    }
    $sql = "SELECT * FROM `gameResult` WHERE `number` > 0";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':account' => $_SESSION['account']]);
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $dit[] = $row;
    }

    return $dit;
}

?>

<html>
<head>
    <title>開獎結果</title>
</head>
<body>

    <table width="1000" border="1">
        <tr>
            <td>注單編號</td>
            <td>開獎編號</td>
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
        <?php $data = comparison();?>
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
<a href ="Open.php">上一頁</a>
</body>
</html>
