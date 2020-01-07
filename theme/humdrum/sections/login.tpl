<div class="container">
    <div class="app-form">
        <form id="loginform" name="loginform" method="post">
        <div class="app-logo-small">
            <img src="{{ site.url ~ 'theme/' ~ site.skin ~ '/images/logo-title.png' }}" />
        </div>
        {% if site.response.message %}
        <h2 class="response {{ site.response.status }}">{{ site.response.message }}</h2>
        {% endif %}
        <ul class="app-form-items">
            <li><input type="email" id="userlogin" name="userlogin" placeholder="Enter E-Mail" value="{{ site.post.userlogin }}" required /></li>
            <li><input type="password" id="userpassword" name="userpassword" placeholder="Enter Password" required /></li>
            <li class="center-block-horizontal form-button">
                <button type="submit" id="submitbutton" name="submitbutton" class="submit-button">
                    <span>Login</span>
                    <i class="fas fa-circle-notch fa-spin hide loader"></i>
                </button>
            </li>
            <li class="form-links"><a href="./{{ site.links.signup.slug }}">{{ site.links.signup.title }}</a> | <a href="./{{ site.links.forgotpassword.slug }}">{{ site.links.forgotpassword.title }}</a></li>
        </ul>
        <input type="hidden" id="app" name="app" value="{{ site.users.class }}" />
        <input type="hidden" id="exe" name="exe" value="{{ site.links.login.slug }}" />
        <input type="hidden" id="csrftoken" name="csrftoken" value="{{ site.csrf }}" />
        </form>
    </div>
</div>
