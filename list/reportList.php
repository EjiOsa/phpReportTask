<?php
    session_start();
    if(!isset($_SESSION['id']) || !isset($_SESSION['name'])){
        $_SESSION["login_message"] = "報告書一覧の閲覧にはログインが必要です。<br>";
        header('Location: http://'.$_SERVER['HTTP_HOST'].'/phpReportTask/management/loginForm.php');
        exit();
    }

    include(dirname(__FILE__).'/../assets/_inc/const.php');
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

    // 以下、header内で使用する定数
    const TITLE = "Report List";
    $path = "..";
    ?>

<?php include(dirname(__FILE__).'/../assets/_inc/header.php'); ?>

        <section>
            <div class="">
                <h1 class="">
                    Report List
                </h1>
            </div>
        </section>

        <section>
            <div class="">
                絞り込み機能<br>
            </div>

            <div class="">
                <div class="">
                    <label class="">報告書タイトル:</label>
                    <input id="search-title-input" class="" name="title" type="text" placeholder="タイトル検索文言"/>
                </div>
                <div class="">
                    <label class="">報告書内容:</label>
                    <input id="search-body-input" class="" name="body" type="text" placeholder="本文検索文言"/>
                </div>
            </div>
                <div class="">
                    <a href="/phpReportTask/list/reportList.php" role="button" class="">クリア</a>
                </div>
                <div class="">
                    <button id="js-list-search" name="narrow"> 絞り込み </button>
                </div>
        </section>

        <section>
            <form action="reportDetail.php" method="GET">
                <table class="" style="table-layout:fixed;">
                    <thead class="">
                        <th class="" scope="col" style="white-space:nowrap; width:20%;">
                            報告書タイトル
                            <button id="sort-title-id" data-id="title" name="sortTitle" class="js-list-sort" data-value="ASC" type="button">ソート</button>
                        </th>
                        <th style="width:37%;">
                            報告書
                            <button id="sort-body-id" data-id="body" name="sortBody" class="js-list-sort" data-value="ASC" type="button">ソート</button>
                        </th>
                        <th class="back1 " scope="col" style="white-space:nowrap;  width:15%;">
                            報告書作成者
                            <button id="sort-user_name-id" data-id="user_name" name="sortName" class="js-list-sort" data-value="ASC" type="button">ソート</button>
                        </th>
                        <th style="width:14%;">
                            作成日時
                            <button id="sort-created_at-id" data-id="created_at" name="sortDate" class="js-list-sort" data-value="ASC" type="button">ソート</button>
                        </th>
                        <th style="width:6%;" class="">添付</th>
                        <th style="width:8%;"></th>
                    </thead>
                    <tbody id="report-list">
                        <?php foreach($selectReportResult as $report) :?>
                        <tr>
                            <td><?php echo $report['title'] ?></td>
                            <td><?php if(mb_strlen($report['body']) > 100) { 
                                            $body = mb_substr($report['body'],0,100);
                                            echo nl2br($body. '...');
                                        } else {
                                            echo $report['body'];
                                        } ?></td>
                            <td><?php echo $report['user_name'] ?></td>
                            <td><?php echo date('Y/m/d H:i', strtotime($report['created_at'])) ?></td>
                            <td>
                                <?php if($report['attachment_flg']): ?>
                                    あり
                                    <p class="">
                                        <i class=""></i>
                                    </p>
                                <?php endif;?> 
                            </td>
                            <td>
                                <button name="detail" class="" formtarget="_blank" value="<?php echo $report['id']?>"> 詳細 </button></td>
                        </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
            </form>
        </section>
        <section>
            <div class="">
                <a href="/phpReportTask" role="button" class="">TOPへ</a>
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
        let jsListSearch = $('#js-list-search');
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
                            + omittedContent(data[i].body)
                            + '</td><td>'
                            + data[i].user_name
                            + '</td><td>'
                            + data[i].created_at.replace(/-/g,'/').slice(0, -3)
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
                            + omittedContent(data[i].body)
                            + '</td><td>'
                            + data[i].user_name
                            + '</td><td>'
                            + data[i].created_at.replace(/-/g,'/').slice(0, -3)
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
        function omittedContent(string) {
            // 絞り込みとソートはjsで最大文字列調整
            const MAX_LENGTH = 100;
            if (string.length > MAX_LENGTH) {
                return string.substr(0, MAX_LENGTH) + '...';
            }
            return string;
        }
    });
    </script>
</body>
</html>
