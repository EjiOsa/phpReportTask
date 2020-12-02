<?php
// 以下、共通パーツ定数
    define("TITLE" ,"SignUp");
    define("NOT_SHOW_AUTH" ,"SignUp");
    ?>

<?php include(dirname(__FILE__).'/../assets/_inc/header.php'); ?>

<h1>新規会員登録</h1>
<form action="register.php" method="post">
<div>
    <label>名前：<label>
    <input type="text" name="name" required>
</div>
<div>
    <label>メールアドレス：<label>
    <input type="text" name="email" required>
</div>
<div>
    <label>パスワード：<label>
    <input type="password" name="pass" required>
</div>
<input type="submit" value="新規登録">
</form>
</body>
</html>