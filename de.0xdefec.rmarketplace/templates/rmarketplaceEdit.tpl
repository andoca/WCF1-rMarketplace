{include file="documentHeader"}
<head>
	<title>{lang}de.0xdefec.rmarketplace.edit{/lang} - {lang}{PAGE_TITLE}{/lang}</title>

	{include file='headInclude' sandbox=false}
	
	<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/TabbedPane.class.js"></script>
	<script type="text/javascript">
		//<![CDATA[
		var INLINE_IMAGE_MAX_WIDTH = {@INLINE_IMAGE_MAX_WIDTH}; 
		//]]>
	</script>
	<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/ImageResizer.class.js"></script>
	{if $canUseBBCodes}{include file="wysiwyg"}{/if}
</head>
<body{if $templateName|isset} id="tpl{$templateName|ucfirst}"{/if}>
{include file='rmarketplaceSearchControls' sandbox=false}
{capture assign=rMBreadCrumbs}
	<div class="breadcrumb2"><a href="index.php?page=RMarketplace{@SID_ARG_2ND}"><span>{lang}de.0xdefec.rmarketplace.title{/lang}</span></a></div>
	{foreach from=$entry->category->getParents() item=parent}
		<div class="breadcrumb3"><a href="index.php?page=RMarketplace&amp;cat={$parent->catID}{@SID_ARG_2ND}"> <span>{$parent->catName}</span></a></div>
	{/foreach}
	<div class="breadcrumb4"><a href="index.php?page=RMarketplace&amp;cat={$entry->category->catID}{@SID_ARG_2ND}"> <span>{$entry->category->catName}</span></a></div>
{/capture}
{include file='header' sandbox=false}

<div id="main">
		<ul class="breadCrumbs">
			<li><a href="index.php?page=RMarketplace{@SID_ARG_2ND}"><img src="{icon}rMarketplaceS.png{/icon}" alt="" /> <span>{lang}de.0xdefec.rmarketplace.title{/lang}</span></a> &raquo;</li>
			{foreach from=$entry->category->getParents() item=parent}
				<li><a href="index.php?page=RMarketplace&amp;cat={$parent->catID}{@SID_ARG_2ND}"><img src="{icon}rMarketplaceS.png{/icon}" alt="" /> <span>{lang}{$parent->catName}{/lang}</span></a> &raquo;</li>
			{/foreach}
			<li><a href="index.php?page=RMarketplace&amp;cat={$entry->category->catID}{@SID_ARG_2ND}"><img src="{icon}rMarketplaceS.png{/icon}" alt="" /> <span>{lang}{$entry->category->catName}{/lang}</span></a> &raquo;</li>
		</ul>
	
	<div class="mainHeadline">
		<img src="{icon}messageEditL.png{/icon}" alt="" />
		<div class="headlineContainer">
			<h2>{lang}de.0xdefec.rmarketplace.edit{/lang}</h2>
		</div>
	</div>
	
	{if $userMessages|isset}{@$userMessages}{/if}
	
	{if $errorField}
		<p class="error">{lang}wcf.global.form.error{/lang}</p>
	{/if}
	
	{if $preview|isset}
		<div class="border messagePreview">
			<div class="containerHead">
				<h3>{lang}wcf.message.preview{/lang}</h3>
			</div>
			<div class="message content">
				<div class="messageInner container-1">
					{if $subject}
						<h4>{$subject}</h4>
					{/if}
					<div class="messageBody">
						<div>{@$preview}</div>
					</div>
				</div>
			</div>
		</div>
	{/if}
	
	<form enctype="multipart/form-data" method="post" action="index.php?form=RMarketplaceEdit&amp;entryID={$entryID}">
		<div class="border content">
			<div class="container-1">
								
				<fieldset>
					<legend>{lang}de.0xdefec.rmarketplace.entry.data{/lang}</legend>
					
					<div class="formElement{if $errorField == 'subject'} formError{/if}">
						<div class="formFieldLabel">
								<label for="subject">{lang}de.0xdefec.rmarketplace.entry.subject{/lang}</label>
						</div>
						<div class="formField">
							<input type="text" class="inputText" name="subject" id="subject" value="{$subject}" tabindex="{counter name='tabindex'}" />
							{if $errorField == 'subject'}
								<p class="innerError">
									{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
								</p>
							{/if}
						</div>
					</div>
					
					{*if MODULE_TAGGING && RM_ENABLE_TAGS}{include file='tagAddBit'}{/if*}
					{if 'MODULE_TAGGING'|defined && MODULE_TAGGING}{include file='tagAddBit'}{/if}
					
					<div class="formElement{if $errorField == 'type'} formError{/if}">
						<div class="formFieldLabel">
								{lang}de.0xdefec.rmarketplace.entry.type{/lang}
						</div>
						<div class="formField">
							<label><input type="radio" name="type" value="search" id="typeSearch" {if $type == 'search'}checked="checked"{/if} tabindex="{counter name='tabindex'}" /> {lang}de.0xdefec.rmarketplace.entry.type.searching{/lang}</label>
							<label><input type="radio" name="type" value="offer" id="typeOffer" {if $type == 'offer'}checked="checked"{/if} tabindex="{counter name='tabindex'}" /> {lang}de.0xdefec.rmarketplace.entry.type.offering{/lang}</label>
						</div>
					</div>
					
					<div class="formElement{if $errorField == 'category'} formError{/if}">
						<div class="formFieldLabel">
								<label for="categoryID">{lang}de.0xdefec.rmarketplace.entry.category{/lang}</label>
						</div>
						<div class="formField">
								<select size="1" name="categoryID" id="categoryID" tabindex="{counter name='tabindex'}">
									{htmlOptions options=$categories disableEncoding=true selected=$categoryID}
								</select>
						</div>
					</div>
					
					{if RM_ENABLE_MAPS}
						<div class="formElement{if $errorField == 'country'} formError{/if}">
							<div class="formFieldLabel">
									<label for="country">{lang}de.0xdefec.rmarketplace.entry.country{/lang}</label>
							</div>
							<div class="formField">
									<select size="1" name="country" id="country" tabindex="{counter name='tabindex'}">
										{foreach from=$countries key=letter item=s_country}
											<option{if $country == $letter} selected="selected"{/if} value="{$letter}">{$s_country}</option>
										{/foreach}
									</select>
							</div>
						</div>
						
						<div class="formElement{if $errorField == 'zipcode'} formError{/if}">
							<div class="formFieldLabel">
									<label for="zipcode">{lang}de.0xdefec.rmarketplace.entry.zipcode{/lang}</label>
							</div>
							<div class="formField">
								<input type="text" class="inputText" name="zipcode" style="width: 5em" id="zipcode" value="{$zipcode}" tabindex="{counter name='tabindex'}" />
								{if $errorField == 'zipcode'}
									<p class="innerError">
										{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
										&nbsp;
									</p>
								{/if}
							</div>
						</div>
					{/if}
					
					<div class="formElement{if $errorField == 'price'} formError{/if}">
						<div class="formFieldLabel">
								<label for="price">{lang}de.0xdefec.rmarketplace.entry.price{/lang}</label>
						</div>
						<div class="formField">
							<input type="text" style="width: 5em;" class="inputText" name="price" id="price" value="{$price}" tabindex="{counter name='tabindex'}" />
							{if $errorField == 'price'}
								<p class="innerError">
									{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
									{if $errorType == 'notNumeric'}{lang}de.0xdefec.rmarketplace.entry.price.error.notNumeric{/lang}{/if}
								</p>
							{/if}
						</div>
					</div>
					
					{if $additionalInformationFields|isset}{@$additionalInformationFields}{/if}
				</fieldset>
				
				<fieldset>
					<legend>{lang}de.0xdefec.rmarketplace.entry.text{/lang}</legend>
					
					<div class="editorFrame formElement{if $errorField == 'text'} formError{/if}" id="textDiv">
	
						<div class="formFieldLabel">
							<label for="text">{lang}de.0xdefec.rmarketplace.entry.text{/lang}</label>
						</div>
						
						<div class="formField">				
							<textarea name="text" id="text" rows="15" cols="40" tabindex="{counter name='tabindex'}">{$text}</textarea>
							{if $errorField == 'text'}
								<p class="innerError">
									{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
									{if $errorType == 'tooLong'}{lang}wcf.message.error.tooLong{/lang}{/if}
									{if $errorType == 'censoredWordsFound'}{lang}wcf.message.error.censoredWordsFound{/lang}{/if}
									{if $errorType == 'tooShort'}{lang}de.0xdefec.rmarketplace.entry.text.error.tooShort{/lang}{/if}
								</p>
							{/if}
						</div>
						
					</div>
						
					{if $errorField == 'attachmentMissing'}
						<p class="error">
							{lang}de.0xdefec.rmarketplace.entry.attachmentMissing{/lang}
						</p>
					{/if}
					
					{include file='messageFormTabs'}
					
				</fieldset>

				<fieldset>
					<legend>{lang}de.0xdefec.rmarketplace.entry.isCommentable{/lang}</legend>
					<div class="formElement{if $errorField == 'isCommentable'} formError{/if}" id="isCommentableDiv">
	
						<div class="formFieldLabel">
							<label for="isCommentable">{lang}de.0xdefec.rmarketplace.entry.isCommentable{/lang}</label>
						</div>
						
						<div class="formField">				
							<label><input type="checkbox" name="isCommentable" value="1" id="isCommentable" {if $isCommentable}checked="checked"{/if} tabindex="{counter name='tabindex'}" /> </label>
						</div>
						<p class="formFieldDesc">{lang}de.0xdefec.rmarketplace.entry.isCommentable.description{/lang}</p>
					</div>
				</fieldset>
				
				{if $additionalFields|isset}{@$additionalFields}{/if}
			</div>
		</div>
		
		<div class="formSubmit">
			<input type="submit" name="send" accesskey="s" value="{lang}wcf.global.button.submit{/lang}" />
			<input type="submit" name="preview" accesskey="p" value="{lang}wcf.global.button.preview{/lang}" />
			<input type="reset" name="reset" accesskey="r" value="{lang}wcf.global.button.reset{/lang}" />
			{@SID_INPUT_TAG}
			<input type="hidden" name="idHash" value="{$idHash}" />
		</div>
	</form>

{lang}de.0xdefec.rmarketplace.copyright{/lang}
</div>

{include file='footer' sandbox=false}
</body>
</html>