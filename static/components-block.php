<?php
include(dirname(__FILE__).'/../config/config.inc.php');
require_once(dirname(__FILE__).'/../init.php');

$number = 5;
$orderBy = date_add;
$orderWay = DESC;
$id_category = 85;
$id_lang = intval($cookie->id_lang); // 3,1,4

$title = $id_lang==3 ? "Alkatrész automaták" : ($id_lang==4 ? "Запчасти для автоматов" : "Spare parts machines");

$products = Product::getProducts($id_lang, 0, $number, $orderBy, $orderWay, $id_category, true);

$link = new Link();

?>

<div class="block">
	<h4><a href="http://www.vendingoutlet.org/category.php?id_category=<?php echo $id_category; ?>" title="<?php echo $title; ?>"><?php echo $title; ?></a></h4>
	<ul class="block_content">
<?php
if($products){
	foreach ($products AS $row)
	{

		$row['link'] = $link->getProductLink($row['id_product'], $row['link_rewrite'], $row['category'], $row['ean13']);
		echo '<li><a href="'.$row['link'].'" title="'.$row['name'].'">'.$row['name'].'</a></li>';
		//echo '<dd class="item"><a href="'.$row['link'].'">'.$row['description_short'].'</a>';
		//echo '&nbsp;<a href="'.$row['link'].'"><img alt=">>" src="'.$img_dir.'bullet.gif"/></a></dd>';
		
	}
}	
?>
	</ul>
</div>

