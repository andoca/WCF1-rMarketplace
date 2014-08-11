		{if $entries|count}
			{if !$this->user->getPermission('user.rmarketplace.canList')}
				<p class="info">{lang}de.0xdefec.rmarketplace.permissionDenied{/lang}</p>
			{else}
				{foreach from=$entries item=entry}
					<div class="entry">
						{if $entry->attachments[$entry->entryID]|isset}
							{if $entry->attachments[$entry->entryID].images|count > 0}
								{assign var=firstHit value=0}
								{foreach from=$entry->attachments[$entry->entryID].images item=attachment}
									{if $firstHit == 0}
										{if $attachment->thumbnailType}
											<img align="left" src="{MP_EXTERNAL_ABS_PATH}index.php?page=Attachment&amp;attachmentID={@$attachment->attachmentID}&amp;h={@$attachment->sha1Hash}&amp;thumbnail=1" style="width: 50px; max-height: 50px" alt="{$attachment->attachmentName}" />
											{assign var=firstHit value=1}
										{/if}
									{/if}
								{/foreach}
							{/if}
						{else}
							<img align="left" src="{MP_EXTERNAL_ABS_PATH}{icon}rM{$entry->type}L.png{/icon}" style="width: 50px; max-height: 50px" alt="" />
						{/if}
						<div>
							<h2>
								<a href="{MP_EXTERNAL_ABS_PATH}index.php?page=RMarketplaceEntry&amp;entryID={$entry->entryID}">
									{if $entry->type == 'search'}{lang}de.0xdefec.rmarketplace.search{/lang}{else}{lang}de.0xdefec.rmarketplace.offer{/lang}{/if}: {$entry->subject|truncate:100:'...'}
								</a>
							</h2>
							<span class="entrytime">{lang}de.0xdefec.rmarketplace.from{/lang}
									{if $entry->userID}<a href="{MP_EXTERNAL_ABS_PATH}index.php?page=User&amp;userID={@$entry->getUser()->userID}{@SID_ARG_2ND}" title="{lang username=$entry->getUser()->username}wcf.user.viewProfile{/lang}">
										<span>{@$entry->username}</span></a>{else}<span>{@$entry->username}</span>{/if} ({lang}de.0xdefec.rmarketplace.entry.date{/lang})</span>
							<p>{$entry->getTextPreview()}</p>
							<span class="entrylink">&raquo; <a href="{MP_EXTERNAL_ABS_PATH}index.php?page=RMarketplaceEntry&amp;entryID={$entry->entryID}">{lang}de.0xdefec.rmarketplace.external.gotoEntry{/lang}</a></span>
						</div>
					</div>
				{/foreach}
			{/if}
		{else}
			<div class="border" style="padding: 1em">{lang}de.0xdefec.rmarketplace.empty{/lang}</div>
		{/if}