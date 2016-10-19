$(document).ready(function () {

    /***    Animazione Logo maxeo.it        ***/
    function fadeLogo(tTr) {
        if (tTr == undefined)   //non capisco perchè safari non prenda il valore predefinito nella funzione
            tTr = 4;
        $(".fadelogo").css({
            transition: 'all ' + tTr + 's ease 0s',
            visibility: 'visible',
            opacity: 1
        });
    }
    var animationState = 0;
    if ($(".logo").hasClass("animation")) {
        $(".fadelogo").css("opacity", 0);
        if (window.screen.width > 620 && !(window.navigator.userAgent.indexOf("Edge") > -1)) {
            $(".rollinglogo img").css("opacity", 1).css("transform", "rotate(900deg)");
            setTimeout(function () {
                fadeLogo()
                animationState++;
            }, 4000);
        } else {
            $(".rollinglogo img").css("display", "none")
            setTimeout(function () {
                fadeLogo()
                animationState++;
            }, 500);
        }
    } else {
        $(".fadelogo").css("opacity", 0);
        setTimeout(function () {
            fadeLogo(6)
        }, 500);
    }
    $(window).on("orientationchange", function (event) {    //fix per cambio orientamento
        if (animationState > 0)
            $(".rollinglogo img").css("display", "none")
        else
            $(".rollinglogo img").css("opacity", 0)
        $(".fadelogo").css("opacity", 0);
        setTimeout(function () {
            fadeLogo(6)
        }, 500);
    });




    /***    Fix Safari        ***/

    if (window.navigator.userAgent.indexOf("Safari") > -1) {
        loadPercent();
    }


    /***    Animazione percentuali        ***/
    function updatePercent(selector, percent) {
        selector.get(0).style.strokeDasharray = (percent * 4.65) + ' 1000';
    }
    function loadPercent() {
        $('.progress-circle-prog').each(function () {
            /***   Fix iPad   ***/
            if (window.navigator.userAgent.indexOf("iPad") > 1) {
                $(".progress-text").css("top", "-150px");
            }
            $(this).css("stroke", "#" + $(this).attr("data-color"));
            updatePercent($(this), $(this).attr("data-perc"));
        })
    }
    function isScrolledIntoView(elem)   //verifica se l'elemento è sato letto
    {
        var docViewTop = $(window).scrollTop();
        var docViewBottom = docViewTop + $(window).height();
        var elemTop = $(elem).first().offset().top;
        var elemBottom = elemTop + $(elem).first().height();
        for (i = 0; i < $(elem).length; i++) {
            elemN = $(elem + ":eq(" + i + ")")
            elemTop = elemN.offset().top;
            elemBottom = elemTop + elemN.height();
            if ((elemBottom <= docViewBottom) && (elemTop >= docViewTop))
                return true;
        }
    }

    var navHeight = parseInt($("#nav").css("height"));
    var perc_are_loaded = 0;
    $(document).bind("scroll", function () {
        if (perc_are_loaded == 0 && $('.progress-circle-prog').is(':visible') && isScrolledIntoView(".progress-circle-prog")) {
            perc_are_loaded = 1;
            setTimeout(function () {
                loadPercent();
            }, 500);
        }
        /***    Scorrimento nav vesioni Desktop       ***/
        var t1,t2,t3,t4;
        if (t1=($(document).scrollTop() > $(".main").offset() - navHeight) && $("#main").height()+$(".main").offset().top<$(document).scrollTop()) {
            $("#nav").css({
                position: 'fixed',
                top: 0,
                "z-index": 10000
            });
        } else {
            $("#nav").css({
                position: 'absolute',
                top: "auto"
            });
        }
        /***    Scorrimento in alto per cellulari        ***/

        if ($("#nav").css("display") == "none")
            if ($(document).scrollTop() > $(".main").offset().top)
                $("#upArrow").css("display", "block");
            else
                $("#upArrow").css("display", "none");
    });


    /***    Scrolltop sugli elementi del menu        ***/


    $(".nav_section, .to_target").click(function () {
        elem = $("#" + $(this).attr("href").substr(1));
        dis = elem.offset().top
        if ($(this).hasClass("to_target") && $("#nav").css("display") == "block")
            dis -= parseInt($("#nav").css("height"));

        $("html, body").animate({scrollTop: dis + "px"},
                500);
        return false;
    })

    $("#upArrow").click(function () {
        valDef = $(".main").offset().top;
        $("html, body").animate({scrollTop: valDef + "px"}, 500);
    })

//    if($(".showAdblockStatus").is(':visible')==false)
//        alert("adblock attivo")

    /***    Protezione mail spamlist        ***/
    $(".mailBoxm img").css("display", "none")
    $(".mailBoxm a").text("inf" + "o" + "@" + "maxeo" + ".it").attr("href", "mailto:inf" + "o" + "@" + "maxeo" + ".it");

})