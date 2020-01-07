<div class="container">
    <div class="app-form">
        <form id="forgotpasswordform" name="forgotpasswordform" method="post">
        <div class="app-logo-small">
            <img src="{{ site.url ~ 'theme/' ~ site.skin ~ '/images/logo-title.png' }}" />
        </div>
        <ul class="app-form-items">
            <li><input type="email" id="useremail" name="useremail" placeholder="Enter E-Mail" required /></li>
            <li class="center-block-horizontal form-button">
                <button id="submitbutton" name="submitbutton" class="submit-button">Send E-Mail</button>
                <i class="fas fa-circle-notch fa-spin hide loader"></i>
            </li>
            <li class="form-links"><a href="./{{ site.links.login.slug }}">{{ site.links.login.title }}</a> | <a href="./{{ site.links.signup.slug }}">{{ site.links.signup.title }}</a></li>
        </ul>
        <input type="hidden" id="app" name="app" value="{{ site.users.class }}" />
        <input type="hidden" id="exe" name="exe" value="{{ site.links.forgotpassword.slug }}" />
        <input type="hidden" id="csrftoken" name="csrftoken" value="{{ site.csrf }}" />
        </form>
    </div>
</div>
