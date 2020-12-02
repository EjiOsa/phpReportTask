<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo TITLE; ?></title>
</head>

<body>
    <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
        <div class="container">

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav mr-auto">
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ml-auto">
                    <div class="top-right links">

                    </div>
                    <!-- Authentication Links -->
                    <?php if (!defined("NOT_SHOW_AUTH")) :?>
                        <?php if (!isset($_SESSION['id'])) :?>
                            <li class="nav-item">
                                <div class="col">
                                    <form action="./management/loginForm" method="GET">
                                    <button class="btn btn-outline-success btn-lg">Login</button>
                                    </form>
                                </div>
                            </li>
                        <?php else :?>
                            <li class="nav-item">
                                <div class="col">
                                    <?php echo $_SESSION['name'] ?>
                                </div>
                                <a class="nav-link" href="<?php echo $path.'/management/logout'; ?>">ログアウト</a>
                            </li>
                        <?php endif ;?>
                    <?php endif ;?>
                </ul>
            </div>
        </div>
    </nav>