<!DOCTYPE html>
<html lang="it">
<head>
	<title>BEdita | {$title_for_layout} | {$this->Html->action}</title>
	{include file="inc/meta.tpl"}

	{assign_associative var="cssOptions" media=all}
	{$this->Html->css("print", null, $cssOptions)}
	
	{assign_concat var="cssfile" 1=$smarty.const.APP 2="webroot" 3=$smarty.const.DS 4="css" 5=$smarty.const.DS 6=$printLayout 7=".css"}
	{if file_exists($cssfile)}
		{$this->Html->css($printLayout, null, $cssOptions)}
	{/if}

	{$scripts_for_layout}
</head>

<body>

{$content_for_layout}

</body>

</html>