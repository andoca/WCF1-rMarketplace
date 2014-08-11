{include file="documentHeader"}
<head>
	<title>{lang}de.0xdefec.rmarketplace.title{/lang} - {lang}{PAGE_TITLE}{/lang}</title>
	
	{include file='headInclude' sandbox=false}
	{include file='rmarketplaceGlobalHeader' sandbox=false}
	
	
	<script type="text/javascript">
		{capture assign=widgetCode}
<!-- Marketplace-Widget BEGIN -->
<div style="background-color:#fff; color:#777; width:450px; border:1px solid #cccccc;">
	<div style="margin:10px">
		<h1 style="font-size: 1.2em;">{lang}{PAGE_TITLE}{/lang} {lang}de.0xdefec.rmarketplace.title{/lang}</h1>
		<p>{lang}de.0xdefec.rmarketplace.external.useSearch{/lang}</p>
  		<form action="{MP_EXTERNAL_ABS_PATH}index.php?form=Search" method="post">
			<input type="text" name="q" value="PLZ oder Stichwort eingeben" onfocus="if (this.value=='{lang}de.0xdefec.rmarketplace.external.searchText{/lang}') this.value=''" onblur="if (this.value=='') this.value='{lang}de.0xdefec.rmarketplace.external.searchText{/lang}'" />
			<input type="hidden" name="types[]" value="mpentry" />
			<input type="submit" value="suchen" />
		</form>
		<div id="rmInclude">
			<script type="text/javascript">
				var rootUrl = '{MP_EXTERNAL_ABS_PATH}';
				var userID = '###userID###';
				var categories = '###categories###';
				var items = '###items###';
				var type = '###type###';
				
				document.write('<script type="text/javascript" src="'+ rootUrl +'index.php?form=RMarketplaceExternal&amp;userID='+ userID +'&amp;cat='+ categories +'&amp;type='+ type +'&amp;items='+ items +'"></scr' + 'ipt>');
			</script>
		</div>	
		<div style="text-align:right; margin-top:10px;">&raquo; <a href="{MP_EXTERNAL_ABS_PATH}index.php?form=RMarketplaceAdd">{lang}de.0xdefec.rmarketplace.external.enterOwn{/lang}</a> &raquo; <a href="{MP_EXTERNAL_ABS_PATH}index.php?page=RMarketplace">{lang}de.0xdefec.rmarketplace.external.viewAll{/lang}</a></div>
	</div>
</div>
<!-- Marketplace-Widget END -->
		{/capture}
	
	
		function updateWidgetCode() {
			var widgetCode = '{@$widgetCode|encodeJS}';
			
			if(document.getElementById('userID') != undefined) {
				if(document.getElementById('userID').checked) widgetCode = widgetCode.replace('###userID###', document.getElementById('userID').value);
				else widgetCode = widgetCode.replace('###userID###', '');
			}
			else widgetCode = widgetCode.replace('###userID###', '');widgetCode = widgetCode.replace('###items###', document.getElementById('items').value);
			
			if(document.getElementById('typeSearch').checked) widgetCode = widgetCode.replace('###type###', 'search');
			else if(document.getElementById('typeOffer').checked) widgetCode = widgetCode.replace('###type###', 'offer');
			else widgetCode = widgetCode.replace('###type###', '');
			
			catSelected = new Array();
			for (var i = 0; i < document.getElementById('cat').options.length; i++)
				if (document.getElementById('cat').options[i].selected)
					catSelected.push(document.getElementById('cat').options[i].value);
			
			widgetCode = widgetCode.replace('###categories###', catSelected.join(','));
			
			
			document.getElementById('widgetCode').value = widgetCode;
		}
	</script>
</head>
<body{if $templateName|isset} id="tpl{$templateName|ucfirst}"{/if}>
{include file='rmarketplaceSearchControls' sandbox=false}
{include file='header' sandbox=false}

<div id="main">
	
		<ul class="breadCrumbs">	
			<li><a href="index.php?page=RMarketplace{@SID_ARG_2ND}"><img src="{icon}rMarketplaceS.png{/icon}" alt="" /> <span>{lang}de.0xdefec.rmarketplace.title{/lang}</span></a> &raquo;</li>
		</ul>
	
		<div class="mainHeadline">
			<img src="{icon}rMarketplaceL.png{/icon}" alt="" />
			<div class="headlineContainer">
				<h2>
					{lang}de.0xdefec.rmarketplace.external{/lang}
				</h2>
				<p></p>
			</div>
		</div>

	
	{if $userMessages|isset}{@$userMessages}{/if}
	
	<div class="border content">
		<div class="container-1">
			<div class="contentBox">
				<h3 class="subHeadline">{lang}de.0xdefec.rmarketplace.external.includeHeadline{/lang}</h3>
				<p>{lang}de.0xdefec.rmarketplace.external.includeDesc{/lang}</p>
			</div>
			<div class="contentBox">
				<h3 class="subHeadline">{lang}de.0xdefec.rmarketplace.external.widget{/lang}</h3>
				<p>{lang}de.0xdefec.rmarketplace.external.widgetDesc{/lang}</p>
			</div>

			<div class="contentBox">
				<textarea id="widgetCode" rows="20" readonly="readonly"></textarea>
			</div>

			<div class="formElement">
				<div class="formFieldLabel">
					<label for="items">{lang}de.0xdefec.rmarketplace.external.items{/lang}</label>
				</div>
				<div class="formField">
					<input class="inputText" type="text" id="items" value="5" />
				</div>
				<div class="formFieldDesc">
					{lang}de.0xdefec.rmarketplace.external.itemsDesc{/lang}
				</div>
			</div>

			<div class="formElement">
				<div class="formFieldLabel">
					<label for="cat">{lang}de.0xdefec.rmarketplace.external.categories{/lang}</label>
				</div>
				<div class="formField">
					 <select name="cat" id="cat" size="7" multiple="multiple">
						{htmloptions options=$categoryTree disableEncoding=true}
					</select>
				</div>
				<div class="formFieldDesc">
					{lang}de.0xdefec.rmarketplace.external.categoriesDesc{/lang}
				</div>
			</div>
			
			{if $this->user->userID}
				<div class="formElement">
					<div class="formFieldLabel">
						<label for="userID">{lang}de.0xdefec.rmarketplace.external.userID{/lang}</label>
					</div>
					<div class="formField">
						 <input type="checkbox" value="{$this->user->userID}" id="userID" />
					</div>
					<div class="formFieldDesc">
						{lang}de.0xdefec.rmarketplace.external.userIDDesc{/lang}
					</div>
				</div>
			{/if}
			
			<div class="formElement">
				<div class="formFieldLabel">
					<label for="username">{lang}de.0xdefec.rmarketplace.external.filter{/lang}</label>
				</div>
				<div class="formField">
					 <input type="radio" name="type" value="" id="typeNone" checked="checked" /> {lang}de.0xdefec.rmarketplace.external.filter.both{/lang}<br />
					 <input type="radio" name="type" value="search" id="typeSearch" /> {lang}de.0xdefec.rmarketplace.filter.search{/lang}<br />
					 <input type="radio" name="type" value="offer" id="typeOffer" /> {lang}de.0xdefec.rmarketplace.filter.offer{/lang}<br />
				</div>
				<div class="formFieldDesc">
				</div>
			</div>
		
			<div class="formSubmit">
				<input type="button" name="send" accesskey="s" value="{lang}de.0xdefec.rmarketplace.external.refreshWidget{/lang}" onclick="updateWidgetCode(); 
					alert('{lang}de.0xdefec.rmarketplace.external.widgetRefreshed{/lang}');" />
			</div>
			
			
			<script type="text/javascript">
				updateWidgetCode();
			</script>
		</div>
	</div>
{lang}de.0xdefec.rmarketplace.copyright{/lang}
</div>

{include file='footer' sandbox=false}
</body>
</html>