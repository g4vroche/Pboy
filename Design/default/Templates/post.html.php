<?php  
/* ___ META DATA OVERRIDE ______________________________ */

$TPL_parent = 'layout.html';
$meta_title = $data['title'].' | '. $meta_title;

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
