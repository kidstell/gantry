<?php

function maker($destination)
{
	if (!is_dir($destination) && !@mkdir($destination)) {
		$parent = pathinfo($destination,PATHINFO_DIRNAME);
		maker($parent);
		return maker($destination);
	}
	return false;
}
function old($var){
	return (isset($_SESSION['post']) && isset($_SESSION['post'][$var]))?$_SESSION['post'][$var]:false;
}
function runner($listing,$fpath,$lmod,$exception_patterns)
{
	if (in_array($fpath, $exception_patterns)) return $listing;

	$dir = pathinfo($fpath, PATHINFO_DIRNAME);
	
	$listing = saveDir($listing, $dir);
	if (!is_dir($fpath) && filemtime($fpath) >= $lmod) {
		// echo $fpath.'::::::::::::'.date('d-m-Y H:i:s',filemtime($fpath)).'<br>';
		return saver($listing, $fpath);
	}
	$set = glob(rtrim($fpath,'/').'/*');
	foreach ($set as $key => $value) {
		$listing = runner($listing, $value, $lmod, $exception_patterns);
	}
	return $listing;
}

function saver($listing,$fpath)
{
	$dir = pathinfo($fpath, PATHINFO_DIRNAME);
	$fn = pathinfo($fpath,PATHINFO_BASENAME);
	
	if (!isset($listing['files']) || !is_array($listing['files'])) {
		$listing['files'] = [];
	}
	if (!isset($listing['files'][$dir]) || !is_array($listing['files'][$dir])) {
		$listing['files'][$dir] = [];
	}
	if (!in_array($fn, $listing['files'][$dir])) {
		$listing['files'][$dir][] = $fn;
	}
	return $listing;
}

function saveDir($listing, $fpath)
{
	$dir = pathinfo($fpath, PATHINFO_DIRNAME);
	$fn = pathinfo($fpath,PATHINFO_BASENAME);
	// print_r(pathinfo($fpath));
	if (!isset($listing['dir']) || !is_array($listing['dir'])) {
		$listing['dir'] = [];
	}
	if (!isset($listing['dir'][$dir]) || !is_array($listing['dir'][$dir])) {
		$listing['dir'][$dir] = [];
	}
	if (!in_array($fn, $listing['dir'][$dir])) {
		$listing['dir'][$dir][] = $fn;
	}
	if (!isset($listing['dir'][$fpath]) || !is_array($listing['dir'][$fpath])) {
		$listing['dir'][$fpath] = [];
	}
	return $listing;
}



?>