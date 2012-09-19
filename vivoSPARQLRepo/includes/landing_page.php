<div class="hero-unit">
	<h1>Welcome to the VIVO SPARQL Repository</h1>
	<p>This serves as a general purpose repository for all SPARQL queries, data management tools, and other useful utility functions for managing your VIVO.</p>
	<?php
		// Check to make sure if the user is logge
		if (! isset($_SESSION['isLoggedIn']) || $_SESSION['isLoggedIn'] === false ) {
	?>
		<p><a class="btn btn-primary btn-large" href="/secure">Log In</a></p>
	<?php
		}
	?>
</div>
