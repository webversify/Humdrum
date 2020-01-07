{% if site.page == site.backend_pages.edit.slug %}
    <span class="app-form-item"><input type="text" id="{{ rowItem.schema.form }}" name="{{ rowItem.schema.form }}" value="{{ rowItem.value }}" /></span>
    {% elseif site.page == site.backend_pages.new.slug %}
        <span class="app-form-item"><input type="text" id="{{ rowItem.form }}" name="{{ rowItem.form }}" value="" /></span>    
{% else %}
    <span class="app-form-item"><i class="{{ rowItemVal.value }} fa-lg"></i></span>
{% endif %}
