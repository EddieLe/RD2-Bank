<html>
    <head>
        <title>"Sign In</title>
    </head>
    <body>
        <form action="MysqlCom.php" method="post">
            Account: <input type="text" name="account" value=""  required/>
            Password: <input type="text" name="password" value=""  required/>
            <input type="submit" value="Sign in" />
        </form>
        <form action="index.php" method="post">
            <input type="submit" value="註冊" />
        </form>
    </body>
</html>