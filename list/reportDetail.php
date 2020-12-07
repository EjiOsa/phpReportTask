<?php
    session_start();
    // 未ログイン時のリダイレクト処理
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
                <h1>
                    Report Detail
                </h1>
            </section>

            <section class="center">
                <h2 class="margin-btm-10">報告書</h2>
            </section>

            <section>
                <div class="center">
                    <div class"confirm">
                        <div class="create block-left">
                            <label>登録日時:</label>
                            <p class="margin-left-20"><?php echo date('Y/m/d H:i', strtotime($selectDetailResult['created_at'])) ?></p>
                        </div>
                        
                        <div class="create">
                            <label>報告書タイトル:</label>
                            <p class="margin-left-20 confirm-title"><?php echo $selectDetailResult['title'] ?></p>
                        </div>
                    
                        <hr>
                        <div class="create">
                            <label>報告書内容:</label>
                            <p class="margin-left-20"><?php echo nl2br($selectDetailResult['body']) ?></p>
                        </div>
                    
                        <hr>
                        <?php if($selectAttachmentResult) : ?>
                            <?php foreach($selectAttachmentResult as $attachment) :?>
                                <form action="download" method="POST">
                                    <div>
                                        <div class="attachment-title margin-btm-10">ファイル名：<?php echo $attachment["file_name"] ?></div>
                                        <a class="margin-left-20" href="<?php echo $attachment["file_path"] ?>" target="_blank">添付ファイルを表示</a>
                                        <a role="button" href="<?php echo $attachment['file_path'] ?>" download="<?php echo $attachment["file_name"] ?>"><?php echo $attachment["file_name"] ?>をダウンロード</a>
                                    </div>
                                    <br>
                                </form>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <br>
                            
                        <div class="create block-left">
                            <label>報告者:</label>
                            <p class="margin-left-20"><?php echo $selectDetailResult['user_name'] ?></p>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <footer class="site-footer footer-btn">
            <div class="">
                <a href="#" onClick="window.close();" role="button" class="">Close</a>
            </div>
            <div></div>
        </footer>
    </body>
</html>