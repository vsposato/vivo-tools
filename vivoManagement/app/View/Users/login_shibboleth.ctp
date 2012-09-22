<div class="row-fluid">
	<div class="span12">
		<div class="hero-unit">
			<h1>VIVO Management Tools Suite</h1>
			<p>We have assembled several tools into one location to make management of VIVO easier, and more user friendly.</p>
			<?php if ( ! AuthComponent::user('id') ) {
				echo $this->BootstrapHtml->link('Shibboleth Sign-In', 'http://web-server.homelinux.net/secure', array('escape' => false, 'class' => 'btn btn-large btn-primary pull-right'));
			} ?>
		</div>
	</div>
</div>