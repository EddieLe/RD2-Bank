<html>
    <head>
        <title>Create Account</title>
    </head>
    <body>
        <form action="MysqlInsert.php" method="post">
            Account: <input type="text" name="account" value="" />
            Password: <input type="text" name="password" value="" />
            <input type="submit" value="Create" />
        </form>
        <form action="SignIn.php" method="post">
            <input type="submit" value="Sign in Page">
        </form>
    </body>
</html>
