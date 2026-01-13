<?php

function stihi_theme_preprocess_page(&$variables, $hook) {
  //to make a new page--type--lyrics.tpl.php file visible
  if (isset($variables['node'])) {  
     $variables['theme_hook_suggestions'][] = 'page__type__'. $variables['node']->type;
     //$variables['theme_hook_suggestions'][] = "page__node__" . $variables['node']->nid;
  }
  
  //to have a node field video visible from page--type--lyrics.tpl.php file
  if (arg(0) == 'node') {
     $variables['node_content'] =& $variables['page']['content']['system_main']['nodes'][arg(1)];
  }
  
//  if (drupal_is_front_page()) {
//	  $title = &drupal_static(__FUNCTION__);
//	  drupal_set_title($title);
//  }
}

//google pagespeed ask it
function stihi_theme_page_alter($page) {
  // <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"/>
  $viewport = array(
    '#type' => 'html_tag',
    '#tag' => 'meta',
    '#attributes' => array(
    'name' =>  'viewport',
    'content' =>  'width=device-width'
    )
  );
  drupal_add_html_head($viewport, 'viewport');
}

/**
 * Implements hook_js_alter().
 */
function stihi_theme_js_alter(&$js) {
  //if (user_is_anonymous()) {
  $path = current_path();
  if ($path == 'imce') return;
	  if ($path != 'poisk') {
		  $js=array();
	  } else {
		  $jsnew = array('misc/drupal.js' => $js['misc/drupal.js'],
		               'misc/jquery.js' => $js['misc/jquery.js'],
		               'misc/jquery.once.js' => $js['misc/jquery.once.js'],
		              'misc/autocomplete.js' => $js['misc/autocomplete.js'],
		              );
		  $js = $jsnew;
		  //unset($js['misc/drupal.js']);
		  //unset($js['misc/jquery.js']);
		  //unset($js['misc/jquery.once.js']);
		  //unset($js['misc/textarea.js']);
		  //unset($js['misc/filter.js']);
      }
  //}
}


function stihi_theme_node_view_alter(&$node, $view_mode, $langcode) {
  //dpm($node);
  if ($view_mode == 'node') {
	    
  // Change titile and h1 tags for lyrics
  if (($node['#bundle'] == 'lyrics') || ($node['#bundle'] == 'lyrics_translate')) {
	  hide($node['field_vid']);
	  /*$title = $node['#node']->title;
	  $old_title = $title;
	  $singer = '';
	  //add first singer if exists
	  if (isset($node['field_singer'][0])) {
		 //dpm($node['field_singer'][0]);
	     $singer = $node['field_singer'][0]['#title'];
	  }
	  $i = 1;
	  while ((isset($node['field_singer'][$i])) && ($i < 3)) {
		  $singer .= ', ' . $node['field_singer'][$i]['#title'];
		  $i++;
	  }
	  if (strlen($singer) > 0) {
		  $title = $singer . ' - '. $title;
	  }
	  //add other singers to tag titile
	  drupal_set_title($title);
	  //add other singers tag h1
	  //$node['#node']->title = $title;  
	  */
  }
  }
}


function stihi_theme_preprocess_node(&$vars) {
  //dpm($vars);
  // Change titile and h1 tags for lyrics
  if (($vars['type'] == 'lyrics') || ($vars['type'] == 'lyrics_translate')) {
	  //if (isset($vars['content']['field_vid'])) hide($vars['content']['field_vid']); //error in page--type-lyrics.tpl.php undefined field_vid
	  $title = $vars['title'];
	  $old_title = $title;
	  $singer = '';
	  //add first singer if exists
	  if (isset($vars['content']['field_singer'][0])) {
		 //dpm($node['field_singer'][0]);
	     $singer = '<a href="/'.drupal_get_path_alias($vars['content']['field_singer'][0]['#href']).'">'.
	               $vars['content']['field_singer'][0]['#title'].'</a>';
	  }
	  $i = 1;
	  while ((isset($vars['content']['field_singer'][$i])) && ($i < 10)) {
		  $singer .= ', ' . '<a href="/'.drupal_get_path_alias($vars['content']['field_singer'][$i]['#href']).'">'.
	               $vars['content']['field_singer'][$i]['#title'].'</a>';
		  $i++;
	  }
	  if (strlen($singer) > 0) {
		  $title = '<h1 class="song-title">' . $singer . ' - '. $title .'</h1>';
	  }
	  //add other singers to tag titile
	  //drupal_set_title($title);
	  //add other singers tag h1
	  //$node['#node']->title = $title;  
	  echo $title;
  }
}


/* remove links from blog tesser: Подробнее о Трактат vse.la про субтон Блог пользователя - Кіт 1 Добавить комментарий */
function stihi_theme_links($links, $attributes = array('class' => 'links')) {
  // HERE: just add this line, the rest are from theme_links() 
  //unset($links['blog_usernames_blog']);
  unset($links);
}

function stihi_theme_preprocess_username(&$vars) {
    //putting back what drupal core messed with
    $vars['name'] = check_plain($vars['name_raw']);
}


/* block song themes HTML menu */
function _stihi_theme_block_32_block_visibility() {
	$path = current_path();
	if (($path == "texty-pesen") || ($path == "perevody-pesen")) return true;	
	if ((arg(0) == 'node') && is_numeric(arg(1))) {
		//nodes
		//$nid = arg(1);
		//$node = node_load($nid);
		//$type = $node->type; 
		//$match = in_array($type, array('lyrics', 'lyrics_translate'));
		//if ($match) return true;
	} elseif ((arg(1) == 'term') && is_numeric(arg(2))) {
		//terms
		$term = taxonomy_term_load(arg(2));
		$vid = $term->vid;
		//17-year, 16-genre, 15 -song themes, 14 - singers, 10-language, 7-feelings
		if (($vid==17) || ($vid==16) || ($vid==15) || ($vid==14)) return true; 
	}
	
	return false;
}



