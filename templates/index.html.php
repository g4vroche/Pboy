
<div class="list posts">

<?php foreach ($data as $item): ?>

    <div class="post">
        
        <div class="post-title">
            <h2><?php echo $item['title'] ?></h2>
        </div>
        
        <div class="post-content">
            <p><?php echo $item['content'] ?></p>
        </div>

    </div>


<?php endforeach ?>

</div>
