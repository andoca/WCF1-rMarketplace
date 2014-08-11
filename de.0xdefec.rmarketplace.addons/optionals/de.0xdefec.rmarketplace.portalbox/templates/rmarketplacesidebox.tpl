<div class="border titleBarPanel boxrMarketplaceSide" id="box{$box->boxID}">
	<div class="containerHead">
		<div class="containerIcon">
		    <a href="javascript:void(0)" onclick="openList('{@$box->getStatusVariable()}', { save: true })"><img src="{icon}minusS.png{/icon}" id="{@$box->getStatusVariable()}Image" alt="" /></a>
		</div>
		<div class="containerContent">
			<h3><a href="index.php?page=RMarketplace{@SID_ARG_2ND}">{lang}de.0xdefec.rmarketplace.title{/lang}</a></h3>
		</div>
	</div>
	<div class="container-1" id="{@$box->getStatusVariable()}">
		<div class="containerContent">
			<div class="rMarketplaceSideBox">
				{if $box->rMentries.search|count || $box->rMentries.offer|count}
					{if $box->rMentries.search|count}
						<h4 style="font-weight: bold">{lang}de.0xdefec.rmarketplace.search{/lang}</h4>
			    		{foreach from=$box->rMentries.search item=entry}
				    		<p class="smallFont">
								<img src="{icon}rM{$entry->type}S.png{/icon}" alt="" />
								<a href="index.php?page=RMarketplaceEntry&amp;entryID={$entry->entryID}{@SID_ARG_2ND}" title="{$entry->subject}">{$entry->subject|truncate:40:'...'}</a>
							</p>
						{/foreach}
					{/if}
					{if $box->rMentries.offer|count}
						<h4 style="font-weight: bold">{lang}de.0xdefec.rmarketplace.offer{/lang}</h4>
			    		{foreach from=$box->rMentries.offer item=entry}
				    		<p class="smallFont">
								<img src="{icon}rM{$entry->type}S.png{/icon}" alt="" />
								<a href="index.php?page=RMarketplaceEntry&amp;entryID={$entry->entryID}{@SID_ARG_2ND}" title="{$entry->subject}">{$entry->subject|truncate:40:'...'}</a>
							</p>
						{/foreach}
					{/if}
				{else}
					<p class="smallFont">{lang}wbb.portal.box.rmarketplace.noentries{/lang}</p>
				{/if}
				<div style="clear:both"></div>
			</div>
		</div>
	</div>
</div>