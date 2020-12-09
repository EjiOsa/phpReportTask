<?php
    session_start();
    //　未ログイン時のリダイレクト処理
    if(!isset($_SESSION['id']) || !isset($_SESSION['name'])){
        $_SESSION["login_message"] = "報告書一覧の閲覧にはログインが必要です。<br>";
        header('Location: http://'.$_SERVER['HTTP_HOST'].'/phpReportTask/management/loginForm.php');
        exit();
    }
    //エスケープ処理
    function h($s) {
        return htmlspecialchars($s, ENT_QUOTES, "UTF-8");
    }

    include(__DIR__.'/../assets/_inc/const.php');
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

<?php include(__DIR__.'/../assets/_inc/header.php'); ?>

            <section>
                <h1>
                    Report List
                </h1>
            </section>

            <section>
                <div class="search-area margin-btm-10">
                    <div class="create width-flex">
                        <label class="">報告書タイトル検索:</label>
                        <input id="search-title-input" class="margin-left-20" name="title" type="text" size="20" placeholder="タイトル検索文言"/>
                    </div>
                    <div class="create width-flex">
                        <label class="">報告書内容検索:</label>
                        <input id="search-body-input" class="margin-left-20" name="body" type="text" size="60" placeholder="本文検索文言"/>
                    </div>
                
                    <div class="search-btn">
                        <button id="js-list-search" name="narrow"> <i class="fas fa-search"></i> 検索 </button>
                    </div>
                    <div class="clear-btn">
                        <a href="/phpReportTask/list/reportList.php" role="button" class="">クリア</a>
                    </div>
                </div>
            </section>

            <section>
                <form action="reportDetail.php" method="GET">
                    <table class="report_table">
                        <thead class="">
                            <th class="" scope="col" style="white-space:nowrap; width:20%;">
                                報告書タイトル
                                <button id="sort-title-id" data-id="title" name="sortTitle" class="js-list-sort" data-value="ASC" type="button">
                                    <i class="fas fa-sort"></i>
                                </button>
                            </th>
                            <th style="width:37%;">
                                報告書
                                <button id="sort-body-id" data-id="body" name="sortBody" class="js-list-sort" data-value="ASC" type="button">
                                    <i class="fas fa-sort"></i>
                                </button>
                            </th>
                            <th class="back1 " scope="col" style="white-space:nowrap;  width:15%;">
                                報告書作成者
                                <button id="sort-user_name-id" data-id="user_name" name="sortName" class="js-list-sort" data-value="ASC" type="button">
                                    <i class="fas fa-sort"></i>
                                </button>
                            </th>
                            <th style="width:14%;">
                                作成日時
                                <button id="sort-created_at-id" data-id="created_at" name="sortDate" class="js-list-sort" data-value="ASC" type="button">
                                    <i class="fas fa-sort-numeric-down"></i>
                                </button>
                            </th>
                            <th style="width:6%;" class="">添付</th>
                            <th style="width:8%;"></th>
                        </thead>
                        <tbody id="report-list">
                            <?php foreach($selectReportResult as $report) :?>
                            <tr>
                                <td><?php echo h($report['title']) ?></td>
                                <td><?php if(mb_strlen($report['body']) > 100) { 
                                                $body = mb_substr(h($report['body']),0,100);
                                                echo nl2br($body. '...');
                                            } else {
                                                echo h($report['body']);
                                            } ?></td>
                                <td><?php echo h($report['user_name']) ?></td>
                                <td><?php echo date('Y/m/d H:i', strtotime($report['created_at'])) ?></td>
                                <td>
                                    <?php if($report['attachment_flg']): ?>
                                    <p style="color: gray; font-size: 20px;"><i class="far fa-file"></i></p>
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
        </main>

        <footer class="site-footer footer-btn">
            <div></div>
            <div>
                <a class="margin-left-80rev" href="/phpReportTask" role="button">TOPへ</a>
            </div>
        </footer>
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
                    url: '../ajax/listSearch.php',
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
                                str += '<p style="color: gray; font-size: 20px;"><i class="far fa-file"></i></p>';
                            }
                                str += '</td><td><button name="detail" class="btn btn-outline-success" formtarget="_blank" value="'
                                + data[i].id
                                + '"> 詳細 </button></td></tr>';
                            }
                        $('#report-list').html(str);
                        $('#report-list').parent().parent().find("h3").empty();
                    }else{
                        $('#report-list').empty();
                        $('#report-list').parent().parent().find("h3").empty();
                        const ZERO = '<h3 class="zero-str">検索結果が0件です。</h3>'
                        $('#report-list').parent().parent().append(ZERO);
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
            const SORT = '<i class="fas fa-sort"></i>';
            const ALPHA_DOWN = '<i class="fas fa-sort-alpha-down"></i>';
            const ALPHA_UP = '<i class="fas fa-sort-alpha-down-alt"></i>';
            const NUM_DOWN = '<i class="fas fa-sort-numeric-down"></i>';
            const NUM_UP = '<i class="fas fa-sort-numeric-down-alt"></i>';
            jsListSort.on('click', function (e) {
                const sortId = e.currentTarget.dataset['id'];
                const TITLE = $('#sort-title-id');
                const BODY = $('#sort-body-id');
                const NAME = $('#sort-user_name-id');
                const DATE = $('#sort-created_at-id');
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
                    url: '../ajax/listSort.php',
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
                                str += '<p style="color: gray; font-size: 20px;"><i class="far fa-file"></i></p>';
                            }
                                str += '</td><td><button name="detail" class="btn btn-outline-success" formtarget="_blank" value="'
                                + data[i].id
                                + '"> 詳細 </button></td></tr>';
                            }
                        $('#report-list').html(str);
                        jsListSort.attr('data-value',"ASC");
                        e.currentTarget.dataset['value'] = sortValueAfter;
                        // ソートアイコンの更新処理
                        jsListSort.empty();
                        sortId != "title" ? TITLE.append(SORT) : TITLE.attr('data-value') == "ASC" ? TITLE.append(ALPHA_DOWN) : TITLE.append(ALPHA_UP);
                        sortId != "body" ? BODY.append(SORT) : BODY.attr('data-value') == "ASC" ? BODY.append(ALPHA_DOWN) : BODY.append(ALPHA_UP);
                        sortId != "user_name" ? NAME.append(SORT) : NAME.attr('data-value') == "ASC" ? NAME.append(ALPHA_DOWN) : NAME.append(ALPHA_UP);
                        sortId != "created_at" ? DATE.append(SORT) : DATE.attr('data-value') == "ASC" ? DATE.append(NUM_DOWN) : DATE.append(NUM_UP);
                        $('#report-list').parent().find("h3").empty();
                    }else{
                        $('#report-list').empty();
                        $('#report-list').parent().parent().find("h3").empty();
                        const ZERO = '<h3 class="zero-str">検索結果が0件です。</h3>'
                        $('#report-list').parent().parent().append(ZERO);
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
