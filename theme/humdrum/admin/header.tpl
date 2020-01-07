<div class="header">
    <div class="app-logo">
        <a href="{{ site.url ~ site.links.admin.slug }}"><img src="{{ site.url ~ 'theme/' ~ site.skin ~ '/images/logo-title.png' }}" /></a>
    </div>
    <div class="app-navbar">
        <ul>
            <li>
                <div class="mobile-nav">
                    <span class="bar1"></span>
                    <span class="bar2"></span>
                    <span class="bar3"></span>
                </div>
            </li>
            <li>
                <a href="{{ site.url ~ site.links.admin.slug ~ '/' ~ site.links.account.slug }}">
                    <i class="fa fa-user fa-lg"></i>
                    <span class="app-user">{{ site.users.info.data.firstname }}</span>
                </a>
            </li>
            <li><a href="{{ site.url }}" target="_blank"><i class="fa fa-home fa-lg"></i></a></li>
            <li><a href="{{ site.url ~ site.links.admin.slug ~ '/' ~ site.links.logout.slug }}"><i class="fa fa-sign-out-alt fa-lg"></i></a></li>
        </ul>
    </div>
</div>
