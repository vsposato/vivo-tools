<?php $title_for_page = "VIVO SPARQL Management Tool"; ?>
<?php
	require_once("includes/header.php");
	require_once("includes/navigation.php");
?>
<div class="container-fluid">
	<div class="row-fluid">
		<div class="span4">
			<?php
				if (isset($_SESSION['isLoggedIn']) && $_SESSION['isLoggedIn'] === true ) {
					require_once("includes/left_sidebar.php");
				}
			?>
		</div>
		<div class="span8">
			<?php require_once("includes/landing_page.php");?>
		</div>
	</div>
</div>
<?php require_once("includes/footer.php"); ?>