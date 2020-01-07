{% if site.page == site.backend_pages.edit.slug %}
    <span><textarea type="text" id="{{ rowItem.schema.form }}" name="{{ rowItem.schema.form }}">{{ rowItem.value }}</textarea></span>
{% elseif site.page == site.backend_pages.new.slug %}
    <span><textarea type="text" id="{{ rowItem.form }}" name="{{ rowItem.form }}"></textarea></span>
{% else %}
    <span>{{ rowItemVal.value }}</span>
{% endif %}
