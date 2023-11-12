{foreach from=$articles item=$article}
<div>
    <div>{$article.titre}</div>
    <div>{$article.description}</div>
</div>
<br>
{/foreach}