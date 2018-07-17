<?php
	$va_comments = $this->getVar("comments");
?>
<div class="row">
	<div class='col-xs-1 col-sm-1 col-md-1 col-lg-1'>
		<div class="detailNavBgLeft">
			{{{previousLink}}}{{{resultsLink}}}
		</div><!-- end detailNavBgLeft -->
	</div><!-- end col -->
	<div class='col-xs-10 col-sm-10 col-md-10 col-lg-10'>
		<div class="container"><div class="row"><div class='col-md-12 col-lg-12'>
			<H4>{{{^ca_entities.preferred_labels.displayname}}}</H4>
			<H6>{{{^ca_entities.type_id}}}{{{<ifdef code="ca_entities.idno">, ^ca_entities.idno</ifdef>}}}</H6>
			
{{{<ifcount code="ca_objects" min="2">
			<div id="detailRelatedObjects">
				<H6>Related Objects <a href="#">view all</a></H6>
				<div class="jcarousel-wrapper">
					<div id="detailScrollButtonNext"><i class="fa fa-angle-right"></i></div>
					<div id="detailScrollButtonPrevious"><i class="fa fa-angle-left"></i></div>
					<!-- Carousel -->
					<div class="jcarousel">
						<ul>
							<unit relativeTo="ca_objects" delimiter=" "><li><div class='detailObjectsResult'><l>^ca_object_representations.media.widepreview</l><br/><l>^ca_objects.preferred_labels.name</l></div></li><!-- end detailObjectsBlockResult --></unit>
						</ul>
					</div><!-- end jcarousel -->
					
				</div><!-- end jcarousel-wrapper -->
			</div><!-- end detailRelatedObjects -->
			<script type='text/javascript'>
				jQuery(document).ready(function() {
					/*
					Carousel initialization
					*/
					$('.jcarousel')
						.jcarousel({
							// Options go here
						});
			
					/*
					 Prev control initialization
					 */
					$('#detailScrollButtonPrevious')
						.on('jcarouselcontrol:active', function() {
							$(this).removeClass('inactive');
						})
						.on('jcarouselcontrol:inactive', function() {
							$(this).addClass('inactive');
						})
						.jcarouselControl({
							// Options go here
							target: '-=1'
						});
			
					/*
					 Next control initialization
					 */
					$('#detailScrollButtonNext')
						.on('jcarouselcontrol:active', function() {
							$(this).removeClass('inactive');
						})
						.on('jcarouselcontrol:inactive', function() {
							$(this).addClass('inactive');
						})
						.jcarouselControl({
							// Options go here
							target: '+=1'
						});
				});
			</script></ifcount>}}}
			</div><!-- end col -->
		</div><!-- end row -->
		<div class="row">
			
			<div class='col-md-6 col-lg-6'>
				{{{<ifdef code="ca_entities.biography"><H6>About</H6>^ca_entities.biography<br/></ifdef>}}}
				
				{{{^ca_entities.media}}}
				
				{{{<ifcount code="ca_occurrences" min="1" max="1"><span class='metaTitle'>Related Work</span></ifcount>}}}
				{{{<ifcount code="ca_occurrences" min="2"><span class="metaTitle">Related Works</span></ifcount>}}}
				
<?php
    $t_entity = $this->getVar('item');
    $q = $t_entity->get('ca_occurrences.occurrence_id', ['returnAsSearchResult' => true]);
  
    $by_work_type = [];
    while($q->nextHit()) {
        $by_work_type[$q->get('ca_occurrences.workType', ['convertCodesToDisplayText' => true])][$q->get('ca_occurrences.preferred_labels.name').$q->get('ca_occurrences.occurrence_id')] =  $q->getWithTemplate('<unit delimiter=" "><div class="worklist"><l>^ca_occurrences.preferred_labels</l></div></div></unit>');
    }
    foreach($by_work_type as $type => $works) {
        ksort($works);
        print "<div class='meta'><h6>{$type}</h6>".join(" ", $works)."</div>\n";
    }
?>			
			
			</div><!-- end col -->
			<div class='col-md-6 col-lg-6'>
				{{{<ifcount code="ca_objects" min="1" max="1"><H6>Related object</H6><unit relativeTo="ca_objects" delimiter=" "><l>^ca_object_representations.media.small</l><br/><l>^ca_objects.preferred_labels.name</l><br/></unit></ifcount>}}}
				
				<div id="detailTools">
					<div class="detailTool"><a href='#' onclick='jQuery("#detailComments").slideToggle(); return false;'><span class="glyphicon glyphicon-comment"></span>Comments (<?php print sizeof($va_comments); ?>)</a></div><!-- end detailTool -->
					<div id='detailComments'>{{{itemComments}}}</div><!-- end itemComments -->
					<div class="detailTool"><span class="glyphicon glyphicon-share-alt"></span>{{{shareLink}}}</div><!-- end detailTool -->
				</div><!-- end detailTools -->
			</div><!-- end col -->
		</div><!-- end row --></div><!-- end container -->
	</div><!-- end col -->
	<div class='col-xs-1 col-sm-1 col-md-1 col-lg-1'>
		<div class="detailNavBgRight">
			{{{nextLink}}}
		</div><!-- end detailNavBgLeft -->
	</div><!-- end col -->
</div><!-- end row -->