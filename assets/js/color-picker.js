jQuery(document).ready(function ($) {    
    $('.color-picker').iris();    
    $(document).click(function (e) {
        if (!$(e.target).is(".color-picker, .iris-picker, .iris-picker-inner")) {
            $('.color-picker').iris('hide');
            return true;
        }
    });
    $('.color-picker').click(function (event) {
        $('.color-picker').iris('hide');
        $(this).iris('show');
        return true;
    });
});

