<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="description" content="Free Web tutorials">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <?php //echo $this->getHeadMeta(); ?>
        
        <title><?=$this->title?></title>

        <link rel="stylesheet" type="text/css" href="/assets/css/main.css">
        <script type="text/javascript" src="/assets/js/jquery-3.4.1.js"></script>
        <script type="text/javascript" src="/assets/js/main.js"></script>
        
        <?php //echo $this->getHeadLink(); ?>
        <?php //echo $this->getHeadScript(); ?>
    </head>
    <body>
        <table class="tbl-main">
            <tr class="tbl-main-header">
                <td>
                    <div class="main-footer">
                        <?php echo $this->viewPartial('header'); ?>
                    </div>
                </td>
            </tr>

            <tr class="tbl-main-content">
                <td>
                    <div class="main-content">
                        <?=$context?>
                    </div>
                </td>
            </tr>

            <tr class="tbl-main-footer">
                <td>
                    <div class="main-footer">
                        <?php echo $this->viewPartial('footer'); ?>
                    </div>
                </td>
            </tr>
        </table>
    </body>
</html>