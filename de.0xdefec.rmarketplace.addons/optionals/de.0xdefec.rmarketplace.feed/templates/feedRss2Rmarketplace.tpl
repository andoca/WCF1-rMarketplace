<?xml version="1.0" encoding="{@CHARSET}"?>
<rss version="2.0">
	<channel>
		<title>{lang}de.0xdefec.rmarketplace.feed.title{/lang}</title>
		<link>{@PAGE_URL}/</link>
		<description>{lang}de.0xdefec.rmarketplace.feed.description{/lang}</description>
		
		<pubDate>{@'r'|gmdate:TIME_NOW}</pubDate>
		<lastBuildDate>{@'r'|gmdate:TIME_NOW}</lastBuildDate>
		<ttl>60</ttl>
		
		{foreach from=$entries item=$entry}
			<item>
				<title>{if $entry->type == 'search'}{lang}de.0xdefec.rmarketplace.search{/lang}{else}{lang}de.0xdefec.rmarketplace.offer{/lang}{/if}: {$entry->subject} ({$entry->category->catName}, {$entry->country} - {$entry->zipcode})</title>
				<author>{$entry->username}</author>
				<link>{@PAGE_URL}/index.php?page=RMarketplaceEntry&amp;entryID={@$entry->entryID}</link>
				<guid>{@PAGE_URL}/index.php?page=RMarketplaceEntry&amp;entryID={@$entry->entryID}</guid>
				<pubDate>{if !$entry->lastEditTime}{@'c'|gmdate:$entry->time}{else}{@'c'|gmdate:$entry->lastEditTime}{/if}</pubDate>
				<description><![CDATA[{@$entry->getFormattedMessage()}{if !$entry->isActive}
		<p class="success">{lang}de.0xdefec.rmarketplace.entry.isNotActive{/lang}</p>
	{/if}]]></description>
			</item>
		{/foreach}
	</channel>
</rss>