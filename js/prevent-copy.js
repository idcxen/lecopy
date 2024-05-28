jQuery(document).ready(function($) {
    $(document).on('contextmenu', function(e) {
        if (!e.ctrlKey) {
            e.preventDefault();
        }
    });

    $(document).on('keydown', function(e) {
        if (e.ctrlKey && (e.which === 67 || e.which === 65 || e.which === 88 || e.which === 85 || e.which === 80)) {
            e.preventDefault();
        }
    });
});
