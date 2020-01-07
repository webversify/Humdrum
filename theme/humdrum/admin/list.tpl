{% if site.apps[site.app].rows %}
<div class="content-wrapper">
    <ul class="heading-list">
    {% for headerKey, headerItem in site.apps[site.app].headers %}
        {% if headerItem %}
            {% if (headerItem[site.page]|default(false) == true) %}
        <li class="app-columns inline-list list-{{ headerItem.type }} app-headings">
            <span class="title">{{ headerItem.label }}</span>
        </li>
            {% endif %}
        {% endif %}
    {% endfor %}
        <li class="app-columns inline-list list-options">&nbsp;</li>
        {% if (site.apps[site.app].sortable|default(false) == true) %}
        <li class="app-columns inline-list list-positioning">&nbsp;</li>
        {% endif %}
    </ul>
    <ul>
    {% for rowKey, rowItem in site.apps[site.app].rows %}
        {% if rowItem %}
        <li class="app-rows">
            <ul class="inline-items">
            {% for rowItemKey, rowItemVal in rowItem %}
                {% if (rowItemVal.schema.key|default(false) == true) %}
                    {% set rowkey = rowItemVal.value %}
                {% endif %}
                {% if (rowItemVal.schema[site.page]|default(false) == true) %}
                <li class="app-columns inline-list list-{{ rowItemVal.schema.type|trim }}">
                    <span class="app-title-responsive">{{ rowItemVal.schema.label }}</span>
                    {% include ['forms/' ~ rowItemVal.schema.type ~ '.tpl', 'forms/text.tpl'] %}
                    {% if (rowItemVal.schema.childbtn|default(false) == true) %}
                    <a href="{{ site.url ~ site.links.admin.slug ~ '/' ~ site.app ~ '/' ~ site.backend_pages.new.slug ~ '/' ~ rowkey }}" title="Create Sub-{{ site.app_titles.singular|capitalize }}"><span class="option-child-button"><i class="fas fa-sitemap fa-lg"></i></span></a>
                    {% endif %}
                </li>
                {% endif %}
            {% endfor %}
                <li class="app-columns inline-list list-options">
                    {% for rowItemKey, rowItemVal in rowItem %}
                        {% if (rowItemVal.schema.key|default(false) == true) %}
                    <a href="{{ site.url ~ site.links.admin.slug ~ '/' ~ site.app ~ '/' ~ site.backend_pages.edit.slug ~ '/' ~ rowItemVal.value }}" title="Edit {{ site.app_titles.singular|capitalize }}">
                        <i class="fas fa-edit fa-lg option-edit"></i>
                    </a>
                    <a href="{{ site.url ~ site.links.admin.slug ~ '/' ~ site.app ~ '/' ~ site.backend_pages.delete.slug ~ '/' ~ rowItemVal.value  }}" title="Delete {{ site.app_titles.singular|capitalize }}">
                        <i class="fas fa-trash fa-lg option-delete"></i>
                    </a>
                        {% endif %}
                    {% endfor %}
                </li>
                {% if (site.apps[site.app].sortable|default(false) == true) %}
                <li class="app-columns inline-list list-positioning" title="Drag To Reorder {{ site.app_titles.plural|capitalize }}">
                    <i class="fas fa-ellipsis-v fa-lg option-position"></i>
                    <i class="fas fa-ellipsis-v fa-lg option-position"></i>
                </li>
                {% endif %}
            </ul>
        </li>
        {% endif %}
    {% endfor %}
    </ul>
</div>
{% endif %}
