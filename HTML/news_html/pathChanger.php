<?php
//リンクと画像を一気に絶対パスに書き換えたい
function fullpath($elements, $base) {
	$changed = url($elements, $base);
	$changed = image($changed, $base);
	
	return $changed;
}

//リンクを絶対パスに書き換える
function url($elements, $base) {
	//elementsのタイプを調べる
	$type = checkElmType($elements);
	//echo $type;
	if($type == 0) {
		//要素を探して書き換え
		foreach($elements['a'] as $tmp) {
			changeHref($tmp, $base);
		}
		foreach($elements['area'] as $tmp) {
			changeHref($tmp, $base);
		}
	} else if($type == 1 || $type == 2) {
		//要素を書き換え
		changeHref($elements, $base);
	} else if($type == -1) {
		foreach($elements as $tmp) {
			//print_r($el);
			url($tmp, $base);
		}
	} else {
		//何もしない
	}
	return $elements;
}

//読み込む画像のsrcを絶対パスに書き換える
function image($elements, $base) {
	//elementsのタイプを調べる
	$type = checkElmType($elements);
	if($type == 0) {
		foreach($elements['img'] as $tmp) {
			changeSrc($tmp, $base);
		}
		foreach($elements['input'] as $tmp) {
			if($tmp->getAttribute('type') == 'image') {
				changeSrc($tmp, $base);
			}
		}
		foreach($elements['span'] as $tmp) {
			$bgImage = $tmp->getAttribute('style');
			$bgImage = str_replace('background-image: url(','',$bgImage);
			$bgImage = str_replace(')','',$bgImage);
			$path = createUri($base, $bgImage);
			$tmp->setAttribute('style', 'background-image: url('.$path.')');
		}
	} else if($type > 0) {
		changeSrc($elements, $base);
	} else if($type == -1) {
		foreach($elements->elements as $el) {
			image($el, $base);
		}
	} else{
		//何もしない
	}
	return $elements;
}

//リンクの書き換え
function changeHref($elm, $base) {
	$href = $elm->getAttribute('href');
	$path = createUri($base, $href);
	$elm->setAttribute('href', $path);
}

//画像のsrcの書き換え
function changeSrc($elm, $base) {
	
	$src = $elm->getAttribute('src');
	$data_src = $elm->getAttribute('data-src');
	if($data_src) {
		$src = $data_src;
	}

	$path = createUri($base, $src);
	$elm->setAttribute('src', $path);
	
}

//入力された要素のタイプを調べる
function checkElmType($elm) {
	$type = 0;
	//var_dump($elm);
	//findを使って取得したやつ※foreachで回してからパスの書き換え
	if(count($elm) == 1 && get_class($elm) == 'phpQueryObject') {
		return -1;
	} else {
		$tag = $elm->tagName;
	}
	//echo $tag;
	if($tag == 'a') {
		return 1;
	} else if($tag == 'area'){
		return 2;
	} else if($tag == 'img') {
		return 3;
	} else if($tag == 'input') {
		return 4;
	} else {
		return $type;
	}
}

/**
 * createUri
 * 相対パスから絶対URLを返します
 *
 * @param string $base ベースURL（絶対URL）
 * @param string $relational_path 相対パス
 * @return string 相対パスの絶対URL
 * @link http://blog.anoncom.net/2010/01/08/295.html
 */
function createUri( $base, $relationalPath ) {
	$parse = array(
		"scheme" => null,
		"user" => null,
		"pass" => null,
		"host" => null,
		"port" => null,
		"query" => null,
		"fragment" => null
	);
	$parse = parse_url( $base );

	if( strpos($parse["path"], "/", (strlen($parse["path"])-1)) !== false ){
		$parse["path"] .= ".";
	}

	if( preg_match("#^https?://#", $relationalPath) ){
		return $relationalPath;
	} else if(preg_match("#//#", $relationalPath)) {
		return 'http:'.$relationalPath;
	} else if( preg_match("#^/.*$#", $relationalPath) ){
		return $parse["scheme"] . "://" . $parse["host"] . $relationalPath;
	}else{
		$basePath = explode("/", dirname($parse["path"]));
		$relPath = explode("/", $relationalPath);
	foreach( $relPath as $relDirName ){
		if( $relDirName == "." ){
			array_shift( $basePath );
			array_unshift( $basePath, "" );
		}else if( $relDirName == ".." ){
			array_pop( $basePath );
			if( count($basePath) == 0 ){
				$basePath = array("");
			}
		}else{
			array_push($basePath, $relDirName);
		}
	}
	  $path = implode("/", $basePath);
	  return $parse["scheme"] . "://" . $parse["host"] . $path;
	}
}
?>