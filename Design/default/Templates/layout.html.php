<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title><?php echo $meta_title?></title>
    <meta name="description" content="<?php echo $meta_description ?>" />
    <meta name="keywords" content="<?php echo $meta_keywords ?>" />
    <?php include 'css.php' ?>
</head>
<body>

    <div class="stories">
        <?php include 'output/stories.html' ?>
    </div>

    <div class="title"><?php echo $site_title ?></div>
    <?php echo $content ?>
    <?php include 'js.php' ?>
</body>
