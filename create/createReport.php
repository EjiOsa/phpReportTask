<?php
    session_start();
    if(!isset($_SESSION['id']) || !isset($_SESSION['name'])){
        $_SESSION['message_error'] = "報告書作成はログインが必要です。<br>";
        header('Location: http://'.$_SERVER['HTTP_HOST'].'/phpReport/management/loginForm');
        exit();
    }

    // 以下、共通パーツ定数
    define("TITLE" ,"Create Report");
    $path = "..";
    ?>

<?php include(dirname(__FILE__).'/../assets/_inc/header.php'); ?>

    <nav aria-label="breadcrumb" class="row d-flex align-items-start">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active font-weight-bold text-primary" aria-current="page">Create</li>
            <li class="breadcrumb-item" aria-current="page">Confirm</li>
            <li class="breadcrumb-item" aria-current="page">Finish</li>
        </ol>
    </nav>
    <div class="row">
        <h1 class="display-5 text-secondary font-weight-lighter">
            Create Report
        </h1>
    </div>

    <!-- 入力フォームエリア -->
    <h2 class="row justify-content-center">報告書作成</h2>
    <form action="confirmReport" method="POST" enctype="multipart/form-data">
        <div class="form-group"></div>
            <label class="col-form-label-lg font-weight-bold">報告書タイトル</label>
            <input id="input-title" class="form-control" type="text" name="title" maxlength="50">
        </div>
        <div class="form-group">
            <label class="col-form-label-lg font-weight-bold">報告内容</label>
            <textarea id="input-body" class="form-control" rows="10" name="body" maxlength="2500"></textarea>
        </div>
        <!-- ファイル選択部分 -->
        <div class="form-group">
            <label class="control-label font-weight-bold">添付（複数可）</label>
            <input type="hidden" name="max_file_size" value="5000000" />
            <input type="file" id="file" name="attachment[]" class="form-control-file" multiple>
        </div>
        <button id="confirm-btn" class="btn btn-outline-primary btn-lg float-right" name="confirm" <?php if(!isset($_SESSION["confirm"])){echo 'disabled="true"';} ?> >確認</button>
    </form>
    <input id="savedata" type="hidden" name="max_file_size" value="" />
    <form action="/phpReport" method="GET">
        <button class="btn btn-outline-primary btn-lg">戻る</button>
    </form>
    </div>
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