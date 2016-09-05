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
//resultInsert();
//header("location:Open.php");
function comparison()
{
    $myPdo = new MyPDO();
    $pdo = $myPdo->pdoConnect;
    $sql = "SELECT * FROM `gameResult`";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }
    for ($i = 0; $i < count($data); $i++) {
        $time[] = strtotime($data[$i]['date']);

        if ($time[$i] >= strtotime($_POST['start'])  &&  $time[$i] <= strtotime($_POST['end'])){
            $result[] = $data[$i];
        }
    }
//    var_dump($result);
    $oneResult = array($_POST['one'], $_POST['two'], $_POST['three']);
    $twoResult = array($_POST['two'], $_POST['three'], $_POST['four']);
    $threeResult = array($_POST['three'], $_POST['four'], $_POST['five']);
//    var_dump($oneResult);
//        var_dump($twoResult);
//        var_dump($threeResult);
    echo $_POST['one'], $_POST['two'], $_POST['three'], $_POST['four'], $_POST['five'];
    for ($i = 0; $i < count($result); $i++) {
        echo $result[$i]['one'];
        var_dump(in_array($result[$i]['one'],$oneResult));
        if (in_array($result[$i]['one'],$oneResult) && in_array($result[$i]['two'],$oneResult) && in_array($result[$i]['three'],$oneResult)) {
            echo "中前三";
            var_dump($result[$i]);
        }
        if (in_array($result[$i]['two'],$twoResult) && in_array($result[$i]['three'],$twoResult) && in_array($result[$i]['four'],$twoResult)) {
            echo "中中三";
            var_dump($result[$i]);
        }
        if (in_array($result[$i]['three'],$threeResult) && in_array($result[$i]['four'],$threeResult) && in_array($result[$i]['five'],$threeResult)) {
            echo "中後三";
            var_dump($result[$i]);
        }
    }
//    var_dump($result);
}
comparison();
?>
<html>
<head>
    <title>開獎結果</title>
</head>
<body>

<form method="post" action="OpenResultInsert.php">
    <input type="date" name="start" value="" placeholder="2014-09-18" required>
    <input type="date" name="end" value="" placeholder="2014-09-18" required>
    數字一: <input type="text" size="3" name="one" value="" readonly="readonly" required pattern="[0-9]{1}"/>
    數字二：<input type="text" size="3" name="two" value="" readonly="readonly" required pattern="[0-9]{1}"/>
    數字三：<input type="text" size="3" name="three" value="" readonly="readonly" required pattern="[0-9]{1}"/>
    數字四：<input type="text" size="3" name="four" value="" readonly="readonly" required pattern="[0-9]{1}"/>
    數字五：<input type="text" size="3" name="five" value="" readonly="readonly" required pattern="[0-9]{1}"/>
    <input type="submit" value="確認" />
</form>
<a href ="Open.php">上一頁</a>
</body>
</html>
