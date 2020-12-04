<?php
    session_start();
    if(!isset($_SESSION['id']) || !isset($_SESSION['name'])){
        $_SESSION["login_message"] = "報告書詳細の閲覧にはログインが必要です。<br>";
        header('Location: http://'.$_SERVER['HTTP_HOST'].'/phpReportTask/management/loginForm.php');
        exit();
    }
    $id = $_GET["detail"];

    include(dirname(__FILE__).'/../assets/_inc/const.php');
    try {
        $dbh = new PDO(DB_DSN,DB_USER, DB_PASSWORD, $options);
        // SQLエラーの表示設定
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // 報告書取得
        $selectDetailSql = "SELECT * FROM reports WHERE id = :id";
        $selectDetailStmt = $dbh->prepare($selectDetailSql);
        $selectDetailStmt -> execute(array(
                            ':id'=>$id
                            ));
        $selectDetailResult = $selectDetailStmt->fetch();

        // 添付情報取得
        $selectAttachmentSql = "SELECT * FROM attachments WHERE parent_id = :id";
        $selectAttachmentStmt = $dbh->prepare($selectAttachmentSql);
        $selectAttachmentStmt -> execute(array(
            ':id'=>$id
            ));
        $selectAttachmentResult = $selectAttachmentStmt->fetchAll();
    } catch (PDOException $e) {
        echo 'DB接続エラー！: ' . $e->getMessage();
    }
    // PDO開放
    $dbh = null;

    // 以下、header内で使用する定数
    const TITLE = "Report Detail";
    const NOT_SHOW_AUTH = "Detail";
    $path = "..";
?>
<?php include(dirname(__FILE__).'/../assets/_inc/header.php'); ?>

        <section>
            <div class="">
                <h1 class="">
                    Report Detail
                </h1>
            </div>
        </section>

        <section>
            <h2 class="">報告書</h2>
            <div class="">
                <div class=""></div>
                <label class="">登録日時:</label>
                <p class=""><?php echo date('Y/m/d H:i', strtotime($selectDetailResult['created_at'])) ?></p>
            </div>

            <div class="">
                <label class="">報告書タイトル:</label>
                <p class=""><?php echo $selectDetailResult['title'] ?></p>
            </div>

            <hr class="">
            <div class="">
                <label class="">報告書内容:</label>
                <p class=""><?php echo nl2br($selectDetailResult['body']) ?></p>
            </div>

            <hr class="">
            <?php if($selectAttachmentResult) : ?>
                <?php foreach($selectAttachmentResult as $attachment) :?>
                    <form action="download" method="POST">
                    <div class="">
                        <div class="">ファイル名：<?php echo $attachment["file_name"] ?></div>
                        <a class="" href="<?php echo $attachment["file_path"] ?>" target="_blank">添付ファイルを表示</a>
                        <div><a class="" role="button" href="<?php echo $attachment['file_path'] ?>" download="<?php echo $attachment["file_name"] ?>"><?php echo $attachment["file_name"] ?>をダウンロード</a></div>
                    </div>
                    <br>
                    </form>
                <?php endforeach; ?>
            <?php endif; ?>
            <br>

            <div class="">
                <div class=""></div>
                <label class="">報告者:</label>
                <p class=""><?php echo $selectDetailResult['user_name'] ?></p>
            </div>
        </section>

        <section>
            <div class="">
                <a href="#" onClick="window.close();" role="button" class="">Close</a>
            </div>
        </section>
        </div>
    </body>
</html>