<?php

/** Error reporting */
error_reporting(E_ALL);

include(dirname(__FILE__).'/config/config.inc.php');

include(dirname(__FILE__).'/init.php');

require_once dirname(__FILE__).'/tools/PHPExcel.php';
//require_once dirname(__FILE__).'/tools/PHPExcel/Cell/AdvancedValueBinder.php';
//require_once dirname(__FILE__).'/tools/PHPExcel/IOFactory.php';

	try {
	

	 	//echo $_GET["type"].'<br>';
	 	
	 	$sql="SELECT DISTINCT
			c.id_category, 
			c.id_parent, 
			' ' as parent_path,
			c.level_depth,
			cl.name, 
			cl.description
			
			FROM "._DB_PREFIX_."category c, 
			"._DB_PREFIX_."category_lang cl
			WHERE
			cl.id_lang = ".intval($cookie->id_lang)." AND
			cl.id_category = c.id_category
			ORDER BY c.id_parent, cl.name";
	 	
	 	//echo 'line 31:<br>'.$sql.'<br>';
	 	$result = Db::getInstance()->ExecuteS($sql);
	 	
	 	if (!$result){
			
			echo "hiba";
			return;
		}
		
	
		$resultParents = array();
		$categories = array();

		
		foreach ($result as $row)
		{
			
			$row['name'] = preg_replace('/^[0-9]+\./', '', $row['name']);
			$resultParents[$row['id_parent']][] = $row;
			$categories[$row['id_category']] = $row;
			
			
		}
		// A home kategoria a kiemelt ajanlatokat tartalmazza, ezert a nevet lecsereljuk erre
		if(isset($categories['1'])){
			$categories['1']['name'] = $_GET["home_category_name"];
		}

		
		$catUtil = new CategoryUtil();
		foreach ($categories as $cat)
		{
//			echo $cat["id_category"]." kozvetlen szulo=".$cat["id_parent"]." feldolgozasa... <br>";
			$categories[$cat["id_category"]]["parent_path"] = $catUtil->uploadPath($$cat, $categories[$cat["id_parent"]], $categories);
			//echo "set ".$categories[$cat["id_category"]]["parent_path"]."<br><br>"; 
		}
		
		$sql="SELECT 	p.id_product, 
				cp.id_category,
				p.description, 
				p.description_short, 
				p.name 	 
			FROM	"._DB_PREFIX_."category_product cp , "._DB_PREFIX_."product_lang p
			WHERE 	cp.id_product = p.id_product
			AND		p.id_lang = ".intval($cookie->id_lang)."
			ORDER BY cp.id_category, cp.position";
			
	 	//echo $sql.'<br>';
		
	 	if (!$result = Db::getInstance()->ExecuteS($sql)){
			echo "product hiba";
			return;
		}
		
		$catProduct = array();
		$product = array();
		
		foreach ($result as $row)
		{	
			// minden termek a kategoria agakhoz lesz levalogatva
			$catProduct[$row['id_category']][] = $row;
		}
		

				
		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();
		
		// Set properties
		$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
		->setLastModifiedBy("Maarten Balliauw")
		->setTitle("Office 2007 XLSX Test Document")
		->setSubject("Office 2007 XLSX Test Document")
		->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
		->setKeywords("office 2007 openxml php")
		->setCategory("Test result file");
		
		
		// Rename sheet
		$objPHPExcel->getActiveSheet()->setTitle('Simple');
		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$worksheet = $objPHPExcel->setActiveSheetIndex(0);
		
		
		$rowindex = 0;
		
		// a listazas a kategoriakon megy vegeig, es mindhez a sajat termekekel folytatodik
		foreach ($categories as $cat)
		{
			
			$products = $catProduct[$cat["id_category"]];
			// a kategoria termekei
			if(isset($products)){
				// termek nelkuli kategoriakat nem listazzuk, ilyenek pl. a nem level kategoriak is
				
				//echo '<br>'.$catUtil->getCategoryName($cat["id_category"], $categories).'<br>';
				
				$rowindex++;
				
				// Add some data
				$worksheet->setCellValue('A'.$rowindex, $catUtil->getCategoryName($cat["id_category"], $categories));
				
				foreach ($products as $prod)
				{
					//echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$prod["name"].'<br>';
					$rowindex++;
					$worksheet->setCellValue('B'.$rowindex, $prod["name"]);
				}	
				
				
			}
			
		}
		
		
		// Redirect output to a client’s web browser (Excel5)
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="vendingoutlet-products.xlsx"');
		header('Cache-Control: max-age=0');
				
		PHPExcel_Settings::setZipClass(PHPExcel_Settings::ZIPARCHIVE);
		
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
		exit;
	} catch (Exception $e) {
		var_dump($e);
	}

 	
class CategoryUtil 
{
	function __construct()
	{
		
	}

	
	
	
	/**
	 * 
	 * A listazasban megjeleno ketegoria?
	 */
	function isEnabledCategory($category){
		return isset($category) && $category["id_category"] > 1; 
	}
	
	function uploadPath($category, $parent, $cats){
		//echo "&nbsp;&nbsp;&nbsp;&nbsp;step:".$parent["id_category"]."[".$parent["id_parent"]."] lekerese... <br>";
		if($this->isEnabledCategory($parent)){
			/* Az aktualis szulo nevenek atvetele */
			
			$category["parent_path"] = '"'.$parent["name"].'" / '.$category["parent_path"];
			/* A szulo szulojenek feldolgozasa */
			$category["parent_path"] = $this->uploadPath($category, $cats[$parent["id_parent"]], $cats);
		}
		//echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;return ".$category["parent_path"]."<br>";
		return $category["parent_path"];
	}
	
	/**
	 * 
	 * Visszadja a kataegoria teljes nevet az szulokkel egyutt.
	 * @param $id_category
	 * @param $cats
	 */
	function getCategoryName($id_category, $cats){
		if (isset($cats[$id_category])){
//			echo "get ".$id_category."<br>";
//			var_dump($cats[$id_category]);
			return $cats[$id_category]["parent_path"].'"'.$cats[$id_category]["name"].'"';
		}
		return "";
	}
	
	function getTree($resultParents, $resultIds, $maxDepth, $id_category = 1, $currentDepth = 0)
	{
		global $link;
		
		$children = array();
		if (isset($resultParents[$id_category]) AND sizeof($resultParents[$id_category]) AND ($maxDepth == 0 OR $currentDepth < $maxDepth))
			foreach ($resultParents[$id_category] as $subcat)
				$children[] = $this->getTree($resultParents, $resultIds, $maxDepth, $subcat['id_category'], $currentDepth + 1);
		if (!isset($resultIds[$id_category]))
			return false;
		return array('id' => $id_category, 'link' => $link->getCategoryLink($id_category, $resultIds[$id_category]['link_rewrite']),
					 'name' => Category::hideCategoryPosition($resultIds[$id_category]['name']), 'desc'=> $resultIds[$id_category]['description'],
					 'children' => $children);
	}
	
	
}
 	?>