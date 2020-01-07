<div class="content">
    {% if site.error|default(false) == false %}
    <h2>
        <i class="fas fa-angle-double-right fa-lg"></i><span class="app-site-page">{{ site.page and site.breadcrumbs ? site.page|capitalize : site.title|capitalize ~ ' Dashboard' }}</span>
        {% for bcrumb in site.breadcrumbs %}
            {% if ((loop.last) and (site.page == site.backend_pages.list.slug)) %}
                {% if site.apps[site.app].paging.count > 1 %}
        <i class="fas fa-angle-double-right fa-lg"></i><span class="app-records">{{ site.apps[site.app].paging.count ~ ' ' ~ site.app_titles.plural|capitalize ~ ' Found ' }}</span>
                {% else %}
        <i class="fas fa-angle-double-right fa-lg"></i><span class="app-records">{{ site.apps[site.app].paging.count ~ ' ' ~ site.app_titles.singular|capitalize ~ ' Found ' }}</span>
                {% endif %}
            {% elseif ((loop.last) and (site.page == site.backend_pages.new.slug)) %}
        <i class="fas fa-angle-double-right fa-lg"></i><span class="app-records">{{ site.apps[site.app].paging.count ~ ' ' ~ site.app_titles.singular|capitalize }}</span>
            {% else %}
        <i class="fas fa-angle-double-right fa-lg"></i><span class="app-names">{{ bcrumb|capitalize }}</span>
            {% endif %}
        {% endfor %}
        {% if site.uuid %}
        <span class="uuid-tag"><i class="fas fa-angle-double-right fa-lg"></i><span class="app-records">{{ site.uuid}}</span></span>
        {% endif %}
    </h2>
    {% endif %}
    {% if site.error|default(false) == false %}
    <form id="{{ site.page ~ 'Form' }}" name="{{ site.page ~ 'Form' }}" method="post">
        {% if site.apps[site.app].paging %}
            {% include 'admin/paging.tpl' %}
        {% endif %}
        {% include 'admin/buttons.tpl' %}
        {% include ['admin/' ~ site.page ~ '.tpl', 'admin/dashboard.tpl'] %}
        {% include 'admin/buttons.tpl' %}
        {% if site.apps[site.app].paging %}
            {% include 'admin/paging.tpl' %}
        {% endif %}        
    </form>
    {% else %}
        {% include ['admin/' ~ site.page ~ '.tpl', 'admin/dashboard.tpl'] %}
    {% endif %}
</div>
