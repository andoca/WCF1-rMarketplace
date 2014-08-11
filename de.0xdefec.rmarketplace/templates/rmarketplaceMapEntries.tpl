		{if $entries|count}
			<script type="text/javascript">
			/* <![CDATA[ */
			
				var mc = '';
				var globalMarkers = [];
				var infoWindow = new google.maps.InfoWindow();
				var markerCollector = [];
				var globalBounds = new google.maps.LatLngBounds();
				
				{foreach from=$entries item=entry}
{if (!$hideOld || !$entry->isOld()) && RM_ENABLE_MAPS && $entry->isGeolocated()}
var pos{$entry->entryID}=new google.maps.LatLng({$entry->lat},{$entry->lng});
//var marker{$entry->entryID}=new GMarker(pos{$entry->entryID},icon{$entry->type|ucfirst});
if('{$entry->type}' == 'search') var icon = "{RELATIVE_WCF_DIR}icon/rm-blue-dot.png";
else if('{$entry->type}' == 'offer') var icon = "{RELATIVE_WCF_DIR}icon/rm-orange-dot.png";
var marker{$entry->entryID}=new google.maps.Marker({
	position: pos{$entry->entryID},
	icon: icon,
	map: map
});
{include file='rmarketplaceMarkerInfoWindow' assign=markerInfo}
addMarker(marker{$entry->entryID}, '{@$markerInfo|encodejs}');
markerCollector.push(marker{$entry->entryID});
globalBounds.extend(pos{$entry->entryID});
{/if}
				{/foreach}
					mc = new MarkerClusterer(map, markerCollector, {
						gridSize: 5
					});
				
				{if $bounds|isset && $bounds == true && RM_ENABLE_MAPS && $entry->isGeolocated()}
					//  we fit the map to the circle bounds, no need to use the markers
					//	map.fitBounds(globalBounds);
				{/if}
				
				function addMarker(marker, content) {
					google.maps.event.addListener(marker, 'click', function() {
						infoWindow.setContent(content);
						infoWindow.open(map, marker);
					});
				}
			/* ]]> */
			</script>
		{/if}