{$javascript->link("jquery/jquery.changealert", false)}

</head>

<body>
	
{include file="../common_inc/modulesmenu.tpl"}

{include file="inc/menuleft.tpl" method="categories"}

<div class="head">
	
	<h1>{t}Categories{/t}</h1>

</div>

{include file="inc/menucommands.tpl" method="categories"}


<div class="mainfull">
	
{include file="../common_inc/list_categories.tpl" method="categories"}

</div>

