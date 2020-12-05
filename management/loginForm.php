<?php
    session_start();
    $errorMassage = "";
    if(isset($_SESSION["login_message"])){
        $errorMassage = $_SESSION["login_message"];
    }

    // 以下、header内で使用する定数
    const TITLE = "Login Form";
    const NOT_SHOW_AUTH = "Login Form";
    ?>

<?php include(dirname(__FILE__).'/../assets/_inc/header.php'); ?>


            <div class="center login">
                <section class="">
                    <h1>ログイン</h1>
                    <h3><?php echo $errorMassage; ?></h3>
                </section>

                <section class="manage-form">
                    <form action="login.php" method="post">
                        <div class="margin-btm-10">
                            <label>メールアドレス：<label>
                            <input type="text" name="email" size="28" required>
                        </div>
                        <div class="margin-btm-10">
                            <label>パスワード：<label>
                            <input type="password" name="password" required>
                        </div>
                        <div class="manage-btn">
                            <button>ログイン</button>
                        </div>
                    </form>
                </section>
            </div>
        </main>
    </body>
</html>