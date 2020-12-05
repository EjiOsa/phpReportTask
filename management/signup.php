<?php
// 以下、共通パーツ定数
    const TITLE = "SignUp";
    const NOT_SHOW_AUTH = "SignUp";
    ?>

<?php include(dirname(__FILE__).'/../assets/_inc/header.php'); ?>

            <div class="center login">
                <section class="">
                    <h1>新規会員登録</h1>
                </section>

                <section class="manage-form">
                    <form action="register.php" method="post">
                        <div class="margin-btm-10">
                            <label>名前：<label>
                            <input type="text" name="name" required>
                        </div>
                        <div class="margin-btm-10">
                            <label>メールアドレス：<label>
                            <input type="text" name="email" size="28" required>
                        </div>
                        <div class="margin-btm-10">
                            <label>パスワード：<label>
                            <input type="password" name="pass" size="25" required>
                        </div>
                        <div class="manage-btn">
                            <button>新規登録</button>
                        </div>
                    </form>
                </section>
            </div>
        </main>
    </body>
</html>