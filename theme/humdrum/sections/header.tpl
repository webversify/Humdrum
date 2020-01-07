<div class="header">
    <div class="container">
        <nav role="navigation">
            <ul class="topbar-navs">
                {% for menuKey, menuItem in site.apps.pages.navbar.rows %}
                    {% if menuItem.page_slug.value == site.page %}
                        {% if menuItem.page_slug.value == 'home' %}
                <li class="inline-list"><a href="{{ site.url }}" class="active">{{ menuItem.page_name.value }}</a></li>
                        {% else %}
                <li class="inline-list"><a href="{{ site.url ~ menuItem.page_slug }}" class="active">{{ menuItem.page_name.value }}</a></li>
                        {% endif %}
                    {% else %}
                        {% if site.page is empty and menuItem.page_slug.value == 'home' %}
                <li class="inline-list"><a href="{{ site.url }}" class="active">{{ menuItem.page_name.value }}</a></li>
                        {% else %}
                            {% if menuItem.page_slug.value == 'home' %}
                <li class="inline-list"><a href="{{ site.url }}">{{ menuItem.page_name.value }}</a></li>
                            {% else %}
                <li class="inline-list"><a href="{{ site.url ~ menuItem.page_slug.value }}">{{ menuItem.page_name.value }}</a></li>
                            {% endif %}
                        {% endif %}
                    {% endif %}
                {% endfor %}
                {% if (site.users.enabled|default(false) == true) %}
                    {% if site.page == site.links.login.slug %}
                <li class="inline-list user-sign-in"><a href="{{ site.url ~ site.links.login.slug }}" class="active"><span class="sign-in-icon active"></span> {{ site.links.login.title }}</a></li>
                    {% else %}
                        {% if (site.users.logged|default(false) == true) %}
                <li class="inline-list user-sign-in"><a href="{{ site.url ~ site.links.admin.slug }}"><span class="sign-in-icon"></span> {{ site.links.admin.title }}</a></li>
                        {% else %}
                <li class="inline-list user-sign-in"><a href="{{ site.url ~ site.links.login.slug }}"><span class="sign-in-icon"></span> {{ site.links.login.title }}</a></li>
                        {% endif %}
                    {% endif %}
                {% endif %}
            </ul>
            <div class="mobile-nav">
                <span class="bar1"></span>
                <span class="bar2"></span>
                <span class="bar3"></span>
            </div>
        </nav>
    </div>
</div>
