<?php theme_include('partial/header'); ?>

<?php if(article_custom_field('article_image')): ?>
<div class="img-splash" style="background-image:url('<?php echo article_custom_field('article_image'); ?>')"></div>
<?php endif; ?>

<main class="container">
	<article id="article-<?php echo article_id(); ?>">
		<header>
			<h1><?php echo article_title(); ?></h1>
			<div class="meta">
				<?php if (article_custom_field('author_name')) {
				echo article_custom_field('author_name');
				} ?>
			</div>
		</header>
    <?php
    $chcemeSloupce = article_custom_field('thumb') || article_custom_field('download') || article_custom_field('metadata_wtf');
    ?>
		<div class="row">
			<?php if ($chcemeSloupce) { ?>
			<div class="col-md-6">
			<?php } ?>
      <?php if (article_description()) echo "<p>".article_description()."</p>"; ?>
			<?php echo article_markdown(); ?>
			<?php if ($chcemeSloupce) { ?>
			</div>
			<?php } ?>
      <?php if ($chcemeSloupce) { ?>
			<div class="col-md-6">
				<div class="panel panel-primary">
					<div class="panel-body">
					<?php if (article_custom_field('thumb')) { ?>
						<img src="<?php echo article_custom_field('thumb'); ?>" class="img-thumbnail img-responsive"/><br/><br/>
					<?php } ?>
          <?php
          if (article_custom_field('download')) {
            $download = parse_wtf(article_custom_field('download'));
            if (!$download['.']) $download['.'] = array();
            foreach ($download['.'] as $format=>$urls) {
              foreach ($urls as $url) {
               if ($format=='external') {
                  echo '<a href="'.$url.'" class="btn btn-primary btn-lg btn-block"><span class="glyphicon glyphicon-link"></span> Přejít na stahování</a>';                    
               } elseif ($format=='html') {
                  echo '<a href="'.$url.'" class="btn btn-primary btn-lg btn-block"><span class="glyphicon glyphicon-eye-open"></span> Číst online</a>';                    
               } else {
                     echo '<a href="'.$url.'" class="btn btn-primary btn-lg btn-block"><span class="glyphicon glyphicon-save"></span> Stáhnout jako '.strtoupper($format).'</a>';                    
               }
              }
            }
            if (!empty($download['backup'])) {
              echo '<br/><strong><span class="glyphicon glyphicon-fire"></span> Záložní kopie</strong><br/>';
              foreach ($download['backup'] as $format=>$urls) {
              foreach ($urls as $url) {
               if ($format=='external') {
                  echo '<a href="'.$url.'" class="btn"><span class="glyphicon glyphicon-link"></span> Přejít na stahování</a><br/>';                    
               } elseif ($format=='html') {
                  echo '<a href="'.$url.'" class="btn"><span class="glyphicon glyphicon-eye-open"></span> Číst online</a></br>';                    
               } else {
                     echo '<a href="'.$url.'" class="btn"><span class="glyphicon glyphicon-save"></span> Stáhnout jako '.strtoupper($format).'</a></br>';                    
               }
              }
            }
            }
             
          } ?>
					<?php
          if (article_custom_field('metadata_wtf')) { 
            $dc = get_dc_fields();
            $metadata = parse_wtf(article_custom_field('metadata_wtf'));
            echo '<hr>';
            echo '<strong><span class="glyphicon glyphicon-info-sign"></span> Informace o díle</strong>';
            echo '<dl>';
            foreach ($dc as $field=>$label) {
	             if (!empty($metadata['.'][$field])) {
		              echo '<dt>'.htmlspecialchars($label).'</dt>';
		              foreach ($metadata['.'][$field] as $name) {
			               echo '<dd>'.dc_format($field, $name).'</dd>';
		              }
	             }
            }
            if (!empty($metadata['.']['s:tag'])) {
                echo '<dt>Tagy</dt>';
                echo '<dd>'.implode(', ',$metadata['.']['s:tag']).'</dd>';
            }
            echo '</dl>';
          }
          ?>
          
					</div>
				</div>
			</div>
      <?php } // div druhý sloupec ?>
		</div>

		
		
		
	</article>

	<?php if(has_comments()): ?>
	<section id="comments">
		<h2><?php echo total_comments(article_id()); ?> comments</h2>
		<ul class="commentlist">
			<?php $i = 0; while(comments()): $i++; ?>
			<li>
				<header>
					<h2 data-id="<?php echo $i; ?>"><?php echo comment_name(); ?></h2>
					<time datetime="<?php echo date(DATE_W3C, article_time()); ?>"><?php echo date('d-m-Y H:i', article_time()); ?></time>
				</header>

				<p><?php echo comment_text(); ?></p>
			</li>
			<?php endwhile; ?>
		</ul>
	</section>
	<?php endif; ?>

	<?php if(comments_open()): ?>
	<form id="comment" class="form-horizontal" role="form" method="post" action="<?php echo comment_form_url(); ?>#comment">
		<?php echo comment_form_notifications(); ?>

		<?php echo comment_form_input_name('placeholder="Your name" class="form-control"'); ?>
		<?php echo comment_form_input_email('placeholder="Your email (won’t be published)" class="form-control"'); ?>
		<?php echo comment_form_input_text('placeholder="Your comment" class="form-control"'); ?>
		<button class="btn btn-default">Post Comment</button>
	</form>
	<?php endif; ?>
</main>

<?php theme_include('partial/footer'); ?>