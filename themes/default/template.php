<?php
if (!defined('SYSTEM')) die();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="ru">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title><?php echo $title; ?></title>
        <link rel="stylesheet" href="<?php echo 'themes/default/css/reset.css'; ?>" type="text/css" media="all" />
        <link rel="stylesheet" href="<?php echo 'themes/default/css/main.css'; ?>" type="text/css" media="all" />
    </head>
    <body>
        <div class="log_block" id="header">
            <div id="h_logo"></div>
            <table cellspacing="0" cellpadding="0">
                <tr>
                    <td>
                        <div class="left"></div>
                    </td>
                    <td>
                        <div id="menu" class="navi"><?php echo $navi; ?></div>
                    </td>
                    <td>
                        <div class="right"></div>
                    </td>
                </tr>
            </table>
        </div>
        <div class="log_block" id="content">
            <p class="invitation"><?php echo $invitation; ?></p>
            <?php if($log) echo $log; ?>
            <?php echo $content; ?>
        </div>
        <div class="log_block" id="footer">
            <div id="copy">
                <p>KQC &copy; <?PHP echo(date('Y')); ?></p>
            </div>
        </div>
    </body>
</html>