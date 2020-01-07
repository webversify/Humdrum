{% if site.page == site.backend_pages.edit.slug %}
    <span class="app-form-item">{{ rowItem.value }}</span>
{% else %}
    <span class="app-form-item">{{ rowItemVal.value }}</span>
{% endif %}
