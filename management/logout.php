<?php
    session_start();
    $_SESSION = array();//セッションの中身をすべて削除
    session_destroy();//セッションを破壊
    
    // 以下、header内で使用する定数
    const TITLE = "LogOut";
    const NOT_SHOW_AUTH = "LogOut";
    ?>

<?php include(dirname(__FILE__).'/../assets/_inc/header.php'); ?>

            <div class="center login">
                <h2 class="margin-btm-10">ログアウトしました。</h2>
                <div>
                    <a href="/phpReportTask">TOPへ</a>
                </div>
            </div>
        </main>
    </body>
</html>