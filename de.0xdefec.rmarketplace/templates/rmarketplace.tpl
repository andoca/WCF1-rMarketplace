{include file="documentHeader"}
{assign var="statistics" value=""}
<head>
	<title>{lang}de.0xdefec.rmarketplace.title{/lang} - {lang}{PAGE_TITLE}{/lang}</title>
	<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/MultiPagesLinks.class.js"></script>
	
	{include file='headInclude' sandbox=false}
	{include file='rmarketplaceGlobalHeader' sandbox=false}
	{if 'MP_FEED_ACTIVE'|defined && MP_FEED_ACTIVE && !$inModeration|isset}
		{include file="rmarketplaceIndexFeedHeader"}
	{/if}
</head>
<body{if $templateName|isset} id="tpl{$templateName|ucfirst}"{/if}>
{include file='rmarketplaceSearchControls' sandbox=false}

{capture assign=rMBreadCrumbs}
	{if $category !== null || $type !== null || $inModeration|isset}
			<div class="breadcrumb2"><a href="index.php?page=RMarketplace{@SID_ARG_2ND}"><span>{lang}de.0xdefec.rmarketplace.title{/lang}</span></a></div>
			{if $type !== null && $category !== null}
				<div class="breadcrumb3"><a href="index.php?page=RMarketplace&amp;type={$type}{@SID_ARG_2ND}">
				<span>
					{if $type == 'search'}
						{lang}de.0xdefec.rmarketplace.search{/lang}
					{else}
						{lang}de.0xdefec.rmarketplace.offer{/lang}
					{/if}
				</span></a></div>
			{/if}
			{if $category !== null}
				{foreach from=$category->getParents() item=parent}
					<div class="breadcrumb4"><a href="index.php?page=RMarketplace&amp;cat={$parent->catID}&amp;type={$type}{@SID_ARG_2ND}"> <span>{lang}{$parent->catName}{/lang}</span></a></div>
				{/foreach}
				{if $newItems}
					<div class="breadcrumb4"><a href="index.php?page=RMarketplace&amp;cat={$category->catID}&amp;type={$type}{@SID_ARG_2ND}"> <span>{lang}{$category->catName}{/lang}</span></a></li>
				{/if}
			{/if}
	{/if}
{/capture}

{include file='header' sandbox=false}

<div id="main">
	
	{if $category !== null || $type !== null || $inModeration|isset || $newItems}
		<ul class="breadCrumbs">	
			<li><a href="index.php?page=RMarketplace{@SID_ARG_2ND}"><img src="{icon}rMarketplaceS.png{/icon}" alt="" /> <span>{lang}de.0xdefec.rmarketplace.title{/lang}</span></a> &raquo;</li>
			{if $type !== null && $category !== null}
				<li><a href="index.php?page=RMarketplace&amp;type={$type}{@SID_ARG_2ND}"><img src="{icon}rM{$type}S.png{/icon}" alt="" /> 
				<span>
					{if $type == 'search'}
						{lang}de.0xdefec.rmarketplace.search{/lang}
					{else}
						{lang}de.0xdefec.rmarketplace.offer{/lang}
					{/if}
				</span></a> &raquo;</li>
			{/if}
			{if $category !== null}
				{foreach from=$category->getParents() item=parent}
					<li><a href="index.php?page=RMarketplace&amp;cat={$parent->catID}&amp;type={$type}{@SID_ARG_2ND}">{if $parent->catIcon}<img src="{icon}{$parent->catIcon}S.png{/icon}" alt="" />{else}<img src="{icon}rMarketplaceS.png{/icon}" alt="" />{/if} <span>{lang}{$parent->catName}{/lang}</span></a> &raquo;</li>
				{/foreach}
				{if $newItems}
					<li><a href="index.php?page=RMarketplace&amp;cat={$category->catID}&amp;type={$type}{@SID_ARG_2ND}">{if $category->catIcon}<img src="{icon}{$category->catIcon}S.png{/icon}" alt="" />{else}<img src="{icon}rMarketplaceS.png{/icon}" alt="" />{/if} <span>{lang}{$category->catName}{/lang}</span></a> &raquo;</li>
				{/if}
			{/if}
		</ul>
	{/if}
	{if $inModeration|isset}
		<div class="mainHeadline">
			<img src="{icon}rMarketplaceModerationL.png{/icon}" alt="" />
			<div class="headlineContainer">
				<h2><a href="index.php?page=RMarketplaceModeration{@SID_ARG_2ND}">{lang}de.0xdefec.rmarketplace.moderation{/lang}</a></h2>
				<p>{lang}de.0xdefec.rmarketplace.moderation.description{/lang}</p>
			</div>
		</div>
	{elseif $newItems}
		<div class="mainHeadline">
			<img src="{icon}rmNewL.png{/icon}" alt="" />
			<div class="headlineContainer">
				<h2><a href="index.php?page=RMarketplaceModeration{@SID_ARG_2ND}">{lang}de.0xdefec.rmarketplace.new{/lang}</a></h2>
				<p>{lang}de.0xdefec.rmarketplace.new.description{/lang}</p>
			</div>
		</div>
	{else}
		<div class="mainHeadline">
		{if $category === null && $type !== null}
			<img src="{icon}rM{$type}L.png{/icon}" alt="" />
			<div class="headlineContainer">
				<h2><a href="index.php?page=RMarketplace&amp;type={$type}{@SID_ARG_2ND}">
					{if $type == 'search'}
						{lang}de.0xdefec.rmarketplace.search{/lang}
					{else}
						{lang}de.0xdefec.rmarketplace.offer{/lang}
					{/if}
					</a>
				</h2>
				<p></p>
			</div>
		{else}
			{if $category !== null && $category->catIcon}<img src="{icon}{$category->catIcon}L.png{/icon}" alt="" />{else}<img src="{icon}rMarketplaceL.png{/icon}" alt="" />{/if}
			<div class="headlineContainer">
				<h2>{if $category === null}<a href="index.php?page=RMarketplace&amp;type={$type}{@SID_ARG_2ND}">{lang}de.0xdefec.rmarketplace.title{/lang}</a>{else}<a href="index.php?page=RMarketplace&amp;cat={$category->catID}&amp;type={$type}{@SID_ARG_2ND}">{lang}{$category->catName}{/lang}</a>{/if}</h2>
				<p>{if $category === null}{lang}de.0xdefec.rmarketplace.welcome{/lang}{else}{lang}{@$category->catDescription}{/lang}{/if}</p>
			</div>
		{/if}
		</div>
	{/if}
	
	{if $userMessages|isset}{@$userMessages}{/if}
	
	<div class="tabMenu">
		<ul>
			<li{if $type != 'search' && $type != 'offer'} class="activeTabMenu"{/if}>
				<a href="index.php?page=RMarketplace{@SID_ARG_2ND}"><img src="{icon}rMarketplaceM.png{/icon}" alt="" /> <span>{lang}de.0xdefec.rmarketplace.title{/lang}</span></a>
			</li>
			{if 'MP_GMAP_LARGE_ENABLED'|defined && MP_GMAP_LARGE_ENABLED && RM_ENABLE_MAPS}
				<li>
					<a href="index.php?page=RMarketplaceMap&amp;cat={$categoryID}{@SID_ARG_2ND}"><img src="{icon}rMmapM.png{/icon}" alt="" /> <span>{lang}de.0xdefec.rmarketplace.map.title{/lang}</span></a>
				</li>
			{/if}
			<li{if $type == 'search'} class="activeTabMenu"{/if}>
				<a href="index.php?page=RMarketplace&amp;cat={$categoryID}{if $newItems}&amp;newItems={$newItems}{/if}&amp;type=search{@SID_ARG_2ND}"><img src="{icon}rMsearchM.png{/icon}" alt="" /> <span>{lang}de.0xdefec.rmarketplace.search{/lang}</span></a>
			</li>
			<li{if $type == 'offer'} class="activeTabMenu"{/if}>
				<a href="index.php?page=RMarketplace&amp;cat={$categoryID}{if $newItems}&amp;newItems={$newItems}{/if}&amp;type=offer{@SID_ARG_2ND}"><img src="{icon}rMofferM.png{/icon}" alt="" /> <span>{lang}de.0xdefec.rmarketplace.offer{/lang}</span></a>
			</li>
		</ul>
	</div>
	
	<div class="subTabMenu">
		<div class="containerHead"></div>
	</div>
	
	<div class="border rmarketplace">
		<div class="layout-2">
			<div class="columnContainer">
				<div class="container-1 column first">
					<div class="columnInner">
						<div class="contentBox">
							<h3 class="subHeadline">{lang}de.0xdefec.rmarketplace.subheadline{/lang}</h3>
							
							{assign var=modButton value=""}
							{if !$inModeration|isset && $this->user->getPermission('mod.rmarketplace.canModerate')}
								{capture append=modButton}
									<li><a href="index.php?page=RMarketplaceModeration{@SID_ARG_2ND}" title="{lang}de.0xdefec.rmarketplace.moderation{/lang}"><img src="{icon}rMarketplaceModerationM.png{/icon}" alt="" /> <span>{lang}de.0xdefec.rmarketplace.moderation{/lang}</span></a></li>
								{/capture}
							{/if}
							
							<div class="contentHeader">
								<div class="pageNavigation">
									{pages page=$pageNum pages=$pages link="index.php?page=RMarketplace&cat=$categoryID&newItems=$newItems&type=$type&pageNum=%d"|concat:SID_ARG_2ND_NOT_ENCODED}
								</div>
								{if $this->user->getPermission('user.rmarketplace.canWrite')}
									<div class="largeButtons">
										<ul>
											<li><a href="index.php?form=RMarketplaceAdd{if $category !== null}&amp;categoryID={$category->catID}{/if}{@SID_ARG_2ND}" title="{lang}de.0xdefec.rmarketplace.newEntry{/lang}"><img src="{icon}messageAddM.png{/icon}" alt="" /> <span>{lang}de.0xdefec.rmarketplace.newEntry{/lang}</span></a></li>
											{@$modButton}
										</ul>
									</div>
								{/if}
							</div>
							
							<div class="rmarketplaceEntries">
								{include file='rmarketplaceEntryList' sandbox='false' map='1' mpControls='1'}
							</div>
							
							<div class="contentFiiter">
								<div class="pageNavigation">
									{pages page=$pageNum pages=$pages link="index.php?page=RMarketplace&cat=$categoryID&newItems=$newItems&type=$type&pageNum=%d"|concat:SID_ARG_2ND_NOT_ENCODED}
								</div>
								{if $this->user->getPermission('user.rmarketplace.canWrite')}
									<div class="largeButtons">
										<ul>
											<li><a href="index.php?form=RMarketplaceAdd{if $category !== null}&amp;categoryID={$category->catID}{/if}{@SID_ARG_2ND}" title="{lang}de.0xdefec.rmarketplace.newEntry{/lang}"><img src="{icon}messageAddM.png{/icon}" alt="" /> <span>{lang}de.0xdefec.rmarketplace.newEntry{/lang}</span></a></li>
											{@$modButton}
										</ul>
									</div>
								{/if}
							</div>
							
							<div class="pageOptions">
								<a href="index.php?action=RMarketplace&amp;methode=markAllRead&amp;t={@SECURITY_TOKEN}{@SID_ARG_2ND}"><img src="{icon}rmNewS.png{/icon}" alt="" /> <span>{lang}de.0xdefec.rmarketplace.markAllRead{/lang}</span></a>
							</div>
						</div>
					</div>
				</div>
				
				<div class="container-3 column second sidebar mpSidebar">
					<div class="columnInner">
						
						{cycle values='container-1,container-2' print=false advance=false}
						
						{if $categories|count}
							<div class="contentBox mpBoxCategories">
								<div class="border">
									<div class="containerHead">
										<h3>{if $category}{lang}{$category->catName}{/lang}{else}{lang}de.0xdefec.rmarketplace.categories{/lang}{/if}</h3>
									</div>
									<ul class="itemList">
										{foreach from=$categories item=cat key=id}
											<li class="{cycle}" >
												<h4 class="itemListTitle">{if $cat->catIcon}<span class="itemListIcon"><img src="{icon}{$cat->catIcon}M.png{/icon}" alt="" /> </span>{/if}<a href="index.php?page=RMarketplace&amp;cat={$cat->catID}{if $newItems}&amp;newItems={$newItems}{/if}&amp;type={$type}{@SID_ARG_2ND}" title="{lang}{$cat->catDescription}{/lang}"><span>{lang}{$cat->catName}{/lang} ({$cat->getItemsCount()}{if $cat->getChildrenCount()}/{$cat->getChildrenCount()}{/if})</span></a></h4>
											</li>
										{/foreach}
									</ul>
								</div>
							</div>
						{else}
							<div class="contentBox mpBoxCategories">
								&nbsp;
							</div>
						{/if}

						{if RM_ENABLE_MAPS}
							<div class="contentBox mpBoxMap">
								<div class="border">
									<div>
										<div id="mpMapDiv" class="{cycle}" ></div>
										<div id="mpMapLoadingContainer" style="display: none; position: relative; top: -300px; left: 0; height: 300px; width; 298px; margin-bottom: -300px;"><div style="height: 100%; width: 100%; background-color: #fff; opacity: 0.6"><img src="{icon}rMloading.gif{/icon}" id="mpMapLoading" style="position: absolute; display: inline; top: 138px; left: 136px" alt="loading..." /></div></div>
									</div>
								</div>
							</div>
							
							<script type="text/javascript">
								/* <![CDATA[ */
								{if MP_GMAP_START_SET}
									var lat = '{MP_GMAP_START_LAT}';
									var lng = '{MP_GMAP_START_LNG}';
								{else}
									if (google.loader.ClientLocation && google.loader.ClientLocation.latitude && google.loader.ClientLocation.longitude) {
										var lat = google.loader.ClientLocation.latitude;
										var lng = google.loader.ClientLocation.longitude;
									}
									else {
										var lat = '{MP_GMAP_START_LAT}';
										var lng = '{MP_GMAP_START_LNG}';
										}
								{/if}
								
								var mapOptions = {
									center: new google.maps.LatLng(lat, lng),
									zoom: {MP_GMAP_START_ZOOM},
									mapTypeId: google.maps.MapTypeId.{MP_GMAP_TYPE},
									mapTypeControlOptions: {
										mapTypeIds: [google.maps.MapTypeId.ROADMAP, google.maps.MapTypeId.HYBRID, google.maps.MapTypeId.TERRAIN]
									},
									streetViewControl: false
								}
						    	
								var map = new google.maps.Map(document.getElementById("mpMapDiv"), mapOptions);
								
								// load marker manager
								
								var mc = '';
								var globalMarkers = [];
								var infoWindow = new google.maps.InfoWindow({
									pixelOffset: new google.maps.Size(-2, 48)
								});
								
								var markers = '';
								
								var lastBounds = false;
								var lastBoundsSE = false;
								var lastBoundsNW = false;
									
								google.maps.event.addListenerOnce(map, 'idle', function() {								
									loadMarkers();
								});		
								google.maps.event.addListener(map, 'dragend', function() {
									currentBounds = map.getBounds();
									currentBoundsSE = currentBounds.getSouthWest();
									currentBoundsNW = currentBounds.getNorthEast();
								
									if(!lastBounds.contains(currentBoundsSE) || !lastBounds.contains(currentBoundsNW)) loadMarkers();
								});		
								google.maps.event.addListener(map, 'zoom_changed', function() {
									currentBounds = map.getBounds();
									currentBoundsSE = currentBounds.getSouthWest();
									currentBoundsNW = currentBounds.getNorthEast();
								
									if(!lastBounds.contains(currentBoundsSE) || !lastBounds.contains(currentBoundsNW)) loadMarkers();
								});
				
								function loadMarkers() {
									// update global last Bounds infos
									lastBounds = map.getBounds();
									lastBoundsSE = lastBounds.getSouthWest();
									lastBoundsNE = lastBounds.getNorthEast();
								
									// get bounds of current map
									var bounds = map.getBounds();
									
									// get boundries of the current map
									var boundsSW = bounds.getSouthWest();
									var boundsNE = bounds.getNorthEast();
									
									// build ajax request url with parameters
									var url = 'index.php?form=RMarketplaceAjax' + SID_ARG_2ND;
									
									var ajaxRequest = new Ajax.Request(url, {
									  method: 'post',
									  parameters: {
									  	t: SECURITY_TOKEN,
									  	methode: 'getMarkers',
									  	boundsSWLat: boundsSW.lat(),
									  	boundsSWLng: boundsSW.lng(),
									  	boundsNELat: boundsNE.lat(),
									  	boundsNELng: boundsNE.lng(),
									  	cat: {if $category !== NULL}{$category->catID}{else}0{/if},
									  	type: '{if $type !== null}{$type}{/if}'
									  },
									  evalJS: 'false',
									  sanitizeJSON: true,
									  encoding: '{CHARSET}',  
									  onSuccess: function(transport) {
									  	displayLoadedMarkers(transport);
									  	},
									  onFailure: function() {
									    alert('sorry, could not load marker!');
									  },
									  onComplete: function() {
									    // hide loading icon
										$('mpMapLoadingContainer').style.display = 'none';
									  },
									  onCreate: function() {
									    // display loading icon
										$('mpMapLoadingContainer').style.display = 'block';
									  }
									});
									
									
									return true;
								}
								
								function displayLoadedMarkers (transport) {
									if (transport.responseText != '') {
										if (mc) {
											mc.clearMarkers();
										}
										// we use json to parse the returned data because of its better performance for this task
										markers = transport.responseText.evalJSON(true);
										var markerCollector = [];
								
										for (var n = 0; n < markers.length; n++) {
											var pos = new google.maps.LatLng(markers[n].lat, markers[n].lng);
											
											if(markers[n].type == 'search') var icon = "{RELATIVE_WCF_DIR}icon/rm-search-marker.png";
											else if(markers[n].type == 'offer') var icon = "{RELATIVE_WCF_DIR}icon/rm-offer-marker.png";
											
											var marker = new google.maps.Marker({
												position: pos,
												icon: icon,
												map: map
											});
											globalMarkers[markers[n].entryID] = marker;
											
											marker.data = markers[n];				
											addMarker(marker, marker.data.infoWindow);
				
											markerCollector.push(marker);
											
										}
										
										mc = new MarkerClusterer(map, markerCollector, {
									//		gridSize: 5
											maxZoom: 11
										});
									}
								}
								
								function addMarker(marker, content) {
									google.maps.event.addListener(marker, 'click', function() {
										infoWindow.setContent(content);
										infoWindow.open(map, marker);
									});
								}
								/* ]]> */
							</script>
						{/if}

						{if 'MP_GMAP_LARGE_ENABLED'|defined && MP_GMAP_LARGE_ENABLED && RM_ENABLE_MAPS}
							<div class="contentBox mpBoxStats">
								<div class="border">
									<ul class="itemList">
										{include file="rmarketplaceIndexLargeMap"}
									</ul>
								</div>
							</div>
						{else}
							{capture assign="statistics"}
								<div class="contentBox mpBoxStats">
									<div class="border">
										<ul class="dataList">		
											<li class="{cycle}">			
												<div class="containerIcon">
													<img src="{icon}statisticsM.png{/icon}" alt="" title="" /> 
												</div>
												<div class="containerContent">
													<h4 class="smallFont">{lang}de.0xdefec.rmarketplace.activeEntries{/lang}</h4>
													<p>{$totalEntries} </p>
												</div>
											</li>
										</ul>
									</div>
								</div>
							{/capture}
						{/if}
						
						{if !$inModeration|isset}
							{@$statistics}
						
							{if 'MP_EXTERNAL_ENABLED'|defined && MP_EXTERNAL_ENABLED && $this->user->getPermission('user.rmarketplace.canViewExternal')}
								{include file="rmarketplaceIndexMpExternal"}
							{/if}
						{/if}
					</div>
				</div>
			</div>
		</div>
	</div>
	{if $additionalBoxes|isset || $tags|count}
		{cycle values='container-1,container-2' print=false advance=false}
		<div class="border infoBox" style="clear: both">
			{if $additionalBoxes|isset}{@$additionalBoxes}{/if}
			
			{if $tags|count}
				<div class="{cycle}">
					<div class="containerIcon"><img src="{icon}tagM.png{/icon}" alt="" /></div>
					<div class="containerContent">
						<h3><a href="index.php?page=TaggedObjects{@SID_ARG_2ND}">{lang}wcf.tagging.mostPopular{/lang}</a></h3>
						{include file='tagCloud'}
					</div>
				</div>
			{/if}
		</div>
	{/if}
	
	{lang}de.0xdefec.rmarketplace.copyright{/lang}
</div>	
	
{include file='footer' sandbox=false}
</body>
</html>