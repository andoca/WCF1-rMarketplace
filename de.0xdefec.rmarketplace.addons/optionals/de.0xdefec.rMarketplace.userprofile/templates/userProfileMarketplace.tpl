<div class="contentBox"><a id="rmarketplace"></a>
	{include file='rmarketplaceGlobalHeader' sandbox=false}
	<h3 class="subHeadline"><a href="index.php?page=RMarketplace{@SID_ARG_2ND}">{lang}de.0xdefec.rmarketplace.title{/lang}</a> <span>({#$marketplaceEntries})</span></h3>
	
	<ul class="dataList floatContainer container-1">
		{include file='rmarketplaceEntryList' sandbox='false' map='0'}
	</ul>
	
	<div class="buttonBar">
		<div class="smallButtons">
			<ul>
				<li class="extraButton"><a href="#top" title="{lang}wcf.global.scrollUp{/lang}"><img src="{icon}upS.png{/icon}" alt="{lang}wcf.global.scrollUp{/lang}" /> <span class="hidden">{lang}wcf.global.scrollUp{/lang}</span></a></li>
				<li><a href="index.php?page=RMarketplace{@SID_ARG_2ND}"><img src="{icon}rMarketplaceS.png{/icon}" alt="{lang}de.0xdefec.rmarketplace.title{/lang}" /> {lang}de.0xdefec.rmarketplace.title{/lang}</a></li>
			</ul>
		</div>
	</div>
</div>