<div class="navbar navbar-fixed-top">
	<div class="navbar-inner">
		<div class="container">
			<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</a>
			<a class="brand" href="#"><?php echo __(' VIVO Management Tools '); ?></a>
			<div class="nav-collapse">
				<ul class="nav">
					<li class="divider-vertical"></li>
					<?php
						if ( $this->request->params['controller'] == 'pages' && $this->request->params['controller'] == 'home' ) {
							echo $this->BootstrapHtml->tag('li', $this->BootstrapHtml->link($this->BootstrapHtml->tag('i', '', array('class' => 'icon-home icon-white')), array('plugin' => '', 'admin' => false, 'controller' => 'pages', 'action' => 'home'), array('escape' => false)), array('class' => 'active', 'escape' => false));
						} else {
							echo $this->BootstrapHtml->tag('li', $this->BootstrapHtml->link($this->BootstrapHtml->tag('i', '', array('class' => 'icon-home icon-white')), array('plugin' => '', 'admin' => false, 'controller' => 'pages', 'action' => 'home'), array('escape' => false)), array('escape' => false));
						}
						echo $this->BootstrapHtml->tag('li','',array('class' => 'divider-vertical'));
						if ( $this->request->params['controller'] == 'contacts' ) {
							echo $this->BootstrapHtml->tag('li', $this->BootstrapHtml->link('SPARQL Queries', array('plugin' => '', 'admin' => false, 'controller' => 'sparql_queries', 'action' => 'index'), array('escape' => false)), array('class' => 'active', 'escape' => false));
						} else {
							echo $this->BootstrapHtml->tag('li', $this->BootstrapHtml->link('SPARQL Queries', array('plugin' => '', 'admin' => false, 'controller' => 'sparql_queries', 'action' => 'index'), array('escape' => false)), array('escape' => false));
						}
						echo $this->BootstrapHtml->tag('li','',array('class' => 'divider-vertical'));
						if ( ! AuthComponent::user('id') ) {
							echo $this->BootstrapHtml->tag('li', $this->BootstrapHtml->link('Request Access', array('plugin' => '', 'admin' => false, 'controller' => 'profiles', 'action' => 'register'), array('escape' => false)), array('escape' => false));
						}
					?>
				</ul>
				<?php if ( ! AuthComponent::user('id') ) { ?>
					<ul class="nav pull-right">
						<li class="divider-vertical"></li>
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">Login<b class="caret"></b></a>
							<ul class="dropdown-menu">
								<?php
									echo $this->BootstrapHtml->tag('li', $this->BootstrapHtml->link('Shibboleth Sign-In', 'http://web-server.homelinux.net/secure', array('escape' => false)), array('escape' => false));
								?>
							</ul>
						</li>
					</ul>
				<?php } elseif ( AuthComponent::user('id') ) { ?>
						<ul class="nav pull-right">
							<li class="divider-vertical"></li>
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">My Account<b class="caret"></b></a>
								<ul class="dropdown-menu">
									<li><?php echo $this->BootstrapHtml->link(__('My Profile'), array('plugin' => '', 'admin' => false, 'controller' => 'users', 'action' => 'view', AuthComponent::user('id')), array('class' => ''));?></li>
									<?php if ( $this->Session->read('SHIBBOLETH_LOGIN') == false ) { ?>
										<li><?php echo $this->BootstrapHtml->link(__('Change Password'), '#changePasswordModal', array('class'=>'', 'data-toggle' => 'modal')); ?></li>
									<?php } ?>
									<li class="divider"></li>
									<li><?php echo $this->BootstrapHtml->link(__('Log Out'), array('plugin' => '', 'admin' => false,'controller' => 'users', 'action' => 'logout'), array('class'=>'')); ?></li>
								</ul>
							</li>
						</ul>
						<!-- Here we place the administrator menu -->
						<?php if ( $this->Session->read('FULL_ACCESS_GRANTED') ) {?>
							<ul class="nav pull-right">
								<li class="divider-vertical"></li>
								<li class="dropdown">
									<a href="#" class="dropdown-toggle" data-toggle="dropdown">Admin<b class="caret"></b></a>
									<ul class="dropdown-menu">
										<?php
											echo $this->BootstrapHtml->tag('li', $this->BootstrapHtml->link(__('Giftr Users'), array('plugin' => '', 'admin' => true, 'controller' => 'profiles', 'action' => 'index'), array('class' => '')), array('escape' => false));
											echo $this->BootstrapHtml->tag('li', $this->BootstrapHtml->link(__('ACL Manager'), array('plugin' => 'acl_manager', 'admin' => true, 'controller' => 'acl', 'action' => 'index'), array('class'=>'')), array('escape' => false));
										?>
									</ul>
								</li>
							</ul>
						<?php } ?>
				<?php } ?>
			</div><!--/.nav-collapse -->
		</div>
	</div>
</div>