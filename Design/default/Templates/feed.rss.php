<?php echo '<?xml version="1.0" encoding="utf-8"?>' ?>

<feed xmlns="http://www.w3.org/2005/Atom">

 <title><?php $meta_title ?></title>
 <link href="<?php echo $site_url?>"/>
 <updated><?php echo date("Y-m-d H:i:s", time())?></updated>
 <author>
   <name><?php $meta_author?></name>
 </author>
 <?php // <id>urn:uuid:60a76c80-d399-11d9-b93C-0003939e0af6</id> ?>


<?php foreach ($data as $item): ?>
 <entry>
   <title><?php echo $item['title'] ?></title>
   <link href="<?php echo $site_url.'/'.$item['slug'] ?>.html"/>

  <?php // <id>urn:uuid:1225c695-cfb8-4ebb-aaaa-80da344efa6a</id> ?>
   <updated><?php echo $item['updated'] ?></updated>
   <summary><?php echo $item['summary'] ?></summary>
 </entry>
<?php endforeach ?>

</feed>

