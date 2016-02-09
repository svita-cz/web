<?php theme_include('partial/header'); ?>

<main class="container">
	<ol class="breadcrumb">
			<li class="active">Sbírky</li>
	</ol>

	<article>
		<header>
			<h1><?php echo page_title(); ?></h1>
		</header>

		<?php echo page_content(); ?>
		
		<ul>
		<?php while(categories()): ?>
		<?php  if (category_id()==2) continue; ?>
			<li><a href="<?php echo category_url();?>"><?php echo category_title(); ?></a> - <?php echo category_description(); ?></li>
		<?php endwhile; ?>	
		</ul>

	</article>
</main>

<?php theme_include('partial/footer'); ?>