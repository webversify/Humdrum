{% if site.page == site.backend_pages.edit.slug %}
    <span class="app-form-item"><select id="{{ rowItem.schema.form }}" name="{{ rowItem.schema.form }}">
        <option>-- Select {{ rowItem.schema.label }} --</option>
    <select></span>
{% elseif site.page == site.backend_pages.new.slug %}
    <span class="app-form-item"><select id="{{ rowItem.form }}" name="{{ rowItem.form }}">
        <option>-- Select {{ rowItem.label }} --</option>
    <select></span>
{% else %}
    <span class="app-form-item">{{ rowItemVal.value }}</span>
{% endif %}
