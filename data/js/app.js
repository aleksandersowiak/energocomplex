App = {
    init: function () {
//$(document).on('click', function(event) {
//  console.log(event);
//})
        $(document).ready(function () {
            //OTHER IN ACTIVITY

            $("input").on('keydown', function (event) {
                $(this).removeClass('error-input');
                $(this).parent('td').removeAttr('data-tip');
            });

            var other_activity = $('#other_activity');
            $('#activity').change(function () {
                $(this).children(':selected').hasClass('other') ? other_activity.show() : other_activity.hide();
            });
            $.fn.child = function (s) {
                return $(this).children(s)[0];
            };
            $($('span[class*="visibleSub"]').parent('li').children('.box')).each(function () {
                $(this).toggle();
            });
            $('#viewTree div[class="can-toggle__switch"]').on('click', function () {
                $($(this).parents('li').child('div[class="box"]')).slideToggle();
                $($($(this)).parent('li').child('div[class="can-toggle__switch"]')).trigger('click');
            });

            $('input[type="checkbox"]').on('keypress', function () {
                var x = event.keyCode;
                if (x == 32) {
                    $($(this).parents('li').child('div[class="box"]')).slideToggle();
                    $($($(this)).parent('li').child('div[class="can-toggle__switch"]')).trigger('click');
                    var $this = $(this).parent('div').find('div[class="can-toggle__switch"]');
                    if ($($this)) {

                        App.showHide($($this));
                    }
                }
            });
            $('div[class="can-toggle__switch"]').on('click', function (event) {
                App.showHide($(this));
            });

            $('input[type="submit"]').click(function (event) {
                $('form').find("input").each(function () {
                    if (($(this).val() == '') && $(this).hasClass('required')) {
                        $(this).addClass('error-input');
                        $(this).parent('td').attr('data-tip', required_message);
                        event.preventDefault();
                        return;
                    }
                });
                if ($('form').find("input.required").first().hasClass('error-input')) {
                    $('html, body').animate({
                        scrollTop: $('form').find('input.required').first().offset().top
                    }, 1000);
                }
                var booking_email = $('input[name="email"]').val();
                if (booking_email != '') {
                    if (!/(.+)@(.+){2,}\.(.+){2,}/.test(booking_email)) {

                        $('input[name="email"]').addClass('error-input');
                        $('input[name="email"]').parent('td').attr('data-tip', email_invalid);
                        $('html, body').animate({
                            scrollTop: $('input[name="email"]').offset().top
                        }, 1000);
                        event.preventDefault();
                        return;

                    }
                }

                var $this = $(this);

                $($this.parents('form')).unbind('submit').submit(function () {
                    var serializeForm = $('table :input:visible').serialize();
                    var serializeObject = $('div#viewTree').find('input[type="text"], input[type="hidden"], textarea').filter(function () {
                        return $(this).is(':visible');
                    }).serialize();
                    var serializeCheckbox = $('div#viewTree').find('input[type="text"], textarea').filter(function () {
                        return $(this).is(':visible');
                    }).parent("li").find('input[type="checkbox"]').serialize();
                    var serializeCheckboxAll = $('div#viewTree').find("li").find('input[type="checkbox"]').filter(function () {
                        return ($(this).is(':visible'))
                    }).serialize();
                    var data = {form: App.URLToArray(serializeForm)};
                    var data_ext = {object: App.URLToArray(serializeObject)};
                    $.extend(data, data_ext);
                    $.extend(data, {objectCheck: App.URLToArray(serializeCheckbox)});
                    $.extend(data, {objectCheckAll: App.URLToArray(serializeCheckboxAll)});
                    $.extend(data, {recaptcha_challenge_field: $('[name="recaptcha_challenge_field"]').val(), recaptcha_response_field: $('[name="recaptcha_response_field"]').val()})
                    var url = $this.parents('form').attr('action');
                    $.ajax({
                        url: url,
                        type: 'post',
                        data: data,
                        success: function (data) {
                            eval(data);
                        }
                    });
                    return false;
                });
            });
        });
    },

    showHide: function ($thisEl) {
        var $this = $($thisEl.parent('label').parent('div.can-toggle').child('input[type="checkbox"]'));
        $this = $($this);
        var input = null;
        input = $this.parents('li').children('textarea, input:not(.begin)');
        input.each(function (i, e) {
            $(e).toggle();

        })
    },
    URLToArray: function (url) {
        var request = {};
        var pairs = url.substring(url.indexOf('?') + 1).split('&');
        for (var i = 0; i < pairs.length; i++) {
            if (!pairs[i])
                continue;
            var pair = pairs[i].split('=');
            request[decodeURIComponent(pair[0])] = decodeURIComponent(pair[1]);
        }
        return request;
    },
    buildList: function (data, isSub, yess, noo) {
        var yes = yess;
        var no = noo;
        var html = (isSub) ? '<div style="display:none" class="box" >' : ''; // Wrap with div if true
        html += '<ul>';
        for (var item in data) {
            var dataName = '';

            if (data[item].name != undefined) {
                var sclass = '';
                if (data[item].class != undefined) {
                    sclass = data[item].class;
                }
                dataName = '<span class="label label-default control control--checkbox '+sclass+'">' + data[item].name + '</span>';
            }
            html += '<li>';
            if (typeof(data[item].sub) === 'object') { // An array will return 'object'
                var input = '';
                if (data[item].input != undefined) {
                    var input = data[item].input;
                    for (inp in input) {
                        if (input[inp].class == 'begin') {
                            html +=
                                '<div class="can-toggle can-toggle--size-small" style="display: inline-block"> ' +
                                    '<input id="' + input[inp].id + '" class="' + input[inp].class + '" name="' + input[inp].name + '" type="' + input[inp].type + '">' +
                                    '<label for="' + input[inp].id + '"><div class="can-toggle__switch" data-checked="' + yes + '" data-unchecked="' + no + '"></div>' +
                                    '<div class="can-toggle__label-text"></div> </label></div>';
                            delete input[inp];
                        } else {
                            if (input[inp].class.indexOf("add")) {
                                var name = input[inp].name
                            }
                            if (input[inp].type == 'textarea') {
                                input[inp] = '<br/><textarea style="clear:both" class="' + input[inp].class + ' "placeholder="' + input[inp].placeholder + '" id="' + input[inp].id + '" name="' + input[inp].name + '"/></textarea>';
                            } else {
                                input[inp] = ' <input type="' + input[inp].type + '" class="' + input[inp].class + ' "placeholder="' + input[inp].placeholder + '" id="' + input[inp].id + '" name="' + name + '" /> ';
                            }
                        }
                    }
                }
                html += dataName; // Submenu found, but top level list item.
                for (var inp in input) {
                    html += input[inp];
                }
                html += App.buildList(data[item].sub, true, yes, no); // Submenu found. Calling recursively same method (and wrapping it in a div)
            } else {
                html += dataName; // No submenu
            }
            html += '</li>';
        }
        html += '</ul>';
        html += (isSub) ? '</div>' : '';

        return html;
    },
    feedBack: function (status, text) {
        if ($('#recaptcha_reload').length > 0) {
            Recaptcha.reload();
        }
        $("body").append("<div class=\"alert box-message\"><div class=\"pop-up alert-" + status + "\">" + text + "</div></div>");
        var refreshIntervalId = setInterval(function () {
            $('.alert').remove();
            clearInterval(refreshIntervalId);
        }, 5000);
    },
    clearForm: function () {
        $('div[class="can-toggle__switch"]').each(function () {
            var $this = $($(this).parent('label').parent('div.can-toggle').child('input[type="checkbox"]'));
            $this = $($this);
            var input = null;
            if ($this.prop('checked')) {
                $($(this)).trigger('click');
            }
        });
        var other_activity = $('#other_activity');
        if (other_activity.is(':visible')) {
            other_activity.hide();
        }
        $('form').trigger('reset');
    }
}