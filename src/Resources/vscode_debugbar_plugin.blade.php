<style>
    .phpdebugbar-widgets-list-item {
        align-items: baseline !important;
    }

    .phpdebugbar-plugin-vscodebutton {
        font-size: 12px !important;
        display: inline-block !important;
        color: var(--mainColor) !important;
        border: 1px solid #9d9090 !important;
        border-radius: 3px !important;
        box-shadow: 0px 2px 3px #00000069 !important;
        padding-left: 8px !important;
        padding-right: 8px !important;
        padding-top: 0 !important;
        padding-bottom: 0 !important;
        margin-left: 8px !important;
        margin-right: 4px !important;
        margin-bottom: 0px !important;
        cursor: pointer !important;
    }
</style>

<script>
    var phpdebugbar_plugin_vscode_mIsLoaded = false;

    function editorUrl(file, lineNumber) {
        const editors = {
            sublime: 'subl://open?url=file://%path&line=%line',
            textmate: 'txmt://open?url=file://%path&line=%line',
            emacs: 'emacs://open?url=file://%path&line=%line',
            macvim: 'mvim://open/?url=file://%path&line=%line',
            phpstorm: 'phpstorm://open?file=%path&line=%line',
            idea: 'idea://open?file=%path&line=%line',
            vscode: 'vscode://file/%path:%line',
            'vscode-insiders': 'vscode-insiders://file/%path:%line',
            atom: 'atom://core/open/file?filename=%path&line=%line',
            nova: 'nova://core/open/file?filename=%path&line=%line',
        };

        @if (env('IGNITION_LOCAL_SITES_PATH') && env('IGNITION_REMOTE_SITES_PATH'))
            file = file.replace('{{env('IGNITION_REMOTE_SITES_PATH')}}', '{{env('IGNITION_LOCAL_SITES_PATH')}}')
        @endif

            return editors['phpstorm']
            .replace('%path', encodeURIComponent(file))
            .replace('%line', encodeURIComponent(lineNumber));
    }

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

            function getFilename(str) {
                var fileName = '{{ str_replace('\\', '/', base_path()) }}/';

                if (isBlade(str)) {
                    var iRes = str.indexOf('resources');
                    if (iRes != -1) {
                        str = str.substring(iRes);
                        var iViews = str.indexOf('views');
                        if (iViews != -1) {
                            var iEnd = str.indexOf(')', iViews);
                            if (iEnd != -1) {
                                str = str.substring(0, iEnd);
                                fileName += str;
                            }
                        }
                    }
                } else if (isController(str)) {
                    var iRes = str.indexOf('.php:');
                    if (iRes != -1) {
                        var iLastDash = str.lastIndexOf('-');
                        fileName += str.substring(0, iLastDash);
                    }
                }

                return fileName;
            }

            $('.phpdebugbar span.phpdebugbar-widgets-name, .phpdebugbar dd.phpdebugbar-widgets-value').each(function () {
                var str = $(this).html();
                let strIsBlade = isBlade(str);
                let strIsController = isController(str);

                // if (isPhp(str)  && !strIsBlade && !strIsController) {
                //     console.log(str);
                // }

                if (strIsBlade || strIsController) {
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

                let pathInfo = getFilename(str).split(':');

                let link = editorUrl(pathInfo[0], pathInfo[1])

                if (strIsBlade) {
                    var oldHtml = $(this).parent().html();
                } else if (strIsController) {
                    var oldHtml = $(this).html();
                }

                if (oldHtml.indexOf('phpdebugbar-plugin-vscodebutton') == -1) {
                    var strNewLink = '';
                    strNewLink = '<a class="phpdebugbar-plugin-vscodebutton" '
                        + 'onclick="phpdebugbar_plugin_onBtnVscodeClicked(event, this);"'
                        + ' data-link="' + link
                        + '" title="' + link + '">&#9998;</a>';

                    if (strIsBlade) {
                        $(strNewLink).insertAfter($(this));
                    } else if (strIsController) {
                        $(strNewLink).appendTo($(this));
                    }
                }
            });
        });

        phpdebugbar_plugin_vscode_mIsLoaded = true;
        clearInterval(phpdebugbar_plugin_vscode_onInit);
    }

    var phpdebugbar_plugin_vscode_mInterval = setInterval(phpdebugbar_plugin_vscode_onInit, 2000);
</script>