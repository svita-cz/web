<?php
function numeral($number, $hideIfOne = false) {
	if($hideIfOne === true and $number == 1) {
		return '';
	}
	
	$test = abs($number) % 10;
	$ext = ((abs($number) % 100 < 21 and abs($number) % 100 > 4) ? 'th' : (($test < 4) ? ($test < 3) ? ($test < 2) ? ($test < 1) ? 'th' : 'st' : 'nd' : 'rd' : 'th'));
	return $number . $ext;
}

function count_words($str) {
	return count(preg_split('/\s+/', strip_tags($str), null, PREG_SPLIT_NO_EMPTY));
}

function split_content($content){
	$paragraphs = explode("\n\n", $content);
	$paragraphs = array_slice($paragraphs, 0, 2);
	return join("\n\n", $paragraphs);
}

function pluralise($amount, $str, $alt = '') {
	return intval($amount) === 1 ? $str : $str . ($alt !== '' ? $alt : 's');
}

function relative_time($date) {
	if(is_numeric($date)) $date = '@' . $date;

	$user_timezone = new DateTimeZone(Config::app('timezone'));
	$date = new DateTime($date, $user_timezone);

	// get current date in user timezone
	$now = new DateTime('now', $user_timezone);

	$elapsed = $now->format('U') - $date->format('U');

	if($elapsed <= 1) {
		return 'Just now';
	}

	$times = array(
		31104000 => 'year',
		2592000 => 'month',
		604800 => 'week',
		86400 => 'day',
		3600 => 'hour',
		60 => 'minute',
		1 => 'second'
	);

	foreach($times as $seconds => $title) {
		$rounded = $elapsed / $seconds;

		if($rounded > 1) {
			$rounded = round($rounded);
			return $rounded . ' ' . pluralise($rounded, $title) . ' ago';
		}
	}
}

function twitter_account() {
	return site_meta('twitter', 'danoxide');
}

function twitter_url() {
	return 'https://twitter.com/' . twitter_account();
}

function total_articles() {
	return Post::where(Base::table('posts.status'), '=', 'published')->count();
}

function parse_wtf($metadata, $filename='.')
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

function get_dc_fields() {
	return array(
		'title' => 'Název',
		's:subtitle' => 'Podtitul', // rovněž bonus
		'creator' => 'Tvůrce',
		'subject' => 'Předmět',
		'description' => 'Popis',
		'publisher' => 'Vydavatel',
		's:publishedIn' => 'Místo vydání',
		's:edition' => 'Edice',
		'contributor' => 'Přispěvatelé',
		'date' => 'Datum', // rok vydání
		'type' => 'Typ / Kategorie',
		'format' => 'Formát',
		'identifier' => 'Identifikátor',
		's:isbn' => 'ISBN', // další bonus
		'source' => 'Zdroj',
		'language' => 'Jazyk',
		'relation' => 'Vztah',
		'coverage' => 'Pokrytí',
		'rights' => 'Autorská práva',
		's:license' => 'Licence',
		's:z' => 'Původní umístění',
		// s:tag => Tagy
	);
}

function dc_license($text)
{
	$licence = array(
		'freeware' => array('label'=>'freeware', 'url'=>''),
		'CC BY' => array('label'=>'Creative Commons Uveďte původ', 'url'=>'http://creativecommons.org/licenses/by/3.0/cz/'),
		'CC BY-SA' => array('label'=>'Creative Commons Uveďte původ-Zachovejte licenci', 'url'=>'http://creativecommons.org/licenses/by-sa/3.0/cz/'),
		'CC BY-ND' => array('label'=>'Creative Commons Uveďte původ-Nezpracovávejte', 'url'=>'http://creativecommons.org/licenses/by-nd/3.0/cz/'),
		'CC BY-NC' => array('label'=>'Creative Commons Uveďte původ-Neužívejte komerčně', 'url'=>'http://creativecommons.org/licenses/by-nc/3.0/cz/'),
		'CC BY-NC-SA' => array('label'=>'Creative Commons Uveďte původ-Neužívejte dílo komerčně-Zachovejte licenci', 'url'=>'http://creativecommons.org/licenses/by-nc-sa/3.0/cz/'),
		'CC BY-NC-ND' => array('label'=>'Creative Commons Uveďte původ-Neužívejte komerčně-Nezpracovávejte', 'url'=>'http://creativecommons.org/licenses/by-nc-nd/3.0/cz/'),
	);
	if (isset($licence[$text])) {
		if (!empty($licence[$text]['url'])) {
			return '<a href="'.$licence[$text]['url'].'">'.$licence[$text]['label'].'</a>';
		} else {
			return $licence[$text]['label'];
		}
	}		
	return $text;
}

function dc_language($text)
{
	$jazyky = array(
		'cs'=>'čeština',
		'en'=>'English',
		'eo'=>'Esperanto',
		'sk'=>'slovenčina'
	);
	if ($jazyky[$text]) {
		return $jazyky[$text];
	}
	return $text;
}

function dc_format($field, $text)
{
	if (preg_match('~^https?://.+~', $text)) {
		return '<a href="'.$text.'">'.htmlspecialchars($text).'</a>';
	} elseif ($field=='language') {
		return dc_language($text);
	} elseif ($field=='s:license') {
		return dc_license($text);
	}
	return htmlspecialchars($text);  
}

function get_author()
{
	if (!article_custom_field('metadata_wtf')) {
		return '';
	}
	$metadata = parse_wtf(article_custom_field('metadata_wtf'));
	$autori = array();
	if (!empty($metadata['.']['creator'])) {
		$autori = array_merge($autori, $metadata['.']['creator']);
	}
	if (!empty($metadata['.']['contributor'])) {
		$autori = array_merge($autori, $metadata['.']['contributor']);
	}
	if (count($autori)==0) {
		$autori[] = '<i>anonymní autor</i>';
	}
	return implode(', ', $autori);
}