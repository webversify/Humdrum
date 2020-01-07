<div class="paging-wrapper">
    <ul>
        {% if site.apps[site.app].paging.previous %}
            <a href="{{ site.url ~ site.links.admin.slug ~ '/' ~ site.app|lower|replace({ ' ' : '-' }) ~ '/' ~ site.backend_pages.list.slug ~ '/1' }}"><li><i class="fas fa-angle-double-left fa-lg"></i></li></a>
            <a href="{{ site.url ~ site.links.admin.slug ~ '/' ~ site.app|lower|replace({ ' ' : '-' }) ~ '/' ~ site.backend_pages.list.slug ~ '/' ~ site.apps[site.app].paging.previous }}"><li><i class="fas fa-angle-left fa-lg"></i></li></a>
        {% else %}
            <li class="inactive"><i class="fas fa-angle-double-left fa-lg"></i></li>
            <li class="inactive"><i class="fas fa-angle-left fa-lg"></i></li>
        {% endif %}
        {% for pagingKey, pagingVal in site.apps[site.app].paging.setup %}
            {%  if (pagingVal.active) %}
            <li class="{{ pagingVal.active ? 'active' }}">{{ pagingVal.page }}</li>
            {% else %}
            <a href="{{ site.url ~ site.links.admin.slug ~ '/' ~ site.app|lower|replace({ ' ' : '-' }) ~ '/' ~ site.backend_pages.list.slug ~ '/' ~ pagingVal.page }}"><li class="{{ pagingVal.active ? 'active' }}">{{ pagingVal.page }}</li></a>
            {% endif %}
        {% endfor %}
        {% if site.apps[site.app].paging.next %}
            <a href="{{ site.url ~ site.links.admin.slug ~ '/' ~ site.app|lower|replace({ ' ' : '-' }) ~ '/' ~ site.backend_pages.list.slug ~ '/' ~ site.apps[site.app].paging.next }}"><li><i class="fas fa-angle-right fa-lg"></i></li></a>
            <a href="{{ site.url ~ site.links.admin.slug ~ '/' ~ site.app|lower|replace({ ' ' : '-' }) ~ '/' ~ site.backend_pages.list.slug ~ '/' ~ site.apps[site.app].paging.max }}"><li><i class="fas fa-angle-double-right fa-lg"></i></li></a>
        {% else %}
            <li class="inactive"><i class="fas fa-angle-right fa-lg"></i></li>
            <li class="inactive"><i class="fas fa-angle-double-right fa-lg"></i></li>
        {% endif %}
    </ul>
</div>
