{include file="rmarketplaceExternalResponse" assign="response" sandbox="false"}

var response = '{@$response|encodeJS}';
document.write(response);