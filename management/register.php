<?php
    //フォームからの値をそれぞれ変数に代入
    $name = $_POST['name'];
    $email = $_POST['email'];
    $pass = password_hash($_POST['pass'], PASSWORD_DEFAULT);

    include(__DIR__.'/../assets/_inc/const.php');
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
        if ($user['email'] === $email) {
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
            $userInsertStmt->bindValue(':email', $email);
            $userInsertStmt->bindValue(':password', $pass);
            $userInsertStmt->execute();
            $msg = '会員登録が完了しました';
            $link = '<a href="loginForm.php">ログインページ</a>';
        }
    } catch (PDOException $e) {
        $msg = $e->getMessage();
    }

    // 以下、共通パーツ定数
    const TITLE = "Register";
    const NOT_SHOW_AUTH = "Register";
    ?>

<?php include(__DIR__.'/../assets/_inc/header.php'); ?>

            <div class="center login">
                <h2  class="margin-btm-10"><?php echo $msg; ?></h2>
                <div>
                    <?php echo $link; ?>
                </div>
            </div>
        </main>
    </body>
</html>