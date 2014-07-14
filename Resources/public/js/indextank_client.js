(function( $ ) {

    $.fn.indexTank = function( options ) {

        var defaultDisplayFunction = function(result) {
            console.log(result);
        }

        var defaultErrorFunction = function(jqXHR, textStatus, errorThrown) {
            console.log(errorThrown);
        }

        var settings = $.extend({
            minLength: 3,
            display: defaultDisplayFunction,
            error: defaultErrorFunction
        }, options );

        this.filter('input').each(function() {
            var input = $(this);

            input.keyup(function() {
                var text = $(this).val();
                if (text.length >= settings.minLength) {
                    var url =
                        settings.url.replace(/\/+$/,'')
                        + '/v1/indexes/'
                        + encodeURIComponent(settings.index)
                        + '/search';

                    $.ajax( {
                        url: url,
                        dataType: "jsonp",
                        data: {
                            q: 'content:*'+text+'* OR title:*'+text+'* OR tags:*'+text+'*',
                            fetch: '*'
                        },
                        timeout: 1000,
                        success: settings.display,
                        error: settings.error
                    });
                }
            });
        });

        return this;

    };

}( jQuery ));