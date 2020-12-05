<?php
    session_start();
    $email = $_POST['email'];

    include(dirname(__FILE__).'/../assets/_inc/const.php');
    try {
        $dbh = new PDO(DB_DSN,DB_USER, DB_PASSWORD, $options);
        // SQLエラーの表示設定
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

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
            $link = '<a href="/phpReportTask">TOPへ</a>';
            if(isset($_SESSION["login_message"])){
                unset($_SESSION["login_message"]);
            }
        } else {
            $msg = 'メールアドレスもしくはパスワードが間違っています。';
            $link = '<a href="/phpReportTask/management/loginForm.php">ログインへ戻る</a>';
        }
    } catch (PDOException $e) {
        $msg = $e->getMessage();
    }

    // 以下、header内で使用する定数
    const TITLE = "Login";
    const NOT_SHOW_AUTH = "Login";
    ?>

<?php include(dirname(__FILE__).'/../assets/_inc/header.php'); ?>

            <div class="center login">
                <h2 class="margin-btm-10"><?php echo $msg; ?></h2>
                <div>
                    <?php echo $link; ?>
                </div>
            </div>
        </main>
    </body>
</html>