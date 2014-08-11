{include file='setupWindowHeader'}

<form method="post" action="index.php?page=Package">
	<fieldset>
		<legend>{lang}wcf.acp.package.0xdefec.license.title{/lang}</legend>
		<div class="inner">	
			<p>{lang}wcf.acp.package.0xdefec.license.description{/lang}</p>
			
			{if $errorField}
				<p class="error">
					{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
					{if $errorType == 'notValid'}{lang}wcf.global.error.notValid{/lang}{/if}
				</p>
			{/if}
			{if $errorType == 'ServerError'}
			<p class="error">
				{lang}wcf.acp.package.0xdefec.license.servererror{/lang}
			</p>
			{/if}
			
			<div{if $errorField} class="errorField"{/if}>
				<label for="licenseKey">{lang}wcf.acp.package.0xdefec.license.key{/lang}</label>
				<input type="text" class="inputText" id="licenseKey" name="licenseKey" value="{$licenseKey}" />
			</div>
			
			<input type="hidden" name="queueID" value="{@$queueID}" />
			<input type="hidden" name="action" value="{@$action}" />
			{@SID_INPUT_TAG}
			<input type="hidden" name="step" value="{@$step}" />
			<input type="hidden" name="packageID" value="{@PACKAGE_ID}" />
			<input type="hidden" name="send" value="send" />
		</div>
	</fieldset>
	
	<div class="nextButton">
		<input type="submit" value="{lang}wcf.global.button.next{/lang}" />
	</div>
</form>

<script type="text/javascript">
	//<![CDATA[
	window.onload = function() {
		changeHeight();	
	};
	parent.showWindow(true);
	parent.setCurrentStep('{lang}wcf.acp.package.step.title{/lang}{lang}wcf.acp.package.step.{if $action == 'rollback'}uninstall{else}{@$action}{/if}.{@$step}{/lang}');
	//]]>
</script>

{include file='setupWindowFooter'}