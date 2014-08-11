<div class="border titleBarPanel" id="box{$box->boxID}" style="overflow: hidden">
	<div class="containerHead">
	        <div class="containerIcon">
				<a href="javascript: void(0)" onclick="openList('{@$box->getStatusVariable()}', { save: true })">
			    		<img src="{icon}minusS.png{/icon}" id="{@$box->getStatusVariable()}Image" alt="" />
				</a>
		</div>
		<div class="containerContent"><h3>{if PORTAL_RMARKETPLACEBOXITEMS_RANDOM}{lang}wbb.portal.box.rmarketplace.title.random{/lang}{else}{lang}wbb.portal.box.rmarketplace.title.new{/lang}{/if}</h3></div>
	</div>
	<div class="container-1" id="{@$box->getStatusVariable()}">
		<div class="containerContent">
		    	{if $box->rMentries|count}
		    		{include file='rmarketplaceEntryList' sandbox='false' map='0' mpControls='0' entries=$box->rMentries}
				{else}
					<p class="normalFont">{lang}wbb.portal.box.rmarketplace.noentries{/lang}</p>
				{/if}
			<div style="clear:both"></div>
		</div>        
	</div>
</div>