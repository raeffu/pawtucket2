<?php
/* ----------------------------------------------------------------------
 * themes/default/views/bundles/ca_objects_default_html.php : 
 * ----------------------------------------------------------------------
 * CollectiveAccess
 * Open-source collections management software
 * ----------------------------------------------------------------------
 *
 * Software by Whirl-i-Gig (http://www.whirl-i-gig.com)
 * Copyright 2013-2015 Whirl-i-Gig
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
 
	$t_object = 			$this->getVar("item");
	$va_comments = 			$this->getVar("comments");
?>
<div class="row">
	<div class='col-xs-12 navTop'><!--- only shown at small screen size -->
		{{{previousLink}}}{{{resultsLink}}}{{{nextLink}}}
	</div><!-- end detailTop -->
	<div class='navLeftRight col-xs-1 col-sm-1 col-md-1 col-lg-1'>
		<div class="detailNavBgLeft">
			{{{previousLink}}}{{{resultsLink}}}
		</div><!-- end detailNavBgLeft -->
	</div><!-- end col -->
	<div class='col-xs-12 col-sm-10 col-md-10 col-lg-10'>
		<div class="container"><div class="row">
			<div class='col-sm-6 col-md-6 col-lg-5 col-lg-offset-1'>
				{{{representationViewer}}}
				
				
				<div id="detailAnnotations"></div>
				
				<?php print caObjectRepresentationThumbnails($this->request, $this->getVar("representation_id"), $t_object, array("returnAs" => "bsCols", "linkTo" => "carousel", "bsColClasses" => "smallpadding col-sm-3 col-md-3 col-xs-4")); ?>
				<div id="detailTools">
					<div class="detailTool"><a href='#' onclick='jQuery("#detailComments").slideToggle(); return false;'><span class="glyphicon glyphicon-comment"></span>Comments (<?php print sizeof($va_comments); ?>)</a></div><!-- end detailTool -->
					<div id='detailComments'>{{{itemComments}}}</div><!-- end itemComments -->
					<div class="detailTool"><span class="glyphicon glyphicon-share-alt"></span>{{{shareLink}}}</div><!-- end detailTool -->
				</div><!-- end detailTools -->
			
			</div><!-- end col -->
			
			<div class='col-sm-6 col-md-6 col-lg-5'>
				<H4>{{{ca_objects.preferred_labels.name}}}</H4>
				<H6>{{{<unit>^ca_objects.type_id</unit>}}}</H6>
				<HR>				
<?php
				if(($t_object->get('ca_objects.type_id', array('convertCodesToDisplayText' => true)) == 'Moving image') | ($t_object->get('ca_objects.type_id', array('convertCodesToDisplayText' => true)) == 'Photograph')) {
					print "<div class='unit'><h6>Identifier</h6>".$t_object->get('ca_objects.idno')."</div>";
				}
				if ($va_author = $t_object->get('ca_entities.preferred_labels', array('restrictToRelationshipTypes' => array('author'), 'returnAsLink' => true, 'delimiter' => '<br/>'))) {
					print "<div class='unit'><h6>Author</h6>".$va_author."</div>";
				}
				if ($va_videographer = $t_object->get('ca_entities.preferred_labels', array('restrictToRelationshipTypes' => array('videographer'), 'returnAsLink' => true, 'delimiter' => '<br/>'))) {
					print "<div class='unit'><h6>Videographer/Filmmaker</h6>".$va_videographer."</div>";
				}				
?>				
				{{{<ifcount min="1" code="ca_objects.date"><div class='unit'><H6>Date:</H6><unit delimiter='<br/>'>^ca_objects.date</unit></div></ifcount>}}}				
<?php
				if ($va_publisher = $t_object->get('ca_entities.preferred_labels', array('restrictToRelationshipTypes' => array('publisher'), 'returnAsLink' => true, 'delimiter' => '<br/>'))) {
					print "<div class='unit'><h6>Publisher</h6>".$va_publisher."</div>";
				}
?>					
				{{{<ifdef code="ca_objects.venue"><div class='unit'><H6>Venue:</H6><unit delimiter='<br/>'>^ca_objects.venue</unit></div></ifdef>}}}				
				{{{<ifdef code="ca_objects.coverage"><div class='unit'><H6>Coverage:</H6><unit delimiter='<br/>'>^ca_objects.coverage</unit></div></ifdef>}}}				
				{{{<ifdef code="ca_objects.isbn"><div class='unit'><H6>ISBN:</H6><unit delimiter='<br/>'>^ca_objects.isbn</unit></div></ifdef>}}}				
				{{{<ifdef code="ca_objects.book_format"><div class='unit'><H6>Format:</H6><unit delimiter=', '>^ca_objects.book_format</unit></div></ifdef>}}}				

				{{{<ifdef code="ca_objects.description">
					<div class='unit'><H6>Description:</H6>
						<span class="trimText">^ca_objects.description</span>
					</div>
				</ifdef>}}}
				{{{<ifdef code="ca_objects.summary">
					<div class='unit'><H6>Summary:</H6>
						<span class="trimText">^ca_objects.summary</span>
					</div>
				</ifdef>}}}	
				{{{<ifdef code="ca_objects.extentDACS"><div class='unit'><H6>Extent:</H6><unit delimiter=', '>^ca_objects.extentDACS</unit></div></ifdef>}}}				
				{{{<ifdef code="ca_objects.medium"><div class='unit'><H6>Medium:</H6><unit delimiter=', '>^ca_objects.medium</unit></div></ifdef>}}}				
				{{{<ifdef code="ca_objects.dimensions.height|ca_objects.dimensions.dwidth|ca_objects.dimensions.length|ca_objects.dimensions.diameter|ca_objects.dimensions.weight|ca_objects.dimensions.measurement_notes"><h6>Dimensions</H6></ifdef>
					<ifdef code="ca_objects.dimensions.height">^ca_objects.dimensions.height H</ifdef>
					<ifdef code="ca_objects.dimensions.height,ca_objects.dimensions.dwidth"> X </ifdef>
					<ifdef code="ca_objects.dimensions.dwidth">^ca_objects.dimensions.dwidth W</ifdef>
					<ifdef code="ca_objects.dimensions.dwidth,ca_objects.dimensions.length"> X </ifdef>
					<ifdef code="ca_objects.dimensions.length">^ca_objects.dimensions.length L</ifdef>
					<ifdef code="ca_objects.dimensions.length,ca_objects.dimensions.diameter"> X </ifdef>
					<ifdef code="ca_objects.dimensions.diameter">^ca_objects.dimensions.diameter Diameter</ifdef>
					<ifdef code="ca_objects.dimensions.weight">, ^ca_objects.dimensions.weight Weight</ifdef>
					<ifdef code="ca_objects.dimensions.measurement_notes"><br/>Notes: ^ca_objects.dimensions.measurement_notes </ifdef>
				}}}		
<?php
				if ($t_object->get('ca_objects.type_id', array('convertCodesToDisplayText' => true)) == 'Photograph') {
					if ($vs_taken = $t_object->get('ca_objects.at_pillow', array('convertCodesToDisplayText' => true))) {
						print "<div class='unit'><h6>Taken at Jacob's Pillow</h6>".$vs_taken."</div>";
					}
				}
				if ($t_object->get('ca_objects.type_id', array('convertCodesToDisplayText' => true)) == 'Periodical') {
					if ($vs_arrangement = $t_object->get('ca_objects.arrangement')) {
						print "<div class='unit'><h6>System of Arrangement</h6>".$vs_arrangement."</div>";
					}
					if ($vs_holdings = $t_object->get('ca_objects.holdings')) {
						print "<div class='unit'><h6>Holdings</h6>".$vs_holdings."</div>";
					}															
				}
				if (($t_object->get('ca_objects.type_id', array('convertCodesToDisplayText' => true)) == 'Periodical')|($t_object->get('ca_objects.type_id', array('convertCodesToDisplayText' => true)) == 'Costumes')) {
					if ($vs_provenance = $t_object->get('ca_objects.provenance')) {
						print "<div class='unit'><h6>Provenance</h6>".$vs_provenance."</div>";
					}				
				}	
				if ($t_object->get('ca_objects.type_id', array('convertCodesToDisplayText' => true)) == "Moving image") {
					if ($vs_access = $t_object->get('ca_objects.access_format')) {
						print "<div class='unit'><h6>Access Format</h6>".$vs_access."</div>";
					}
					if ($vs_master = $t_object->get('ca_objects.master_format')) {
						print "<div class='unit'><h6>Master Format</h6>".$vs_master."</div>";
					}
					if ($vs_duration = $t_object->get('ca_objects.duration')) {
						print "<div class='unit'><h6>Duration</h6>".$vs_duration."</div>";
					}
					if ($vs_camera = $t_object->get('ca_objects.camera', array('convertCodesToDisplayText' => true))) {
						print "<div class='unit'><h6>Camera</h6>".$vs_camera."</div>";
					}
					if ($vs_tech_notes = $t_object->get('ca_objects.technical_notes')) {
						print "<div class='unit'><h6>Technical Notes</h6>".$vs_tech_notes."</div>";
					}																				
				}			
?>				
				<hr></hr>
					<div class="row">
						<div class="col-sm-6">		
							{{{<ifcount code="ca_entities.preferred_labels" min="1"><h6>Related Entities</h6><unit relativeTo="ca_objects_x_entities" delimiter=', ' excludeRelationshipTypes="author,videographer"><l>^ca_entities.preferred_labels</l> (^relationship_typename)</unit></ifcount>}}}


<?php
							if ($va_related_occurrences = $t_object->get('ca_occurrences.preferred_labels', array('delimiter' => '<br/>', 'returnAsLink' => true, 'restrictToTypes' => array('production')))) {
								print "<h6>Related productions</h6>".$va_related_occurrences;
							}
							if ($va_related_works = $t_object->get('ca_occurrences.preferred_labels', array('delimiter' => '<br/>', 'returnAsLink' => true, 'restrictToTypes' => array('work')))) {
								print "<h6>Related works</h6>".$va_related_works;
							}
							if ($va_related_events = $t_object->get('ca_occurrences.preferred_labels', array('delimiter' => '<br/>', 'returnAsLink' => true, 'restrictToTypes' => array('event')))) {
								print "<h6>Related events</h6>".$va_related_events;
							}
							if ($va_related_collections = $t_object->get('ca_collections.preferred_labels', array('delimiter' => '<br/>', 'returnAsLink' => true))) {
								print "<h6>Related collections</h6>".$va_related_collections;
							}	
							if ($va_related_storage = $t_object->get('ca_storage_locations.preferred_labels', array('delimiter' => '<br/>'))) {
								print "<h6>Related Storage Locations</h6>".$va_related_storage;
							}
							if (($t_object->get('ca_objects.type_id', array('convertCodesToDisplayText' => true)) != 'Book') && ($t_object->get('ca_objects.type_id', array('convertCodesToDisplayText' => true)) != 'Periodical')) {							
								if ($va_rights = $t_object->getWithTemplate('<unit>Statement: ^ca_objects.rights.rightsStatement<br/>Rights Holder: ^ca_objects.rights.rightsHolder<br/>Rights Notes: ^ca_objects.rights.rightsNotes</unit>')) {
									print "<div class='unit'><h6>Rights</h6>".$va_rights."</div>";
								}	
							}																
?>								
							
							{{{<ifcount code="ca_list_items" min="1" max="1"><H6>Related Term</H6></ifcount>}}}
							{{{<ifcount code="ca_list_items" min="2"><H6>Related Terms</H6></ifcount>}}}
							{{{<unit relativeTo="ca_list_items" delimiter="<br/>">^ca_list_items.preferred_labels.name_plural</unit>}}}
							
							{{{<ifcount code="ca_objects.LcshNames" min="1"><H6>LC Terms</H6></ifcount>}}}
							{{{<unit delimiter="<br/>"><l>^ca_objects.LcshNames</l></unit>}}}
							
<?php
							if ($t_object->get('ca_objects.external_link.url_entry')) {
								$va_external_links = $t_object->get('ca_objects.external_link', array('returnWithStructure' => true));
								print "<div class='unit'><h6>Links</h6>";
								foreach ($va_external_links as $va_key => $va_external_link_t) {
									foreach ($va_external_link_t as $va_key => $va_external_link) {
										if ($va_external_link['url_source'] && $va_external_link['url_entry']) {
											print "<a href='".$va_external_link['url_entry']."' target='_blank'>".$va_external_link['url_source']."</a><br/>";
										} elseif ($va_external_link['url_entry']) {
											print "<a href='".$va_external_link['url_entry']."' target='_blank'>".$va_external_link['url_entry']."</a><br/>";
										}
									}
								}
								print "</div>";
							}
?>							
						</div><!-- end col -->				
						<div class="col-sm-6 colBorderLeft">
							{{{map}}}
						</div>
					</div><!-- end row -->
			</div><!-- end col -->
		</div><!-- end row --></div><!-- end container -->
	</div><!-- end col -->
	<div class='navLeftRight col-xs-1 col-sm-1 col-md-1 col-lg-1'>
		<div class="detailNavBgRight">
			{{{nextLink}}}
		</div><!-- end detailNavBgLeft -->
	</div><!-- end col -->
</div><!-- end row -->

<script type='text/javascript'>
	jQuery(document).ready(function() {
		$('.trimText').readmore({
		  speed: 75,
		  maxHeight: 120
		});
	});
</script>