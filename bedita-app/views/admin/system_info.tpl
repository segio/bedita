{$html->css('module.superadmin')}
{$javascript->link("jquery/jquery.treeview", false)}
{$javascript->link("jquery/interface", false)}
{$javascript->link("form", false)}
{$javascript->link("jquery/jquery.changealert", false)}

{literal}
<script type="text/javascript">
	$(document).ready(function(){
		var openAtStart ="#system_info";
		$(openAtStart).prev(".tab").BEtabstoggle();
	});
</script>
{/literal}

</head>

<body>

{include file="../common_inc/modulesmenu.tpl"}

{include file="inc/menuleft.tpl" method="systemInfo"}

{include file="inc/menucommands.tpl" method="systemInfo" fixed=true}

<div class="mainfull">
	
	{include file="inc/form_info.tpl" method="systemInfo"}

</div>