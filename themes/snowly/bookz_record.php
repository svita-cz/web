<?php
$R = Registry::get('record');
$metadata = parse_wtf($R->metadata);
?>


<?php theme_include('partial/header'); ?>

<main class="container">
	<article>
		<?php 
		if (isset($R->parent)) {
			echo '<a href="/bookz/'.$R->parent->id.'"><span class="glyphicon glyphicon-chevron-up"></span> '.$R->parent->title.'</a>';
		}
		?>
	
		<header>
			<h1><?php echo page_title(); ?></h1>
		</header>

		<?php  if ($R->short_desc) echo '<p>' . $R->short_desc . '</p>'; ?>
		
		<?php
	$download = array();
	if ($R->url_list) {
            $download = parse_wtf($R->url_list);
	} elseif ($R->primary_url) {
		$download = array('.'=>array('external_web'=>array($R->primary_url)));
	}
            if (!isset($download['.'])) $download['.'] = array();
            foreach ($download['.'] as $format=>$urls) {
              foreach ($urls as $url) {
               if ($format=='external') {
                  echo '<a href="'.$url.'" class="btn btn-primary btn-lg btn-block"><span class="glyphicon glyphicon-link"></span> Přejít na stahování</a>';
		} elseif ($format=='external_web') {
                  echo '<a href="'.$url.'" class="btn btn-primary btn-lg btn-block"><span class="glyphicon glyphicon-link"></span> Přejít na web</a>';
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
             
          ?>
	
	<?php
		if ($R->long_desc) {
			echo '<hr>'. parse($R->long_desc) . '<hr>';
		}
	?>
	
	<?php
          if ($R->metadata) { 
            $dc = get_dc_fields();
            $metadata = parse_wtf($R->metadata);
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
	  
	<?php if (count($R->child)) { ?>
		<hr>
		<?php foreach ($R->child as $ch) { ?>
		<article>
			<header>
				<h1><?php echo $ch->title; ?></h1>
				<div class="meta">
				<?php 
				//echo get_author();
				?>
				</div>
			</header>

			<?php 
      if ($ch->short_desc) {
        echo "<p>".$ch->short_desc."</p>";
      }
      ?>
      
      <p><a href="/bookz/<?php echo $ch->id; ?>" rel="article">detaily</a></p>
		</article>
		<?php } ?>
	<?php } /*   */  ?>
	  
		
	</article>
</main>

<?php theme_include('partial/footer'); ?>