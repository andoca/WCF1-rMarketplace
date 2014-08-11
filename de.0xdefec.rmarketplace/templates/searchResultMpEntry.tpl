<div class="message content">
	<div class="messageInner container-{cycle name='results'}">
		<div class="messageHeader">
			<div class="containerIcon">
				<a href="index.php?page=RMarketplaceEntry&amp;entryID={@$item.message->entryID}{@SID_ARG_2ND}"><img src="{icon}messageM.png{/icon}" alt="" /></a>
			</div>
			<div class="containerContent">
				<p class="light smallFont">{@$item.message->time|time}</p>
				<p class="light smallFont">{$item.message->username}</p>
			</div>
		</div>
		<h3><a href="index.php?page=RMarketplaceEntry&amp;entryID={@$item.message->entryID}{@SID_ARG_2ND}">{$item.message->subject}</a></h3>
		<div class="messageBody">
			{@$item.message->getFormattedMessage()}
		</div>
		<div class="messageFooter">
			<ul class="breadCrumbs light">
				{*<li><a href="index.php?page=RMarketplace{@SID_ARG_2ND}"><img src="{icon}rMarketplaceS.png{/icon}" alt="" /> <span>{lang}de.0xdefec.rmarketplace.title{/lang}</span></a> &raquo;</li>*}
				{foreach from=$item.message->category->getParents() item=parent}
					<li>{if $parent->catIcon}<img src="{icon}{$parent->catIcon}S.png{/icon}" alt="" />{else}<img src="{icon}rMarketplaceS.png{/icon}" alt="" />{/if} <a href="index.php?page=RMarketplace&amp;cat={$parent->catID}{@SID_ARG_2ND}">{$parent->catName}</a> &raquo;</li>
				{/foreach}
				<li>{if $item.message->category->catIcon}<img src="{icon}{$item.message->category->catIcon}S.png{/icon}" alt="" />{else}<img src="{icon}rMarketplaceS.png{/icon}" alt="" />{/if} <a href="index.php?page=RMarketplace&amp;cat={$item.message->category->catID}{@SID_ARG_2ND}">{$item.message->category->catName}</a> </li>	
			</ul>
			<div class="smallButtons">
				<ul>
					<li class="extraButton"><a href="#top" title="{lang}wcf.global.scrollUp{/lang}"><img src="{icon}upS.png{/icon}" alt="" /> <span class="hidden">{lang}wcf.global.scrollUp{/lang}</span></a></li>
				</ul>
			</div>
			
		</div>
		<hr />
	</div>
</div>