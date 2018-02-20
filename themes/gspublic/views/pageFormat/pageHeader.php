<?php
/* ----------------------------------------------------------------------
 * views/pageFormat/pageHeader.php : 
 * ----------------------------------------------------------------------
 * CollectiveAccess
 * Open-source collections management software
 * ----------------------------------------------------------------------
 *
 * Software by Whirl-i-Gig (http://www.whirl-i-gig.com)
 * Copyright 2014 Whirl-i-Gig
 *
 * For more information visit http://www.CollectiveAccess.org
 *
 * This program is free software; you may redistribute it and/or modify it under
 * the terms of the provided license as published by Whirl-i-Gig
 *
 * CollectiveAccess is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTIES whatsoever, including any implied warranty of 
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  
 *
 * This source code is free and modifiable under the terms of 
 * GNU General Public License. (http://www.gnu.org/copyleft/gpl.html). See
 * the "license.txt" file for details, or visit the CollectiveAccess web site at
 * http://www.CollectiveAccess.org
 *
 * ----------------------------------------------------------------------
 */
	$va_lightboxDisplayName = caGetLightboxDisplayName();
	$vs_lightbox_sectionHeading = ucFirst($va_lightboxDisplayName["section_heading"]);
	$va_classroomDisplayName = caGetClassroomDisplayName();
	$vs_classroom_sectionHeading = ucFirst($va_classroomDisplayName["section_heading"]);
	
	# Collect the user links: they are output twice, once for toggle menu and once for nav
	$va_user_links = array();
	if($this->request->isLoggedIn()){
		$va_user_links[] = '<li role="presentation" class="dropdown-header">'.trim($this->request->user->get("fname")." ".$this->request->user->get("lname")).', '.$this->request->user->get("email").'</li>';
		$va_user_links[] = '<li class="divider nav-divider"></li>';
		$va_user_links[] = "<li>".caNavLink($this->request, _t('Contribute'), '', '', 'contribute', 'objects', array())."</li>";
		if(caDisplayClassroom($this->request)){
			$va_user_links[] = "<li>".caNavLink($this->request, $vs_classroom_sectionHeading, '', '', 'Classroom', 'Index', array())."</li>";
		}
		$va_user_links[] = "<li>".caNavLink($this->request, _t('User Profile'), '', '', 'LoginReg', 'profileForm', array())."</li>";
		$va_user_links[] = "<li>".caNavLink($this->request, _t('Logout'), '', '', 'LoginReg', 'Logout', array())."</li>";
	} else {	
		if (!$this->request->config->get('dont_allow_registration_and_login') || $this->request->config->get('pawtucket_requires_login')) { $va_user_links[] = "<li><a href='#' onclick='caMediaPanel.showPanel(\"".caNavUrl($this->request, '', 'LoginReg', 'LoginForm', array())."\"); return false;' >"._t("Login")."</a></li>"; }
		if (!$this->request->config->get('dont_allow_registration_and_login')) { $va_user_links[] = "<li><a href='#' onclick='caMediaPanel.showPanel(\"".caNavUrl($this->request, '', 'LoginReg', 'RegisterForm', array())."\"); return false;' >"._t("Register")."</a></li>"; }
	}
	$vb_has_user_links = (sizeof($va_user_links) > 0);

?><!DOCTYPE html>
<html lang="en">
	<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0"/>
	<?php print MetaTagManager::getHTML(); ?>
	<?php print AssetLoadManager::getLoadHTML($this->request); ?>

	<title><?php print (MetaTagManager::getWindowTitle()) ? MetaTagManager::getWindowTitle() : $this->request->config->get("app_display_name"); ?></title>
	
	<script type="text/javascript">
		jQuery(document).ready(function() {
    		jQuery('#browse-menu').on('click mouseover mouseout mousemove mouseenter',function(e) { e.stopPropagation(); });
    	});
	</script>
<?php
	if(Debug::isEnabled()) {		
		//
		// Pull in JS and CSS for debug bar
		// 
		$o_debugbar_renderer = Debug::$bar->getJavascriptRenderer();
		$o_debugbar_renderer->setBaseUrl(__CA_URL_ROOT__.$o_debugbar_renderer->getBaseUrl());
		print $o_debugbar_renderer->renderHead();
	}
?>
</head>
<body class="initial">
	<div class="container headerContainer" style="max-width:none;">
		<div class="row topHeader">
			<div class="container">
				<div class="col-xs-12 col-sm-6">
			
<?php
				print caNavLink($this->request, caGetThemeGraphic($this->request, 'gsusaLogo.png'), "navbar-brand initialLogo", "", "","");
?>					
					<div style="clear:both;"></div>
				</div>
				<div class="col-sm-6 text-right">
					<form class="headerForm navbar-form role="search" action="<?php print caNavUrl($this->request, '', 'MultiSearch', 'Index'); ?>">
						<div class="formOutline">
							<div class="form-group">
								<input type="text" class="form-control" placeholder="" name="search">
							</div>
							<button type="submit" class="btn-search"><?php print caGetThemeGraphic($this->request, 'search.jpg'); ?></button>
						</div>
					</form>	
				</div>
			</div>
		</div>
		<nav class="navbar navbar-default yamm main-nav row" role="navigation">
				<!-- Brand and toggle get grouped for better mobile display -->
				<div class="navbar-header">
	<?php
		if ($vb_has_user_links) {
	?>
					<button type="button" class="navbar-toggle navbar-toggle-user" data-toggle="collapse" data-target="#user-navbar-toggle">
						<span class="sr-only">User Options</span>
						<span class="glyphicon glyphicon-user"></span>
					</button>
	<?php
		}
	?>
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-main-navbar-collapse-1">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
				</div>

			<!-- Collect the nav links, forms, and other content for toggling -->
				<!-- bs-user-navbar-collapse is the user menu that shows up in the toggle menu - hidden at larger size -->
	<?php
		if ($vb_has_user_links) {
	?>
				<div class="collapse navbar-collapse" id="user-navbar-toggle">
					<ul class="nav navbar-nav">
						<?php print join("\n", $va_user_links); ?>
					</ul>
				</div>
	<?php
		}
	?>
				<div class="collapse navbar-collapse" id="bs-main-navbar-collapse-1">

					<form class="navbar-form  navbarForm" role="search" action="<?php print caNavUrl($this->request, '', 'MultiSearch', 'Index'); ?>">
						<div class="formOutline">
							<div class="form-group">
								<input type="text" class="form-control" placeholder="Search" name="search">
							</div>
							<button type="submit" class="btn-search"><span class="glyphicon glyphicon-search"></span></button>
						</div>
					</form>
					<ul class="nav navbar-nav ">
						<li class='dropdown<?php print (($this->request->getController() == "About")&&($this->request->getAction() != "browse")) ? ' active' : ''; ?>' style="position:relative;">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">About</a>
							<ul class="dropdown-menu">
								<li><?php print caNavLink($this->request, _t("About the Cultural & Property Assets Department"), "", "", "About", "Collection"); ?></li>
								<li><?php print caNavLink($this->request, _t("About Girl Scouts of the USA"), "", "", "About", "GSUSA"); ?></li>
								<li><?php print caNavLink($this->request, _t("Rights, Reproduction and Usage"), "", "", "About", "Usage"); ?></li>
								<li><?php print caNavLink($this->request, _t("Research Services"), "", "", "About", "Services"); ?></li>
								<li><?php print caNavLink($this->request, _t("Internship and Volunteer Opportunities"), "", "", "About", "Opportunities"); ?></li>
							</ul>
						</li>
						<li <?php print (($this->request->getController() == "About")&&($this->request->getAction() == "browse")) ? 'class="active"' : ''; ?>><?php print caNavLink($this->request, _t("Discover"), "", "", "About", "browse"); ?></li>	
						<li <?php print ($this->request->getController() == "Gallery") ? 'class="active"' : ''; ?>><?php print caNavLink($this->request, _t("Gallery"), "", "", "Gallery", "Index"); ?></li>
						<li <?php print ($this->request->getController() == "Contact") ? 'class="active"' : ''; ?>><?php print caNavLink($this->request, _t("Contact"), "", "", "Contact", "Form"); ?></li>
						<li <?php print ($this->request->getController() == "Collections") ? 'class="active"' : ''; ?>><?php print caNavLink($this->request, _t("Research"), '', '', 'Collections', 'Index'); ?></li>
						<li <?php print ($this->request->getController() == "News") ? 'class="active"' : ''; ?>><?php print caNavLink($this->request, _t("News"), "", "", "News", ""); ?></li>

	<?php
						if(caDisplayLightbox($this->request)){
							print "<li ".(($this->request->getController() == "Lightbox") ? 'class="active"' : '').">".caNavLink($this->request, $vs_lightbox_sectionHeading, '', '', 'Lightbox', 'Index', array())."</li>";
						}

		if ($vb_has_user_links) {
	?>
					<ul class="nav navbar-nav " id="user-navbar">
						<li class="dropdown" style="position:relative;">
							<a href="#" class="dropdown-toggle icon" data-toggle="dropdown"><?php print caGetThemeGraphic($this->request, 'GS_OutlinedTrefoil_RGB_white_fill_small.png'); ?></a>
							<ul class="dropdown-menu"><?php print join("\n", $va_user_links); ?></ul>
						</li>
					</ul>
	<?php
		}
	?>				
					</ul>
				</div><!-- /.navbar-collapse -->
		</nav>
	</div>
	<div class="container"><div class="row"><div class="col-xs-12">
		<div id="pageArea" <?php print caGetPageCSSClasses(); ?>>
