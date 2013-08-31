<?php  
/* ___ META DATA OVERRIDE ______________________________ */

$TPL_parent = 'layout.html';

/* _____________________________________________________ */
?>
<div class="list posts">

<?php foreach ($data as $item): ?>

    <div class="post">
        
        <div class="post-title">
            <h2><a href="<?php echo $item['slug'] ?>.html"><?php echo $item['title'] ?></a></h2>
            <div class="date"><?php echo $item['date'] ?></div>
        </div>
        
        <div class="post-content">
            <?php echo substr($item['content'], 0, 100).'...' ?>
        </div>

    </div>


<?php endforeach ?>

</div>

