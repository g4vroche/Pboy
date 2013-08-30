<?php foreach ($vars['css'] as $media => $stylesheet): ?>
<link rel="stylesheet" type="text/css" href="<?php echo $stylesheet ?>" media="<?php echo $media ?>"/>
<?php endforeach ?>
