{include file='header'}
<div class="mainHeadline">
	<img src="{@RELATIVE_WCF_DIR}icon/bbcodeEditL.png" alt="" />
	<div class="headlineContainer">
		<h2>{lang}wcf.acp.rmarketplace.category.edit{/lang}</h2>
	</div>
</div>

{if $errorField}
	<p class="error">{lang}wcf.global.form.error{/lang}</p>
{/if}

{if $success|isset}
	<p class="success">{lang}wcf.acp.option.success{/lang}</p>	
{/if}

<div class="contentHeader">
	<div class="largeButtons">
		<ul><li><a href="index.php?page=RMarketplaceCategoryList&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/managementM.png" alt="" title="{lang}wcf.acp.rmarketplace.categories.list{/lang}" /> <span>{lang}wcf.acp.rmarketplace.categories.list{/lang}</span></a></li></ul>
	</div>
</div>

	<div class="border content">
		<div class="container-1">
		<form method="post" action="index.php?form=RMarketplaceCategoryEdit">
			<fieldset>
				<legend>{lang}wcf.acp.rmarketplace.category{/lang}</legend>
				<div class="formElement{if $errorField == 'catName'} formError{/if}" id="nameDiv">
					<div class="formFieldLabel">
						<label for="catName">{lang}wcf.acp.rmarketplace.category.catName{/lang}</label>
					</div>
					<div class="formField">
						<input type="text" class="inputText" name="catName" id="catName" value="{$category->catName}" />
						{if $errorField == 'catName'}
							<p class="innerError">
								{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
							</p>
						{/if}
					</div>
					<div class="formFieldDesc hidden" id="catNameHelpMessage">
						{lang}wcf.acp.rmarketplace.category.catName.description{/lang}
					</div>
					<script type="text/javascript">//<![CDATA[
						inlineHelp.register('catName');
					//]]></script>
				</div>
				
				<div class="formElement{if $errorField == 'catOrder'} formError{/if}" id="hrefDiv">
					<div class="formFieldLabel">
						<label for="catOrder">{lang}wcf.acp.rmarketplace.category.catOrder{/lang}</label>
					</div>
					<div class="formField">
						<input type="text" class="inputText" name="catOrder" id="catOrder" value="{$category->catOrder}" />
						{if $errorField == 'catOrder'}
							<p class="innerError">
								{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
							</p>
						{/if}
					</div>
					<div class="formFieldDesc hidden" id="catOrderHelpMessage">
						{lang}wcf.acp.rmarketplace.category.catOrder.description{/lang}
					</div>
					<script type="text/javascript">//<![CDATA[
						inlineHelp.register('catOrder');
					//]]></script>
				</div>
				
				<div class="formElement{if $errorField == 'catParent'} formError{/if}" id="titleDiv">
					<div class="formFieldLabel">
						<label for="catParent">{lang}wcf.acp.rmarketplace.category.catParent{/lang}</label>
					</div>
					<div class="formField">
						<select size="1" name="catParent" id="catParent">
							<option value="0"></option>
							{htmlOptions options=$categories disableEncoding=true selected=$category->catParent}
						</select>
						
						{if $errorField == 'catParent'}
							<p class="innerError">
								{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
							</p>
						{/if}
					</div>
					<div class="formFieldDesc hidden" id="catParentHelpMessage">
						{lang}wcf.acp.rmarketplace.category.catParent.description{/lang}
					</div>
					<script type="text/javascript">//<![CDATA[
						inlineHelp.register('catParent');
					//]]></script>
				</div>
				
				<div class="formElement{if $errorField == 'catDescription'} formError{/if}" id="noteDiv">
					<div class="formFieldLabel">
						<label for="catDescription">{lang}wcf.acp.rmarketplace.category.catDescription{/lang}</label>
					</div>
					<div class="formField">
						<textarea name="catDescription" id="catDescription" cols="40" rows="10">{$category->catDescription}</textarea>
						{if $errorField == 'catDescription'}
							<p class="innerError">
								{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
							</p>
						{/if}
					</div>
					<div class="formFieldDesc hidden" id="catDescriptionHelpMessage">
						{lang}wcf.acp.rmarketplace.category.catDescription.description{/lang}
					</div>
					<script type="text/javascript">//<![CDATA[
						inlineHelp.register('catDescription');
					//]]></script>
				</div>
				
				<div class="formElement{if $errorField == 'catInfo'} formError{/if}" id="infoDiv">
					<div class="formFieldLabel">
						<label for="catDescription">{lang}wcf.acp.rmarketplace.category.catInfo{/lang}</label>
					</div>
					<div class="formField">
						<textarea name="catInfo" id="catInfo" cols="40" rows="10">{$category->catInfo}</textarea>
						{if $errorField == 'catInfo'}
							<p class="innerError">
								{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
							</p>
						{/if}
					</div>
					<div class="formFieldDesc hidden" id="catInfoHelpMessage">
						{lang}wcf.acp.rmarketplace.category.catInfo.description{/lang}
					</div>
					<script type="text/javascript">//<![CDATA[
						inlineHelp.register('catInfo');
					//]]></script>
				</div>
				
				<div class="formElement{if $errorField == 'catIcon'} formError{/if}" id="titleDiv">
					<div class="formFieldLabel">
						<label for="catIcon">{lang}wcf.acp.rmarketplace.category.catIcon{/lang}</label>
					</div>
					<div class="formField">
						<input type="text" class="inputText" name="catIcon" id="catIcon" value="{$category->catIcon}" />
						{if $errorField == 'catIcon'}
							<p class="innerError">
								{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
							</p>
						{/if}
					</div>
					<div class="formFieldDesc hidden" id="catIconHelpMessage">
						{lang}wcf.acp.rmarketplace.category.catIcon.description{/lang}
					</div>
					<script type="text/javascript">//<![CDATA[
						inlineHelp.register('catIcon');
					//]]></script>
				</div>
			</fieldset>

			<div class="formSubmit">
				<input type="hidden" name="catID" value="{$category->catID}" />
				<input type="submit" accesskey="s" value="{lang}wcf.global.button.submit{/lang}" />
				<input type="reset" accesskey="r" value="{lang}wcf.global.button.reset{/lang}" />
				<input type="hidden" name="packageID" value="{@PACKAGE_ID}" />
		 		{@SID_INPUT_TAG}
		 	</div>
		</form>
	</div>
</div>

{include file='footer'}