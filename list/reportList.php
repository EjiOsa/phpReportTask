<?php
    session_start();
    if(!isset($_SESSION['id']) || !isset($_SESSION['name'])){
        $_SESSION["message_error"] = "報告書一覧の閲覧にはログインが必要です。<br>";
        header('Location: http://'.$_SERVER['HTTP_HOST'].'/phpReportTask/management/loginForm');
        exit();
    }
    // PDO用の定数と変数宣言
    define('DB_DSN', 'mysql:dbname=report_project;host=localhost');
    define('DB_USER', 'root');
    define('DB_PASSWORD', 'root');
    $options = array(PDO::MYSQL_ATTR_INIT_COMMAND=>"SET CHARACTER SET 'utf8'");
    try {
        $dbh = new PDO(DB_DSN,DB_USER, DB_PASSWORD, $options);
        // SQLエラーの表示設定
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // 報告書取得
        $selectReportSql = "SELECT * FROM reports ORDER BY 'created_at'";
        $selectReportStmt = $dbh->prepare($selectReportSql);
        $selectReportStmt -> execute();
        $selectReportResult = $selectReportStmt->fetchAll();
    } catch (PDOException $e) {
        echo 'DB接続エラー！: ' . $e->getMessage();
    }
    // PDO開放
    $dbh = null;

    // 以下、共通パーツ定数
    define("TITLE" ,"Report List");
    $path = "..";
    ?>

<?php include(dirname(__FILE__).'/../assets/_inc/header.php'); ?>

<div class="container">
        <div class="row">
            <h1 class="display-5 text-secondary font-weight-lighter">
                Report List
            </h1>
        </div>

        <div class="row font-weight-bold h5">
            絞り込み機能<br>
        </div>

            <div class="form-row">
                <div class="form-group col-4">
                    <label class="">報告書タイトル:</label>
                    <input id="search-title-input" class="form-control " name="title" type="text" placeholder="タイトル検索文言"/>
                </div>
                <div class="form-group col-4">
                    <label class="">報告書内容:</label>
                    <input id="search-body-input" class="form-control" name="body" type="text" placeholder="本文検索文言"/>
                </div>
            </div>
                <div class="m-4 clearfix float-right">
                    <a href="/phpReport/list/reportList" role="button" class="links btn btn-outline-info">クリア</a>
                </div>
                <div class="my-4 clearfix float-right">
                    <button class="btn btn-outline-info js-list-search" name="narrow"> 絞り込み </button>
                </div>

        <form action="reportDetail" method="GET">
        <table class="table" style="table-layout:fixed;">
            <thead class="thead-light">
                <th class="back1 " scope="col" style="white-space:nowrap; width:20%;">
                    報告書タイトル
                    <button id="sort-title-id" data-id="title" name="sortTitle" class="btn btn-outline-success js-list-sort" data-value="ASC" type="button">ソート</button>
                </th>
                <th style="width:37%;">
                    報告書
                    <button id="sort-body-id" data-id="body" name="sortBody" class="btn btn-outline-success js-list-sort" data-value="ASC" type="button">ソート</button>
                </th>
                <th class="back1 " scope="col" style="white-space:nowrap;  width:15%;">
                    報告書作成者
                    <button id="sort-user_name-id" data-id="user_name" name="sortName" class="btn btn-outline-success js-list-sort" data-value="ASC" type="button">ソート</button>
                </th>
                <th style="width:14%;">
                    作成日時
                    <button id="sort-created_at-id" data-id="created_at" name="sortDate" class="btn btn-outline-success js-list-sort" data-value="ASC" type="button">ソート</button>
                </th>
                <th style="width:6%;" class="text-center">添付</th>
                <th style="width:8%;"></th>
            </thead>
            <tbody id="report-list">
                <?php foreach($selectReportResult as $report) :?>
                    
                        <tr>
                            <td><?php echo $report['title'] ?></td>
                            <td><?php if(mb_strlen($report['body']) > 100) { 
                                            $body = mb_substr($report['body'],0,100);
                                            echo nl2br($body. '･･･');
                                        } else {
                                            echo $report['body'];
                                        } ?></td>
                            <td><?php echo $report['user_name'] ?></td>
                            <td><?php echo date('Y/m/d H:i', strtotime($report['created_at'])) ?></td>
                            <td>
                                <?php if($report['attachment_flg']): ?>
                                    あり
                                    <p style="color: gray; font-size: 20px;" class="text-center">
                                        <i class="far fa-file"></i>
                                    </p>
                                <?php endif;?> 
                            </td>
                            <td>
                                <button name="detail" class="btn btn-outline-success" formtarget="_blank" value="<?php echo $report['id']?>"> 詳細 </button></td>
                        </tr>
                    
                <?php endforeach;?>
            </tbody>
        </table>
        </form>
        <div class="">
            <a href="/phpReport" role="button" class="links btn btn-outline-info btn-lg">TOPへ</a>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
    $(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        let jsListSearch = $('.js-list-search');
        let searchTitle;
        let searchBody;
        jsListSearch.on('click', function () {
            searchTitle = $('#search-title-input').val();
            searchBody = $('#search-body-input').val();
            $.ajax({
                url: '../ajax/listSearch',
                type: 'POST',
                data: {
                    'searchTitle': searchTitle,
                    'searchBody': searchBody
                    },
                dataType:"json"
                })
                // Ajaxリクエストが成功した場合
            .done(function (data) {
                if(data.length != 0){
                    let str = "";
                    for(i=0; data.length>i; i++){
                        str += '<tr><td>'
                            + data[i].title 
                            + '</td><td>'
                            + data[i].body
                            + '</td><td>'
                            + data[i].user_name
                            + '</td><td>'
                            + data[i].created_at
                            + '</td><td>';
                        if(data[i].attachment_flg == 1){
                            str += 'あり<p style="color: gray; font-size: 20px;" class="text-center"><i class="far fa-file"></i></p>';
                        }
                            str += '</td><td><button name="detail" class="btn btn-outline-success" formtarget="_blank" value="'
                            + data[i].id
                            + '"> 詳細 </button></td></tr>';
                        }
                    $('#report-list').html(str);
                }else{
                    var zeroStr = '<div>検索結果が0件です。</div>'
                    $('#report-list').html(zeroStr);
                }
            })
            // Ajaxリクエストが失敗した場合
            .fail(function (data, xhr, err) {
                alert("ajax fail");
                console.log('エラー');
                console.log(err);
                console.log(xhr);
            });
        });

        let jsListSort = $('.js-list-sort');
        jsListSort.on('click', function (e) {
            const sortId = e.currentTarget.dataset['id'];
            let sortValueBefore = e.currentTarget.dataset['value'];
            let sortValueAfter = "";
            if(sortValueBefore == "ASC"){
                sortValueAfter = "DESC";
            }else{
                sortValueAfter = "ASC";
            }

            const order = "ORDER BY "+sortId+" "+sortValueAfter;
            const targetId = $('#sort-'+sortId+'-id');
            
            $.ajax({
                url: '../ajax/listSort',
                type: 'POST',
                data: {
                    'searchTitle': searchTitle,
                    'searchBody': searchBody,
                    'order': order
                    },
                dataType:"json"
                })
                // Ajaxリクエストが成功した場合
            .done(function (data) {
                if(data.length != 0){
                    let str = "";
                    for(i=0; data.length>i; i++){
                        str += '<tr><td>'
                            + data[i].title 
                            + '</td><td>'
                            + data[i].body
                            + '</td><td>'
                            + data[i].user_name
                            + '</td><td>'
                            + data[i].created_at
                            + '</td><td>';
                        if(data[i].attachment_flg == 1){
                            str += 'あり<p style="color: gray; font-size: 20px;" class="text-center"><i class="far fa-file"></i></p>';
                        }
                            str += '</td><td><button name="detail" class="btn btn-outline-success" formtarget="_blank" value="'
                            + data[i].id
                            + '"> 詳細 </button></td></tr>';
                        }
                    $('#report-list').html(str);
                    jsListSort.attr('data-value',"ASC");
                    e.currentTarget.dataset['value'] = sortValueAfter;
                }else{
                    var zeroStr = '<div>検索結果が0件です。</div>'
                    $('#report-list').html(zeroStr);
                }
            })
            // Ajaxリクエストが失敗した場合
            .fail(function (data, xhr, err) {
                alert("ajax fail");
                console.log('エラー');
                console.log(err);
                console.log(xhr);
            });
        });
    });
    </script>
</body>
</html>
