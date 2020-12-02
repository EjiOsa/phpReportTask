<?php
    //フォームからの値をそれぞれ変数に代入
    $name = $_POST['name'];
    $email = $_POST['email'];
    $pass = password_hash($_POST['pass'], PASSWORD_DEFAULT);

    date_default_timezone_set('Asia/Tokyo');
    $date = date('Y-m-d H:i:s');

    define('DB_DSN', 'mysql:dbname=report_project;host=localhost');
    define('DB_USER', 'root');
    define('DB_PASSWORD', 'root');
    $options = array(PDO::MYSQL_ATTR_INIT_COMMAND=>"SET CHARACTER SET 'utf8'");
    try {
        $dbh = new PDO(DB_DSN,DB_USER, DB_PASSWORD, $options);
    } catch (PDOException $e) {
        $msg = $e->getMessage();
    }

    //フォームに入力されたmailがすでに登録されていないかチェック
    $confirmUserSql = "SELECT * FROM users WHERE email = :email";
    $confirmUserStmt = $dbh->prepare($confirmUserSql);
    $confirmUserStmt->bindValue(':email', $email);
    $confirmUserStmt->execute();
    $user = $confirmUserStmt->fetch();
    if ($user['mail'] === $mail) {
        $msg = '同じメールアドレスが存在します。';
        $link = '<a href="signup">戻る</a>';
    } else {
        //登録されていなければinsert 
        $userInsertSql = "INSERT INTO users(
                                name, email, password,created_at,updated_at
                            ) VALUES (
                                :name, :email, :password,'$date','$date')";
        $userInsertStmt = $dbh->prepare($userInsertSql);
        $userInsertStmt->bindValue(':name', $name);
        $userInsertStmt->bindValue(':email', $mail);
        $userInsertStmt->bindValue(':password', $pass);
        $userInsertStmt->execute();
        $msg = '会員登録が完了しました';
        $link = '<a href="login">ログインページ</a>';
    }

    // 以下、共通パーツ定数
    define("TITLE" ,"Register");
    define("NOT_SHOW_AUTH" ,"Register");
    ?>

<?php include(dirname(__FILE__).'/../assets/_inc/header.php'); ?>

<h1><?php echo $msg; ?></h1><!--メッセージの出力-->
<?php echo $link; ?>

</body>
</html>