<?php

   function getInfo($url)
   {
    $sengine = array();
	$sengine[] = array('name'=>'Google-Blogsearch','url'=>'http://blogsearch.google.com','host'=>'blogsearch\.google\.com','query_field'=>'q');
	$sengine[] = array('name'=>'Google-De','url'=>'http://www.google.de','host'=>'google\.de','query_field'=>'q');
	$sengine[] = array('name'=>'Google-Com','url'=>'http://www.google.com','host'=>'google\.com','query_field'=>'q');
	$sengine[] = array('name'=>'Google','url'=>'http://www.google.com','host'=>'google\.','query_field'=>'q');
	$sengine[] = array('name'=>'Yahoo-de','url'=>'http://www.yahoo.de','host'=>'de\.search\.yahoo\.com','query_field'=>'p');
	$sengine[] = array('name'=>'Yahoo-com','url'=>'http://www.yahoo.com','host'=>'search\.yahoo\.com','query_field'=>'p');
	$sengine[] = array('name'=>'MSN-Live','url'=>'http://search.live.com','host'=>'search\.live\.com','query_field'=>'q');
	$sengine[] = array('name'=>'MSN','url'=>'http://search.msn.com','host'=>'search\.msn\.com','query_field'=>'q');
	$sengine[] = array('name'=>'Alltheweb','url'=>'http://www.alltheweb.com','host'=>'alltheweb\.com','query_field'=>'q');
	$sengine[] = array('name'=>'Ask-De','url'=>'http://de.ask.com','host'=>'de\.ask\.com','query_field'=>'q');
	$sengine[] = array('name'=>'Ask-Com','url'=>'http://www.ask.com','host'=>'ask\.com','query_field'=>'q');
	$sengine[] = array('name'=>'LookSmart','url'=>'http://www.looksmart.com','host'=>'search\.looksmart\.com','query_field'=>'qt');
	$sengine[] = array('name'=>'Altavista-De','url'=>'http://www.altavista.de','host'=>'de\.altavista\.com','query_field'=>'q');
	$sengine[] = array('name'=>'Altavista-com','url'=>'http://www.altavista.com','host'=>'altavista\.com','query_field'=>'q');
	$sengine[] = array('name'=>'Web-De','url'=>'http://www.web.de','host'=>'suche\.web\.de','query_field'=>'su');
	$sengine[] = array('name'=>'Fireball','url'=>'http://www.fireball.de','host'=>'suche\.fireball\.de','query_field'=>'query');
	$sengine[] = array('name'=>'Lycos-De','url'=>'http://www.lycos.de','host'=>'suche\.lycos\.de','query_field'=>'query');
	$sengine[] = array('name'=>'Lycos-Com','url'=>'http://www.lycos.com','host'=>'search\.lycos\.com','query_field'=>'query');

      // no search engines in the db? then no need to search ...
      if (!is_array($sengine))
	      return false;

      $url_info = parse_url($url); // parse the url

      // check each search engine in the database
      foreach ($sengine as $se)
      {
         // if the host of the search engine matches
         // the one of the url we have successfully
         // located the search engine
         if (preg_match("/".$se['host']."/", $url_info['host']))
         {
            // parse the query
            parse_str($url_info['query'], $query_info);
            // return all information
            return array('sengine_name'=>$se['name'],
                         'sengine_url'=>$se['url'],
                         'query'=>$query_info[$se['query_field']],
                         'url'=>$url);
         }
      }
      // if no search engine was found return false
      return false;
   }


?>