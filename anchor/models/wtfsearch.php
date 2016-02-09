<?php

class WTFSearch
{
  static function addToIndex($id)
  {
    $indexThese = array('title', 'creator', 'subject', 'publisher', 'contributor', 'identifier', 's:subtitle', 's:isbn', 's:tag');
    $prefix = Config::db('prefix', '');
    $title = Input::get('title');
    $input = Input::get('extend.metadata_wtf');
    $metadata = self::parse_wtf($input);
    
    if (empty($metadata['.']['title'])) {
       $metadata['.']['title'][] = $title;
    }
    
    $query = Query::table($prefix . 'wtfsearch')
					->where('post_id', '=', $id);
          
    $query->delete();
          
    foreach ($metadata['.'] as $k=>$vals) {
      if (in_array($k, $indexThese)) {
        foreach ($vals as $val) {
          $query->insert(array(
            'post_id'=>$id,
            'meta_key'=>$k,
            'meta_value'=>normalize($val)
          ));
        }
      }
       
    }
    
    //var_dump($prefix, $metadata, $id);
    //die;
  }
  
  
  static function parse_wtf($metadata, $filename='.')
  {
	$ret = array();
	foreach (explode("\n", $metadata) as $line) {
		if (preg_match('~^[#;]~', $line, $matches)) {
			// koment
		} elseif (preg_match('~([^=]+)=(.*)~', $line, $matches)) {
			$ret[$filename][trim($matches[1])][] = trim($matches[2]);
		} elseif (preg_match('~^\[(.*)\]~', $line, $matches)) {
			$filename = trim($matches[1]);
		}
	}
	return $ret;
  }
  
  
  public static function search($term, $page = 1, $per_page = 10) {
		
    $query = Query::table(Base::table('posts'));    
    $query->join(Base::table('wtfsearch'), Base::table('posts.id'), '=', Base::table('wtfsearch.post_id'));
    $query->left_join(Base::table('users'), Base::table('users.id'), '=', Base::table('posts.author'))
			->where(Base::table('posts.status'), '=', 'published')
			// ->where(Base::table('posts.title'), 'like', '%' . $term . '%');
      ->where(Base::table('wtfsearch.meta_value'), 'like', '%' . $term . '%');
      
    $query->group(Base::table('posts.id'));

		$total = $query->count();

		$posts = $query->take($per_page)
			->skip(--$page * $per_page)
			->get(array(Base::table('posts.*'),
				Base::table('users.id as author_id'),
				Base::table('users.bio as author_bio'),
				Base::table('users.real_name as author_name')));

		return array($total, $posts);
	}
}