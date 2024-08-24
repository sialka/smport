    
window.timeOutTypeAhead = {};
window.lastSelectionTypeAhead = {};

function LoadSearchTypeAhead(options) {
    typeof options.hint             === 'undefined' ? options.hint              = true          : options.hint;
    typeof options.minLength        === 'undefined' ? options.minLength         = 1             : options.minLength;
    typeof options.displayKey       === 'undefined' ? options.displayKey        = ''            : options.displayKey;
    typeof options.limit            === 'undefined' ? options.limit             = 22            : options.limit;
    typeof options.method           === 'undefined' ? options.method            = 'POST'        : options.method;
    typeof options.url              === 'undefined' ? options.url               = ''            : options.url;
    typeof options.data             === 'undefined' ? options.data              = {}            : options.data;
    typeof options.model            === 'undefined' ? options.model             = ''            : options.model;
    typeof options.pending          === 'undefined' ? options.pending           = ''            : options.pending;
    typeof options.notFound         === 'undefined' ? options.notFound          = ''            : options.notFound;
    typeof options.suggestion       === 'undefined' ? options.suggestion        = []            : options.suggestion;
    typeof options.fillFields       === 'undefined' ? options.fillFields        = []            : options.fillFields;
    typeof options.selector         === 'undefined' ? options.selector          = '.search'     : options.selector;
    typeof options.width            === 'undefined' ? options.width             = '100%'        : options.width;
    typeof options.suggestionStyle  === 'undefined' ? options.suggestionStyle   = ''            : options.suggestionStyle;
    typeof options.fieldSearch      === 'undefined' ? options.fieldSearch       = '_all'        : options.fieldSearch;
    typeof options.delay            === 'undefined' ? options.delay             = 500           : options.delay;
    typeof options.modelAlias       === 'undefined' ? options.modelAlias        = options.model : options.modelAlias;
    typeof options.triggers         === 'undefined' ? options.triggers          = []            : options.triggers;
    typeof options.replaceBool      === 'undefined' ? options.replaceBool       = true          : options.replaceBool;


    $(options.selector+' .typeahead').typeahead({
        hint:       options.hint,
        highlight:  true,
        minLength:  options.minLength,
    },
    {
        displayKey  : options.displayKey,
        limit       : options.limit,
        source: function (query, sync, async) {
            options.data[options.fieldSearch] = query;

            var fullModelAlias = options.modelAlias;
            var hasIdx = $(this).data('idx');
            if (typeof hasIdx !== 'undefined') {
                fullModelAlias = fullModelAlias + hasIdx;
            }

            if (typeof window.timeOutTypeAhead[fullModelAlias] !== 'undefined') {
                clearTimeout(window.timeOutTypeAhead[fullModelAlias]);
                window.lastSelectionTypeAhead[fullModelAlias] = null;
            }

            window.timeOutTypeAhead[fullModelAlias] = setTimeout(function() {
                $.ajax({
                    method: options.method,
                    url:    options.url,
                    headers: {
                        Accept:         "application/json; charset=utf-8",
                        'X-CSRF-Token': $("input[name='_csrfToken']").val()
                    },
                    data:   options.data
                })
                .done(function(_data) {
                    if (_data[options.model].length === 0) {
                        async([]);
                    }
                    $.each(_data[options.model], function(key, value){
                        async([value]);
                    });
                    return true;//;async(newData);
                })
                .fail(function(jqXHR, textStatus, errorThrown ) {
                    return async([]);
                })
                .always(function() {
                });
            }, options.delay);

            return window.timeOutTypeAhead[fullModelAlias];
        },
        templates: {
           pending:
               [
                   '<div class="card card-body searching-typeahead">',
                   '<p>Pesquisando...</p>',
                   '<div class="progress"><div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"><span class="sr-only">100% Complete</span></div></div>',
                   '</div>'
               ].join('\n'),
           notFound:
               [
                   '<div class="card card-body notFound-typeahead text-center">',
                   '<strong>Não localizado!</strong>',
                   '</div>'
               ].join('\n'),
           suggestion: function (data) {
               var line = '';
               $.each(options.suggestion, function (key, value) {
                   var texto = recoverInfo(value, data, options);
                   line = line === '' ? texto : line + ' - ' + texto;
               });

               return '<div class=""><div style="'+options.suggestionStyle+' display: block">'+line+'</div></div>';
           }
        }
    });

    $(options.selector).find('.tt-menu').css('width', options.width);

    $(options.selector+' .typeahead').bind('typeahead:select', function(ev, suggestion) {
        selectSuggestion(this, ev, suggestion, options);
    });

    $(options.selector+' .typeahead').bind('typeahead:autocomplete', function(ev, suggestion) {
        selectSuggestion(this, ev, suggestion, options);
    });

    $(options.selector+' .typeahead').bind("typeahead:close", function(e) {
        var fullModelAlias = options.modelAlias;
        var hasIdx = $(this).data('idx');
        if (typeof hasIdx !== 'undefined') {
            fullModelAlias = fullModelAlias + hasIdx;
        }

        var val = $(e.currentTarget).val();
        if (val != window.lastSelectionTypeAhead[fullModelAlias]) {
            $(e.currentTarget).val('');

            $.each(options.fillFields, function (key, value) {
                if (typeof hasIdx !== 'undefined') {
                    var new_selector = value.selector.replace("idx", hasIdx);
                } else {
                    var new_selector = value.selector;
                }
                element = $(new_selector);
                if ($(element).is(':input')) {
                    $(element).val('');
                } else {
                    $(element).html('');
                }
            });
        }
    });

    var elementsChecks = $(options.selector+' .typeahead');
    $.each(elementsChecks, function (key, value) {
        var fullModelAlias = options.modelAlias;
        var hasIdx = $(this).data('idx');
        if (typeof hasIdx !== 'undefined') {
            fullModelAlias = fullModelAlias + hasIdx;
        }

        if (typeof window.timeOutTypeAhead[fullModelAlias] !== 'undefined') {
            clearTimeout(window.timeOutTypeAhead[fullModelAlias]);
            window.timeOutTypeAhead[fullModelAlias] = undefined;
        }
        window.lastSelectionTypeAhead[fullModelAlias] = $(value).val();
    });
}

function recoverInfo(value, suggestion, options) {
    var arrField = value.split(".");
    var finalVal = suggestion;
    $.each(arrField, function(key, value) {
        if (typeof finalVal === 'undefined' || finalVal === null) {
            return;
        }

        if (typeof finalVal[value] === 'undefined') {
            return;
        }
        finalVal = finalVal[value];
    });

    if (typeof finalVal === 'undefined' || finalVal === null) {
        return finalVal;
    }

    if (typeof finalVal === 'boolean' && (typeof options.replaceBool !== 'undefined')) {
        if (options.replaceBool === true) {
            finalVal = finalVal === true ? 1 : 0;
        } else {
            finalVal = finalVal === true ? options.replaceBool.trueValue : options.replaceBool.falseValue;
        }
    }

    return finalVal;
}

function selectSuggestion(elementThis, ev, suggestion, options) {
    var fullModelAlias = options.modelAlias;
    var hasIdx = $(elementThis).data('idx');
    if (typeof hasIdx !== 'undefined') {
        fullModelAlias = fullModelAlias + hasIdx;
    }

    var the_type_search = elementThis;
    var hasIdx          = $(elementThis).data('idx');
    $.each(options.fillFields, function (key, value) {
        if (typeof hasIdx !== 'undefined') {
            var new_selector = value.selector.replace("idx", hasIdx);
        } else {
            var new_selector = value.selector;
        }
        element = $(new_selector);
        if ($(element).is(':input')) {
            $(element).val(recoverInfo(value.field, suggestion, options));
        } else {
            $(element).html(recoverInfo(value.field, suggestion, options));
        }
    });

    window.lastSelectionTypeAhead[fullModelAlias] = suggestion[options.displayKey];

    triggerFunction(options, elementThis, ev, suggestion);
}

function triggerFunction(options, elementThis, ev, suggestion) {
    if (options.triggers.length > 0) {
        $.each(options.triggers, function (key, trigger) {
            if (typeof trigger.field !== 'undefined') {
                if (suggestion[trigger.field] === trigger.value || trigger.value === '_ANY_') {
                    window[trigger.functionName](options, elementThis, ev, suggestion, suggestion[trigger.field]);
                }
            } else {
                if (typeof trigger.fields !== 'undefined') {
                    var equal = true;
                    $.each(trigger.fields, function (fkey, field) {
                        if (suggestion[field.name] !== field.value) {
                            equal = false;
                            return;
                        }
                    });

                    if (equal === true) {
                        window[trigger.functionName](options, elementThis, ev, suggestion, '');
                    }
                }
            }
        });
    }
}
