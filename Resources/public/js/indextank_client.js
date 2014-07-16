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
            error: defaultErrorFunction,
            searchFieldSelector: "#search-field"
        }, options );

        this.filter('form').each(function() {
            var form = $(this);
            var inputField = form.find(settings.searchFieldSelector);

            var onEventProcess = function (minLength) {
                return function() {
                    var text = inputField.val();
                    if (text.length >= minLength) {
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

                    return false;
                }
            };

            inputField.keyup(onEventProcess(settings.minLength));
            form.submit(onEventProcess(0));
        });

        return this;

    };

}( jQuery ));