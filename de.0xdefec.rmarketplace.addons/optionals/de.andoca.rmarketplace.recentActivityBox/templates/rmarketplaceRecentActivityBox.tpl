<table class="tableList">
	<tbody>
		{foreach from=$rmEntries item=entry}
			{assign var=entryID value=$entry->entryID}
			<tr class="container-{cycle values='1,2'}">
				<td class="columnIcon">
					{if $entry->category->catIcon}<img src="{icon}{$entry->category->catIcon}M.png{/icon}" alt="" />{else}<img src="{icon}rMarketplaceM.png{/icon}" alt="" />{/if}
				</td>
				
				<td style="width: 25%">
					<p><a href="index.php?page=RMarketplace&amp;cat={$entry->category->catID}">{lang}{$entry->category->catName}{/lang}</a></p>
				</td>
				
				<td>
					<img src="{icon}rM{@$entry->type}M.png{/icon}" alt="" />
				</td>
					
				<td style="width: 50%">
					<div id="rmEntry{@$entry->entryID}" class="{if $entry->isNew()}new{/if}">
						<p>
							{if $entry->type == 'search'}
								{lang}de.0xdefec.rmarketplace.search{/lang}:
							{else}
								{lang}de.0xdefec.rmarketplace.offer{/lang}:
							{/if}
							<a href="index.php?page=RMarketplaceEntry&amp;entryID={$entry->entryID}{@SID_ARG_2ND}">{$entry->subject|truncate:200:'...'}</a>
							{if !$entry->isActive}
								<img src="{icon}successS.png{/icon}" alt="" />
							{/if}
						</p>
						<p class="smallFont light">{if $entry->price}{lang}de.0xdefec.rmarketplace.entry.price{/lang}: {$entry->price}{/if} - {$entry->comments} {if $entry->comments == 1}{lang}de.0xdefec.rmarketplace.entry.comment{/lang}{else}{lang}de.0xdefec.rmarketplace.entry.comments{/lang}{/if}</p>
					</div>
				</td>
				
				<td style="width: 25%">
					<div class="">
						<p>{lang}de.0xdefec.rmarketplace.from{/lang} {if $entry->userID}<a href="index.php?page=User&amp;userID={@$entry->userID}{@SID_ARG_2ND}" title="{lang username=$entry->username}wcf.user.viewProfile{/lang}"><span>{@$entry->username}</span></a>{else}<span>{@$entry->username}</span>{/if}</p>
						<p class="smallFont light">({lang}de.0xdefec.rmarketplace.entry.date{/lang})</p>
					</div>
				</td>
			</tr>
		{/foreach}
	</tbody>
</table>