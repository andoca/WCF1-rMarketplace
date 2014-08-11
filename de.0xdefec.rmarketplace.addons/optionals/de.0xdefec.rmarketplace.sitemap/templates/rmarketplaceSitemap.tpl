<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.google.com/schemas/sitemap/0.84">
	{foreach from=$entries item=$entry}
		<url>
			<loc>{@PAGE_URL}/index.php?page=RMarketplaceEntry&amp;entryID={@$entry->entryID}</loc>
			<lastmod>{if !$entry->lastEditTime}{@'c'|gmdate:$entry->time}{else}{@'c'|gmdate:$entry->lastEditTime}{/if}</lastmod>
		</url>
	{/foreach}
</urlset>