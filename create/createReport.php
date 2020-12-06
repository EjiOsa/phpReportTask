<?php
    session_start();
    $errorMassage = "";
    if(isset($_SESSION["login_message"])){
        $errorMassage = $_SESSION["login_message"];
    }
    if(!isset($_SESSION['id']) || !isset($_SESSION['name'])){
        $_SESSION['login_message'] = "報告書作成はログインが必要です。<br>";
        header('Location: http://'.$_SERVER['HTTP_HOST'].'/phpReportTask/management/loginForm.php');
        exit();
    }

    // 以下、header内で使用する定数
    const TITLE = "Create Report";
    $path = "..";
    ?>

<?php include(dirname(__FILE__).'/../assets/_inc/header.php'); ?>

            <section>
                <h1>
                    Create Report
                </h1>
                <h2><?php echo $errorMassage; ?></h2>
                <nav aria-label="breadcrumb">
                    <nl class="breadcrumb">
                        <li class="breadcrumb-item-current">Create</li>
                            <i class="fas fa-angle-double-right"></i>
                        <li class="breadcrumb-item">Confirm</li>
                            <i class="fas fa-angle-double-right"></i>
                        <li class="breadcrumb-item">Finish</li>
                    </nl>
                </nav>
            </section>

            <section class="center">
                <h2 class="margin-btm-10">報告書作成</h2>
            </section>

            <section><!-- 入力フォームエリア -->
                <form id="create-form" action="confirmReport.php" method="POST" enctype="multipart/form-data">
                        <div class="center">
                            <div>
                                <div class="create">
                                    <label class="">報告書タイトル:</label>
                                    <input id="input-title" class="margin-btm-10 margin-left-20" type="text" name="title" maxlength="50" size="40">
                                </div>
                                <div class="create">
                                    <label class="">報告内容:</label>
                                    <textarea id="input-body" class="margin-btm-10 margin-left-20" rows="15" cols="60" name="body" maxlength="2500"></textarea>
                                </div>
                                <div class="create">
                                    <label class="">添付（複数可）</label><!-- ファイル選択部分 -->
                                    <input type="hidden" name="max_file_size" value="5000000" />
                                    <input type="file" id="file" name="attachment[]" class="margin-left-20" multiple>
                                </div>
                            </div>
                        </div>
                </form>
            </section>
        </main>
        
        <footer class="site-footer footer-btn">
            <button id="confirm-btn" form="create-form" class="" name="confirm" <?php if(!isset($_SESSION["confirm"])){echo 'disabled="true"';} ?> >確認</button>
            <form action="/phpReportTask" method="GET" class="">
                <button class="">戻る</button>
            </form>
        </footer>
        
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script>
            // 報告書タイトルと報告内容の入力チェック
            let titleTarget = $('#input-title');
            let bodyTarget = $('#input-body');
            let titleFlg = false;
            let bodyFlg = false;
            let confirmBtn = $('#confirm-btn');

            $(document).ready(function(){
                titleTarget.on('input',function(){
                    let val = $(this).val()
                    if(val == 0){
                        titleFlg = false;
                        alert('報告書タイトルは必須項目です。');
                        confirmBtn.prop('disabled', true);
                    }else if(val.length > 50){
                        titleFlg = false;
                        alert('報告書タイトルは50文字以内で記載してください。');
                        confirmBtn.prop('disabled', true);
                    }else if(val > 0 || val.length <= 10){
                        titleFlg = true;
                    }
                    if(titleFlg && bodyFlg){
                        // どちらも入力チェックを通過した場合にボタン活性
                        confirmBtn.prop('disabled', false);
                    }
                });
                bodyTarget.on('input',function(){
                    let val = $(this).val()
                    if(val == 0){
                        bodyFlg = false;
                        alert('報告内容は必須項目です。');
                        confirmBtn.prop('disabled', true);
                    }else if(val.length > 2500){
                        bodyFlg = false;
                        alert('報告内容は2500文字以内で記載してください。');
                        confirmBtn.prop('disabled', true);
                    }else if(val > 0 || val.length <= 2500){
                        bodyFlg = true;
                    }
                    if(titleFlg && bodyFlg){
                        // どちらも入力チェックを通過した場合にボタン活性
                        confirmBtn.prop('disabled', false);
                    }
                });
            });
        </script>
    </body>
</html>