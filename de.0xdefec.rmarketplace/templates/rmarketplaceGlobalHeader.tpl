{if RM_ENABLE_MAPS}
	<script type="text/javascript">
		/* <![CDATA[ */
		{if 'GMAP_API_KEY'|defined && 'GMAP_API_KEY' != ''}
			{assign var="apiKey" value=GMAP_API_KEY}
		{else}
			{assign var="apiKey" value=MP_GMAP_KEY}
		{/if}
	
		GMAP_API_KEYS = new Object();
		GMAP_API_KEY = '';
		{assign var="hasMultipleKeys" value=false}
		{foreach name="keys" from=$apiKey|arrayfromlist key=host item=key}
			GMAP_API_KEYS['{@$host}'] = '{@$key}';
			{if $tpl.foreach.keys.iteration == 2}{assign var="hasMultipleKeys" value=true}{/if}
		{/foreach}
	
		for (host in GMAP_API_KEYS) {
			if (window.location.host == host) {
				GMAP_API_KEY = GMAP_API_KEYS[host];
			}
		}
	
		{if !$hasMultipleKeys}
			if (GMAP_API_KEY == '') {
				GMAP_API_KEY = '{$apiKey}';
			}
		{/if}
		document.write('<script type="text/javascript" src="http://www.google.com/jsapi?key='
		   + GMAP_API_KEY + '"></scr' + 'ipt>');
		   
		/* ]]> */
	</script>
	<script type="text/javascript">
		/* <![CDATA[ */
		google.load("maps", "3", {ldelim}other_params: "sensor=false"{rdelim});
		{if 'MP_GMAP_LARGE_ENABLED'|defined && MP_GMAP_LARGE_ENABLED}
			google.load("language", "1");
		{/if}
		/* ]]> */
	</script>
	<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/markerclusterer_compiled.js"></script>
{/if}
{include file='imageViewer'}