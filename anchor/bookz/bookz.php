<?php

error_reporting(E_ALL);

// speed things up with gzip plus ob_start() is required for csv export
if(!ob_start('ob_gzhandler'))
	ob_start();

header('Content-Type: text/html; charset=utf-8');

include('lazy_mofo.php');

echo "
<!DOCTYPE html>
<html>
<head>
	<meta charset='UTF-8'>
	<title>admin</title>
	<link rel='stylesheet' type='text/css' href='/lm_style.css'>
</head>
<body>
<a href='/admin'>administrace</a> 
<a href='/admin/bookz'>výpis</a> 
<a href='/admin/bookz?action=edit'>přidat</a> 
<hr>
"; 

$conns = Config::get('db.connections');
$connId = Config::get('db.default');

// enter your database host, name, username, and password
$db_host = $conns[$connId]['hostname'];
$db_name = $conns[$connId]['database'];
$db_user = $conns[$connId]['username'];
$db_pass = $conns[$connId]['password'];

// connect with pdo 
try {
	$dbh = new PDO("mysql:host=$db_host;dbname=$db_name;", $db_user, $db_pass);
}
catch(PDOException $e) {
	die('pdo connection error: ' . $e->getMessage());
}

// create LM object, pass in PDO connection
$lm = new lazy_mofo($dbh); 


// table name for updates, inserts and deletes
$lm->table = 'bookz_record';


// identity / primary key for table
$lm->identity_name = 'id';


// optional, make friendly names for fields
//$lm->rename['country_id'] = 'Country';


// optional, define input controls on the form

$lm->form_input_control['id_type'] = 'select id, name from bookz_type; --select';
$lm->form_input_control['is_published'] = '--checkbox';

//$lm->form_input_control['photo'] = '--image';
//$lm->form_input_control['is_active'] = "select 1, 'Yes' union select 0, 'No' union select 2, 'Maybe'; --radio";
//$lm->form_input_control['country_id'] = 'select country_id, country_name from country; --select';


// optional, define editable input controls on the grid
//$lm->grid_input_control['is_active'] = '--checkbox';


// optional, define output control on the grid 
//$lm->grid_output_control['contact_email'] = '--email'; // make email clickable
//$lm->grid_output_control['photo'] = '--image'; // image clickable  


// new in version >= 2015-02-27 all searches have to be done manually
//$lm->grid_show_search_box = true;


// optional, query for grid(). LAST COLUMN MUST BE THE IDENTITY for [edit] and [delete] links to appear
$lm->grid_sql = "
select  r.id,
t.name as typename,
r.title as title,
r.is_published,
r.short_desc,
p.title as parent,
r.id
from bookz_record r
join bookz_type t on r.id_type=t.id
left join bookz_record p on r.id_parent=p.id
";
/*
$lm->grid_sql_param[':_search'] = '%' . trim(@$_REQUEST['_search']) . '%';
*/

// optional, define what is displayed on edit form. identity id must be passed in also.  
/*$lm->form_sql = "
select 
  market_id
, market_name
, country_id
, photo
, contact_email
, is_active
, create_date
, notes 
from  market 
where market_id = :market_id
";
$lm->form_sql_param[":$lm->identity_name"] = @$_REQUEST[$lm->identity_name]; 
*/

// optional, validation. input:  regular expression (with slashes), error message, tip/placeholder
// first element can also be a user function or 'email'
//$lm->on_insert_validate['market_name'] = array('/.+/', 'Missing Market Name', 'this is required'); 
//$lm->on_insert_validate['contact_email'] = array('email', 'Invalid Email', 'this is optional', true); 


// copy validation rules to update - same rules
//$lm->on_update_validate = $lm->on_insert_validate;  


// use the lm controller
$lm->run();
echo '<hr>';
echo '<endora>';
echo "</body></html>";