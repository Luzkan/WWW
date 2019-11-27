(function($) {

    var $window = $(window);
    var $body = $("body");
    var $header = $("#header");
    var $footer = $("#footer");
    var $main = $("#main");
    var $main_articles = $main.children("article");

    breakpoints({
        xlarge:   [ "1281px",  "1680px" ],
        large:    [ "981px",   "1280px" ],
        medium:   [ "737px",   "980px"  ],
        small:    [ "481px",   "736px"  ],
        xsmall:   [ "361px",   "480px"  ],
        xxsmall:  [ null,      "360px"  ]
    });

    // Play init animations on pageload
    $window.on("load", function() {
        window.setTimeout(function() {
            $body.removeClass("is-preload");
        }, 100);
    });

    // Nav
    var $nav = $header.children("nav");
    var $nav_li = $nav.find("li");

    // Add "middle" alignment classes if we"re dealing
    // with an even number of items.
    if ($nav_li.length % 2 == 0) {
        $nav.addClass("use-middle");
        $nav_li.eq($nav_li.length / 2).addClass("is-middle");
    }

    // Main
    var delay = 325;
    var locked = false;

    $main.showMain = function(id, initial) {
        var $article = $main_articles.filter("#" + id);

        // Handle lock
        if (locked || (typeof initial != "undefined" && initial === true)) {

            // Mark as switching, visible, deactive all articles
            $body.addClass("is-switching");
            $body.addClass("is-article-visible");
            $main_articles.removeClass("active");

            // Hide header, footer and
            // show main and article, activate the article
            $header.hide();
            $footer.hide();
            $main.show();
            $article.show();
            $article.addClass("active");

            locked = false;

            // End switching state
            setTimeout(function() {
                $body.removeClass("is-switching");
            }, (initial ? 1000 : 0));
            return;
        }

        locked = true;

        // Article swaping
        if ($body.hasClass("is-article-visible")) {

            // Deactivate current article
            var $currentArticle = $main_articles.filter(".active");
            $currentArticle.removeClass("active");

            // Show article
            setTimeout(function() {

                // Hide current article and show the new one then activate it
                $currentArticle.hide();
                $article.show();

                // Activate article.
                setTimeout(function() {
                    $article.addClass("active");
                    $window.scrollTop(0).triggerHandler("resize.flexbox-fix");
                    setTimeout(function() {
                        locked = false;
                    }, delay);
                }, 25);
            }, delay);
        } else {

            // Mark as visible and show article
            $body.addClass("is-article-visible");
            setTimeout(function() {

                // Hide header, footer and
                // show main and article then activate the article
                $header.hide();
                $footer.hide();
                $main.show();
                $article.show();
                setTimeout(function() {

                    $article.addClass("active");
                    $window.scrollTop(0).triggerHandler("resize.flexbox-fix");
                    setTimeout(function() {
                        locked = false;
                    }, delay);

                }, 25);

            }, delay);
        }
    };

    $main.hideMain = function(addState) {
        var $article = $main_articles.filter(".active");

        // Add state?
        if (typeof addState != "undefined" && addState === true){
            history.pushState(null, null, "#");
        }

        // Handle lock.
        if (locked) {

            // Mark as switching, deactive article
            $body.addClass("is-switching");
            $article.removeClass("active");

            // Hide article and main
            // show footer header and unmark as visible
            $article.hide();
            $main.hide();
            $footer.show();
            $header.show();
            $body.removeClass("is-article-visible");

            locked = false;

            // Unmark as switching
            $body.removeClass("is-switching");
            $window.scrollTop(0).triggerHandler("resize.flexbox-fix");
            return;
        }

        locked = true;

        // Deactivate article and hide article
        $article.removeClass("active");
        setTimeout(function() {

            // Hide article and main, show footer and header
            $article.hide();
            $main.hide();
            $footer.show();
            $header.show();

            // Unmark as visible
            setTimeout(function() {
                $body.removeClass("is-article-visible");
                $window.scrollTop(0).triggerHandler("resize.flexbox-fix");

                setTimeout(function() {
                    locked = false;
                }, delay);
            }, 25);
        }, delay);
    };

    // Articles
    $main_articles.each(function() {
        var articIdx = $(this);

        // Close
        $("<div class='close'>Close</div>")
        .appendTo(articIdx).on("click", function() {
                location.hash = "";
        });

        // Disable click inside articles
        articIdx.on("click", function(event) {
            event.stopPropagation();
        });
    });

    // If in article and clicked outside - hide
    $body.on("click", function(event) {
        if ($body.hasClass("is-article-visible")){
            $main.hideMain(true);
        }
    });

    // Handle hash (in urls)
    $window.on("hashchange", function(event) {

        // If nothing - do nothing, else check for matching articles
        if (location.hash == "" || location.hash == "#") {
            event.preventDefault();
            event.stopPropagation();
            $main.hideMain();
        } else if ($main_articles.filter(location.hash).length > 0) {
            event.preventDefault();
            event.stopPropagation();
            $main.showMain(location.hash.substr(1));
        }
    });

    // Scroll restore to prevent page from jumping on article switch
    if ("scrollRestoration" in history)    {
        history.scrollRestoration = "manual";
    } else {
        var oldScrollPos = 0;
        var    scrollPos = 0;
        var    $htmlbody = $("html,body");

        $window.on("scroll", function() {
            oldScrollPos = scrollPos;
            scrollPos = $htmlbody.scrollTop();
        }).on("hashchange", function() {
            $window.scrollTop(oldScrollPos);
        });
    }

    // Init - show only header and navbar
    // and look if article should be displayed
    $main.hide();
    $main_articles.hide();
    if (location.hash != "" && location.hash != "#")
        $window.on("load", function() {
            $main.showMain(location.hash.substr(1), true);
        });

})(jQuery);