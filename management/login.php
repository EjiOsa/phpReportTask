<?php
    session_start();
    $errorMassage = $_SESSION["message_error"];
    $email = $_POST['email'];
    define('DB_DSN', 'mysql:dbname=report_project;host=localhost');
    define('DB_USER', 'root');
    define('DB_PASSWORD', 'root');
    $options = array(PDO::MYSQL_ATTR_INIT_COMMAND=>"SET CHARACTER SET 'utf8'");
    try {
        $dbh = new PDO(DB_DSN,DB_USER, DB_PASSWORD, $options);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        $msg = $e->getMessage();
    }

    $confirmUserSql = "SELECT * FROM users WHERE email = :email";
    $confirmUserStmt = $dbh->prepare($confirmUserSql);
    $confirmUserStmt->bindValue(':email', $email);
    $confirmUserStmt->execute();
    $user = $confirmUserStmt->fetch();
    //指定したハッシュがパスワードにマッチしているかチェック
    if (password_verify($_POST['password'], $user['password'])) {
        //DBのユーザー情報をセッションに保存
        $_SESSION['id'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        $msg = 'ログインしました。';
        $link = '<a href="/phpReport">TOPへ</a>';
    } else {
        $msg = 'メールアドレスもしくはパスワードが間違っています。';
        $link = '<a href="/phpReport/management/loginForm">戻る</a>';
    }
    // 以下、共通パーツ定数
    define("TITLE" ,"Login");
    define("NOT_SHOW_AUTH" ,"Login");
    ?>

<?php include(dirname(__FILE__).'/../assets/_inc/header.php'); ?>


<h1><?php echo $msg; ?></h1>
<?php echo $link; ?>
</body>
</html>