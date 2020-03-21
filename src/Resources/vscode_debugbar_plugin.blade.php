<style>
    .phpdebugbar-widgets-list-item {
        align-items: baseline !important;
    }

    .phpdebugbar-plugin-vscodebutton {
        font-size: 12px !important;
        display: inline-block !important;
        color: #000 !important;
        border: 1px solid #9d9090 !important;
        border-radius: 3px !important;
        box-shadow: 0px 2px 3px #00000069 !important;
        padding-left: 8px !important;
        padding-right: 8px !important;
        padding-top: 4px !important;
        padding-bottom: 4px !important;
        margin-left: 8px !important;
        margin-right: 4px !important;
        margin-bottom: 0px !important;
        cursor: pointer !important;
    }
</style>

<script>
    var phpdebugbar_plugin_vscode_mIsLoaded = false;

    function phpdebugbar_plugin_onBtnVscodeClicked(ev, el) {
        window.location.href = $(el).data('link');
        event.stopPropagation();
    }

    var phpdebugbar_plugin_vscode_onInit = function () {
        if ($) {
            // OK
        } else {
            // jQuery not yet available
            return;
        }

        if ($('.phpdebugbar').length) {
            // OK
        } else {
            // laravel-debugbar not yet available
            return;
        }

        if (phpdebugbar_plugin_vscode_mIsLoaded) {
            return;
        }

        $(function onDocumentReady() {
            function getSchemeName() {
                return "{{ (isset($phpdebugbar_editor) 
                                ? $phpdebugbar_editor 
                                : 'http://localhost:8091/?message=') }}";
            }

            function getBasePath() {
                return "{{ str_replace('\\', '/', base_path()) }}";
            }

            function isPhp(str) {
                return str.indexOf('.php') != -1;
            }

            function isController(str) {
                return str.indexOf('.php:') != -1;
            }

            function isBlade(str) {
                return str.indexOf('.blade.php') != -1;
            }

            function getLink(str) {
                var result = '';

                result += getSchemeName();
                result += '://file/';
                result += getBasePath();

                if (isBlade(str)) {
                    var iRes = str.indexOf('resources');
                    if (iRes != -1) {
                        str = str.substring(iRes - 1);
                        var iViews = str.indexOf('views');
                        if (iViews != -1) {
                            var iEnd = str.indexOf(')', iViews);
                            if (iEnd != -1) {
                                str = str.substring(0, iEnd);
                                result += str;
                            }
                        }
                    }
                } else if (isController(str)) {
                    var iRes = str.indexOf('.php:');
                    if (iRes != -1) {
                        var iLastDash = str.lastIndexOf('-');
                        result += str.substring(0, iLastDash);
                    }
                }

                return result;
            }

            var funOnHoverIn = function (e) {
                e.stopPropagation();

                var str = $(this).html();
                if (isPhp(str) || isBlade(str) || isController(str)) {
                    // OK
                } else {
                    // Unknown format
                    return;
                }

                if (str.indexOf('vscode_debugbar_plugin') == -1) {
                    // OK
                } else {
                    // Don't add button to this plugin view path
                    return;
                }

                var strFullPath = getLink(str);

                if (isBlade(str)) {
                    var oldHtml = $(this).parent().html();
                    var strNewLink = '';
                    if (oldHtml.indexOf('phpdebugbar-plugin-vscodebutton') == -1) {
                        strNewLink = '<a class="phpdebugbar-plugin-vscodebutton" onclick="phpdebugbar_plugin_onBtnVscodeClicked(event, this);" data-link="' + strFullPath + '" title="' + strFullPath + '">' +  '&#9998;' +  '</a>';
                    }
                    $(strNewLink).insertAfter($(this));
                } else if (isController(str)) {
                    var oldHtml = $(this).html();
                    var strNewLink = '';
                    if (oldHtml.indexOf('phpdebugbar-plugin-vscodebutton') == -1) {
                        strNewLink = '<a class="phpdebugbar-plugin-vscodebutton" onclick="phpdebugbar_plugin_onBtnVscodeClicked(event, this);" data-link="' + strFullPath + '" title="' + strFullPath + '">' +  '&#9998;' +  '</a>';
                    }
                    $(strNewLink).appendTo($(this));
                }
            };

            var funOnHoverOut = function (e) {
                e.stopPropagation();
            };

            $('.phpdebugbar span.phpdebugbar-widgets-name').hover(funOnHoverIn, funOnHoverOut);
            $('.phpdebugbar dd.phpdebugbar-widgets-value').hover(funOnHoverIn, funOnHoverOut);
        });

        phpdebugbar_plugin_vscode_mIsLoaded = true;
        clearInterval(phpdebugbar_plugin_vscode_mInterval);
    }

    var phpdebugbar_plugin_vscode_mInterval = setInterval(phpdebugbar_plugin_vscode_onInit, 3000);
</script>