		{cycle values='container-1,container-2' print=false advance=false}
		{if !$map|isset}{assign var="map" value=false}{/if}
		{if !$mpControls|isset}{assign var="mpControls" value=false}{/if}

		{if $entries|count}
			{*<style type="text/css">
				<!--[if IE 6]>
					.entryBlock {
						overflow: visible;
					}
				<![endif]-->
				.entryBlock {
					overflow: auto;
				}
			</style>*}
			{if !$this->user->getPermission('user.rmarketplace.canList')}
				<p class="info">{lang}de.0xdefec.rmarketplace.permissionDenied{/lang}</p>
			{else}
				{foreach from=$entries item=entry}
					<div class="{cycle} round border mp{$entry->type|ucfirst}{if $this->user->getPermission('mod.rmarketplace.canModerate')} deletable{/if} entryBlock">
						<div style="position:relative">
							<h3 style="margin-right: 25px;">
								{if $entry->time > $this->getSession()->getVar('rmLastVisitTime')}
									<img src="{icon}rmNewM.png{/icon}" alt="" style="position: absolute; top:0; right:0" />
								{/if}
								{if !$entry->isActive && $mpControls}
									<img src="{icon}successS.png{/icon}" alt="" style="vertical-align:-10%" />
								{/if}
								{if $entry->isDisabled && $mpControls}
									{if $entry->userCanDisable()}
										<a href="index.php?action=RMarketplace&amp;t={@SECURITY_TOKEN}&amp;methode=enable&amp;entryID={$entry->entryID}" title="{lang}de.0xdefec.rmarketplace.enable{/lang}"><img src="{icon}enabledS.png{/icon}" alt="" style="vertical-align: -10%" /></a>
									{else}
										<img src="{icon}disabledS.png{/icon}" alt="" style="vertical-align: -10%" />
									{/if}
								{/if}
								
								{if $this->user->getPermission('mod.rmarketplace.canModerate') && $mpControls}
									<a class="deleteButton" href="index.php?action=RMarketplace&amp;t={@SECURITY_TOKEN}&amp;methode=delete&amp;entryID={$entry->entryID}{@SID_ARG_2ND}" title="{lang}de.0xdefec.rmarketplace.delete{/lang}"><img src="{icon}deleteS.png{/icon}" style="vertical-align: -10%" alt="" /></a>
								{/if}
								
								{if $entry->type == 'search'}
									{lang}de.0xdefec.rmarketplace.search{/lang}:
								{else}
									{lang}de.0xdefec.rmarketplace.offer{/lang}:
								{/if}
								{if $map == 1 && RM_ENABLE_MAPS && $entry->isGeolocated()}
									{include file='rmarketplaceMarkerInfoWindow' assign=infoWindow}
									<script type="text/javascript"> 
										/* <![CDATA[ */ 
										var infoWindow{$entry->entryID} = '{@$infoWindow|encodejs}'; 
										/* ]]> */
									</script>
								{/if}
								 <a href="index.php?page=RMarketplaceEntry&amp;entryID={$entry->entryID}{@SID_ARG_2ND}">{$entry->subject|truncate:100:'...'}</a> {if $map == 1 && RM_ENABLE_MAPS && $entry->isGeolocated() && 1 == 0}<a href="javascript:addAndDisplayMarker('{$entry->entryID}', '{$entry->lat}', '{$entry->lng}', '{$entry->type}', infoWindow{$entry->entryID})"><img src="{icon}rMmapS.png{/icon}" alt="" style="vertical-align: -10%" /></a>{/if}
							</h3>
							<div style="float: left;">
								{if $entry->attachments[$entry->entryID]|isset && $entry->attachments[$entry->entryID].images|count > 0}
									{assign var=firstHit value=0}
									{foreach from=$entry->attachments[$entry->entryID].images item=attachment}
										{if $firstHit == 0}
											{if $attachment->thumbnailType}
												<a class="enlargable" style="width: 50px; height: 50px" href="index.php?page=Attachment&amp;attachmentID={@$attachment->attachmentID}&amp;h={@$attachment->sha1Hash}{@SID_ARG_2ND}" title="{$entry->subject|truncate:100:'...'}"><img src="index.php?page=Attachment&amp;attachmentID={@$attachment->attachmentID}&amp;h={@$attachment->sha1Hash}&amp;thumbnail=1{@SID_ARG_2ND}" alt="{$attachment->attachmentName}" style="width: 50px; max-height: 50px" /></a>
												{assign var=firstHit value=1}
											{/if}
										{/if}
									{/foreach}
								{else}
									<img src="{icon}rM{$entry->type}L.png{/icon}" alt="" style="display: block;" />
								{/if}
							</div>
							<div style="margin-left: 60px; margin-top:5px; padding-left: 1em; border-left: 2px solid #cccccc;">
								<p class="smallFont light">
									{lang}de.0xdefec.rmarketplace.from{/lang}
									{if $entry->userID}<a href="index.php?page=User&amp;userID={@$entry->userID}{@SID_ARG_2ND}" title="{lang username=$entry->username}wcf.user.viewProfile{/lang}">
										<span>{@$entry->username}</span></a>{else}<span>{@$entry->username}</span>{/if} ({lang}de.0xdefec.rmarketplace.entry.date{/lang}{if $entry->price} - {lang}de.0xdefec.rmarketplace.entry.price{/lang}: {$entry->price}{/if} - {$entry->comments} {if $entry->comments == 1}{lang}de.0xdefec.rmarketplace.entry.comment{/lang}{else}{lang}de.0xdefec.rmarketplace.entry.comments{/lang}{/if}) - <a href="index.php?page=RMarketplace&amp;cat={$entry->category->catID}">{lang}{$entry->category->catName}{/lang}</a>
								</p>
								<p>{$entry->getTextPreview()}</p>
							</div>
							<div class="clears">&nbsp;</div>
						</div>
					</div>
				{/foreach}
			{/if}
		{else}
			<div class="{cycle} border" style="padding: 1em">{lang}de.0xdefec.rmarketplace.empty{/lang}</div>
		{/if}