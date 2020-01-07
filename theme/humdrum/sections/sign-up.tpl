<div class="container">
    <div class="app-form">
        <form id="signupform" name="signupform" method="post">
        <div class="app-logo-small">
            <img src="{{ site.url ~ 'theme/' ~ site.skin ~ '/images/logo-title.png' }}" />
        </div>
        {% if site.response.message %}
        <h2 class="response {{ site.response.status }}">{{ site.response.message }}</h2>
        {% endif %}
        <ul class="app-form-items">
            <li><input type="text" id="firstname" name="firstname" placeholder="Enter Firstname" required /></li>
            <li><input type="text" id="lastname" name="lastname" placeholder="Enter Lastname" required /></li>
            <li>&nbsp;</li>
            <li><input type="email" id="useremail" name="useremail" placeholder="Enter E-Mail" required /></li>
            <li><input type="password" id="userpassword" name="userpassword" placeholder="Enter Password" required /></li>
            <li><input type="password" id="userpassword2" name="userpassword2" placeholder="Re-Enter Password" required /></li>
            <li class="center-block-horizontal form-button">
                <button id="submitbutton" name="submitbutton" class="submit-button">{{ site.links.signup.title }}</button>
                <i class="fas fa-circle-notch fa-spin hide loader"></i>
            </li>
            <li class="form-links"><a href="./{{ site.links.login.slug }}">{{ site.links.login.title }}</a> | <a href="./{{ site.links.forgotpassword.slug }}">{{ site.links.forgotpassword.title }}</a></li>
        </ul>
        <input type="hidden" id="app" name="app" value="{{ site.users.class }}" />
        <input type="hidden" id="exe" name="exe" value="{{ site.links.signup.slug }}" />
        <input type="hidden" id="csrftoken" name="csrftoken" value="{{ site.csrf }}" />
        </form>
    </div>
</div>
