<?php
    session_start();
    // セッション削除と破壊
    $_SESSION = array();
    session_destroy();
    
    // 以下、header内で使用する定数
    const TITLE = "LogOut";
    const NOT_SHOW_AUTH = "LogOut";
    ?>

<?php include(__DIR__.'/../assets/_inc/header.php'); ?>

            <div class="center login">
                <h2 class="margin-btm-10">ログアウトしました。</h2>
                <div>
                    <a href="/phpReportTask">TOPへ</a>
                </div>
            </div>
        </main>
    </body>
</html>