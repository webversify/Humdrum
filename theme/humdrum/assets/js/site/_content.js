
$('#loginform').onsubmit = (function(event) {
    $('.submit-button').childNodes.forEach(function(el) {
        if (el.firstChild) {
            el.classList.add('fade-out', 'fast');
            el.classList.add('hide');
        }else{
            setTimeout(function() {
                el.classList.remove('hide');
                el.classList.add('fade-in', 'fast');
            });
        }
    });
});
