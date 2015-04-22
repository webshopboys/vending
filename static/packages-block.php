<?php


include(dirname(__FILE__).'/../config/config.inc.php');
require_once(dirname(__FILE__).'/../init.php');

$number = 5;
$orderBy = date_add;
$orderWay = DESC;
$mediumSize = Image::getSize('small');
$id_category = 149;
$id_lang = intval($cookie->id_lang); // 3,1,4

$products = Product::getProducts($id_lang, 0, $number, $orderBy, $orderWay, $id_category, true);

$title = $id_lang==3 ? "Csomagajánlatok" : ($id_lang==4 ? "Пакет предложения" : "Package deals");

$link = new Link();

?>


<div class="block products_block exclusive">
	<h4 class="pulsing_content redheader"><a href="http://www.vendingoutlet.org/category.php?id_category=<?php echo $id_category; ?>" title="<?php echo $title; ?>"><?php echo $title; ?></a></h4>
	<div class="block_content">
		<ul class="products">
<?php
if($products){
	foreach ($products AS $row)
	{
		$row['id_image'] = Product::defineProductImage($row);
		
		$row['link'] = $link->getProductLink($row['id_product'], $row['link_rewrite'], $row['category'], $row['ean13']);
		$row['imglink'] = $link->getImageLink($row['link_rewrite'], $row['id_image'], 'medium');

		echo '<li class="product_image"><a href="'.$row['link'].'">';
		echo '<span><img src="'.$row['imglink'].'" height="'.$mediumSize['height'].'" width="'.$mediumSize['width'].'" />';
		
		echo ''.$row['name'].'</span></a></li>';
		
				
	}
}	
?>		
			<li></li>
		</ul>
	</div>
</div>