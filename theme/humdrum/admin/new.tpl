<div class="content-wrapper">
    <ul>
    {% for rowKey, rowItem in site.apps[site.app].schema %}
        {% if rowItem %}
        <li class="app-rows app-edit-item">
            <div class="app-item">
                <span class="title">{{ rowItem.label }}</span>
                <span class="data">{% include ['forms/' ~ rowItem.type ~ '.tpl', 'forms/text.tpl'] %}</span>
            </div>
        </li>
        {% endif %}
    {% endfor %}
    </ul>
    <input type="hidden" id="app" name="app" value="{{ site.users.class }}" />
    <input type="hidden" id="exe" name="exe" value="{{ site.links.login.slug }}" />
    <input type="hidden" id="csrftoken" name="csrftoken" value="{{ site.csrf }}" />
</div>
