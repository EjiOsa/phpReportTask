<?php
    //フォームからの値をそれぞれ変数に代入
    $name = $_POST['name'];
    $email = $_POST['email'];
    $pass = password_hash($_POST['pass'], PASSWORD_DEFAULT);

    include(dirname(__FILE__).'/../assets/_inc/const.php');
    try {
        $dbh = new PDO(DB_DSN,DB_USER, DB_PASSWORD, $options);
        // SQLエラーの表示設定
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            //フォームに入力されたmailがすでに登録されていないかチェック
        $confirmUserSql = "SELECT * FROM users WHERE email = :email";
        $confirmUserStmt = $dbh->prepare($confirmUserSql);
        $confirmUserStmt->bindValue(':email', $email);
        $confirmUserStmt->execute();
        $user = $confirmUserStmt->fetch();
        if ($user['mail'] === $mail) {
            $msg = '同じメールアドレスが存在します。';
            $link = '<a href="signup.php">戻る</a>';
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
            $link = '<a href="login.php">ログインページ</a>';
        }
    } catch (PDOException $e) {
        $msg = $e->getMessage();
    }

    // 以下、共通パーツ定数
    const TITLE = "Register";
    const NOT_SHOW_AUTH = "Register";
    ?>

<?php include(dirname(__FILE__).'/../assets/_inc/header.php'); ?>

            <div class="center-login">
                <h1><?php echo $msg; ?></h1>
                <?php echo $link; ?>
            </div>
        </div>
    </body>
</html>