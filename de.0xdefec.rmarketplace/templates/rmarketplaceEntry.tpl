{include file="documentHeader"}
<head>
	<title>{lang}de.0xdefec.rmarketplace.title{/lang} - {$entry->subject} - {lang}{PAGE_TITLE}{/lang}</title>

	{include file='headInclude' sandbox=false}
	
	<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/TabbedPane.class.js"></script>
	{include file='rmarketplaceGlobalHeader' sandbox=false}
	
	<script type="text/javascript">
		//<![CDATA[
		var INLINE_IMAGE_MAX_WIDTH = {@INLINE_IMAGE_MAX_WIDTH}; 
		//]]>
	</script>
	<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/ImageResizer.class.js"></script>
	<meta name="description" content="{$entry->getTextPreview()}" />
</head>
<body{if $templateName|isset} id="tpl{$templateName|ucfirst}"{/if}>
{include file='rmarketplaceSearchControls' sandbox=false}

{capture assign=rMBreadCrumbs}
	<div class="breadcrumb2"><a href="index.php?page=RMarketplace{@SID_ARG_2ND}"><span>{lang}de.0xdefec.rmarketplace.title{/lang}</span></a></div>
	{foreach from=$entry->category->getParents() item=parent}
		<div class="breadcrumb3"><a href="index.php?page=RMarketplace&amp;cat={$parent->catID}{@SID_ARG_2ND}"> <span>{lang}{$parent->catName}{/lang}</span></a></div>
	{/foreach}
	<div class="breadcrumb4"><a href="index.php?page=RMarketplace&amp;cat={$entry->category->catID}{@SID_ARG_2ND}"> <span>{lang}{$entry->category->catName}{/lang}</span></a></div>
{/capture}

{include file='header' sandbox=false}

<div id="main">
	<ul class="breadCrumbs">
		<li><a href="index.php?page=RMarketplace{@SID_ARG_2ND}"><img src="{icon}rMarketplaceS.png{/icon}" alt="" /> <span>{lang}de.0xdefec.rmarketplace.title{/lang}</span></a> &raquo;</li>
		{foreach from=$entry->category->getParents() item=parent}
			<li><a href="index.php?page=RMarketplace&amp;cat={$parent->catID}{@SID_ARG_2ND}">{if $parent->catIcon}<img src="{icon}{$parent->catIcon}S.png{/icon}" alt="" />{else}<img src="{icon}rMarketplaceS.png{/icon}" alt="" />{/if} <span>{lang}{$parent->catName}{/lang}</span></a> &raquo;</li>
		{/foreach}
		<li><a href="index.php?page=RMarketplace&amp;cat={$entry->category->catID}{@SID_ARG_2ND}">{if $entry->category->catIcon}<img src="{icon}{$entry->category->catIcon}S.png{/icon}" alt="" />{else}<img src="{icon}rMarketplaceS.png{/icon}" alt="" />{/if} <span>{lang}{$entry->category->catName}{/lang}</span></a> &raquo;</li>
	</ul>
	
	<div class="mainHeadline">
		<img src="{icon}messageL.png{/icon}" alt="" />
		<div class="headlineContainer">
			<h2>{$entry->subject}</h2>
		</div>
	</div>
	
	{if $userMessages|isset}{@$userMessages}{/if}
	
	{if $entry->isDisabled}
		<p class="info">{lang}de.0xdefec.rmarketplace.entry.isDisabled{/lang}</p>
	{/if}
	{if !$entry->isActive}
		<p class="success">{lang}de.0xdefec.rmarketplace.entry.isNotActive{/lang}</p>
	{/if}
	
	{assign var="sidebar" value=$sidebarFactory->get('mpentry', $entry->entryID)}
	{assign var="author" value=$sidebar->getUser()}
	{assign var="messageID" value=$entry->entryID}

	<div class="tabMenu">
		<ul>
			<li class="activeTabMenu">
				<a href="index.php?page=RMarketplace{@SID_ARG_2ND}"><img src="{icon}rMarketplaceM.png{/icon}" alt="" /> <span>{lang}de.0xdefec.rmarketplace.title{/lang}</span></a>
			</li>
			{if 'MP_GMAP_LARGE_ENABLED'|defined && MP_GMAP_LARGE_ENABLED && RM_ENABLE_MAPS}
				<li>
					<a href="index.php?page=RMarketplaceMap{@SID_ARG_2ND}"><img src="{icon}rMmapM.png{/icon}" alt="" /> <span>{lang}de.0xdefec.rmarketplace.map.title{/lang}</span></a>
				</li>
			{/if}
			<li>
				<a href="index.php?page=RMarketplace&amp;type=search{@SID_ARG_2ND}"><img src="{icon}rMsearchM.png{/icon}" alt="" /> <span>{lang}de.0xdefec.rmarketplace.search{/lang}</span></a>
			</li>
			<li>
				<a href="index.php?page=RMarketplace&amp;type=offer{@SID_ARG_2ND}"><img src="{icon}rMofferM.png{/icon}" alt="" /> <span>{lang}de.0xdefec.rmarketplace.offer{/lang}</span></a>
			</li>
		</ul>
	</div>
	
	<div class="subTabMenu">
		<div class="containerHead"></div>
	</div>
	
	<div class="border rmarketplace">
			<div class="layout-2">
				<div class="columnContainer">
					<div class="container-1 column first">
						<div class="columnInner">
							<div class="contentBox">
							
								<p class="messageCount">
									<a href="index.php?page=rMarketplaceEntry&amp;entryID={$entry->entryID}{SID_ARG_2ND}" class="messageNumber">{#$entry->entryID}</a>
								</p>
							
								<h3 class="subHeadline">{if $entry->type == 'search'}{lang}de.0xdefec.rmarketplace.search{/lang}{else}{lang}de.0xdefec.rmarketplace.offer{/lang}{/if}: {$entry->subject}</h3>
								
								<div class="contentHeader">
									<p class="light firstPost">{lang}de.0xdefec.rmarketplace.entry.category{/lang}: {lang}{$entry->category->catName}{/lang}</p>
								</div>

								{if $entry->isOld()}
									<p class="warning">{lang}de.0xdefec.rmarketplace.entry.isOld{/lang}</p>
								{/if}

								<div>
									<div class="messageBody">
										<div id="entryText{@$entry->entryID}">
											{@$entry->getFormattedMessage()}
										</div>
									</div>
									
									{include file='attachmentsShow' messageID=$entry->entryID author=$entry->getUser()}
										
									{if MODULE_USER_SIGNATURE == 1 && $entry->getSignature()}
										<div class="signature">
											{@$entry->getSignature()}
										</div>
									{/if}
										
									{if $entry->editCount > 0}
										<p class="editNote smallFont light">{lang}de.0xdefec.rmarketplace.entry.editNote{/lang}</p>
									{/if}
								</div>
								
								<div class="buttonBar">
									<div class="smallButtons">
										<ul>
											<li class="extraButton"><a href="#top" title="{lang}wcf.global.scrollUp{/lang}"><img src="{icon}upS.png{/icon}" alt="{lang}wcf.global.scrollUp{/lang}" /> <span class="hidden">{lang}wcf.global.scrollUp{/lang}</span></a></li>
											
											{if 'MODULE_USER_INFRACTION'|defined && MODULE_USER_INFRACTION == 1 && $this->user->getPermission('admin.user.infraction.canWarnUser')}
												<li><a href="index.php?form=UserWarn&amp;userID={@$entry->userID}&amp;objectType=mpEntry&amp;objectID={@$entry->entryID}{@SID_ARG_2ND}" title="{lang}wcf.user.infraction.button.warn{/lang}"><img src="{icon}infractionWarningS.png{/icon}" alt="" /> <span>{lang}wcf.user.infraction.button.warn{/lang}</span></a></li>
											{/if}
											{if $entry->userCanDelete()}
												<li><a href="index.php?action=RMarketplace&amp;t={@SECURITY_TOKEN}&amp;methode=delete&amp;entryID={$entry->entryID}{@SID_ARG_2ND}" onclick="if(!confirm('{lang}de.0xdefec.rmarketplace.askDelete{/lang}')) return false;" title="{lang}de.0xdefec.rmarketplace.delete{/lang}"><img src="{icon}deleteS.png{/icon}" alt="" /> <span>{lang}de.0xdefec.rmarketplace.delete{/lang}</span></a></li>
											{/if}
											{if $entry->userCanEdit()}
												<li><a href="index.php?form=RMarketplaceEdit&amp;entryID={$entry->entryID}{@SID_ARG_2ND}"  title="{lang}de.0xdefec.rmarketplace.edit{/lang}"><img src="{icon}editS.png{/icon}" alt="" /> <span>{lang}de.0xdefec.rmarketplace.edit{/lang}</span></a></li>
											{/if}
											{if $entry->userCanDeactivate() && $entry->isActive}
												<li><a href="index.php?action=RMarketplace&amp;t={@SECURITY_TOKEN}&amp;methode=deactivate&amp;entryID={$entry->entryID}{@SID_ARG_2ND}" title="{lang}de.0xdefec.rmarketplace.deactivate{/lang}"><img src="{icon}checkS.png{/icon}" alt="" /> <span>{lang}de.0xdefec.rmarketplace.deactivate{/lang}</span></a></li>
											{/if}
											{if $entry->userCanDeactivate() && !$entry->isActive}
												<li><a href="index.php?action=RMarketplace&amp;t={@SECURITY_TOKEN}&amp;methode=activate&amp;entryID={$entry->entryID}{@SID_ARG_2ND}" title="{lang}de.0xdefec.rmarketplace.activate{/lang}"><img src="{icon}submitS.png{/icon}" alt="" /> <span>{lang}de.0xdefec.rmarketplace.activate{/lang}</span></a></li>
											{/if}
											{if $entry->userCanPush()}
												<li><a href="index.php?action=RMarketplace&amp;t={@SECURITY_TOKEN}&amp;methode=push&amp;entryID={$entry->entryID}{@SID_ARG_2ND}" title="{lang}de.0xdefec.rmarketplace.push{/lang}"><img src="{icon}pushS.png{/icon}" alt="" /> <span>{lang}de.0xdefec.rmarketplace.push{/lang} ({if ($this->user->getPermission('user.rmarketplace.maxPushCount')-$entry->pushCount) > 0 && !$this->user->getPermission('mod.rmarketplace.canModerate')}{#($this->user->getPermission('user.rmarketplace.maxPushCount')-$entry->pushCount)}{else if $this->user->getPermission('mod.rmarketplace.canModerate')}&infin;{/if})</span></a></li>
											{/if}
											{if $entry->userCanDisable() && $entry->isDisabled}
												<li><a href="index.php?action=RMarketplace&amp;t={@SECURITY_TOKEN}&amp;methode=enable&amp;entryID={$entry->entryID}{@SID_ARG_2ND}" title="{lang}de.0xdefec.rmarketplace.enable{/lang}"><img src="{icon}enabledS.png{/icon}" alt="" /> <span>{lang}de.0xdefec.rmarketplace.enable{/lang}</span></a></li>
											{/if}
											{if $entry->userCanDisable() && !$entry->isDisabled}
												<li><a href="index.php?action=RMarketplace&amp;t={@SECURITY_TOKEN}&amp;methode=disable&amp;entryID={$entry->entryID}{@SID_ARG_2ND}" title="{lang}de.0xdefec.rmarketplace.disable{/lang}"><img src="{icon}disabledS.png{/icon}" alt="" /> <span>{lang}de.0xdefec.rmarketplace.disable{/lang}</span></a></li>
											{/if}
										</ul>
									</div>
								</div>

								{if $comments|count > 0}
									{if $entry->isCommentable()}{assign var=commentUsername value=$username}{/if}
										<div class="messageContentInner contentBox">
											<h3 class="subHeadline">{lang}de.0xdefec.rmarketplace.entry.comments{/lang} <span>({#$items})</span></h3>
										
											<div class="contentHeader">
												{capture assign="entryID"}{$entry->entryID}{/capture}
												{pages print=true assign=pagesOutput link="index.php?page=rMarketplaceEntry&entryID=$entryID&pageNo=%d#comments"|concat:SID_ARG_2ND_NOT_ENCODED}
											</div>
											
											<ul class="dataList messages">
												{assign var='messageNumber' value=$items-$startIndex+1}
												{foreach from=$comments item=commentObj}
													<li class="{cycle values='container-1,container-2'}">
														<a id="comment{@$commentObj->commentID}"></a>
														<div class="containerIcon">
															{if $commentObj->getUser()->getAvatar()}
																{assign var=x value=$commentObj->getUser()->getAvatar()->setMaxSize(24, 24)}
																{if $commentObj->userID}<a href="index.php?page=User&amp;userID={@$commentObj->userID}{@SID_ARG_2ND}" title="{lang username=$commentObj->username}wcf.user.viewProfile{/lang}">{/if}{@$commentObj->getUser()->getAvatar()}{if $commentObj->userID}</a>{/if}
															{else}
																{if $commentObj->userID}<a href="index.php?page=User&amp;userID={@$commentObj->userID}{@SID_ARG_2ND}" title="{lang username=$commentObj->username}wcf.user.viewProfile{/lang}">{/if}<img src="{@RELATIVE_WCF_DIR}images/avatars/avatar-default.png" alt="" style="width: 24px; height: 24px" />{if $commentObj->userID}</a>{/if}
															{/if}
														</div>
														<div class="containerContent">
															{if $action == 'edit' && $commentID == $commentObj->commentID}
																<form method="post" action="index.php?page=RMarketplaceEntry&amp;entryID={@$entry->entryID}&amp;commentID={@$commentObj->commentID}&amp;action=edit">
																	<div{if $errorField == 'comment'} class="formError"{/if}>
																		<textarea name="comment" id="comment" rows="10" cols="40">{$comment}</textarea>
																		{if $errorField == 'comment'}
																			<p class="innerError">
																				{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
																				{if $errorType == 'tooLong'}{lang}de.0xdefec.rmarketplace.entry.comment.error.tooLong{/lang}{/if}
																			</p>
																		{/if}
																	</div>
																	<div class="formSubmit">
																		{@SID_INPUT_TAG}
																		<input type="submit" accesskey="s" value="{lang}wcf.global.button.submit{/lang}" />
																		<input type="reset" accesskey="r" value="{lang}wcf.global.button.reset{/lang}" />
																	</div>
																</form>
															{else}
																<div class="buttons">
																	{if $commentObj->isEditable()}<a href="index.php?page=RMarketplaceEntry&amp;entryID={@$entry->entryID}&amp;commentID={@$commentObj->commentID}&amp;action=edit{@SID_ARG_2ND}#comment{@$commentObj->commentID}" title="{lang}de.0xdefec.rmarketplace.entry.comment.edit{/lang}"><img src="{icon}editS.png{/icon}" alt="" /></a>{/if}
																	{if $commentObj->isDeletable()}<a href="index.php?action=RMarketplaceEntryCommentDelete&amp;commentID={@$commentObj->commentID}&amp;t={@SECURITY_TOKEN}{@SID_ARG_2ND}" onclick="return confirm('{lang}de.0xdefec.rmarketplace.entry.comment.delete.sure{/lang}')" title="{lang}de.0xdefec.rmarketplace.entry.comment.delete{/lang}"><img src="{icon}deleteS.png{/icon}" alt="" /></a>{/if}
																	<a href="index.php?page=RMarketplaceEntry&amp;entryID={@$entry->entryID}&amp;commentID={@$commentObj->commentID}{@SID_ARG_2ND}#comment{@$commentObj->commentID}" title="{lang}de.0xdefec.rmarketplace.entry.comment.permalink{/lang}">#{#$messageNumber}</a>
																</div>
																<p class="firstPost smallFont light">{lang}de.0xdefec.rmarketplace.entry.comment.by{/lang} {if $commentObj->userID}<a href="index.php?page=User&amp;userID={@$commentObj->userID}{@SID_ARG_2ND}">{$commentObj->username}</a>{else}{$commentObj->username}{/if} ({@$commentObj->time|time})</p>
																<p>{@$commentObj->getFormattedComment()}</p>
																
															{/if}
														</div>
													</li>
													{assign var='messageNumber' value=$messageNumber-1}
												{/foreach}
											</ul>
								
											<div class="contentFooter">
												{@$pagesOutput}
											</div>
											
											<div class="buttonBar">
												<div class="smallButtons">
													<ul>
														<li class="extraButton"><a href="#top" title="{lang}wcf.global.scrollUp{/lang}"><img src="{icon}upS.png{/icon}" alt="{lang}wcf.global.scrollUp{/lang}" /> <span class="hidden">{lang}wcf.global.scrollUp{/lang}</span></a></li>
													</ul>
												</div>
											</div>
											
										</div>
									{if $entry->isCommentable()}{assign var=username value=$commentUsername}{/if}
								{/if}
								
								{if $entry->isCommentable() && $action != 'edit'}
									<div class="contentBox">
										<form method="post" action="index.php?page=RMarketplaceEntry&amp;entryID={@$entry->entryID}&amp;action=add">
											<fieldset>
												<legend>{lang}de.0xdefec.rmarketplace.entry.comment.add{/lang}</legend>
												
												{if !$this->user->userID}
													<div class="formElement{if $errorField == 'username'} formError{/if}">
														<div class="formFieldLabel">
															<label for="username">{lang}wcf.user.username{/lang}</label>
														</div>
														<div class="formField">
															<input type="text" class="inputText" name="username" id="username" value="{$username}" />
															{if $errorField == 'username'}
																<p class="innerError">
																	{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
																	{if $errorType == 'notValid'}{lang}wcf.user.error.username.notValid{/lang}{/if}
																	{if $errorType == 'notAvailable'}{lang}wcf.user.error.username.notUnique{/lang}{/if}
																</p>
															{/if}
														</div>
													</div>
												{/if}
												
												<div class="formElement{if $errorField == 'comment' && $action == 'add'} formError{/if}">
													<div class="formFieldLabel">
														<label for="comment">{lang}de.0xdefec.rmarketplace.entry.comment{/lang}</label>
													</div>
													<div class="formField">
														<textarea name="comment" id="comment" rows="10" cols="40">{$comment}</textarea>
														{if $errorField == 'comment' && $action == 'add'}
															<p class="innerError">
																{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
																{if $errorType == 'tooLong'}{lang}de.0xdefec.rmarketplace.entry.comment.error.tooLong{/lang}{/if}
															</p>
														{/if}
													</div>
												</div>
												
												{include file='captcha' enableFieldset=false}
											</fieldset>
											
											<div class="formSubmit">
												{@SID_INPUT_TAG}
												<input type="submit" accesskey="s" value="{lang}wcf.global.button.submit{/lang}" />
												<input type="reset" accesskey="r" value="{lang}wcf.global.button.reset{/lang}" />
											</div>
										</form>
									</div>
								{/if}
								
							</div>
						</div>
					</div>
					
					{cycle values='container-1,container-2' print=false advance=false}
					<div class="container-3 column second sidebar mpSidebar">
						<div class="columnInner">
							
							<div class="contentBox mpBoxPrice">
								<div class="border">
									<div class="containerHead">
										<h3>{lang}de.0xdefec.rmarketplace.entry.meta{/lang}</h3>
									</div>
									<ul class="dataList">
										
										{if $entry->price}
											<li class="{cycle}">
												<div class="containerIcon"><img src="{icon}rmPriceM.png{/icon}" alt="" /></div>
												<div class="containerContent">
													<h4 class="smallFont">{lang}de.0xdefec.rmarketplace.entry.price{/lang}</h4>
													<p>{$entry->price}</p>
												</div>
											</li>
										{/if}
										{*if $entry->insertTime != $entry->time}{lang}de.0xdefec.rmarketplace.entry.lastPushed{/lang}{else}{@$entry->time|time}{/if}{if MP_ENTRY_OLD_TIME}; {lang}de.0xdefec.rmarketplace.entry.validTo{/lang} {@($entry->insertTime+MP_ENTRY_OLD_TIME*24*60*60)|time}{/if*}
										<li class="{cycle}">
											<div class="containerIcon"><img src="{icon}postNewM.png{/icon}" alt="" /></div>
											<div class="containerContent">
												<h4 class="smallFont">{lang}de.0xdefec.rmarketplace.entry.created{/lang}</h4>
												<p>{@$entry->insertTime|time}</p>
											</div>
										</li>
										
										{if $entry->insertTime != $entry->time}
											<li class="{cycle}">
												<div class="containerIcon"><img src="{icon}updateM.png{/icon}" alt="" /></div>
												<div class="containerContent">
													<h4 class="smallFont">{lang}de.0xdefec.rmarketplace.entry.pushed{/lang}</h4>
													<p>{@$entry->time|time}</p>
												</div>
											</li>
										{/if}
										
										<li class="{cycle}">
											<div class="containerIcon"><img src="{icon}checkM.png{/icon}" alt="" /></div>
											<div class="containerContent">
												<h4 class="smallFont">{lang}de.0xdefec.rmarketplace.entry.validTo{/lang}</h4>
												<p>{@($entry->insertTime+MP_ENTRY_OLD_TIME*24*60*60)|time}</p>
											</div>
										</li>

										<li class="{cycle}">
											<div class="containerIcon">
												<img src="{icon}visitsM.png{/icon}" alt="" /> 
											</div>
											<div class="containerContent">
												<h4 class="smallFont">{lang}de.0xdefec.rmarketplace.entry.clicks{/lang}</h4>
												<p>{$entry->clicks}</p>
											</div>
										</li>


										{if $this->user->getPermission('admin.general.canViewIpAddress')}
											<li class="{cycle}">
												<div class="containerIcon">
													<img src="{icon}ipAddressM.png{/icon}" alt="" /> 
												</div>
												<div class="containerContent">
													<h4 class="smallFont">{lang}wcf.usersOnline.ipAddress{/lang}</h4>
													<p>{$entry->ipAddress}</p>
												</div>
											</li>
										{/if}
									</ul>
								</div>
							</div>
										
							{if $tags|count}
								<div class="contentBox mpBoxTags">
									<div class="border">
										<div class="containerHead">
											<h3>{lang}wcf.tagging.tags.used{/lang}</h3>
										</div>
										{if $tags|count}
											<div class="{cycle}">
												<div class="containerIcon"><img src="{icon}tagM.png{/icon}" alt="" /></div>
												<div class="tagList">
													{implode from=$tags item=tag}<a href="index.php?page=TaggedObjects&amp;tagID={@$tag->getID()}{@SID_ARG_2ND}">{$tag->getName()}</a>{/implode}
												</div>
											</div>
										{/if}
									</div>
								</div>
							{/if}
							
							{if RM_ENABLE_MAPS && $entry->isGeolocated()}
								<div class="contentBox">
									<div class="border">
										<div id="mpEntryMap" class="{cycle}" ></div>
										<ul class="dataList">
											<li class="{cycle}">
												<div class="containerIcon">
													<img src="{icon}mapM.png{/icon}" alt="" /> 
												</div>
												<div class="containerContent">
													<h4 class="smallFont">{lang}de.0xdefec.rmarketplace.entry.location{/lang}</h4>
													<p>{$entry->zipcode} {lang}{$entry->getCountryName()}{/lang}</p>
												</div>
											</li>
										</ul>
									</div>
								</div>
								<script type="text/javascript">	
									/* <![CDATA[ */
									var lat = {$entry->lat};
									var lng = {$entry->lng};
									
									var mapOptions = {
										center: new google.maps.LatLng(lat, lng),
										zoom: {MP_GMAP_START_ZOOM},
							//			mapTypeId: google.maps.MapTypeId.{MP_GMAP_TYPE},
										mapTypeId: google.maps.MapTypeId.ROADMAP,
										mapTypeControlOptions: {
											mapTypeIds: [google.maps.MapTypeId.ROADMAP, google.maps.MapTypeId.HYBRID, google.maps.MapTypeId.TERRAIN]
										},
										streetViewControl: false
									}
							    	
									var map = new google.maps.Map(document.getElementById("mpEntryMap"), mapOptions);
									
									var pos{$entry->entryID} = new google.maps.LatLng({$entry->lat}, {$entry->lng});
									
									if('{$entry->type}' == 'search') var icon = "{RELATIVE_WCF_DIR}icon/rm-search-marker.png";
									else if('{$entry->type}' == 'offer') var icon = "{RELATIVE_WCF_DIR}icon/rm-offer-marker.png";
									
									var marker{$entry->entryID} = new google.maps.Marker({
										position: pos{$entry->entryID},
										icon: icon,
										map: map
									});
									/* ]]> */
								</script>
							{/if}
							
							<div class="message {cycle}">
								<div class="{if !$sidebar->getUser()->userID} guestPost{/if}">
									{include file='messageSidebar'}
								</div>			
							</div>
						
							{if ($entry->getUser()->getUserOption('profile.rmarketplace.contactAllowGuest') || $this->user->userID) && $entry->getUser()->userID}
								<div class="contentBox mpBoxContact">
									<div class="border">
										<div class="containerHead">
											<h3>{lang}de.0xdefec.rmarketplace.contact{/lang}</h3>
										</div>
										<ul class="dataList">			
											{if MODULE_PM}
												<li class="{cycle}">
													<div class="containerIcon"><img src="{icon}pmM.png{/icon}" /></div>
													<div class="containerContent">
														<h4>
															<a href="index.php?form=RMarketplacePMNew&amp;userID={$entry->getUser()->userID}&amp;entryID={$entry->entryID}{@SID_ARG_2ND}">{lang}de.0xdefec.rmarketplace.contact.pm{/lang} </a>
														</h4>
													</div>
												</li>	
											{/if}
											{if $entry->getUser()->getUserOption('profile.rmarketplace.contactAllowEMail')}		
												<li class="{cycle}">
													<div class="containerIcon"><img src="{icon}emailM.png{/icon}" /></div>
													<div class="containerContent">
														<h4>
															{if $entry->getUser()->getUserOption('hideEmailAddress')}
																<a href="index.php?form=RMarketplaceMail&amp;userID={$entry->getUser()->userID}&amp;entryID={$entry->entryID}{@SID_ARG_2ND}">{lang}de.0xdefec.rmarketplace.contact.email{/lang} </a>
															{else}
																{mailto address=$entry->getUser()->email encode='javascript'}
															{/if}
														</h4>
													</div>
												</li>
											{/if}	
											{if $entry->getUser()->getUserOption('profile.rmarketplace.contactPhone')}		
												<li class="{cycle}">
													<div class="containerIcon"><img src="{icon}phoneM.png{/icon}" /></div>
													<div class="containerContent">
														<h4>
															{$entry->getUser()->getUserOption('profile.rmarketplace.contactPhone')}
														</h4>
													</div>
												</li>
											{/if}
											{if $entry->getUser()->getUserOption('profile.rmarketplace.contactPostal')}		
												<li class="{cycle}">
													<div class="containerIcon"><img src="{icon}homeM.png{/icon}" /></div>
													<div class="containerContent">
														<h4>
															{@$entry->getUser()->getUserOption('profile.rmarketplace.contactPostal')|htmlspecialchars|nl2br} 
														</h4>
													</div>
												</li>
											{/if}
											{if $entry->getUser()->getUserOption('profile.rmarketplace.contactOther')}		
												<li class="{cycle}">
													<div class="containerIcon"><img src="{icon}infoM.png{/icon}" /></div>
													<div class="containerContent">
														<h4>
															{@$entry->getUser()->getUserOption('profile.rmarketplace.contactOther')|htmlspecialchars|nl2br}
														</h4>
													</div>
												</li>
											{/if}
										</ul>
									</div>
								</div>
							{/if}
							
							<div class="contentBox">
								<div class="border">
									<div class="containerHead">
										<h3>{lang}de.0xdefec.rmarketplace.spread{/lang}</h3>
									</div>
									
									<ul class="dataList">
										<li class="container-1">
											<div class="containerIcon">
												<img src="{icon}wysiwyg/linkInsertM.png{/icon}" alt="" onclick="document.getElementById('mpEntryLink').select()" />
											</div>
											<div class="containerContent">
												<h4 class="smallFont" onclick="document.getElementById('mpEntryLink').select()">{lang}de.0xdefec.rmarketplace.spread.link{/lang}</h4>
												<p><input type="text" class="inputText" id="mpEntryLink" readonly="readonly" onclick="this.select()" value="{PAGE_URL}/index.php?page=RMarketplaceEntry&amp;entryID={$entry->entryID}" /></p>
											</div>
										</li>
										<li class="container-2">
											<div class="containerIcon">
												<img src="{icon}messageM.png{/icon}" alt="" onclick="document.getElementById('mpEntryEmbed').select()" />
											</div>
											<div class="containerContent">
												<h4 class="smallFont" onclick="document.getElementById('mpEntryEmbed').select()">{lang}de.0xdefec.rmarketplace.spread.embed{/lang}</h4>
												<p><input type="text" class="inputText" id="mpEntryEmbed" readonly="readonly" onclick="this.select()" value="[mp]{$entry->entryID}[/mp]" /></p>
											</div>
										</li>
									</ul>	
								</div>
							</div>
							
						</div>
					</div>
				</div>
			</div>
		</div>
	
{lang}de.0xdefec.rmarketplace.copyright{/lang}
</div>

{include file='footer' sandbox=false}
</body>
</html>