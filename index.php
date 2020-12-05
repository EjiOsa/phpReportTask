<?php
    session_start();
    if(isset($_SESSION["confirm"])){
        unset($_SESSION["confirm"]);
    }
    if(isset($_SESSION["login_message"])){
        unset($_SESSION["login_message"]);
    }
    // 以下、共通パーツ定数
    const TITLE = "Report TOP";
    $path = ".";
?>
<?php include(dirname(__FILE__).'/assets/_inc/header.php'); ?>

            <div class="center top">
                <section class="">
                    <h1 class="index">
                        Report Create&View TOP
                    </h1>
                </section>

                <section class="top-btn-flex">
                    <div class="create-report">
                        <form action="create/createReport.php" method="GET">
                            <button class=""> Create Report </button>
                        </form>
                    </div>
                    <div class="report-list">
                        <form action="list/reportList.php" method="GET">
                            <button class="">Report List</button>
                        </form>
                    </div>
                </section>
            </div>
        </div>
    </body>
</html>