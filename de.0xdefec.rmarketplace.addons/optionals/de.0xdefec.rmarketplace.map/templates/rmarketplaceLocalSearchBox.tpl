{if !$radius|isset}
	{assign var="radius" value=1}
{/if}
{* 
	remove to stars (there are two of them) from the next 
	"assign" line and set value to your prefered location 
	to be used as default for the local search input box.
	Otherwise the users location based on his ip will be
	used.
*}
{*assign var="address" value="New York, USA"*}
						<li class="{cycle}">
							<div class="containerIcon">
								<img src="{icon}searchM.png{/icon}" alt="" title="{lang}de.0xdefec.rmarketplace.map.localSearch.title{/lang}" />
							</div>
							<div class="containerContent">
								<form method="post" action="index.php?form=RMarketplaceLocalSearch">
									<h4 class="smallFont">{lang}de.0xdefec.rmarketplace.map.localSearch.title{/lang}</h4>
									<p><input class="inputText" id="rMSearchBox" name="address" type="text" value="{if $address|isset}{$address}{else}{lang}de.0xdefec.rmarketplace.map.localSearch.address{/lang}{/if}" onfocus="if (this.value == '{lang}de.0xdefec.rmarketplace.map.localSearch.address{/lang}') this.value='';" onblur="if(this.value=='')this.value='{lang}de.0xdefec.rmarketplace.map.localSearch.address{/lang}'" style="width: 50%" />
										<select name="radius" size="1">
											<option {if $radius == 1}selected="selected"{/if} value="1">1 km</option>
											<option {if $radius == 2}selected="selected"{/if} value="2">2 km</option>
											<option {if $radius == 5}selected="selected"{/if} value="5">5 km</option>
											<option {if $radius == 15}selected="selected"{/if} value="15">15 km</option>
											<option {if $radius == 25}selected="selected"{/if} value="25">25 km</option>
											<option {if $radius == 50}selected="selected"{/if} value="50">50 km</option>
											<option {if $radius == 100}selected="selected"{/if} value="100">100 km</option>
											<option {if $radius == 300}selected="selected"{/if} value="300">300 km</option>
										</select>{@SID_INPUT_TAG}
										<input type="image" class="inputImage" src="{icon}submitS.png{/icon}" alt="{lang}wcf.global.button.submit{/lang}" />
									</p>
								</form>
								<script type="text/javascript">
								/* <![CDATA[ */
									if (google.loader.ClientLocation && google.loader.ClientLocation.address.country && google.loader.ClientLocation.address.city) {
										var city =  google.loader.ClientLocation.address.city; 
										var country =  google.loader.ClientLocation.address.country; 
										
										$('rMSearchBox').value = city + ', ' + country;
										
										google.language.detect(city, function(result) { 
											if (!result.error && result.language) { 
												google.language.translate(city, result.language, '{LANGUAGE_CODE}', function(result) {
													if (result.translation) { 
														city = result.translation;
														google.language.detect(country, function(result) { 
															if (!result.error && result.language) { 
															google.language.translate(country, result.language, '{LANGUAGE_CODE}', function(result) {
																if (result.translation) { 
																	country = result.translation;
																	$('rMSearchBox').value = city + ', ' + country;
																	} 
																}); 
															} 
														});
													} 
												}); 
											} 
										}); 
									}
								/* ]]> */
								</script>
							</div>
						</li>
