(function () {
    // Load the script
    var script = document.createElement("SCRIPT");
    script.src = 'https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js';
    script.type = 'text/javascript';
    document.getElementsByTagName("head")[0].appendChild(script);

    // Poll for jQuery to come into existence => ???
    var checkReady = function (callback) {
        if (window.jQuery) {
            callback(jQuery);
        }
        else {
            window.setTimeout(function () { checkReady(callback); }, 20);
        }
    };

    checkReady(function ($) {
        $(function () {
            jQuery(function () {
                document.onkeydown = PressedKey;
                
                function PressedKey(e) {
                    if (e.keyCode == 116) {
                        e.preventDefault();
                        
                        $.ajax({
                            type: "POST",
                            url: "gitSync.php",
                            success: function () {
                                location.reload(true);
                            }
                        });
                    }
                }
            });
        });
    });
})();