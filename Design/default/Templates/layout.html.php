<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title><?php echo $vars['meta_title']?></title>
    <meta name="description" content="<?php echo $vars['meta_description'] ?>" />
    <meta name="keywords" content="<?php echo $vars['meta_keywords'] ?>" />
    <?php include 'css.php' ?>
</head>
<body>
    <?php echo $content ?>
    <?php include 'js.php' ?>
</body>
