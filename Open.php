<?php
require_once 'MyPDO.php';
session_start();

if (!isset($_SESSION['account'])) {
    header("location:SignIn.php");
}
//隨機產生不重複亂數
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
?>

<html>
    <head>
        <title>開獎頁</title>
        <link rel="stylesheet" href="//apps.bdimg.com/libs/jqueryui/1.10.4/css/jquery-ui.min.css">
        <script src="//apps.bdimg.com/libs/jquery/1.10.2/jquery.min.js"></script>
        <script src="//apps.bdimg.com/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>
        <link rel="stylesheet" href="jqueryui/style.css">
    </head>
    <body>
    <?php $data = result();?>
        <form method="post" action="OpenResultInsert.php">
            開獎區間 : <input id="datepicker1" type="text" name="start" value="" placeholder="2014-09-18" required/>
            ～ <input id="datepicker2" type="text" name="end" value="" placeholder="2014-09-18" required/>
            <script language="JavaScript">
                $(document).ready(function(){
                    $("#datepicker1").datepicker();
                    $("#datepicker2").datepicker({firstDay: 1});
                });
            </script>
<!--            數字一: <input type="text" size="3" name="one" value="--><?php //echo $data[0];?><!--" readonly="readonly" required pattern="[0-9]{1}"/>-->
<!--            數字二：<input type="text" size="3" name="two" value="--><?php //echo $data[1];?><!--" readonly="readonly" required pattern="[0-9]{1}"/>-->
<!--            數字三：<input type="text" size="3" name="three" value="--><?php //echo $data[2];?><!--" readonly="readonly" required pattern="[0-9]{1}"/>-->
<!--            數字四：<input type="text" size="3" name="four" value="--><?php //echo $data[3];?><!--" readonly="readonly" required pattern="[0-9]{1}"/>-->
<!--            數字五：<input type="text" size="3" name="five" value="--><?php //echo $data[4];?><!--" readonly="readonly" required pattern="[0-9]{1}"/>-->
            數字一: <input type="text" size="3" name="one" value="<?php echo $data[0];?>" required pattern="[0-9]{1}"/>
            數字二：<input type="text" size="3" name="two" value="<?php echo $data[1];?>" required pattern="[0-9]{1}"/>
            數字三：<input type="text" size="3" name="three" value="<?php echo $data[2];?>" required pattern="[0-9]{1}"/>
            數字四：<input type="text" size="3" name="four" value="<?php echo $data[3];?>" required pattern="[0-9]{1}"/>
            數字五：<input type="text" size="3" name="five" value="<?php echo $data[4];?>" required pattern="[0-9]{1}"/>
            <input type="submit" value="確認" />
        </form>
        <a href ="Game.php">上一頁</a>
    </body>
</html>
