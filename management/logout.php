<?php
    session_start();
    $_SESSION = array();//セッションの中身をすべて削除
    session_destroy();//セッションを破壊
    
    // 以下、共通パーツ定数
    define("TITLE" ,"LogOut");
    define("NOT_SHOW_AUTH" ,"LogOut");
    ?>

<?php include(dirname(__FILE__).'/../assets/_inc/header.php'); ?>

<p>ログアウトしました。</p>
<a href="/phpReportTask">TOPへ</a>

</body>
</html>