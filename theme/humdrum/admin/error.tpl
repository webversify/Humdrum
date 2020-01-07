{% if site.error|default(false) == true %}
<div class="app-error">
    <i class="fas fa-exclamation-triangle fa-6x error"></i>
    <h2>{{ site.error_message }}</h2>
</div>
{% endif %}
