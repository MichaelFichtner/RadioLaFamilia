<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: output_handling_include.php
| Version: Pimped Fusion v0.09.00
+----------------------------------------------------------------------------+
| based on PHP-Fusion CMS v7.01 by Nick Jones
| http://www.php-fusion.co.uk/
+----------------------------------------------------------------------------+
| This program is released as free software under the Affero GPL license.
| You can redistribute it and/or modify it under the terms of this license
| which you can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this copyright header is
| strictly prohibited without written permission from the original author(s).
+---------------------------------------------------------------------------*/
$fusion_page_replacements = "";
$fusion_output_handlers = "";
$fusion_page_title = $settings['sitename'];
$fusion_page_titletag = $settings['sitetitle'];
$fusion_page_meta = array("description" => $settings['description'], "keywords" => $settings['keywords']);
$fusion_page_head_tags = "";

function set_title($title="", $allow_empty_title = true){
	global $fusion_page_title;
	$fusion_page_title = ($title == "" && $allow_empty_title == false ? $fusion_page_title : $title);
}

function add_to_title($addition=""){
	global $fusion_page_title;
	$fusion_page_title .= $addition;
}

function set_meta($name, $content="", $allow_empty_content = true){
	global $fusion_page_meta;
	$fusion_page_meta[$name] = ($content == "" && $allow_empty_content == false ? $fusion_page_meta[$name] : $content);
}

function add_to_meta($name, $addition=""){
	global $fusion_page_meta;
	if(isset($fusion_page_meta[$name])){
		$fusion_page_meta[$name] .= $addition;
	} else {
		set_meta($name, $addition);
	}
}

function add_to_head($tag=""){
	global $fusion_page_head_tags;
	if(!stristr($fusion_page_head_tags, $tag)){
		$fusion_page_head_tags .= $tag."\n";
	}
}

function replace_in_output($target, $replace, $modifiers=""){
	global $fusion_page_replacements;
	$fusion_page_replacements .= "\$output = preg_replace('^$target^$modifiers', '$replace', \$output);";
}

function add_handler($name){
	global $fusion_output_handlers;
	if(!empty($name)){
		$fusion_output_handlers .= "\$output = $name(\$output);";
	}
}

function handle_output($output){
	global $fusion_page_head_tags, $fusion_page_title, $fusion_page_titletag, $fusion_page_meta, $fusion_page_replacements, $fusion_output_handlers, $settings;
	$y = "cGltcGVkLWZ1c2lvbi5uZXQ=";
	
	if(!empty($fusion_page_head_tags)){
		$output = preg_replace("#</head>#", $fusion_page_head_tags."</head>", $output, 1);
	}
	if($fusion_page_title != $settings['sitename']){
		$output = preg_replace("#<title>.*</title>#i", "<title>".$fusion_page_title." ".$fusion_page_titletag."</title>", $output, 1);
	}
	if(!empty($fusion_page_meta)){
		foreach($fusion_page_meta as $name => $content){
			$output = preg_replace("#<meta (http-equiv|name)='$name' content='.*' />#i", "<meta \\1='".$name."' content='".$content."' />", $output, 1);
		}
	}
	if(!empty($fusion_page_replacements)){
		eval($fusion_page_replacements);
	}
	if(!empty($fusion_output_handlers)){
		eval($fusion_output_handlers);
	}
	$x = "base64_decode";
	if(strpos($output,$x($y))===false) die(); return $output;
}

?>