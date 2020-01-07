<div class="app-button">
    {% if site.page == site.backend_pages.list.slug %}
    <button type="button" onclick="window.location='{{ site.url ~ site.links.admin.slug ~ '/' ~ site.app ~ '/' ~ site.backend_pages.new.slug }}'" id="{{ site.page ~ 'button' }}" name="{{ site.page ~ 'button' }}" class="{{ site.page ~ '-button' }}">New</button>    
    {% endif %}
    {% if site.page == site.backend_pages.edit.slug %}
    <button type="submit" id="{{ site.page ~ 'button' }}" name="{{ site.page ~ 'button' }}" class="{{ site.page ~ '-button' }}">Update</button>
    <button type="button" onclick="window.location='{{ site.url ~ site.links.admin.slug ~ '/' ~ site.app ~ '/' ~ site.backend_pages.delete.slug ~ '/' ~ site.uuid }}'" id="{{ site.page ~ 'button' }}" name="{{ site.page ~ 'button' }}" class="{{ site.page ~ '-button' }}">Delete</button>
    <button type="button" onclick="window.location='{{ site.url ~ site.links.admin.slug ~ '/' ~ site.previous_page.app ~ '/' ~ site.previous_page.page }}'" id="{{ site.page ~ 'button' }}" name="{{ site.page ~ 'button' }}" class="{{ site.page ~ '-button' }}">Back</button>
    {% endif %}
    {% if site.page == site.backend_pages.new.slug %}
    <button type="submit" id="{{ site.page ~ 'button' }}" name="{{ site.page ~ 'button' }}" class="{{ site.page ~ '-button' }}">Create</button>
    <button type="button" onclick="window.location='{{ site.url ~ site.links.admin.slug ~ '/' ~ site.previous_page.app ~ '/' ~ site.previous_page.page }}'" id="{{ site.page ~ 'button' }}" name="{{ site.page ~ 'button' }}" class="{{ site.page ~ '-button' }}">Back</button>
    {% endif %}
</div>
