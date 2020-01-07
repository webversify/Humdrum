{% spaceless %}
<!DOCTYPE html>
<html>
    <head>
        <meta charset="{{ site.encoding }}">
        <title>{{ site.title }}</title>
        <meta name="description" content="{{ site.description }}" />
        <meta name="keywords" content="" />
        <meta name="author" content="" />
        <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1" />
        <meta name="csrf" content="{{ site.csrf }}" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge;chrome=1" />
        <link href="{{ site.url ~ 'theme/' ~ site.skin ~ '/images/favicon.png' }}" rel="shortcut icon" />
        <link href="{{ site.url ~ 'theme/' ~ site.skin ~ '/assets/fontawesome/css/all.min.css' }}" rel="stylesheet" media="screen" />
        {% if site.users.info is not empty  %}
            <link href="{{ site.url ~ 'theme/' ~ site.skin ~ '/assets/css/style.admin.min.css' }}" rel="stylesheet" media="screen" />
        {% else %}
            <link href="{{ site.url ~ 'theme/' ~ site.skin ~ '/assets/css/style.min.css' }}" rel="stylesheet" media="screen" />
        {% endif %}
    </head>
    <body>
        <section id="header">
        {% if site.users.info is not empty  %}
            {% include('admin/header.tpl') ignore missing %}
        {% else %}
            {% include('sections/header.tpl') ignore missing %}
        {% endif %}
        </section>
        <section id="content">
        {% if site.users.info  is not empty %}
            {% include('admin/' ~ site.content) ignore missing %}
        {% else %}
            {% include('sections/' ~ site.content) ignore missing %}
        {% endif %}
        </section>
        <section id="sidebar">
        {% if site.users.info is not empty %}
            {% include('admin/sidebar.tpl') ignore missing %}
        {% else %}
            {% include('sections/sidebar.tpl') ignore missing %}
        {% endif %}
        </section>
        <section id="footer">
        {% if site.users.info is not empty %}
            {% include('admin/footer.tpl') ignore missing %}
        {% else %}
            {% include('sections/footer.tpl') ignore missing %}
        {% endif %}
        </section>
        {% if site.users.info is not empty  %}
        <script src="{{ site.url ~ 'theme/' ~ site.skin ~ '/assets/js/script.admin.min.js' }}"></script>
        {% else %}
        <script src="{{ site.url ~ 'theme/' ~ site.skin ~ '/assets/js/script.min.js' }}"></script>
        {% endif %}
    </body>
</html>
{% endspaceless %}
