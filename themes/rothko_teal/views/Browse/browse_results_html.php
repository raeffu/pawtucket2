<?php
/* ----------------------------------------------------------------------
 * views/Browse/browse_results_html.php : 
 * ----------------------------------------------------------------------
 * CollectiveAccess
 * Open-source collections management software
 * ----------------------------------------------------------------------
 *
 * Software by Whirl-i-Gig (http://www.whirl-i-gig.com)
 * Copyright 2014-2016 Whirl-i-Gig
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
 
	$qr_res 					= $this->getVar('result');				// browse results (subclass of SearchResult)
	$va_facets 					= $this->getVar('facets');				// array of available browse facets
	$va_criteria 				= $this->getVar('criteria');			// array of browse criteria
	$vs_browse_key 				= $this->getVar('key');					// cache key for current browse
	$va_access_values 			= $this->getVar('access_values');		// list of access values for this user
	$vn_hits_per_block 			= (int)$this->getVar('hits_per_block');	// number of hits to display per block
	$vn_start		 			= (int)$this->getVar('start');			// offset to seek to before outputting results
	$vn_is_advanced				= (int)$this->getVar('is_advanced');
	$vb_showLetterBar			= (int)$this->getVar('showLetterBar');	
	$va_letter_bar				= $this->getVar('letterBar');
	$vs_letter					= $this->getVar('letter');
	$vn_row_id 					= $this->request->getParameter('row_id', pInteger);
	
	$va_views					= $this->getVar('views');
	$vs_current_view			= $this->getVar('view');
	$va_view_icons				= $this->getVar('viewIcons');
	
	$vs_current_sort			= $this->getVar('sort');
	$vs_sort_dir				= $this->getVar('sort_direction');
	
	$vs_table 					= $this->getVar('table');
	$t_instance					= $this->getVar('t_instance');
	
	$vb_is_search				= ($this->request->getController() == 'Search');
	
	
	$va_options					= $this->getVar('options');
	$vs_extended_info_template 	= caGetOption('extendedInformationTemplate', $va_options, null);
	$vb_ajax					= (bool)$this->request->isAjax();
	$va_browse_info 			= $this->getVar("browseInfo");
	$vs_sort_control_type 		= caGetOption('sortControlType', $va_browse_info, 'dropdown');
	$o_config 					= $this->getVar("config");
	$vs_result_col_class 		= $o_config->get('result_col_class');
	$vs_refine_col_class 		= $o_config->get('refine_col_class');
	$va_export_formats 			= $this->getVar('export_formats');

	
	$va_add_to_set_link_info 	= caGetAddToSetInfo($this->request);

if($this->request->getParameter("detailNav", pInteger)){
	# --- this is the filter/sort nav bar above related objects on authority detail pages.
	# --- get the current search
	if (sizeof($va_criteria) > 0) {
		foreach($va_criteria as $va_criterion) {
			if ($va_criterion['facet_name'] == '_search') {
				$vs_search = $va_criterion['id'];
				break;
			}
		}
	}
	
	$vb_show_filter = false; # collection pages have filter option
	$vs_current_facet = null;
	foreach($va_criteria as $va_criterion) {
		if (in_array($va_criterion['facet_name'], ['collection', 'current_collection', 'past_collection'])) { 
			$vb_show_filter = true; 
			$vs_current_facet = $va_criterion['facet_name'];
			$vn_record_id = $va_criterion['id'];
			$t_collection = new ca_collections($vn_record_id);
			if ($t_collection->get('ca_collections.type_id', array('convertCodesToDisplayText' => true)) == "Sketchbook") {
				$vb_is_sketchbook = true; 
			}
			break; 
		}
	}
	$vs_search_target = "artworks";
?>
	<div class="row" style="clear:both; position:relative;">
		<div class="col-sm-12">	
			<div class="viewAll">
<?php 
				$vs_value = explode('.',$va_criteria[0]['value']);
				$vs_search_value = $vs_value[0];
				$vs_string = null;
				if (in_array($va_criteria[0]['facet_name'], ['collection', 'current_collection']))  {
					print caNavLink($this->request, 'View All', '', '', 'Browse', 'works_in_collection/facet/collection/id/'.$this->request->getParameter('id', pInteger)); 
				} else if (($va_criteria[0]['facet_name'] == '_search') && (preg_match("!^occurrence_id:([\d]+)$!", $vs_search_value, $va_matches))) {
				    if($this->request->getParameter('type', pString) == 'reference') {
				        print caNavLink($this->request, 'View All', '', '', 'Browse', 'works_in_occurrence/facet/reference/id/'.$va_matches[1]); 
				    } else {
					    print caNavLink($this->request, 'View All', '', '', 'Browse', 'works_in_occurrence/facet/exhibition/id/'.$va_matches[1]); 
					}
				}	
				
?>		
			</div>		
			<div class="btn-group sortResults">
<?php
				$vs_sort_display = str_replace('+', ' ', $vs_current_sort);
?>			
				<span class="sortMenu" data-toggle="dropdown">Sort by: <span class="sortValue"><?php print ucfirst($vs_sort_display); ?><i class='fa fa-chevron-down'></i></span></span>
				<ul class="dropdown-menu " role="menu">
<?php
					if($vs_sort_control_type == 'dropdown'){
						if(is_array($va_sorts = $this->getVar('sortBy')) && sizeof($va_sorts)) {
							
							if ($vb_show_filter){ 
								foreach($va_sorts as $vs_sort => $vs_sort_flds) {
									if ($vs_current_sort === $vs_sort) {
										print "<li><a href='#' onClick='return false;'><div class='circleSelected'></div>{$vs_sort}</a></li>\n";
									} else {
										print '<li><a href="#" onClick="loadResults(\''.caNavUrl($this->request, '', 'Browse', 'works_in_collection', array('facet' => $vs_facet, 'id' => $this->request->getParameter('id', pInteger), 'detailNav' => '1', 'key' => $vs_browse_key, 'sort' => urlencode($vs_sort), 'view' => $vs_current_view), array('dontURLEncodeParameters' => true)).'\', \'\'); return false;"><div class="circleSelect"></div>'.$vs_sort.'</a></li>';
									}
								}
								print "<li class='divider'></li>\n";
								print '<li><a href="#" onClick="loadResults(\''.caNavUrl($this->request, '', 'Browse', 'works_in_collection', array('facet' => $vs_facet, 'id' => $this->request->getParameter('id', pInteger), 'detailNav' => '1', 'key' => $vs_browse_key, 'sort' => urlencode($vs_current_sort), 'view' => $vs_current_view, 'direction' => 'asc'), array('dontURLEncodeParameters' => true)).'\', \'\'); return false;">'.(($vs_sort_dir == 'asc') ? '<div class="circleSelected"></div>' : '<div class="circleSelect"></div>')._t("Ascending").'</a></li>';
								print '<li><a href="#" onClick="loadResults(\''.caNavUrl($this->request, '', 'Browse', 'works_in_collection', array('facet' => $vs_facet, 'id' => $this->request->getParameter('id', pInteger), 'detailNav' => '1', 'key' => $vs_browse_key, 'sort' => urlencode($vs_current_sort), 'view' => $vs_current_view, 'direction' => 'desc'), array('dontURLEncodeParameters' => true)).'\', \'\'); return false;">'.(($vs_sort_dir == 'desc') ? '<div class="circleSelected"></div>' : '<div class="circleSelect"></div>')._t("Descending").'</a></li>';

							} else {
								foreach($va_sorts as $vs_sort => $vs_sort_flds) {
									if ($vs_current_sort === $vs_sort) {
										print "<li><a href='#' onClick='return false;'><div class='circleSelected'></div>{$vs_sort}</a></li>\n";
									} else {
										print '<li><a href="#" onClick="loadResults(\''.caNavUrl($this->request, '', 'Search', $vs_search_target, array('detailNav' => '1', 'key' => $vs_browse_key, 'sort' => $vs_sort, 'view' => $vs_current_view), array('dontURLEncodeParameters' => true)).'\', \'\'); return false;"><div class="circleSelect"></div>'.$vs_sort.'</a></li>';
									}
								}
								print "<li class='divider'></li>\n";
								print "<li class='dropdown-header'>"._t("Sort order:")."</li>\n";
								print '<li><a href="#" onClick="loadResults(\''.caNavUrl($this->request, '', 'Search', $vs_search_target, array('detailNav' => '1', 'key' => $vs_browse_key, 'sort' => $vs_current_sort, 'view' => $vs_current_view, 'direction' => 'asc'), array('dontURLEncodeParameters' => true)).'\', \'\'); return false;">'.(($vs_sort_dir == 'asc') ? '<div class="circleSelected"></div>' : '<div class="circleSelect"></div>')._t("Ascending").'</a></li>';
								print '<li><a href="#" onClick="loadResults(\''.caNavUrl($this->request, '', 'Search', $vs_search_target, array('detailNav' => '1', 'key' => $vs_browse_key, 'sort' => $vs_current_sort, 'view' => $vs_current_view, 'direction' => 'desc'), array('dontURLEncodeParameters' => true)).'\', \'\'); return false;">'.(($vs_sort_dir == 'desc') ? '<div class="circleSelected"></div>' : '<div class="circleSelect"></div>')._t("Descending").'</a></li>';
							}
						}
					}
?>
				</ul>
			</div><!-- end btn-group -->
			<div id="bViewButtons">
<?php
			if(is_array($va_views) && (sizeof($va_views) > 1)){
				foreach($va_views as $vs_view => $va_view_info) {
					if ($vs_current_view == $vs_view) {
						print '<a href="#" class="active" onClick="return false;"><span class="glyphicon '.$va_view_icons[$vs_view]['icon'].'"></span></a> ';
					} else {
?>
						<a href="#" class="disabled" onClick="loadResults('<?php print caNavUrl($this->request, '', 'Search', $vs_search_target, array('detailNav' => '1', 'key' => $vs_browse_key, 'view' => $vs_view), array('dontURLEncodeParameters' => true)); ?>', ''); return false;"><span class="glyphicon <?php print $va_view_icons[$vs_view]['icon']; ?>"></span></a>
<?php
					}
				}
			}
?>			
			</div><!-- end bViewButtons -->				
<?php
			if($vb_show_filter && !$vb_is_sketchbook){
				$va_options = ['collection' => 'all', 'current_collection' => 'current', 'past_collection' => 'previous'];
				$t_lists = new ca_lists();
				if (!($vn_collection_id = $this->request->getParameter('collection_id', pInteger))) { $vn_collection_id = $this->request->getParameter('id', pInteger);}
?>
				<div class="btn-group sortResults">
					<span class="sortMenu" data-toggle="dropdown">Collection Status: <span class="sortValue"><?php print ucfirst($va_options[$vs_current_facet]); ?><i class='fa fa-chevron-down'></i></span></span>
					<ul class="dropdown-menu " role="menu">
<?php
							# --- add any as an option
							foreach($va_options as $vs_facet => $vs_label) {
								if ($vs_facet == $vs_current_facet) {
									print "<li><a href='#' onClick='return false;'><div class='circleSelected'></div>{$vs_label}</a></li>\n";
								} else {
									print '<li><a href="#" onClick="loadResults(\''.caNavUrl($this->request, '', 'Browse', 'works_in_collection', ['facet' => $vs_facet, 'id' => $vn_collection_id, 'detailNav' => '1', 'sort' => $vs_current_sort, 'view' => $vs_current_view], ['dontURLEncodeParameters' => true]).'\'); return false;"><div class="circleSelect"></div>'.$vs_label.'</a></li>';
								}
							}
?>
					</ul>
				</div><!-- end btn-group -->
<?php
			}
?>
			<H6 style='margin-top:-30px;'>
<?php
				print _t('<span class="hitCount">%1 %2</span>', $qr_res->numHits(), ($qr_res->numHits() !== 1) ? $va_browse_info["labelPlural"] : $va_browse_info["labelSingular"]);	

?>		
			</H6>
		</div><!-- end col -->
	</div><!-- end row -->
	<br/><br/>
	<script type="text/javascript">		
		function loadResults(url, searchParam) {
			jQuery("#browseResultsContainer").data('jscroll', null);
			jQuery("#browseResultsContainer").load(url, {'search': searchParam}, function() {
				jQuery("#browseResultsContainer").jscroll({
					autoTrigger: true,
					loadingHtml: "<?php print caBusyIndicatorIcon($this->request).' '.addslashes(_t('Loading...')); ?>",
					padding: 20,
					nextSelector: "a.jscroll-next"
				});
			});
		}
	</script>
<?php
}	
if (!$vb_ajax) {	// !ajax
	$va_browse_types = caGetBrowseTypes();
	$vs_current_browse = $this->getVar('browse_type');
	print '<div class="container"><div class="row"><div class="col-sm-1"></div><div class="col-sm-10">';
	print '<div class="container"><div class="browseTargets row">';
	
	print "<div class='col-xs-12 col-md-12 col-lg-12'>";
	foreach ($va_browse_types as $va_browse_type => $va_browse_info_list) {
		if (in_array($va_browse_type, ['works_in_collection', 'works_in_occurrence'])) { continue; }
		if ($vs_current_browse == $va_browse_type) {
			$vs_active_class = "active";
		} else {
			$vs_active_class = null;
		}
		print '<div class="browseTargetLink '.$va_browse_type.'">'.caNavLink($this->request, $va_browse_info_list['displayName'], $vs_active_class, '', 'Browse', $va_browse_type).'</div>';
	}
	print "</div>";

	print '</div></div>';

		print $this->render("Browse/browse_refine_subview_html.php");
?>	
		</div><div class="col-sm-1"></div></div></div>
<div class="container">	
<div class="row" style="clear:both;">
	<div class='col-sm-10 col-sm-offset-1'>
<?php 
			if($vs_sort_control_type == 'list'){
				if(is_array($va_sorts = $this->getVar('sortBy')) && sizeof($va_sorts)) {
					print "<H5 id='bSortByList'><ul><li><strong>"._t("Sort by")."</strong></li>\n";
					$i = 0;
					foreach($va_sorts as $vs_sort => $vs_sort_flds) {
						$i++;
						if ($vs_current_sort === $vs_sort) {
							print "<li class='selectedSort'>{$vs_sort}</li>\n";
						} else {
							print "<li>".caNavLink($this->request, $vs_sort, '', '*', '*', '*', array('view' => $vs_current_view, 'key' => $vs_browse_key, 'sort' => $vs_sort, '_advanced' => $vn_is_advanced ? 1 : 0))."</li>\n";
						}
						if($i < sizeof($va_sorts)){
							print "<li class='divide'>&nbsp;</li>";
						}
					}
					print "<li>".caNavLink($this->request, '<span class="glyphicon glyphicon-sort-by-attributes'.(($vs_sort_dir == 'asc') ? '' : '-alt').'"></span>', '', '*', '*', '*', array('view' => $vs_current_view, 'key' => $vs_browse_key, 'direction' => (($vs_sort_dir == 'asc') ? _t("desc") : _t("asc")), '_advanced' => $vn_is_advanced ? 1 : 0))."</li>";
					print "</ul></H5>\n";
				}
			}
?>
			<H2>
<?php
            $vn_num_hits = $qr_res->numHits();
            	#print _t('<span class="hitCount">%1 %2</span>', $qr_res->numHits(), ($vn_num_hits == 1) ? ($va_browse_info["labelSingular"]) ? $va_browse_info["labelSingular"] : $t_instance->getProperty('NAME_SINGULAR') : ($va_browse_info["labelPlural"]) ? $va_browse_info["labelPlural"] : $t_instance->getProperty('NAME_PLURAL'));	

				print _t('<span class="hitCount">%1 %2</span>', $qr_res->numHits(), ($qr_res->numHits() !== 1) ? $va_browse_info["labelPlural"] : $va_browse_info["labelSingular"]);	

?>		
			</H2>
				
			<div class="btn-group sortResults">
<?php			
				$vs_sort_display = str_replace('+', ' ', $vs_current_sort);
?>
				<span class="sortMenu" data-toggle="dropdown">Sort by: <span class="sortValue"><?php print ucfirst($vs_sort_display); ?><i class='fa fa-chevron-down'></i></span></span>
				<ul class="dropdown-menu " role="menu">
<?php
					if($qr_res->numHits() && (is_array($va_add_to_set_link_info) && sizeof($va_add_to_set_link_info))){
						print "<li><a href='#' onclick='caMediaPanel.showPanel(\"".caNavUrl($this->request, '', $va_add_to_set_link_info['controller'], 'addItemForm', array("saveLastResults" => 1))."\"); return false;'>"._t("Add all results to %1", $va_add_to_set_link_info['name_singular'])."</a></li>";
						print "<li><a href='#' onclick='jQuery(\".bSetsSelectMultiple\").toggle(); return false;'>"._t("Select results to add to %1", $va_add_to_set_link_info['name_singular'])."</a></li>";
						print "<li class='divider'></li>";
					}
					if($vs_sort_control_type == 'dropdown'){
						if(is_array($va_sorts = $this->getVar('sortBy')) && sizeof($va_sorts)) {
							foreach($va_sorts as $vs_sort => $vs_sort_flds) {
								if ($vs_current_sort === $vs_sort) {
									print "<li><a href='#'><div class='circleSelected'></div>".strtolower($vs_sort)."</a></li>\n";
								} else {
									print "<li>".caNavLink($this->request, "<div class='circleSelect'></div>".strtolower($vs_sort), '', '*', '*', '*', array('view' => $vs_current_view, 'key' => $vs_browse_key, 'sort' => $vs_sort, '_advanced' => $vn_is_advanced ? 1 : 0))."</li>\n";
								}
							}
							print "<li class='divider'></li>\n";
							print "<li>".caNavLink($this->request, (($vs_sort_dir == 'asc') ? '<div class="circleSelected"></div>' : '<div class="circleSelect"></div>')._t("ascending"), '', '*', '*', '*', array('view' => $vs_current_view, 'key' => $vs_browse_key, 'direction' => 'asc', '_advanced' => $vn_is_advanced ? 1 : 0))."</li>";
							print "<li>".caNavLink($this->request, (($vs_sort_dir == 'desc') ? '<div class="circleSelected"></div>' : '<div class="circleSelect"></div>')._t("descending"), '', '*', '*', '*', array('view' => $vs_current_view, 'key' => $vs_browse_key, 'direction' => 'desc', '_advanced' => $vn_is_advanced ? 1 : 0))."</li>";
						}
					
						if ((sizeof($va_criteria) > ($vb_is_search ? 1 : 0)) && is_array($va_sorts) && sizeof($va_sorts)) {

						}
					}				

?>
				</ul>
			</div><!-- end btn-group -->
			<div id="bViewButtons">
<?php
				if(is_array($va_views) && (sizeof($va_views) > 1)){
					foreach($va_views as $vs_view => $va_view_info) {
						if ($vs_current_view === $vs_view) {
							print '<a href="#" class="active"><span class="glyphicon '.$va_view_icons[$vs_view]['icon'].'"></span></a> ';
						} else {
							print caNavLink($this->request, '<span class="glyphicon '.$va_view_icons[$vs_view]['icon'].'"></span>', 'disabled', '*', '*', '*', array('view' => $vs_view, 'key' => $vs_browse_key)).' ';
						}
					}
				}
?>
			</div>			
			<H5>				
<?php
			if (sizeof($va_criteria) > 0) {
				$i = 0;
				print "<div style='height:15px;clear:both;'></div>";
				#print "<span class='filteringBy'>Filtering by </span>";
				foreach($va_criteria as $va_criterion) {
					#print "<strong class='criterion'>".strtolower($va_criterion['facet']).':</strong>';
					if ($va_criterion['facet_name'] != '_search') {
						print caNavLink($this->request, '<button type="button" class="btn btn-default btn-sm refine">'.ucfirst($va_criterion['value']).' '.caGetThemeGraphic($this->request, 'closewhite.png', array('class' => 'clearFacet')).'</button>', 'browseRemoveFacet', '*', '*', '*', array('removeCriterion' => $va_criterion['facet_name'], 'removeID' => $va_criterion['id'], 'view' => $vs_current_view, 'key' => $vs_browse_key));
					}else{
						print ' <button type="button" class="btn btn-default btn-sm refine">'.ucfirst($va_criterion['value']).'</button>';
						$vs_search = $va_criterion['value'];
					}
					$i++;
					if($i < sizeof($va_criteria)){
						print " ";
					}
					$va_current_facet = $va_facets[$va_criterion['facet_name']];
					if((sizeof($va_criteria) == 1) && !$vb_is_search && $va_current_facet["show_description_when_first_facet"] && ($va_current_facet["type"] == "authority")){
						$t_authority_table = new $va_current_facet["table"];
						$t_authority_table->load($va_criterion['id']);
						$vs_facet_description = $t_authority_table->get($va_current_facet["show_description_when_first_facet"]);
					}
				}
			}
			if(is_array($va_facets) && sizeof($va_facets)){
?>
			<a href='#' id='bRefineButton' onclick='jQuery("#bRefine").toggle(); return false;'><i class="fa fa-table"></i></a>
<?php
			}
			if(is_array($va_add_to_set_link_info) && sizeof($va_add_to_set_link_info)){
				print "<a href='#' class='bSetsSelectMultiple' id='bSetsSelectMultipleButton' onclick='jQuery(\"#setsSelectMultiple\").submit(); return false;'><button type='button' class='btn btn-default btn-sm'>"._t("Add selected results to %1", $va_add_to_set_link_info['name_singular'])."</button></a>";
			}

			if($vs_facet_description){
				print "<div class='bFacetDescription'>".$vs_facet_description."</div>";
			}
			if (sizeof($va_criteria) > ($vb_is_search ? 1 : 0)) {
				print "<span>".caNavLink($this->request, "<button type='button' class='btn btn-default clear'>"._t("Clear All")."</button>", '', '*', '*', '*', array('view' => $vs_current_view, 'key' => $vs_browse_key, 'clear' => 1, '_advanced' => $vn_is_advanced ? 1 : 0))."</span>";
			}
			
?>		
			</H5>	
				
			<div class="row">
				<div class="col-sm-9">
<?php
				if(($vb_showLetterBar) && (($vs_current_sort == "Title") | ($vs_current_sort == "Name"))){
					print "<div id='bLetterBar'>";
					foreach(array_keys($va_letter_bar) as $vs_l){
						if(trim($vs_l)){
							print caNavLink($this->request, $vs_l, ($vs_letter == $vs_l) ? 'selectedLetter' : '', '*', '*', '*', array('key' => $vs_browse_key, 'l' => $vs_l))." ";
						}
					}
					print " | ".caNavLink($this->request, _t("All"), (!$vs_letter) ? 'selectedLetter' : '', '*', '*', '*', array('key' => $vs_browse_key, 'l' => 'all')); 
					print "</div>";
				}
?>				
				</div><!-- enf col -->
				<div class="col-sm-3">
		
				</div><!-- end col -->
			</div><!-- end row -->

		<div style="clear:both;height:0px;"></div>
		<form id="setsSelectMultiple">

			<div id="browseResultsContainer">
<?php
		if($vb_is_search && !$qr_res->numHits() && $vs_search){
			# --- try to display did you mean results if available
			$o_search = caGetSearchInstance($vs_table);
			if (sizeof($va_suggestions = $o_search->suggest($vs_search, array('request' => $this->request)))) {
				$va_suggest_links = array();
				foreach($va_suggestions as $vs_suggestion){
					$va_suggest_links[] = caNavLink($this->request, $vs_suggestion, '', '*', '*', '*', array('search' => $vs_suggestion, 'sort' => $vs_current_sort, 'view' => $vs_current_view));
				}
				
				if (sizeof($va_suggest_links) > 1) {
					print "<div class='col-sm-12'>"._t("Did you mean one of these: %1?", join(', ', $va_suggest_links))."</div>";
				} else {
					print "<div class='col-sm-12'>"._t("Did you mean %1?", join(', ', $va_suggest_links))."</div>";
				}
			}
		}
} // !ajax

# --- check if this result page has been cached
# --- key is MD5 of browse key, sort, sort direction, view, page/start, items per page, row_id
$vs_cache_key = md5($vs_browse_key.$vs_current_sort.$vs_sort_dir.$vs_current_view.$vn_start.$vn_hits_per_block.$vn_row_id);
if(($o_config->get("cache_timeout") > 0) && ExternalCache::contains($vs_cache_key,'browse_results')){
	print ExternalCache::fetch($vs_cache_key, 'browse_results');
}else{
	$vs_result_page = $this->render("Browse/browse_results_{$vs_current_view}_html.php");
	ExternalCache::save($vs_cache_key, $vs_result_page, 'browse_results');
	print $vs_result_page;
}		

if (!$vb_ajax) {	// !ajax
?>
			</div><!-- end browseResultsContainer -->

		</div><!-- end row -->
		</form>
	</div><!-- end col-8 -->

	
	
</div><!-- end row -->	
</div><!-- end container -->
<script type="text/javascript">
	jQuery(document).ready(function() {
		jQuery('#browseResultsContainer').jscroll({
			autoTrigger: true,
			loadingHtml: "<?php print caBusyIndicatorIcon($this->request).' '.addslashes(_t('Loading...')); ?>",
			padding: 800,
			nextSelector: 'a.jscroll-next'
		});
<?php
		if($vn_row_id){
?>
			window.setTimeout(function() {
				$("window,body,html").scrollTop( $("#row<?php print $vn_row_id; ?>").offset().top);
			}, 0);
<?php
		}
		if(is_array($va_add_to_set_link_info) && sizeof($va_add_to_set_link_info)){
?>
		jQuery('#setsSelectMultiple').submit(function(e){		
			objIDs = [];
			jQuery('#setsSelectMultiple input:checkbox:checked').each(function() {
			   objIDs.push($(this).val());
			});
			objIDsAsString = objIDs.join(';');
			caMediaPanel.showPanel('<?php print caNavUrl($this->request, '', $va_add_to_set_link_info['controller'], 'addItemForm', array("saveSelectedResults" => 1)); ?>/object_ids/' + objIDsAsString);
			e.preventDefault();
			return false;
		});
<?php
		}
?>
	});

</script>
<?php
		print $this->render('Browse/browse_panel_subview_html.php');
} //!ajax
