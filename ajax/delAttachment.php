<?php
    $attachment = $_POST['attachmentName'];
    // 一時ファイル削除
    unlink("../storage/attachment/tmp/".$attachment);
    session_start();
    // セッション内も削除
    unset($_SESSION["attachments"][$attachment]);