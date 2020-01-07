{% if site.page == site.backend_pages.edit.slug %}
    {% if rowItemVal.value or rowItem.value %}
        <span class="app-form-item"><i class="fas fa-toggle-on fa-2x success"></i></span>
    {% else %}
        <span class="app-form-item"><i class="fas fa-toggle-off fa-2x empty"></i></span>
    {% endif %}
{% elseif site.page == site.backend_pages.new.slug %}
    <span class="app-form-item"><i class="fas fa-toggle-off fa-2x empty"></i></span>
{% else %}
    {% if rowItemVal.value or rowItem.value %}
        <span class="app-form-item"><i class="fas fa-toggle-on fa-lg success"></i></span>
    {% else %}
        <span class="app-form-item"><i class="fas fa-toggle-off fa-lg empty"></i></span>
    {% endif %}
{% endif %}
