<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../../../phpReportTask/css/reset.css">
    <link rel="stylesheet" href="../../../phpReportTask/css/original.css">
    <title><?php echo TITLE; ?></title>
</head>

<body class="">
    <header class="site-header">
        <!-- Left Side Of Navbar -->
        <nav id="nav-left"> 
            <h3>Report create&view</h3>
        </nav>

        <!-- Right Side Of Navbar -->
        <nav id="nav-right">
            <ul class="">
                <!-- 認証情報 -->
                <?php if (!defined("NOT_SHOW_AUTH")) :?>
                    <?php if (!isset($_SESSION['id'])) :?>
                        <li class="">
                            <div class="">
                                <form action="./management/loginForm.php" method="GET">
                                <button class="">Login</button>
                                </form>
                            </div>
                        </li>
                    <?php else :?>
                        <li class="">
                            <div class="name">
                                <?php echo $_SESSION['name'] ?>
                            </div>
                        </li>
                        <li>
                            <a class="" href="<?php echo $path.'/management/logout.php'; ?>">Logout</a>
                        </li>
                    <?php endif ;?>
                <?php endif ;?>
            </ul>
        </nav>
    </header>
    <div class="container">