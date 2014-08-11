{* --- quick search controls --- *}
{assign var='searchFieldTitle' value='{lang}de.0xdefec.rmarketplace.search.query{/lang}'}
{capture assign=searchHiddenFields}
	<input type="hidden" name="types[]" value="mpentry" />
{/capture}
{* --- end --- *}