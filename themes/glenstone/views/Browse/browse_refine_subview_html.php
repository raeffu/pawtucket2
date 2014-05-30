<?php
/* ----------------------------------------------------------------------
 * views/Browse/browse_refine_subview_html.php : 
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
 
	$va_facets 			= $this->getVar('facets');				// array of available browse facets
	$va_criteria 		= $this->getVar('criteria');			// array of browse criteria
	$vs_key 			= $this->getVar('key');					// cache key for current browse
	$va_access_values 	= $this->getVar('access_values');		// list of access values for this user
	$vs_view			= $this->getVar('view');
	
	$vn_facet_display_length_initial = 7;
	$vn_facet_display_length_maximum = 60;
	
	if(is_array($va_facets) && sizeof($va_facets)){
		print "<div id='bMorePanel'><!-- long lists of facets are loaded here --></div>";
		print "<div id='bRefine'>";
		print "<H3>"._t("Filter by")."</H3>";
		foreach($va_facets as $vs_facet_name => $va_facet_info) {
			if (!is_array($va_facet_info['content']) || !sizeof($va_facet_info['content'])) { continue; }
			
			print "<h3><a href='#' class='more' id='{$vs_facet_name}_more_link' onclick='jQuery(\"#{$vs_facet_name}_more\").slideToggle(250); return false;'>".$va_facet_info['label_singular']."</a></h3>";

			print "<div id='{$vs_facet_name}_more' class='expand' style='display: none;'>";

			$vn_facet_size = sizeof($va_facet_info['content']);
			$vn_c = 0;
			foreach($va_facet_info['content'] as $va_item) {
				print "<div>".caNavLink($this->request, $va_item['label'], '', '*', '*','*', array('key' => $vs_key, 'facet' => $vs_facet_name, 'id' => $va_item['id'], 'view' => $vs_view))."</div>";
				$vn_c++;
				
			}
			print "</div>\n";
			$vs_link_close_text = _t("close", $vn_facet_size - $vn_facet_display_length_initial);
		}
		print "</div><!-- end bRefine -->\n";
?>
	<script type="text/javascript">
		jQuery(document).ready(function() {
			var offsetBrowseResultsContainer = $("H3:first").offset();
			var lastOffset = $("#bRefine").offset();
			$("body").data("lastOffsetTop", lastOffset.top);
			$(window).scroll(function() {
				if(($(document).scrollTop() < $(document).height() - ($("#bRefine").height() + 250)) && (($(document).scrollTop() < $("body").data("lastOffsetTop")) || ($(document).scrollTop() > ($("body").data("lastOffsetTop") + ($("#bRefine").height() - ($(window).height()/3)))))){
					var offset = $("#bRefine").offset();
					if($(document).scrollTop() < offsetBrowseResultsContainer.top){
						jQuery("#bRefine").offset({top: offsetBrowseResultsContainer.top, left: offset.left});
					}else{
						jQuery("#bRefine").offset({top: $(document).scrollTop(), left: offset.left});
					}
				}
				clearTimeout($.data(this, 'scrollTimer'));
				$.data(this, 'scrollTimer', setTimeout(function() {
					// do something
					var lastOffset = $("#bRefine").offset();
					$("body").data("lastOffsetTop", lastOffset.top);
					
				}, 250));
			});
		});
	</script>
<?php	
	}
?>