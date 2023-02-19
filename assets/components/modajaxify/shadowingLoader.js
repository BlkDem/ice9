/**
 * modAjaxify js support
 *
 * With very simple configuration it anables any link to load the referensed content in specific targets
 * It is based on the Ajaxify plugin https://github.com/browserstate/ajaxify
 * 
 * @author Giorgos Mylonas <info(at)sofar.gr>
 * @author Dimitris Chalvatzatas <info(at)sofar.gr>
 *
 * @package modAjaxify
 */
var _gaq = _gaq || [];
function enableGoogleAnalytics() {
    var ga = document.createElement('script');
    ga.type = 'text/javascript';
    ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(ga, s);
}
;
(function (window, undefined) {
    'use strict'
    // Prepare our Variables

    var lazyImgs = [],
            History = window.History,
            $ = window.jQuery,
            document = window.document,
            formData = null,
            formMethod = 'post';

    // Check to see if History.js is enabled for our Browser
    if (!History.enabled) {
        return false;
    }
    var loadLazyImgs = function (imageArr, load_index) {

        if (imageArr.length && imageArr.length > load_index) {
            var lzimg = imageArr[load_index];
            if (lzimg.attr('lazy-image') != undefined && lzimg.attr('lazy-image') != '')
                $('<img>').attr({
                    src: lzimg.attr('lazy-image')
                }).load(function () {
                    var $imgtrgt = $('[src="' + lzimg.attr('src') + '"]');

                    $imgtrgt.attr('src', lzimg.attr('lazy-image'));
                    lzimg.removeAttr('lazy-image');

                    loadLazyImgs(imageArr, ++load_index);
                });
        } else{
            lazyImgs = [];
            return;
        }
    };
    $.fn.preloadHtmlImages = function (endCallback, progrCallback, retinaSteps) {
        var checklist = $("img", this).toArray(), self = this;
        lazyImgs = [];
        var imgi = 0, ww = $(window).width();

        if (checklist.length < 1) {
            progrCallback(1);
            endCallback();
        }
        for (var i = 0; i < checklist.length; ++i) {



            var imgdata = $(checklist[i]), imgsrc = null;
            if (imgdata.attr('lazy-image') != undefined && imgdata.attr('lazy-image') != '')
                lazyImgs.push(imgdata);
            var retinaSteps = getRetinaSteps($(checklist[i]));
            if (retinaSteps.length > 0)
                $.each(retinaSteps, function (index, rStep) {
                    if (rStep < ww) {
                        var newsrc = imgdata.attr('src-' + rStep);
                        if (newsrc != undefined)
                        {
                            imgdata.attr('src', newsrc);

                            return false;
                        }
                    }
                });

            if (imgdata.attr('src') == undefined || imgdata.attr('src') == '') {
                ++imgi;
                progrCallback(imgi / checklist.length);
                // alert('ooo'+imgi+"=="+checklist.length);
                if (checklist.length - 1 < imgi) {

                    endCallback();
                    loadLazyImgs(lazyImgs, 0);
                }
            } else {

                $('<img>').attr({
                    src: imgdata.attr('src')
                }).load(function () {

                    // checklist.remove($(this).attr('src'));
                    ++imgi;
                    progrCallback(imgi / checklist.length);

                    if (checklist.length - 1 < imgi) {

                        endCallback();
                        loadLazyImgs(lazyImgs, 0);
                    }

                }).bind('error', function () {
                    ++imgi;
                    progrCallback(imgi / checklist.length);

                    if (checklist.length - 1 < imgi) {

                        endCallback();
                        loadLazyImgs(lazyImgs, 0);
                    }
                    console.warn('An image is missing:', this);
                    return false;
                });

            }
        }
    };
    $.shadowUrls = function (options) {

        var
                /* Application Generic Variables */
                $window = $(window),
                $body = $(document.body),
                rootUrl = History.getRootUrl(),
                preload = new createjs.LoadQueue(),
                prevTitle = null;

        preload.setMaxConnections(5);
        preload.addEventListener("progress", function p(e) {
            settings.onProgress.call(null, null, 0.1 + preload.progress * 0.9);
        });

        // Prepare Variables
        var settings = $.extend({
            'linkSelector': 'body',
            'targetSelectorId': 'body',
            'menuSelector': '.menu',
            'activeClass': 'active',
            'menuChildrenSelector': 'span',
            'noAjaxClass': '.noajax',
            'completedEventName': 'statechangecomplete',
            'excludeLinkExtentions': 'jpg|png|pdf|zip|rar|tiff|bmp',
            'preloadInitEnabled': false,
            'retinaSteps': [0, 1200],
            'googleAnalyticsId': undefined,
            'includeForms': true,
            onSuccess: function (url, response) {
            },
            onRender: function (url, data) {

                $(this).html(data).shadowlinks();

            },
            onProgress: function (url, progress) {
            },
            beforeLoad: function (url) {
            },
            onInitStart: function (url) {
            },
            onInitProgress: function (progress, url) {
            },
            onInitSuccess: function (url) {
            }

        },
                options);
        var selectorId = settings['targetSelectorId'];
        selectorId = selectorId.replace('#', '');
        var
                $content = $(selectorId).first(),
                $menus;

        // Ensure Content
        if ($content.length === 0) {
            $content = $body;
        }

//activate menu link
        var curUrl = window.location.href;
        var parser = document.createElement('a');
        parser.href = curUrl;
        var urlParts = parser.pathname.split('/');
        /*
         parser.protocol; // => "http:"
         parser.hostname; // => "example.com"
         parser.port;     // => "3000"
         parser.pathname; // => "/pathname/"
         parser.search;   // => "?search=test"
         parser.hash;     // => "#hash"
         parser.host;     // => "example.com:3000"*/


        if (settings['googleAnalyticsId'] != undefined) {
            enableGoogleAnalytics();
            _gaq.push(['_setAccount', settings['googleAnalyticsId']]);
            _gaq.push(['_trackPageview']);


        }


        var srelativeUrl = urlParts[urlParts.length - 1];
        if (srelativeUrl == "")
            srelativeUrl = urlParts[urlParts.length - 2];
        $(settings['menuSelector']).each(function () {
            var $menuChildren = $(settings['menuChildrenSelector'], this);

            $('a[href^="' + srelativeUrl + '"],a[href^="/' + srelativeUrl + '"]', this).each(function () {
                if ($(this).is(settings['menuChildrenSelector']))
                {
                    $menuChildren.removeClass(settings['activeClass']);
                    $(this).addClass(settings['activeClass']);
                } else {
                    $menuChildren.removeClass(settings['activeClass']);
                    var op = $(this).parent(settings['menuChildrenSelector']);
                    $(op).addClass(settings['activeClass']);
                }

            });

        });
        var excludedExtsPtrn = settings['excludeLinkExtentions'].split(',');

        var excludedExtsPtrn = null;
        if (settings['excludeLinkExtentions'] != "")
            excludedExtsPtrn = new RegExp('\\.(' + settings['excludeLinkExtentions'] + ')$', 'i');

        // Internal Helper
        $.expr[':'].internal = function (obj, index, meta, stack) {
            // Prepare
            var
                    $this = $(obj),
                    url = $this.attr('href') || '',
                    isInternalLink;

            // Check link
            isInternalLink = url.substring(0, rootUrl.length) === rootUrl || url.indexOf(':') === -1;
            //check if this is an excluded link by extention
            if (isInternalLink && excludedExtsPtrn != null)
                isInternalLink = !excludedExtsPtrn.test(url);

            // Ignore or Keep
            return isInternalLink;
        };



        // HTML Helper
        var documentHtml = function (html) {
            // Prepare
            var result = String(html)
                    .replace(/<\!DOCTYPE[^>]*>/i, '')
                    .replace(/<(html|head|body|title|meta|script)([\s\>])/gi, '<div class="document-$1"$2')
                    .replace(/<\/(html|head|body|title|meta|script)\>/gi, '</div>')
                    ;

            // Return
            return $.trim(result);
        };

        $.fn.shadowlinks = function () {
            // Prepare
            var $this = $(this);
            var noAjaxClass = settings['noAjaxClass'];
            if (noAjaxClass.indexOf('.') < 0)
                noAjaxClass = '.' + noAjaxClass;

            $this.find('a:internal:not(' + noAjaxClass + ')').on('click', function (event) {

                var
                        $this = $(this),
                        url = $this.attr('href'),
                        title = $this.attr('title') || null;
                if ($this.attr('context') != undefined)
                {
                    var callChar = "?"
                    if (url.indexOf('?') > -1)
                        callChar = "&";
                    url += callChar + "context=" + $this.attr('context');
                }
                // Continue as normal for cmd clicks etc
                if (event.which == 2 || event.metaKey) {
                    return true;
                }
                if (title != null)
                    prevTitle = title;
                if (title == null)
                    title = prevTitle;
                url = decodeURIComponent(url);
                History.pushState(null, title, url);
                lazyImgs = [];
                event.preventDefault();
                return false;
            });
            if (settings['includeForms'])
                $this.find('form:not(' + noAjaxClass + ')').on('submit', function (event) {

                    var
                            $this = $(this),
                            url = $this.attr('action') + '&t=' + (new Date()).getTime(),
                            title = $this.attr('title') || null;
                    if ($this.attr('context') != undefined)
                    {
                        var callChar = "?"
                        if (url.indexOf('?') > -1)
                            callChar = "&";
                        url += callChar + "context=" + $this.attr('context');
                    }

                    if ($this.attr('method') != undefined)
                        formMethod = $this.attr('method');
                    else
                        formMethod = 'post';
                    // Continue as normal for cmd clicks etc
                    if (event.which == 2 || event.metaKey) {
                        return true;
                    }
                    formData = $(this).serializeArray();

                    History.pushState(null, title, url);
                    event.preventDefault();
                    return false;
                });

            return $this;
        };

        $(settings['linkSelector']).shadowlinks();

        $window.bind('statechange', function () {
            // Prepare Variables
            var
                    State = History.getState(),
                    url = State.url,
                    // relativeUrl = url.replace(rootUrl, '').split('&context')[0],
                    ajaxType = 'GET';

            var parser = document.createElement('a');
            parser.href = url;
            var urlParts = parser.pathname.split('/');

            var relativeUrl = urlParts[urlParts.length - 1];
            if (relativeUrl == "")
                relativeUrl = urlParts[urlParts.length - 2];

            if (url.indexOf('context') < 0) {
                var callChar = "?"
                if (url.indexOf('?') > -1)
                    callChar = "&";
                url = url + callChar + "context=" + settings['targetSelectorId'];//choose main target
            }

            var sentData = {
                ajaxview: (new Date()).getTime()
            };


            if (formData != null) {
                //in case the url is an action of a form then the form data are post in the server
                formData.push({name: 'ajaxview', value: (new Date()).getTime()});
                sentData = $.param(formData);
                formData = null;
                ajaxType = formMethod;

            }
            // Set Loading
            $body.addClass('loading');

            // Ajax Request the Traditional Page
            $.ajax({
                url: url,
                type: ajaxType,
                data: sentData,
                dataType: 'html',
                beforeSend: function (jqXHR) {
                    var uriparams = URLToArray(url), targetSelectorA;
                     lazyImgs = [];

                    //there is a need to undentify again the context in case there where multiple hits
                    if (uriparams['context'] == undefined)
                        targetSelectorA = "#" + settings['targetSelectorId'].replace('#', '');
                    else
                        targetSelectorA = "#" + uriparams['context'].replace('#', '');
                    var t = $(targetSelectorA);
                    
                    settings.beforeLoad.call(t[0], url);
                    settings.onProgress(t[0], url, 0.1);
                },
                success: function (data, textStatus, jqXHR) {

                    var
                            $data = $(documentHtml(data)),
                            $dataContent = null, targetSelector = settings['targetSelectorId'],
                            contentHtml, $scripts, $menuChildren, $imgs;
                    var uriparams = URLToArray(url);

                    //there is a need to undentify again the context in case there where multiple hits
                    if (uriparams['context'] == undefined)
                    {
                        //   if(settings['targetSelectorId']=='body')   targetSelector = settings['targetSelectorId'];
                        //   else
                        targetSelector = "#" + settings['targetSelectorId'];
                    } else
                        targetSelector = "#" + uriparams['context'];

                    $dataContent = $data.find(targetSelector).first();
                    $content = $(targetSelector).first();
                    if ($content.length == 0)
                        $content = $body;
                    if ($dataContent.length == 0)
                        $dataContent = $data;


                    // Fetch the scripts
                    $scripts = $dataContent.find('.document-script');
                    if ($scripts.length) {
                        $scripts.detach();
                    }

                    contentHtml = $dataContent.html() || $data.html();
                    if (!contentHtml) {
                        document.location.href = url;
                        return false;
                    }
                    //responsive handling

                    // Update the menu
                    //find possible menus
                    $(settings['menuSelector']).each(function () {
                        $menuChildren = $(settings['menuChildrenSelector'], this);

                        $('a[href^="' + relativeUrl + '"],a[href^="/' + relativeUrl + '"],a[href^="' + url + '"]', this).each(function () {
                            if ($(this).is(settings['menuChildrenSelector']))
                            {
                                $menuChildren.removeClass(settings['activeClass']);
                                $(this).addClass(settings['activeClass']);
                            } else {
                                $menuChildren.removeClass(settings['activeClass']);
                                var op = $(this).parent(settings['menuChildrenSelector']);
                                $(op).addClass(settings['activeClass']);
                            }

                        });

                    });


                    if (preload != null) {
                        preload.close();
                    }

                    var retinaUsedSteps = [];

                    preload.addEventListener("complete", function c(e) {
                        $content.stop(true, true);
                        for (var ii = 0; ii < retinaUsedSteps.length; ++ii) {
                            var re = new RegExp(retinaUsedSteps[ii], "g");
                            contentHtml = contentHtml.replace(re, "src");
                        }

                        settings.onRender.call($content.get(0), url, contentHtml);
                        $(window).trigger('resize');
                        settings.onProgress.call($content.get(0), url, 1);
                        // Add the scripts
                        $scripts.each(function () {
                            var $script = $(this), scriptText = $script.text(), scriptNode = document.createElement('script');
                            if ($script.attr('src')) {
                                if (!$script[0].async) {
                                    scriptNode.async = false;
                                }
                                scriptNode.src = $script.attr('src');
                            }
                            scriptNode.appendChild(document.createTextNode(scriptText));

                            if ($content.get(0) != undefined)
                                $content.get(0).appendChild(scriptNode);
                        });

                        $body.removeClass('loading');


                        $window.trigger(settings['completedEventName']);
                        settings.onSuccess.call($content.get(0), url, data);
                        loadLazyImgs(lazyImgs, 0);

                        // Inform Google Analytics of the change
                        //if (typeof window._gaq !== 'undefined')
                        if (settings['googleAnalyticsId'] != undefined) {
                            _gaq.push(['_trackPageview', relativeUrl]);
                        }
                        e.target.removeEventListener(e.type, c);
                    });

                    var ww = $(window).width();
                    // lazyImgs = [];


                    $imgs = $(contentHtml).filter('img');
                    if ($imgs.length == 0)
                        $imgs = $(contentHtml).find('img');
                    if ($imgs.length == 0) {
                        preload.dispatchEvent('complete');
                    } else {
                        $imgs.each(function () {

                            var $imgC = $(this), srcAtrr = 'src';
                            if ($imgC.attr('lazy-image') != undefined && $imgC.attr('lazy-image') != '')
                                lazyImgs.push($imgC);
                            var retinaArr = getRetinaSteps($imgC);
                            for (var ii = 0; ii < retinaArr.length; ++ii)
                            {

                                if (retinaArr[ii] < ww) {
                                    srcAtrr = 'src-' + retinaArr[ii];

                                    if ($imgC.attr(srcAtrr) != undefined)
                                    {

                                        if (retinaUsedSteps.indexOf(srcAtrr))
                                            retinaUsedSteps.push(srcAtrr);
                                        $imgC.attr('src', $imgC.attr(srcAtrr));
                                        break;
                                    }

                                }
                            }

                            if ($imgC.attr('src') != undefined && $imgC.attr('src') != '') {
                                preload.loadFile($imgC.attr('src'));
                            } else {
                                console.warn('Image preload interupted, image found without srs defined:', $imgC);
                                //   preload.dispatchEvent('complete');
                                return;
                            }
                        });

                    }


                },
                error: function (jqXHR, textStatus, errorThrown) {
                    document.location.href = url;
                    return false;
                }
            }); // end ajax

        }); // end onStateChange

        var State = History.getState(),
                url = State.url,
                relativeUrl = url.replace(rootUrl, '').split('&context')[0]

        if (settings['preloadInitEnabled'] === false) {
            return false;
        }



        settings['onInitStart'].call(null, relativeUrl);



        $('body').preloadHtmlImages(
                function () {
                    settings['onInitSuccess'].call(null, relativeUrl);
                },
                function (progress) {
                    settings['onInitProgress'].call(null, progress, relativeUrl);
                }
        );


    }; // end shadowUrls

})(window); // end closure

function URLToArray(url) {
    var request = {};
    var arr = [];
    var pairs = url.substring(url.indexOf('?') + 1).split('&');
    for (var i = 0; i < pairs.length; i++) {
        var pair = pairs[i].split('=');

        //check we have an array here - add array numeric indexes so the key elem[] is not identical.
        if (endsWith(decodeURIComponent(pair[0]), '[]')) {
            var arrName = decodeURIComponent(pair[0]).substring(0, decodeURIComponent(pair[0]).length - 2);
            if (!(arrName in arr)) {
                arr.push(arrName);
                arr[arrName] = [];
            }

            arr[arrName].push(decodeURIComponent(pair[1]));
            request[arrName] = arr[arrName];
        } else {
            request[decodeURIComponent(pair[0])] = decodeURIComponent(pair[1]);
        }
    }
    return request;
}

function endsWith(str, suffix) {
    return str.indexOf(suffix, str.length - suffix.length) !== -1;
}

function intArraySort(c, a) {
    function d(a, b) {
        return b - a
    }
    "string" == typeof a && a.toLowerCase();
    switch (a) {
        default:
            return c.sort(function (a, b) {
                return a - b
            });
        case 1:
        case "d":
        case "dc":
        case "desc":
            return c.sort(d)
    }
}
function getRetinaSteps($element) {
    var retinaSteps = [];
    $($element[0].attributes).each(function () {

        var attrParts = this.nodeName.split('-');

        if (attrParts[1] != undefined)
            if (attrParts[0] == 'src')
                retinaSteps.push(attrParts[1]);
    });
    intArraySort(retinaSteps, 'dc');
    return retinaSteps;
}
