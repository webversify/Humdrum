<div class="sidebar">
    <ul>
        {% for key, app in site.apps.applications.sidebar.rows %}
            <a href="{{ site.url ~ site.links.admin.slug ~ '/' ~ app.app_name.value|lower|replace({ ' ' : '-' }) ~ '/' ~ site.backend_pages.list.slug ~ '/1'  }}">
                <li {{ (site.app == app.app_name.value|lower|replace({ ' ' : '-' })) ? 'class="active"' }}><span class="app-icon"><i class="{{ app.app_icon.value }}"></i></span> {{ app.app_name.value }}</li>
            </a>
        {% endfor %}
    </ul>
</div>
