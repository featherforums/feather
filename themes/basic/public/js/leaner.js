(function($){
    $.fn.extend({
        leaner: function(options) {

            var close = function (id){
                $("#lean-overlay").fadeOut(200);
                $('#box-modal').hide();
            };

            var center = function(width, height)
            {
                 $('#box-modal').css({
                    'display' : 'block',
                    'position' : 'fixed',
                    'opacity' : 0,
                    'z-index': 11000,
                    'left' : 50 + '%',
                    'margin-left' : -(width / 2) + "px",
                    'top' : 50 + '%',
                    'margin-top' : -(height / 2) + "px"
                });
            }

            var defaults = {
                top: 100,
                overlay: 0.7
            };

            $("body").append($("<div id='lean-overlay'></div>")).append($("<div id='box-modal'><div id='box-close'></div><div id='box-content'></div></div>"));

            options = $.extend({}, defaults, options);

            return this.each(function() {

                $(this).click(function(e) {

                    $('#box-modal').show().find('#box-content').html('<div class="loading"></div>');

                    center($('#box-modal').outerWidth(), $('#box-modal').outerHeight());

                    $('#box-modal').find('#box-content').load($(this).attr("href") + ' .content', function()
                    {
                        $("#lean-overlay, #box-close").click(function() {
                            close();
                        });

                        $(document).one('keydown', function(e) {
                            if(e.keyCode == 27) {
                                close();
                            }
                        });

                        var overlay = $('#lean-overlay');

                        overlay.css({ 'display' : 'block', opacity : 0 });
                        overlay.fadeTo(200, options.overlay);

                        center($('#box-modal').outerWidth(), $('#box-modal').outerHeight());

                        $('#box-modal').fadeTo(200, 1);
                    });

                    e.preventDefault();
                });
            });
        }
    });
})(jQuery);
