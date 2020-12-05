<?php
    // 登録用データ
    session_start();
    if(!isset($_SESSION['title']) || !isset($_SESSION['body'])){
        $_SESSION['login_message'] = "報告書作成画面から開始してください。<br>";
        header('Location: http://'.$_SERVER['HTTP_HOST'].'/phpReportTask/create/createReport.php');
        exit();
    }
    $title = $_SESSION["title"];
    $body = $_SESSION["body"];
    $name = $_SESSION["name"];
    $attachmentFlg = 0;
    if (isset($_SESSION["attachments"])){
        // 添付ファイルの有無Flag
        $attachmentFlg = 1;
    }

    include(dirname(__FILE__).'/../assets/_inc/const.php');
    try {
        $dbh = new PDO(DB_DSN,DB_USER, DB_PASSWORD, $options);
        // SQLエラーの表示設定
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // 報告書のDB登録
        $insertReportSql = "INSERT INTO reports(
                            title,body,user_name,attachment_flg,created_at,updated_at)
                        VALUES (
                            :title,:body,:name,'$attachmentFlg','$date','$date')";
        $insertReportStmt = $dbh->prepare($insertReportSql);
        $insertReportStmt -> bindParam(':title',$title);
        $insertReportStmt -> bindParam(':body',$body);
        $insertReportStmt -> bindParam(':name',$name);
        $insertReportStmt -> execute();
        $parentId = $dbh->lastInsertId();

        if ($attachmentFlg){     
            // 添付ファイル情報の登録
            $key = 'attachments';
            $insertAttachmentSql = "INSERT INTO attachments(
                                    parent_id,file_name,file_path,attachment_key,created_at,updated_at)
                                VALUES(
                                    :parent_id,:file_name,:file_path,'$key','$date','$date')";
            $insertAttachmentStmt = $dbh->prepare($insertAttachmentSql);
            foreach($_SESSION["attachments"] as $attachment){
                $insertAttachmentStmt -> execute(array(
                                            ':parent_id'=>$parentId,
                                            ':file_name'=>$attachment["name"],
                                            ':file_path'=>$attachment["path"]
                                        ));
                // ファイルの移動
                rename("../storage/attachment/tmp/".$attachment["newName"],$attachment["path"]);
            }
        }
    } catch (PDOException $e) {
        echo 'DB接続エラー！: ' . $e->getMessage();
    }
    // PDO開放
    $dbh = null;

    // 他の一時ファイルの削除
    array_map('unlink', array_filter((array) glob("../storage/attachment/tmp/*")));

    // セッション削除
    unset($_SESSION["title"]);
    unset($_SESSION["body"]);
    unset($_SESSION["attachments"]);
    unset($_SESSION["confirm"]);

    // 以下、共通パーツ定数
    const TITLE = "Create Finish";
    $path = "..";
    ?>

<?php include(dirname(__FILE__).'/../assets/_inc/header.php'); ?>

            <section>
                <h1 class="">
                    Create Finish
                </h1>
                <nav aria-label="breadcrumb" class="">
                    <nl class="breadcrumb">
                        <li class="breadcrumb-item">Create</li>
                            <i class="fas fa-angle-double-right"></i>
                        <li class="breadcrumb-item">Confirm</li>
                            <i class="fas fa-angle-double-right"></i>
                        <li class="breadcrumb-item-current">Finish</li>
                    </nl>
                </nav>
            </section>

            <div class="center finish">
                <div class="margin-btm-10">
                    <h2>
                        報告書の登録が完了しました。
                    </h2>
                </div>
                <div class="">
                    <a class="" href="/phpReportTask">TOPへ戻る</a>
                </div>
            </div>
        </main>
    </body>
</html>