{include file='header'}
<div class="mainHeadline">
	<img src="{@RELATIVE_WCF_DIR}icon/contentL.png" alt="" />
	<div class="headlineContainer">
		<h2>{lang}wcf.acp.rmarketplace.category{/lang}</h2>
	</div>
</div>

<div class="contentHeader">
	<div class="largeButtons">
		<ul><li><a href="index.php?form=RMarketplaceCategoryAdd&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/bbcodeAddM.png" alt="" title="{lang}wcf.acp.rmarketplace.categories.add{/lang}" /> <span>{lang}wcf.acp.rmarketplace.categories.add{/lang}</span></a></li></ul>
	</div>
</div>
{if $moved == true}
	<p class="success">{lang}wcf.acp.rmarketplace.category.move.success{/lang}</p>
{/if}
{if $categories|count}
	<div class="border">
		<div class="containerHead"><h3>{lang}wcf.acp.rmarketplace.categories.list{/lang}</h3></div>
	</div>
	<div class="border borderMarginRemove">
		<table class="tableList">
			<thead>
				<tr class="tableHead">
					<th style="width: 80px"><div>{lang}wcf.acp.rmarketplace.category.action{/lang}</div></th>
					<th><div>{lang}wcf.acp.rmarketplace.category.name{/lang}</div></th>
					<th><div>{lang}wcf.acp.rmarketplace.category.move{/lang}</div></th>
				</tr>
			</thead>
			<tbody id="categories">
			{foreach from=$categories key=id item=category}
				<tr class="{cycle values="container-1,container-2"}">
					<td>
						<a href="index.php?form=RMarketplaceCategoryEdit&amp;catID={$id}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}" title="{lang}wcf.acp.rmarketplace.category.edit{/lang}"><img src="{@RELATIVE_WCF_DIR}icon/editS.png" alt="" /></a> 
						{if $id != 1}<a onclick="if(!confirm('{lang}wcf.acp.rmarketplace.category.askDelete{/lang}')) return false;" href="index.php?form=RMarketplaceCategoryEdit&amp;catID={$id}&amp;action=delete&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}" title="{lang}wcf.acp.rmarketplace.category.delete{/lang}"><img src="{@RELATIVE_WCF_DIR}icon/deleteS.png" alt="" /></a>{/if}
						{*<img src="{@RELATIVE_WCF_DIR}icon/moveS.png" class="dragger" alt="" />*}
					</td>
					
					<td>{@$category}</td>
					<td>
						<form action="index.php" method="get">
						<select name="to" onchange="if(this.value != {$id} && confirm('{lang}wcf.acp.rmarketplace.category.move.ask{/lang}')) this.form.submit()">
							{htmloptions options=$categories selected=$id disableEncoding=true}
						</select>
						<input type="hidden" name="form" value="RMarketplaceCategoryEdit" />
						<input type="hidden" name="action" value="move" />
						<input type="hidden" name="from" value="{$id}" />
						{@SID_INPUT_TAG}
						</form>
					</td>
				</tr>
			{/foreach}
			</tbody>
		</table>
	</div>
	{*<script type="text/javascript">
		Sortable.create("categories", {ldelim} tag: 'tr', handles:$$('#dragger') {rdelim});
	</script>*}
{else}
<div class="contentFooter">
	{lang}wcf.acp.rmarketplace.category.empty{/lang}
</div>
{/if}
{include file='footer'}