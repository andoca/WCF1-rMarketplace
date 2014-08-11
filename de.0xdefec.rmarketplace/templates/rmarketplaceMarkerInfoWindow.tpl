<div style="float: left;">
{if $entry->attachments[$entry->entryID]|isset}
	{if $entry->attachments[$entry->entryID].images|count > 0}
		{assign var=firstHit value=0}
		{foreach from=$entry->attachments[$entry->entryID].images item=attachment}
			{if $firstHit == 0}
				{if $attachment->thumbnailType}
					<img src="index.php?page=Attachment&amp;attachmentID={@$attachment->attachmentID}&amp;h={@$attachment->sha1Hash}&amp;thumbnail=1{@SID_ARG_2ND}" alt="{$attachment->attachmentName}" style="width: 50px;" />
					{assign var=firstHit value=1}
				{/if}
			{/if}
		{/foreach}
	{/if}
{else}
	<img src="{icon}rM{$entry->type}L.png{/icon}" />
{/if}
</div>
<div style="margin-left: 60px; margin-top:5px; padding-left: 1em; border-left: 2px solid #cccccc">

<strong class="smallFont light">
	{if $entry->type == 'search'}
		{lang}de.0xdefec.rmarketplace.search{/lang}:
	{else}
		{lang}de.0xdefec.rmarketplace.offer{/lang}:
	{/if}
	 <a href="index.php?page=RMarketplaceEntry&amp;entryID={$entry->entryID}{@SID_ARG_2ND}">{$entry->subject|truncate:70:'...'}</a>
</strong>
<p class="smallFont light">
	{lang}de.0xdefec.rmarketplace.from{/lang}

	{if $entry->userID}<a href="index.php?page=User&amp;userID={@$entry->userID}{@SID_ARG_2ND}" title="{lang username=$entry->username}wcf.user.viewProfile{/lang}">
		<span>{@$entry->username}</span></a>{else}<span>{@$entry->username}</span>{/if} {lang}de.0xdefec.rmarketplace.entry.date{/lang} ({$entry->clicks})
</p>
<p class="smallFont light">
	{$entry->country}-{$entry->zipcode} ({lang}de.0xdefec.rmarketplace.markerPositionApproximate{/lang})
</p>
</div>