<?php theme_include('partial/header'); ?>

<main class="container">
	<article>
		<?php if(has_search_results()): ?>
		<header>
			<h1>Výsledky pro dotaz <b>&ldquo;<?php echo search_term(); ?>&rdquo;</b></h1>
		</header>

		<ul class="list-unstyled">
			<?php $i = 0; while(search_results()): $i++; ?>
			<li>
				<h2>
					<a href="<?php echo article_url(); ?>" title="<?php echo article_title(); ?>">
						<?php echo article_title(); ?>
					</a>
				</h2>
			</li>
			<?php endwhile; ?>
		</ul>
	</article>

	<?php if(has_pagination()): ?>
	<nav class="pagination">
		<div class="wrap">
			<?php echo search_prev(); ?>
			<?php echo search_next(); ?>
		</div>
	</nav>
	<?php endif; ?>

	<?php else: ?>
		<article>
			<header>
				<h1>Nenalezeno...</h1>
			</header>

			<p>Pro dotaz &ldquo;<?php echo search_term(); ?>&rdquo; nebylo nic nalezeno.</p>
			<ul>
				<li>prohledáváme jen informace o dílech, ostatní části stránek neprohledáváme</li>
				<li>zkuste zkrátit/zjednodušit váš dotaz, např. <code>Neslyšný kočkopes</code> stačí zkrátit na <code>kockopes</code></li>
				<li>jména zadávejte ve tvaru <code>Jméno Příjmení</code>, tvar <code>PŘÍJMENÍ, Jméno</code> nepodporujeme</li>
				<li>nemusíte zadávat diakritiku, vyhledávání na ní nebere ohled</li>
			</ul>
			<p><small>Nová verze vyhledávání se připravuje.</small></p>
		</article>
	<?php endif; ?>
</main>

<?php theme_include('partial/footer'); ?>