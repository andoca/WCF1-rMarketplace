	<div class="rmarketplaceBox">
		<div class="border titleBarPanel">
		        <div class="containerHead">
		                <div class="containerIcon">
		                        <a href="javascript: void(0)" onclick="openList('rmarketplaceTopList', { save:true })"><img src="{icon}minusS.png{/icon}" id="rmarketplaceTopListImage" alt="" /></a>
		                </div>
		                <div class="containerContent">
		                        <img src="{icon}rMarketplaceS.png{/icon}" alt="" /> <a href="index.php?page=RMarketplace{@SID_ARG_2ND}"><strong>{lang}de.0xdefec.rmarketplace.title{/lang}</strong></a>
		                </div>
		        </div>
	           
			<div id="rmarketplaceTopList">
			    {if $rmEntries|count == 0}
			    	<p>{lang}de.0xdefec.rmarketplace.empty{/lang}</p>
			    {else}
				<table class="tableList">                			                                                        	
			        <tbody>
			            {cycle values='container-1,container-2' name='className' print=false advance=false}
			            {foreach from=$rmEntries item=entry}
							<tr class="{cycle name='className'} normalFont">				
								<td style="width:20%;">
			                  		{if $entry->category->catIcon}<img src="{icon}{$entry->category->catIcon}S.png{/icon}" alt="" />{else}<img src="{icon}rMarketplaceS.png{/icon}" alt="" />{/if} <a href="index.php?page=RMarketplace&amp;cat={$entry->category->catID}">{lang}{$entry->category->catName}{/lang}</a>
			                    </td>                        
								<td>
									<strong>{if $entry->type == 'search'}
										{lang}de.0xdefec.rmarketplace.search{/lang}:
									{else}
										{lang}de.0xdefec.rmarketplace.offer{/lang}:
									{/if}</strong>
										
									{if !$entry->isActive}
										<img src="{icon}successS.png{/icon}" alt="" style="vertical-align:-10%" />
									{/if}
									<a href="index.php?page=RMarketplaceEntry&amp;entryID={$entry->entryID}{@SID_ARG_2ND}">{$entry->subject|truncate:200:'...'}</a><p class="smallFont">{if $entry->userID}<a href="index.php?page=User&amp;userID={@$entry->userID}{@SID_ARG_2ND}" title="{lang username=$entry->username}wcf.user.viewProfile{/lang}"><span>{@$entry->username}</span></a>{else}<span>{@$entry->username}</span>{/if} <span class="light">({lang}de.0xdefec.rmarketplace.entry.date{/lang})</span></p>								
							
								</td>
							</tr>				
						{/foreach}
			            </tbody>
			     	</table>
				{/if}
			</div>
		        <script type="text/javascript">
		                //<![CDATA[
		                initList('rmarketplaceTopList', {@$rmarketplaceTopListStatus});
		                //]]>
		        </script>
	        </div>     
	</div>