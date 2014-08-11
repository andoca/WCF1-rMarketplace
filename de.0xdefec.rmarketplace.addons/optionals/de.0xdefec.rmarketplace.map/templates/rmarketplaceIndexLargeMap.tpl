			<li class="{cycle}">
				<h4 class="itemListTitle">
					<img src="{icon}rMmapM.png{/icon}" alt="" title="{lang}de.0xdefec.rmarketplace.map.show{/lang}" />
					<a href="index.php?page=RMarketplaceMap{if $categoryID}&amp;cat={$categoryID}{/if}{@SID_ARG_2ND}">{lang}de.0xdefec.rmarketplace.map.show{/lang}</a> ({$totalEntries} {lang}de.0xdefec.rmarketplace.activeEntries{/lang})
				</h4>
			</li>
			{include file='rmarketplaceLocalSearchBox' sandbox='false'}