{include file="documentHeader"}
<head>
	<title>{lang}de.0xdefec.rmarketplace.title{/lang} - {lang}{PAGE_TITLE}{/lang}</title>
	<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/MultiPagesLinks.class.js"></script>
	
	{include file='headInclude' sandbox=false}
	{include file='rmarketplaceGlobalHeader' sandbox=false}
</head>
<body{if $templateName|isset} id="tpl{$templateName|ucfirst}"{/if}>
{include file='rmarketplaceSearchControls' sandbox=false}
{capture assign=rMBreadCrumbs}
	<div class="breadcrumb2"><a href="index.php?page=RMarketplace{@SID_ARG_2ND}"><span>{lang}de.0xdefec.rmarketplace.title{/lang}</span></a></div>
	{if $category !== null}
		<div class="breadcrumb3"><a href="index.php?page=RMarketplaceMap{@SID_ARG_2ND}"> <span>{lang}de.0xdefec.rmarketplace.map.title{/lang}</span></a></div>
		{foreach from=$category->getParents() item=parent}
			<div class="breadcrumb4"><a href="index.php?page=RMarketplaceMap&amp;cat={$parent->catID}{@SID_ARG_2ND}"> <span>{lang}{$parent->catName}{/lang}</span></a></div>
		{/foreach}
	{/if}
{/capture}
{include file='header' sandbox=false}

<div id="main">
	<ul class="breadCrumbs">
			<li><a href="index.php?page=RMarketplace{@SID_ARG_2ND}"><img src="{icon}rMarketplaceS.png{/icon}" alt="" /> <span>{lang}de.0xdefec.rmarketplace.title{/lang}</span></a> &raquo;</li>
			{if $category !== null}
				<li><a href="index.php?page=RMarketplaceMap{@SID_ARG_2ND}"><img src="{icon}rMmapS.png{/icon}" alt="" /> <span>{lang}de.0xdefec.rmarketplace.map.title{/lang}</span></a> &raquo;</li>
				{foreach from=$category->getParents() item=parent}
					<li><a href="index.php?page=RMarketplaceMap&amp;cat={$parent->catID}{@SID_ARG_2ND}">{if $parent->catIcon}<img src="{icon}{$parent->catIcon}S.png{/icon}" alt="" />{else}<img src="{icon}rMarketplaceS.png{/icon}" alt="" />{/if} <span>{lang}{$parent->catName}{/lang}</span></a> &raquo;</li>
				{/foreach}
			{/if}
	</ul>
	<div class="mainHeadline">
		<img src="{icon}{if $category === null}rMmapL.png{else}{if $category->catIcon}{$category->catIcon}L.png{else}rMarketplaceL.png{/if}{/if}{/icon}" alt="" />
		<div class="headlineContainer">
			<h2>{if $category === null}{lang}de.0xdefec.rmarketplace.map.title{/lang}{else}{lang}{$category->catName}{/lang}{/if}</h2>
			<p>{if $category === null}{lang}de.0xdefec.rmarketplace.map.description{/lang}{else}{lang}{@$category->catDescription}{/lang}{/if}</p>
		</div>
	</div>
	
	{if $userMessages|isset}{@$userMessages}{/if}
	
	<div class="tabMenu">
		<ul>
			<li>
				<a href="index.php?page=RMarketplace{@SID_ARG_2ND}"><img src="{icon}rMarketplaceM.png{/icon}" alt="" /> <span>{lang}de.0xdefec.rmarketplace.title{/lang}</span></a>
			</li>
			<li class="activeTabMenu">
				<a href="index.php?page=RMarketplaceMap&amp;cat={$categoryID}{@SID_ARG_2ND}"><img src="{icon}rMmapM.png{/icon}" alt="" /> <span>{lang}de.0xdefec.rmarketplace.map.title{/lang}</span></a>
			</li>
			<li>
				<a href="index.php?page=RMarketplace&amp;cat={$categoryID}&amp;type=search{@SID_ARG_2ND}"><img src="{icon}rMsearchM.png{/icon}" alt="" /> <span>{lang}de.0xdefec.rmarketplace.search{/lang}</span></a>
			</li>
			<li>
				<a href="index.php?page=RMarketplace&amp;cat={$categoryID}&amp;type=offer{@SID_ARG_2ND}"><img src="{icon}rMofferM.png{/icon}" alt="" /> <span>{lang}de.0xdefec.rmarketplace.offer{/lang}</span></a>
			</li>
		</ul>
	</div>
	
	<div class="subTabMenu">
		<div class="containerHead"></div>
	</div>
	
	<div class="border">
		<div style="width: 100%;position: relative; left: -1px" class="container-1">
			<div id="mpMap" style="width: 100%; height: 500px;" class="" ></div>
			<div id="mpMapLoadingContainer" style="display: none; position: relative; top:-500px; left: 0; height: 100%; width; 100%;">
				<div style="height: 500px; width: 100%; background-color: #fff; opacity: 0.6; margin-bottom: -500px;">
					<img src="{icon}rMloading.gif{/icon}" id="mpMapLoading" style="position: absolute; display: inline; top: 250px; left: 50%" alt="loading..." />
					<div class="clear"></div>
				</div>
			</div>
		</div>
	</div>
	
	<script type="text/javascript">
		/* <![CDATA[ */
		{if MP_GMAP_LARGE_START_SET}
			var lat = '{MP_GMAP_LARGE_LAT}';
			var lng = '{MP_GMAP_LARGE_LNG}';
		{else}
			if (google.loader.ClientLocation && google.loader.ClientLocation.latitude && google.loader.ClientLocation.longitude) {
				var lat = google.loader.ClientLocation.latitude;
				var lng = google.loader.ClientLocation.longitude;
			}
			else {
				var lat = '{MP_GMAP_LARGE_LAT}';
				var lng = '{MP_GMAP_LARGE_LNG}';
				}
		{/if}

		var mapOptions = {
			center: new google.maps.LatLng(lat, lng),
			zoom: {MP_GMAP_LARGE_ZOOM},
			mapTypeId: google.maps.MapTypeId.{MP_GMAP_LARGE_TYPE},
		//	mapTypeId: google.maps.MapTypeId.ROADMAP,
			mapTypeControlOptions: {
				mapTypeIds: [google.maps.MapTypeId.ROADMAP, google.maps.MapTypeId.HYBRID, google.maps.MapTypeId.TERRAIN]
			},
			streetViewControl: false
		}
    	
		var map = new google.maps.Map(document.getElementById("mpMap"), mapOptions);
		
		
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
				lastBoundsNE = lastBounds.getNorthEast();				// get bounds of current map
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
				  	cat: {if $category !== NULL}{$category->catID}{else}0{/if}
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
		//				gridSize: 40
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
	
	<form method="get" action="index.php" class="quickJump">
		<div>
			<input type="hidden" name="page" value="RMarketplaceMap" />
			<select name="cat" onchange="this.form.submit()" style="max-width: 90%;">
				<option value="0">{lang}de.0xdefec.rmarketplace.quickJump.title{/lang}</option>
				<option value="0">-----------------------</option>
				{htmloptions options=$categoryTree selected=$categoryID disableEncoding=true}
			</select>
			
			{@SID_INPUT_TAG}
			<input type="image" class="inputImage" src="{icon}submitS.png{/icon}" alt="{lang}wcf.global.button.submit{/lang}" />
		</div>
	</form>
	{lang}de.0xdefec.rmarketplace.copyright{/lang}
</div>
	{include file='footer' sandbox=false}

</body>
</html>