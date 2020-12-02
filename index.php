<?php
    session_start();
    if(isset($_SESSION["confirm"])){
        unset($_SESSION["confirm"]);
    }
    
    // 以下、共通パーツ定数
    define("TITLE" ,"Report TOP");
    $path = ".";
?>
<?php include(dirname(__FILE__).'/assets/_inc/header.php'); ?>
    <div class="container">
        <div class="row mt-5"><h1 class="display-5"></h1></div>
        <div class="row mt-5"><h1 class="display-5"></h1></div>
        <div class="row mt-5"><h1 class="display-5"></h1></div>

        <div class="row justify-content-center mt-5">
            <h1 class="display-4 text-secondary font-weight-lighter">
                Report Create&View TOP
            </h1>
        </div>

        <div class="row justify-content-center mt-5">
            <div class="col">
                <form action="create/createReport" method="GET">
                    <button class="btn btn-outline-success btn-lg float-right"> Create Report </button>
                </form>
            </div>
            <div class="col">
                <form action="list/reportList" method="GET">
                    <button class="btn btn-outline-success btn-lg">Report List</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>