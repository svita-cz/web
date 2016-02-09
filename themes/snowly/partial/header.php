<!DOCTYPE html>
<html lang="pl">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, user-scalable=no">
	<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700&amp;subset=latin,latin-ext">
	<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Montserrat:400,700">
	<link rel="stylesheet" type="text/css" href="<?php echo theme_url('css/bootstrap.min.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo theme_url('css/styles.css'); ?>">
	<title><?php echo page_title("Page can't be fount."); ?> - <?php echo site_name(); ?></title>
</head>
<body>

<header id="top">
	<nav class="navbar navbar-default">
		<div class="navbar-header">
			<?php if(has_menu_items()): ?>
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse-menu">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<?php endif; ?>
			<a href="<?php echo base_url(); ?>" class="navbar-brand" style="background-image:url('<?php echo theme_url("img/avatar.png"); ?>');padding-left:55px;">
				<strong><?php echo site_name(); ?></strong>
			</a>
		</div>

		<?php if(has_menu_items()): ?>
		<div class="collapse navbar-collapse" id="navbar-collapse-menu">
			<ul class="nav navbar-nav navbar-right">
				<?php while(menu_items()): ?>
				<li<?php echo (menu_active() ? ' class="active"' : ''); ?>><a href="<?php echo menu_url(); ?>" title="<?php echo menu_title(); ?>"><?php echo menu_name(); ?></a></li>
				<?php endwhile; ?>
				<li><a href="#" title="Search..." data-target="#search"><span class="glyphicon glyphicon-search"></span></a></li>
			</ul>
		</div>
		<?php endif; ?>
	</nav>
</header>

<div class="container">
	<div class="alert alert-info"><span class="glyphicon glyphicon-flash"></span> Na stránkách probíhají úpravy.</div>
</div>