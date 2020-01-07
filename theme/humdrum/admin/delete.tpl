{% set displayItems = '' %}
{% for rowKey, rowItem in site.apps[site.app].rows %}
    {% if (rowItem.schema.delete|default(false) == true) %}
        {% set displayItems = displayItems ~ ' ' ~ rowItem.value  %}
    {% endif %}
{% endfor %}
<div class="content-wrapper">
    <ul>
        <li class="app-delete center-block-horizontal">
            <i class="fas fa-trash-alt fa-4x"></i>
            <h2>{{ site.response_codes[2]|replace({ '[%NAME%]': displayItems, '[%APP%]' : site.app_titles.plural }) }}</h2>
            <div class="delete-buttons">
                <button type="submit" id="{{ site.page ~ 'button' }}" name="{{ site.page ~ 'button' }}" class="{{ site.page ~ '-button' }}">Delete</button>
                <button type="button" onclick="window.location='{{ site.url ~ site.links.admin.slug ~ '/' ~ site.previous_page.app ~ '/' ~ site.previous_page.page }}'" id="{{ site.page ~ 'button' }}" name="{{ site.page ~ 'button' }}" class="{{ site.page ~ '-button' }}">Cancel</button>
            </div>
        </li>
    </ul>
</div>
