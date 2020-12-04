<?php
    session_start();
    $_SESSION['confirm'] = true;
    if((!isset($_POST['title']) || !isset($_POST['body']))
        || (!($_POST['title']) || !($_POST['body'])) ){
        $_SESSION['login_message'] = "報告書作成画面から開始してください。<br>";
        header('Location: http://'.$_SERVER['HTTP_HOST'].'/phpReportTask/create/createReport.php');
        exit();
    }
    $_SESSION['title'] = $_POST['title'];
    $_SESSION['body'] = $_POST['body'];
    $uploaded = false;
    // セッション削除(ブラウザバック対応)
    unset($_SESSION["attachments"]);
    unset($_SESSION["login_message"]);

    if(isset($_FILES["attachment"]["name"])){
        for($i = 0; $i < count($_FILES["attachment"]["name"]); $i++ ){
            if(is_uploaded_file($_FILES["attachment"]["tmp_name"][$i])){
                $fileData = pathinfo($_FILES["attachment"]["name"][$i]);
                $newName = $fileData["filename"]."_".uniqid().".".$fileData["extension"];
                move_uploaded_file($_FILES["attachment"]["tmp_name"][$i], "../storage/attachment/tmp/".$newName);

                $_FILES["attachment"]["newName"][$i] = $newName;
                $attachmentArray["newName"] = $newName;
                $attachmentArray["path"] = "../storage/attachment/".$newName;
                $attachmentArray["name"] = $_FILES["attachment"]["name"][$i];
                $_SESSION["attachments"][$newName] = $attachmentArray;
                $uploaded = true;
            }else{
            //エラー確認用（コピーに失敗（だいたい、ディレクトリがないか、パーミッションエラー））
            // echo "error while saving.";
            }
        }
    }else{
        //エラー確認用（ファイルが来ていない）
        // echo "file not uploaded.";
    }
    
    // 以下、共通パーツ定数
    const TITLE = "Confirm Report";
    $path = "..";
    ?>

<?php include(dirname(__FILE__).'/../assets/_inc/header.php'); ?>

        <section>
            <div class="">
                <h1 class="">
                    Confirm Report
                </h1>
            </div>
            <nav aria-label="breadcrumb" class="">
                <ol class="">
                    <li class="" aria-current="page">Create</li>
                    <li class="" aria-current="page">Confirm</li>
                    <li class="" aria-current="page">Finish</li>
                </ol>
            </nav>
        </section>

        <section>
            <!-- 投稿確認エリア -->
            <h2 class="">報告書</h2>
            <div class=""><h1 class=""></h1></div>
            <div class="">
                <div class=''>
                    <label class="">報告書タイトル:</label>
                    <p class=""><?php echo $_SESSION['title'] ?></p>
                </div>
            
                <hr class="">
                <div class="">
                    <label class="">報告書内容:</label>
                    <p class=""><?php echo nl2br($_SESSION['body']) ?></p>
                </div>
            
                <hr class="">
            
                <?php if($uploaded) : ?>
                    <?php for($i = 0; $i < count($_FILES["attachment"]["name"]); $i++ ) :?>
                        <div class="">
                            <div class="">ファイル名：<?php echo $_FILES["attachment"]["name"][$i] ?></div>
                            <a class="" href="<?php echo '../storage/attachment/tmp/'.$_FILES["attachment"]["newName"][$i] ?>" target="_blank">アップロードファイルを表示</a>
                            <button class="js-attachment-delete" name="delete" value="<?php echo $_FILES["attachment"]["newName"][$i] ?>"> <?php echo $_FILES["attachment"]["name"][$i] ?>を削除</button>
                        </div>
                        <br>
                    <?php endfor; ?>
                <?php endif; ?>
                <br>
                    
                <div class="">
                    <div class=""></div>
                    <label class="">報告者:</label>
                    <p class=""><?php echo $_SESSION['name'] ?></p>
                </div>
            </div>
                    <form action="createFinish.php" method="POST">
                        <button class="" name="insert"> 登録 </button>
                    </form>
                    <div>
                        <a href="javascript:history.back()" role="button"
                        class="">戻る</a>
                    </div>
        </section>
        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script>
            $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            let attachmentDelete = $('.js-attachment-delete');
            let attachmentName;
            attachmentDelete.on('click', function () {
                var deleteConfirm = confirm('添付ファイルを削除してよろしいでしょうか？');
                    if(deleteConfirm == true) {
                        var $this = $(this);
                        attachmentName = $this.attr('value');
                        $.ajax({
                            url: '../ajax/delAttachment',
                            type: 'POST',
                            data: {
                                'attachmentName': attachmentName
                            },
                        })
                        // Ajaxリクエストが成功した場合
                        .done(function (data) {
                            $this.parent().next('br').remove();
                            $this.parent().remove();
                        })
                        // Ajaxリクエストが失敗した場合
                        .fail(function (data, xhr, err) {
                            alert("ajax fail");
                            console.log('エラー');
                            console.log(err);
                            console.log(xhr);
                        });
                return false;
                    }
                });
            });
        </script>
    </body>
</html>