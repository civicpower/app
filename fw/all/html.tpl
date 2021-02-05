{if $WRITE_HTML}
{if $HTML_DOCTYPE}{$HTML_DOCTYPE}{/if}
<html xmlns="http://www.w3.org/1999/xhtml" lang="fr">
<head>
{capture name="capture_title" assign="full_title"}{if $HTML_PREFIX_TITLE}{$HTML_PREFIX_TITLE|html_entity_decode}{if $HTML_TITLE} | {/if}{/if}{$HTML_TITLE|html_entity_decode}{/capture}
<title>{$full_title}</title>

<meta property="og:title" content="{$full_title}" />
{if $HTML_META_DESCRIPTION}
<meta name="description" content="{$HTML_META_DESCRIPTION|htmlentities}" />
<meta property="og:description" content="{$HTML_META_DESCRIPTION|htmlentities}" />
<meta name="twitter:description" content="{$HTML_META_DESCRIPTION|htmlentities}" />
{/if}

{if !is_null($HTML_META_CANONICAL)}
{capture assign=canonical_url_start}https://{$smarty.env.SITE_URL}/{/capture}
{capture name="capture_canon" assign="full_canonical"}{$canonical_url_start}{$canonical_url_start|str_replace:"":$HTML_META_CANONICAL|html_entity_decode}{/capture}
<link rel="canonical" href="{$full_canonical}" />
<meta property="og:url" content="{$full_canonical}" />
{else}
<meta property="og:url" content="http://{$smarty.env.SITE_URL}{$smarty.server.SCRIPT_URL}" />
{/if}

{if !is_null($HTML_META_PUBLISH_DATE)}
<meta property="article:published_time" content="{$HTML_META_PUBLISH_DATE}" />
{/if}

{if !is_null($HTML_META_FB_APPID)}
<meta property="fb:app_id" content="{$HTML_META_FB_APPID}"/>
{/if}

{if !is_null($HTML_META_OG_IMAGE)}
{capture assign=og_image_url_start}{*http://{$smarty.env.SITE_URL}*}{/capture}
<meta property="og:image" content="{$og_image_url_start}{$og_image_url_start|str_replace:"":$HTML_META_OG_IMAGE|html_entity_decode}" />
<meta property="twitter:image" content="{$og_image_url_start}{$og_image_url_start|str_replace:"":$HTML_META_OG_IMAGE|html_entity_decode}"/>
{/if}

{if $HTML_META_KEYWORDS}
<meta name="keywords" content="{$HTML_META_KEYWORDS|html_entity_decode}" />
{/if}
{if $HTML_META_AUTHOR}
<meta name="author" content="{$HTML_META_AUTHOR|html_entity_decode}" />
{/if}
{*<meta name="generator" content="Smarty {$smarty.version}" />*}

{foreach from=$HTML_TAB_CSS item=item key=key}
{if $item|substr:0:9 eq '<include>'}
<style type="text/css">
{'<include>'|str_replace:'':$item|project_file_get_contents}
</style>
{else}
<link rel="stylesheet" type="text/css" href="{$item}" />
{/if}
{/foreach}

{$HTML_ENTETE}
{if $HTML_ONLOAD|strlen>0}
<script type="text/javascript">
function my_onload(){ldelim}
{$HTML_ONLOAD}
{rdelim}
</script>
{/if}
</head>
<body class="body_{$BROWSER|strtolower} page_{$smarty.server.SCRIPT_FILENAME|basename|rewriting:'_'|strtolower} {$HTML_BODY_CLASS}" {if $HTML_ONLOAD|strlen>0}onload="my_onload()"{/if}>
<!--body-->
{if $HTML_HEADER}{eval var=$HTML_HEADER}{/if}
{/if}
{if $HTML_BODY}{eval var=$HTML_BODY}{/if}
{if $WRITE_HTML}
{if $HTML_FOOTER}{eval var=$HTML_FOOTER}{/if}

{foreach from=$HTML_TAB_JS item=item key=key}
{if $item|substr:0:9 eq '<include>'}
<script type="text/javascript">
{'<include>'|str_replace:'':$item|project_file_get_contents}
</script>
{else}
<script type="text/javascript" src="{$item}"></script>
{/if}
{/foreach}
<!--/body-->
</body>
</html>{/if}