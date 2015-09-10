<?php
	$max_count_page = 500;
	echo '<html xmlns="http://www.w3.org/1999/xhtml" lang="ru"><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><style>
    body { 
     font-family: Courier New;
	 font-size: 11px;
    }
    .letter-red { 
     color: #b61039;
    }
	.letter-green { 
     color: #39892f;
    }
  </style> </head><body>';

	ini_set("max_execution_time", "600000000");

	$mysqli = new mysqli("localhost", "root", "", "yug");
	$mysqli->set_charset("utf8");
	$mysqli->query("SET NAMES 'utf8'");
	if ($mysqli->connect_error) {
		die('Connect Error: ' . $mysqli->connect_error);
	}
require('phpQuery.php');

  	$StartUrl = "http://yugcontract.ua/shop/";
	$html_start = file_get_contents($StartUrl);
	phpQuery::newDocument($html_start);
	  $CategoryLink = pq('.shop-categories a');
	foreach($CategoryLink as $CLink){
		 $CategoryTop[] = pq($CLink)->attr('href');
	}
//var_dump($CategoryTop);


	foreach ($CategoryTop as $CT) {
		for ($i=1;$i<=$max_count_page;$i++) {
			$html_cat = file_get_contents('http://yugcontract.ua'.$CT.'pg'.$i);
//			echo 'http://yugcontract.ua'.$CT.'pg'.$i;
//			echo "<br>"."1";
			phpQuery::newDocument($html_cat);
			$CatItem = pq('.cat-item a');

			if (count($CatItem)>0) {
				foreach($CatItem as $CItem) {
					$ItemUrl = pq($CItem)->attr('href');

					$sel1___ = $mysqli->query('SELECT 1 FROM yug_base where url = "'.$ItemUrl.'"');
					$res1___ = $sel1___->fetch_assoc();
					if ($res1___['1'] == 1) {
						echo '<span class="letter-red">'.$ItemUrl.'</span><br />';
					}
					else {
						echo '<span class="letter-green">'.$ItemUrl.'</span><br />';
						insert_pars($ItemUrl, $mysqli);
					}
				}
			}
			else break 1;
		}
	}
	function insert_pars($link, $m) {
		$url = 'http://yugcontract.ua'.$link;
		$html = file_get_contents($url);
		phpQuery::newDocument($html);

		$ProductName = pq('.good-info-header');
		$ProductId = pq('.good-info-number');
		$ProductDescription = pq('.goods-item-panes');
		$ProductSlug = pq('.item-descr-small-content');
		$ProductImages = pq('#images-list a');
		$ProductImage = pq('#images-preview');
		$ProductPropertiesDeleteClass = pq('.properties-table .prop-header')->removeAttr('class');
		$ProductPropertiesName = pq('.properties-table .prop');
		//print_r($ProductPropertiesName);
		$ProductPropertiesValue = pq('.properties-table .val');

		$Product['url'] = $link;
		$Product['name'] = $m->real_escape_string(trim(pq($ProductName)->text()));

		$str = $Product['name'];

		$www = strstr($str,"(");
		$Product['Product_code']= substr(substr($www, 0, -1),1);

		$Product['id'] = (int)preg_replace("/[^0-9]/", '', pq($ProductId)->text());
		$Product['description'] = $m->real_escape_string(trim(pq($ProductDescription)->find('.content')->html()));
		$Product['slug'] = $m->real_escape_string(trim(pq($ProductSlug)->find('ul li')->html()));
		$Product['image_yug'] = pq($ProductImage)->attr('href');


		if ($Product['image_yug']) {
			$Product['image_local'] = copy___($Product['image_yug']);
		}
		else { $Product['image_local'] = ''; }
		foreach ($ProductImages as $PI) {
			$Product['amages_yug'][] = pq($PI)->attr('href');
			$Product['images_local'][] = copy___(pq($PI)->attr('href'));
		}
		if (isset($Product['amages_yug'])) {
			$CountProductImages = count($Product['amages_yug']);
			for($cnt_img=0;$cnt_img<$CountProductImages;$cnt_img++) {
				$ProductI[$cnt_img]['amages_yug'] = $Product['amages_yug'][$cnt_img];
				$ProductI[$cnt_img]['images_local'] = $Product['images_local'][$cnt_img];
			}
			$Product['images'] = $ProductI;
		}

		foreach ($ProductPropertiesName as $PPN) {
			$Product['properties_name'][] = pq($PPN)->removeClass('prop-header')->text();
		}
		foreach ($ProductPropertiesValue as $PPV) {
			$Product['properties_value'][] = pq($PPV)->text();
		}
		if (isset($Product['properties_name'])) {
			$CountProductProperties = count($Product['properties_name']);
			for($cnt_prop=0;$cnt_prop<$CountProductProperties;$cnt_prop++) {
				$ProductProperties[$cnt_prop]['properties_name'] = $m->real_escape_string(trim($Product['properties_name'][$cnt_prop]));
				$ProductProperties[$cnt_prop]['properties_value'] = $m->real_escape_string(trim($Product['properties_value'][$cnt_prop]));
			}
			$Product['properties'] = $ProductProperties;
		}
		$ins0___ = $m->query("INSERT INTO yug_base VALUES ('', '".$Product['id']."', '".$Product['name']."','".$Product['slug']."', '".$Product['url']."', '".$Product['description']."', '".$Product['Product_code']."',0, now());");


		$sel0___ = $m->query("SELECT MAX(id) m FROM yug_base");
		$res0___ = $sel0___->fetch_assoc();
		if (isset($Product['images'])) {
		foreach ($Product['images'] as $PI___) {
			$ins1___ = $m->query("INSERT INTO yug_images VALUES ('', '".$res0___['m']."', '".$PI___['amages_yug']."', '".$PI___['images_local']."');");
		} }
		if (isset($Product['properties'])) {
		foreach ($Product['properties'] as $PP___) {
			$ins2___ = $m->query("INSERT INTO yug_attributes VALUES ('', '".$res0___['m']."', '".$PP___['properties_name']."', '".$PP___['properties_value']."');");
			}
		}
		unset($html);
	}
	function copy___($filename) {
		$md5_first = substr(md5($filename), 0, 4);
			if(!is_dir('D:\amages_yug'.$md5_first.'/')) { mkdir('D:\amages_yug'.$md5_first.'/'); }
		$new_name = 'D:\amages_yug'.$md5_first.'/'.md5($filename).'.'.getExtension($filename);
		if (file_exists($new_name)) {
		} else {
			copy('http://yugcontract.ua'.$filename, $new_name);
		}
		return substr($new_name, 23);
	}

	function getExtension($filename) {
		return end(explode(".", $filename));
	}
	$mysqli->close();
	echo '</body></html>';
?>
                                                                                                                                                                                                                                                                                                                                