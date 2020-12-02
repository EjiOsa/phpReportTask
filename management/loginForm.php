<?php
    session_start();
    $errorMassage = "";
    if(isset($_SESSION["login_message"])){
        $errorMassage = $_SESSION["login_message"];
    }

    // 以下、共通パーツ定数
    define("TITLE" ,"Login Form");
    define("NOT_SHOW_AUTH" ,"Login Form");
    ?>

<?php include(dirname(__FILE__).'/../assets/_inc/header.php'); ?>


<h1>ログインページ</h1>
<h2><?php echo $errorMassage; ?></h2>
<form action="login" method="post">
<div>
    <label>メールアドレス：<label>
    <input type="text" name="email" required>
</div>
<div>
    <label>パスワード：<label>
    <input type="password" name="password" required>
</div>
<input type="submit" value="ログイン">
</form>
</body>
</html>