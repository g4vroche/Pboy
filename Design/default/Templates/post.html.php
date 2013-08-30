<?php  
/* ___ META DATA OVERRIDE ______________________________ */

$layout = 'layout.html';
$vars['meta_title'] = $data['title'].' | '. $vars['meta_title'];

/* _____________________________________________________ */
?>

<div class="post">
    <div class="post-title">
        <h1><?php echo $data['title'] ?></h1>
    </div>


    <div class="post-content">
        <p><?php echo $data['content'] ?></p>
    </div>

</div>
