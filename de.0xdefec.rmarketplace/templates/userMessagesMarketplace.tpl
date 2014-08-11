<div class="info deletable">
	<a href="index.php?page=RMarketplace&amp;action=disableNotifications&amp;t={@SECURITY_TOKEN}{@SID_ARG_2ND}" class="close deleteButton"><img src="{icon}closeS.png{/icon}" alt="" title="" longdesc="" /></a>
	<p>{lang}de.0xdefec.rmarketplace.notification.title{/lang}</p>
	<ul>
		{foreach from=$uMentries item=uMentry}
			<li title="{$uMentry->getTextPreview()}">
				<a href="index.php?page=RMarketplaceEntry&amp;entryID={@$uMentry->entryID}{@SID_ARG_2ND}">{$uMentry->subject}</a> {lang}de.0xdefec.rmarketplace.from{/lang} {if $uMentry->userID}<a href="index.php?page=User&amp;userID={@$uMentry->getUser()->userID}{@SID_ARG_2ND}" title="{lang username=$uMentry->getUser()->username}wcf.user.viewProfile{/lang}"><span>{$uMentry->username}</span></a>{else}{$uMentry->username}{/if}
			</li>
		{/foreach}
	</ul>
</div>