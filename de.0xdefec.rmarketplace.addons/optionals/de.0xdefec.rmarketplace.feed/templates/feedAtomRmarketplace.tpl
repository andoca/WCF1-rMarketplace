<?xml version="1.0" encoding="{@CHARSET}"?>
<feed xmlns="http://www.w3.org/2005/Atom">
	<title>{lang}de.0xdefec.rmarketplace.feed.title{/lang}</title>
	<id>{@PAGE_URL}/</id>
	<updated>{@'c'|gmdate:TIME_NOW}</updated>
	<link href="{@PAGE_URL}/" />
	<subtitle>{lang}de.0xdefec.rmarketplace.feed.description{/lang}</subtitle>
	
	{foreach from=$entries item=$entry}
		<entry>
			<title>{if $entry->type == 'search'}{lang}de.0xdefec.rmarketplace.search{/lang}{else}{lang}de.0xdefec.rmarketplace.offer{/lang}{/if}: {$entry->subject} ({$entry->category->catName}, {$entry->country} - {$entry->zipcode})</title>
			<id>{@PAGE_URL}/index.php?page=RMarketplaceEntry&amp;entryID={@$entry->entryID}</id>
			<updated>{if !$entry->lastEditTime}{@'c'|gmdate:$entry->time}{else}{@'c'|gmdate:$entry->lastEditTime}{/if}</updated>
			<author>
				<name>{$entry->username}</name>
			</author>
			<content type="html"><![CDATA[{@$entry->getFormattedMessage()}{if !$entry->isActive}
		<p class="success">{lang}de.0xdefec.rmarketplace.entry.isNotActive{/lang}</p>
	{/if}]]></content>
			<link href="{@PAGE_URL}/index.php?page=RMarketplaceEntry&amp;entryID={@$entry->entryID}" />
		</entry>
	{/foreach}
</feed>