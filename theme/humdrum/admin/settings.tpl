
<ul>    
{% for rowKey, rowItem in apps[site.page]['rows'] %}
    <li>
        <div class="app-rows">
        {% if rowItem %}
            <span class="title">{{ rowItem.setting_name.value }}</span>
            <span class="data">{% include ['forms/' ~ rowItem.setting_name.schema.type ~ '.tpl', 'forms/text.tpl'] %}</span>
        {% endif %}
        </div>
    </li>
{% endfor %}
</ul>
