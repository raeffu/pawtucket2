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
	$va_tags = 				$this->getVar("tags_array");
	$vn_comments_enabled = 	$this->getVar("commentsEnabled");
	$vn_share_enabled = 	$this->getVar("shareEnabled");
	$vn_pdf_enabled = 		$this->getVar("pdfEnabled");
	$vn_id =				$t_object->get('ca_objects.object_id');
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
		<div class="container">
			<div class="row">
<?php
				$vs_representationViewer = trim($this->getVar("representationViewer"));
				if($vs_representationViewer){
?>
				<div class='col-sm-12 col-md-4'>
					<?php print $vs_representationViewer; ?>				
					<div id="detailAnnotations"></div>
<?php				
					print caObjectRepresentationThumbnails($this->request, $this->getVar("representation_id"), $t_object, array("returnAs" => "bsCols", "linkTo" => "carousel", "bsColClasses" => "smallpadding col-sm-3 col-md-3 col-xs-4"));
?>
				</div><!-- end col -->
<?php
				}
?>
				<div class='col-sm-12 col-md-<?php print ($vs_representationViewer) ? "5" : "7"; ?>'>
					<div class="stoneBg">				
						{{{<ifdef code="ca_objects.preferred_labels.name">
							<H4><span data-toggle="popover" data-html="true" data-placement="left" data-trigger="hover" title="Note" data-content="^ca_objects.ISADG_titleNote">^ca_objects.preferred_labels.name</span>
							<ifdef code="ca_objects.link"><br/><a href="^ca_objects.link" class='redLink' target="_blank">Source record <span class="glyphicon glyphicon-new-window"></span></a></div></ifdef>
							</H4>
						</ifdef>}}}
						{{{<ifdef code="ca_objects.nonpreferred_labels.name" excludeTypes="exhibition_title"><H6>Alternate Title(s)</H6><unit relativeTo="ca_objects" delimiter="<br/>" excludeTypes="exhibition_title">^nonpreferred_labels.name</unit></ifdef>}}}
						{{{<ifdef code="ca_objects.displayDate">
							<ifdef code="ca_objects.nonpreferred_labels.name" excludeTypes="exhibition_title"><h6></h6></ifdef>
							<div class="unit" data-toggle="popover" data-html="true" data-placement="left" data-trigger="hover" title="Note" data-content="^ca_objects.ISADG_dateNote">
								^ca_objects.displayDate
							<div>
						</ifdef>}}}
				
						<H6>
							{{{<ifdef code="ca_objects.resource_type">^ca_objects.resource_type%useSingular=1</ifdef><ifdef code="ca_objects.genre,ca_objects.resource_type"> > </ifdef><ifdef code="ca_objects.genre">^ca_objects.genre%delimiter=,_</unit></ifdef>}}}
						</H6>
						{{{<ifcount code="ca_entities.related" restrictToTypes="repository" min="1"><div class="unit"><H6>Holding Repository</H6><span class="trimTextShort"><unit relativeTo="ca_entities.related" restrictToTypes="repository" delimiter=", "><l>^ca_entities.preferred_labels.displayname</l></unit></span></div></ifcount>}}}				
						{{{<ifcount code="ca_entities.related" restrictToRelationshipTypes="archival_creator,artist,author,composer,contributor,creator,curator,director,editor,filmmaker,funder,illustrator,interviewee,interviewer,narrator,organizer,performer,photographer,producer,researcher,speaker,translator,subject,videographer,venue" min="1"><div class="unit"><H6>Creators and Contributors</H6><span class="trimTextShort"><unit relativeTo="ca_objects_x_entities" restrictToRelationshipTypes="archival_creator,artist,author,composer,contributor,creator,curator,director,editor,filmmaker,funder,illustrator,interviewee,interviewer,narrator,organizer,performer,photographer,producer,researcher,speaker,translator,subject,videographer,venue" delimiter=", "><unit relativeTo="ca_entities"><l>^ca_entities.preferred_labels.displayname</l></unit> (^relationship_typename)</unit></span></div></ifcount>}}}
						{{{<ifdef code="ca_objects.RAD_extent"><div class='unit'><h6>Extent and Medium</h6>^ca_objects.RAD_extent</div></ifdef>}}}
						{{{<ifdef code="ca_objects.scope_new.scope_new_text">
							<div class="unit" data-toggle="popover" data-html="true" data-placement="left" data-trigger="hover" title="Source" data-content="^ca_objects.scope_new.scope_new_source"><h6>Description</h6>
								<span class="trimText">^ca_objects.scope_new.scope_new_text</span>
							<div>
						</ifdef>}}}
						{{{<ifdef code="ca_objects.curators_comments.comments">
							<div class="unit" data-toggle="popover" data-html="true" data-placement="left" data-trigger="hover" title="Source" data-content="^ca_objects.curators_comments.comment_reference"><h6>Curatorial comment</h6>
								<span class="trimText">^ca_objects.curators_comments.comments</span>
							</div>
						</ifdef>}}}
						{{{<ifdef code="ca_objects.community_input_objects.comments_objects">
							<div class='unit' data-toggle="popover" data-html="true" data-placement="left" data-trigger="hover" title="Source" data-content="^ca_objects.community_input_objects.comment_reference_objects"><h6>Dialogue</h6>
								<span class="trimText">^ca_objects.community_input_objects.comments_objects</span>
							</div>
						</ifdef>}}}
						{{{<ifdef code="ca_objects.language">
							<div class="unit" data-toggle="popover" data-html="true" data-placement="left" data-trigger="hover" title="Note" data-content="^ca_objects.language_note"><h6>Language</h6>
								^ca_objects.language
							<div>
						</ifdef>}}}
					</div><!-- end stoneBg -->
				</div>
				<div class='col-sm-12 col-md-<?php print ($vs_representationViewer) ? "3" : "5"; ?>'>
	<?php
					# Comment and Share Tools
						
					print '<div id="detailTools" class="detailToolsInline">';
					if ($vn_share_enabled) {
						print '<div class="detailTool"><span class="glyphicon glyphicon-share-alt"></span>'.$this->getVar("shareLink").'</div><!-- end detailTool -->';
					}
					if ($vn_pdf_enabled) {
						print "<div class='detailTool'><span class='glyphicon glyphicon-file'></span>".caDetailLink($this->request, "Download as PDF", "faDownload", "ca_objects",  $vn_id, array('view' => 'pdf', 'export_format' => '_pdf_ca_objects_summary'))."</div>";
					}
					print "<div class='detailTool'><span class='glyphicon glyphicon-envelope'></span>".caNavLink($this->request, "Ask a Question", "", "", "Contact", "Form", array("contactType" => "askArchivist", "object_id" => $t_object->get("object_id")))."</div>";
					if($t_object->get("trc", array("convertCodesToDisplayText" => true)) == "yes"){
						print "<div class='detailTool'><span class='glyphicon glyphicon-envelope'></span>".caNavLink($this->request, "Request Takedown", "", "", "Contact", "Form", array("contactType" => "takedown", "object_id" => $t_object->get("object_id")))."</div>";
					}
					print '</div><!-- end detailTools -->';			

							# --- themes AKA "Local" subjects
							$va_attributes = array("narrative_thread" => "narrative_threads_facet", "themes" => "theme_facet", "keywords" => "keyword_facet");
							$vs_themes = "";
							$t_list = new ca_lists();
							foreach($va_attributes as $vs_attribute => $vs_facet){
								if($va_themes = $t_object->get("ca_objects.".$vs_attribute, array("returnAsArray" => true))){
									foreach($va_themes as $vn_theme_id){
										$vs_theme_name = $t_list->getItemFromListForDisplayByItemID($vs_attribute, $vn_theme_id);
										$vs_themes .= caNavLink($this->request, "<i class='fa fa-angle-right' aria-hidden='true'></i> ".$vs_theme_name." <span>(".str_replace("_", " ", $vs_attribute).")</span>", "", "", "browse", "objects", array("facet" => $vs_facet, "id" => $vn_theme_id));
									}
								}
							}
							
							if($vs_themes){
?>							
								<div class="block">
									<h3>Themes</H3>
									<div class="blockContent trimTextSubjects">
<?php
											print $vs_themes; 
											
?>
									</div>
								</div>
<?php
							}

						if ($vn_comments_enabled) {
							$vn_num_comments = sizeof($va_comments) + sizeof($va_tags);
?>				
							<div class="collapseBlock">
								<h3>Discussion <i class="fa fa-toggle-up" aria-hidden="true"></i></H3>
								<div class="collapseContent open">
									<div id='detailDiscussion'>
										Do you have a story to contribute related to these records or a comment about this item?<br/>
<?php
										
										if($this->request->isLoggedIn()){
											print "<button type='button' class='btn btn-default' onclick='caMediaPanel.showPanel(\"".caNavUrl($this->request, '', 'Detail', 'CommentForm', array("tablename" => "ca_objects", "item_id" => $t_object->getPrimaryKey()))."\"); return false;' >"._t("Add your tags and comment")."</button>";
										}else{
											print "<button type='button' class='btn btn-default' onclick='caMediaPanel.showPanel(\"".caNavUrl($this->request, '', 'LoginReg', 'LoginForm', array())."\"); return false;' >"._t("Login/register to comment on this object")."</button>";
										}
										if($vn_num_comments){
											print "<br/><br/><a href='#comments'>Read All Comments <i class='fa fa-angle-right' aria-hidden='true'></i></a>";
										}
?>
									</div><!-- end itemComments -->
								</div>
							</div>
<?php				
						}
?>
						{{{<ifdef code="ca_objects.source_identifier|ca_objects.NCTR_id|ca_objects.related_collection_list|ca_objects.ownership_credit">
							<div class="collapseBlock">
								<h3>More Information <i class="fa fa-toggle-down" aria-hidden="true"></i></H3>
								<div class="collapseContent">
									<ifdef code="ca_objects.source_identifer"><div class='unit'><h6>Holding Repository Object Identifier</h6>^ca_objects.source_identifer</div></ifdef>
									<ifdef code="ca_objects.NCTR_id"><div class='unit'><h6>NCTR Object Identifier</h6>^ca_objects.NCTR_id</div></ifdef>
									<ifdef code="ca_objects.related_collection_list"><div class='unit'><H6>Collection Hierarchy List</H6><unit relativeTo="ca_objects" delimiter="<br/>"><l>^ca_objects.related_collection_list</unit></div></ifdef>
									<ifdef code="ca_objects.ownership_credit"><div class='unit'><h6>Credit/Citation</h6>^ca_objects.ownership_credit</div></ifdef>						
								</div>
							</div>
						</ifdef>}}}
							{{{<ifdef code="ca_objects.alternate_text.alternate_desc_upload.url">
								<div class="collapseBlock">
									<h3>Research Guide <i class="fa fa-toggle-down" aria-hidden="true"></i></H3>
									<div class="collapseContent">
										<div class='unit icon transcription'><h6></h6><unit relativeTo="ca_objects" delimiter="<br/>"><ifdef code="ca_objects.alternate_text.alternate_desc_upload"><a href="^ca_objects.alternate_text.alternate_desc_upload.url%version=original">View ^ca_objects.alternate_text.alternate_text_type</a></ifdef><ifdef code="ca_objects.alternate_text.alternate_desc_note">^ca_objects.alternate_text.alternate_desc_note</ifdef></unit></div>
									</div>
								</div>
							</ifdef>}}}
							{{{<ifdef code="ca_objects.MARC_generalNote|ca_objects.ISADG_archNote">
								<div class="collapseBlock">
									<h3>Notes <i class="fa fa-toggle-down" aria-hidden="true"></i></H3>
									<div class="collapseContent">
										<ifdef code="ca_objects.MARC_generalNote"><div class='unit'><h6>Notes</h6>^ca_objects.MARC_generalNote</div></ifdef>
										<ifdef code="ca_objects.ISADG_archNote"><div class='unit'><h6>Note on Description</h6>^ca_objects.ISADG_archNote</div></ifdef>
									</div>
								</div>
							</ifdef>}}}
							{{{<ifdef code="ca_objects.govAccess|ca_objects.rights_new|ca_objects.RAD_local_rights">
								<div class="collapseBlock">
									<h3>Rights <i class="fa fa-toggle-down" aria-hidden="true"></i></H3>
									<div class="collapseContent">
										<ifdef code="ca_objects.govAccess"><div class='unit'><h6>Conditions Governing Access</h6>^ca_objects.govAccess</div></ifdef>
										<ifdef code="ca_objects.rights_new"><div class='unit'><h6>Terms Governing Use and Reproduction</h6>^ca_objects.rights_new</div></ifdef>
										<ifdef code="ca_objects.RAD_local_rights"><div class='unit'><h6>Notes: Rights and Access</h6>^ca_objects.RAD_local_rights</div></ifdef>
									
									</div>
								</div>
							</ifdef>}}}
							<div class="collapseBlock last">
								<h3>Permalink <i class="fa fa-toggle-down" aria-hidden="true"></i></H3>
								<div class="collapseContent">
									
<?php
						print "<div class='unit'><br/><textarea name='permalink' id='permalink' class='form-control input-sm'>".$this->request->config->get("site_host").caNavUrl($this->request, '', 'Detail', 'objects/'.$t_object->get("object_id"))."</textarea></div>";
					
?>
								</div>
							</div>
<?php
					if($vs_map = $this->getVar("map")){
						print "<div class='unit'>".$vs_map."</div>";
					}
?>
				</div>
			</div>
			<div class="row" style="margin-top:30px;">
				<div class="col-sm-12">		
<!--
					{{{<ifcount code="ca_entities.related" restrictToTypes="school" restrictToRelationshipTypes="related" min="1">
						<div class="relatedBlock">
							<h3>Schools</H3>
							<div class="relatedBlockContent">
								<div class="row">
									<unit relativeTo="ca_entities" restrictToTypes="school" restrictToRelationshipTypes="related" delimiter=" "><div class="col-sm-12 col-md-3"><l><span>^ca_entities.preferred_labels.displayname (^relationship_typename)</span></l></div></unit>
								</div>
							</div>
						</div>

					</ifcount>}}}
					
					{{{<ifcount code="ca_places.related" restrictToRelationshipTypes="related" min="1">
						<div class="relatedBlock">
							<h3>Places</H3>
							<div class="relatedBlockContent">
								<div class="row">
									<unit relativeTo="ca_places" delimiter=", "><div class="col-sm-12 col-md-3"><l><span>^ca_places.preferred_labels.name (^relationship_typename)</span></l></div></unit>
								</div>
							</div>
						</div>

					</ifcount>}}}
					
					{{{<ifcount code="ca_entities.related" excludeRelationshipTypes="repository,original_source" excludeTypes="school" min="1">
						<div class="relatedBlock">
							<h3>People/Organizations</H3>
							<div class="relatedBlockContent">
								<div class="row">
									<unit relativeTo="ca_entities" excludeRelationshipTypes="repository,original_source" excludeTypes="school" delimiter=" "><div class="col-sm-12 col-md-3"><l><span>^ca_entities.preferred_labels.displayname (^relationship_typename)</l></div></unit>
								</div>
							</div>
						</div>

					</ifcount>}}}
-->
<?php
		include("related_tabbed_html.php");
?>
					{{{<ifcount code="ca_objects.related" min="1">
						<div class="relatedBlock">
							<h3>Related Objects</H3>
							<div class="row" id="browseResultsContainer">
								<unit relativeTo="ca_objects.related" delimiter=" ">
									<div class="bResultItemCol col-xs-12 col-sm-6 col-md-3">
										<div class="bResultItem" onmouseover="jQuery("#bResultItemExpandedInfo^ca_objects.object_id").show();" onmouseout="jQuery("#bResultItemExpandedInfo^ca_objects.object_id").hide();">
											<div class="bResultItemContent"><div class="text-center bResultItemImg"><l>^ca_object_representations.media.medium</l></div>
												<div class="bResultItemText">
													<small><l>^ca_objects.preferred_labels.name</l></small>
												</div><!-- end bResultItemText -->
											</div><!-- end bResultItemContent -->
											<div class="bResultItemExpandedInfo" id="bResultItemExpandedInfo^ca_objects.object_id" style="display: none;">
											</div><!-- bResultItemExpandedInfo -->
										</div><!-- end bResultItem -->
									</div>
								</unit>
							</div>
						</div>
					</ifcount>}}}
<?php
					if($vn_num_comments){
?>
						<a name="comments"></a><div class="block">
							<h3>Discussion</H3>
							<div class="blockContent">
								<div id="detailComments">
<?php
								if(sizeof($va_comments)){
									print "<H2>Comments</H2>";
								}
								print $this->getVar("itemComments");
?>
								</div>
							</div>
						</div>
<?php
					}
?>				
			
					
				</div>
			</div>
		</div><!-- end container -->
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
		  maxHeight: 60
		});
		$('.trimTextShort').readmore({
		  speed: 75,
		  maxHeight: 18
		});
		$('.trimTextSubjects').readmore({
		  speed: 75,
		  maxHeight: 80,
		  moreLink: '<a href="#" class="moreLess">More</a>',
		  lessLink: '<a href="#" class="moreLess">Less</a>'
		});
		
		$('[data-toggle="popover"]').popover();
		
		$('.collapseBlock h3').click(function() {
  			block = $(this).parent();
  			block.find('.collapseContent').toggle();
  			block.find('.fa').toggleClass("fa-toggle-down");
  			block.find('.fa').toggleClass("fa-toggle-up");
  			
		});
	});
</script>