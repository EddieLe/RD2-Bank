<html>
    <head>
        <title>Create Account</title>
    </head>
    <body>
        <form action="MysqlInsert.php" method="post">
            Account: <input type="text" name="account" value="" required pattern="[A-Za-z0-9]{1,10}"/>
            Password: <input type="text" name="password" value="" required pattern="[A-Za-z0-9]{1,10}"/>
            <input type="submit" value="Create" />
        </form>
        <form action="SignIn.php" method="post">
            <input type="submit" value="Sign in Page">
        </form>
    </body>
</html>
