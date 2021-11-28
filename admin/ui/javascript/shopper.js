$(document).ready(function () {

    //Form Submit for IE Browser
    $('button[type=\'submit\']').on('click', function () {
        $("form[id*='form-']").submit();
    });
    
    // Set last page opened on the menu
    $('#navbar a[href]').on('click', function () {
        sessionStorage.setItem('menu', $(this).attr('href'));
    });

    if (!sessionStorage.getItem('menu')) {
        $('#navbar #home').addClass('active');
    } else {
        // Sets active and open to selected page in the left column menu.
        $('#navbar a[href=\'' + sessionStorage.getItem('menu') + '\']').parents('li').addClass('active');
    }

    // tooltips on hover
    $('[data-toggle=\'tooltip\']').tooltip({container: 'body', html: true});

    // Makes tooltips work on ajax generated content
    $(document).ajaxStop(function () {
        $('[data-toggle=\'tooltip\']').tooltip({container: 'body'});
    });

    $('[data-toggle=\'tooltip\']').on('remove', function () {
        $(this).tooltip('destroy');
    });
    
});
