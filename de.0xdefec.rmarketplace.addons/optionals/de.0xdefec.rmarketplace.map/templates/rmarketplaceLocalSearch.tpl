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
{/capture}
{include file='header' sandbox=false}

<div id="main">
	
	<ul class="breadCrumbs">	
		<li><a href="index.php?page=RMarketplace{@SID_ARG_2ND}"><img src="{icon}rMarketplaceS.png{/icon}" alt="" /> <span>{lang}de.0xdefec.rmarketplace.title{/lang}</span></a> &raquo;</li>
	</ul>
	<div class="mainHeadline">
		<img src="{icon}rMmapL.png{/icon}" alt="" />
		<div class="headlineContainer">
			<h2>{lang}de.0xdefec.rmarketplace.map.localSearch.title{/lang}</h2>
			<p>{lang}de.0xdefec.rmarketplace.map.localSearch.welcome{/lang}</p>
		</div>
	</div>
	
	{if $userMessages|isset}{@$userMessages}{/if}
	
	<div id="rMwrapper">
		<div id="mpRight">
			{include file='rmarketplaceEntryList' sandbox='false' map='1' mpControls='1'}
		</div>
	</div>
	
	
	<div id="mpLeft">
		{cycle values='container-1,container-2' print=false advance=false}
		<div class="contentBox">
			<div class="border">
				<div id="mpMap" class="{cycle}" ></div>
			</div>
		</div>
		
		<script type="text/javascript">
			var lat = '{$coord.lat}';
			var lng = '{$coord.lng}';
			
			var mapOptions = {
				center: new google.maps.LatLng(lat, lng),
				zoom: {MP_GMAP_START_ZOOM},
				mapTypeId: google.maps.MapTypeId.{MP_GMAP_TYPE},
				mapTypeControlOptions: {
					mapTypeIds: [google.maps.MapTypeId.ROADMAP, google.maps.MapTypeId.HYBRID, google.maps.MapTypeId.TERRAIN]
				},
				streetViewControl: false
			}
	    	
			var map = new google.maps.Map(document.getElementById("mpMap"), mapOptions);
			
			var posCenter = new google.maps.LatLng(lat, lng);
			
			var markerCenter = new google.maps.Marker({
				position: posCenter,
				icon: "{RELATIVE_WCF_DIR}icon/rm-green-dot.png",
				map: map
			});

	      var circleRadius = {$radius}*1000;

			var circleOptions = {
				strokeColor: "#000000",
				strokeOpacity: 0.3,
				strokeWeight: 1,
				fillColor: "#0f0fff",
				fillOpacity: 0.15,
				map: map,
				center: new google.maps.LatLng(lat, lng),
				radius: circleRadius
			};

			var circle = new google.maps.Circle(circleOptions);
			map.fitBounds(circle.getBounds());
		</script>
		{include file='rmarketplaceMapEntries' sandbox='false' entries=$entries hideOld=false bounds=true}
			<div class="contentBox">
				<div class="border">
					<ul class="dataList">
						{include file='rmarketplaceLocalSearchBox' sandbox='false'}
					</ul>
				</div>
			</div>
	</div>
	{lang}de.0xdefec.rmarketplace.copyright{/lang}
</div>
	
	{include file='footer' sandbox=false}

</body>
</html>