<li{if $rmEntriesNew > 0} class="new"{/if} id="userMenuRMarketplace">
	<a href="index.php?page=RMarketplace{if $rmEntriesNew > 0}&amp;newItems=1{/if}{@SID_ARG_2ND}">
		<img src="{icon}rMarketplaceS.png{/icon}" alt="" /> 
		<span>{lang}de.0xdefec.rmarketplace.title{/lang}{if $rmEntriesNew > 0} ({#$rmEntriesNew}){/if}</span>
	</a>
</li>