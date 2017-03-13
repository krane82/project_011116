$( document ).ready(function() {
    //Init Step view
    $("#wizard").steps({
        headerTag: "h3",
        bodyTag: "section",
        transitionEffect: "slideLeft",
        autoFocus: true,
        onFinished: function (event, currentIndex)
        {
            $('#new-client-form').submit();
        }
    });
    
});