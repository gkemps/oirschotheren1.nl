<?php
	require_once("functions/layout.inc.php");
	head();
	?>
	<div class="tabber">
		<div class="tabbertab">
			<h3>Grafisch</h3>
			<?php
				include("wedstrijd_grafisch.php");
			?>
		</div>
		
		<div class="tabbertab">
			<h3>Score sheet</h3>
			<?php
				include("wedstrijd_verslag.php");
			?>
		</div>
		
		<div class="tabbertab">
			<h3>Verslag</h3>
			<?php
				include("wedstrijd_verslag.php");
			?>
		</div>
	</div>
	<?php
	close();
?>