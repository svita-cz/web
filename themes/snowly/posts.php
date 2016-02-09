<?php theme_include('partial/header'); ?>

<main class="container">
	<?php $cat = Registry::get('post_category');
		if ($cat && $cat->id!=2) { ?>
		<ol class="breadcrumb">
			<li><a href="/sbirky">Sbírky</a></li>
			<li class="active"><?php echo $cat->title; ?></li>
		</ol>
	<?php } ?>

	<?php if(has_posts()): ?>
		<?php while(posts()): ?>
		<article>
			<header>
				<h1><a href="<?php echo article_url(); ?>"><?php echo article_title(); ?></a></h1>
				<div class="meta">
				<?php 
				echo get_author();
				?>
				</div>
			</header>

			<?php 
      if (article_description()) {
        echo "<p>".article_description()."</p>";
      } else {
        echo split_content(article_markdown());
      }
      ?>
      
      <p><a href="<?php echo article_url(); ?>" rel="article">detaily</a></p>
		</article>
		<?php endwhile; ?>
	<?php endif; ?>

	<?php if(has_pagination()): ?>
	<ul class="pager">
		<?php if (category_id()==2) { ?>
		<li class="previous"><? echo posts_prev('&larr; starší'); ?></li>
		<li class="next"><? echo posts_next('novější &rarr;'); ?></li>
		<?php } else { ?>
		<li class="previous"><? echo posts_next('&larr; předchozí'); ?></li>
		<li class="next"><? echo posts_prev('další &rarr;'); ?></li>
		<?php } ?>
	</ul>
	<?php endif; ?>
</main>

<?php theme_include('partial/footer'); ?>