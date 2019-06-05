var mark;
var page = 1;
var outerPane = $('#content');
var timeout = null;
var check = false;
didScroll = false;
function roundRect(ctx, x, y, width, height, radius) {
    if (typeof radius === 'undefined') {
        radius = 5;
    }
    if (typeof radius === 'number') {
        radius = {tl: radius, tr: radius, br: radius, bl: radius};
    } else {
        var defaultRadius = {tl: 0, tr: 0, br: 0, bl: 0};
        for (var side in defaultRadius) {
            radius[side] = radius[side] || defaultRadius[side];
        }
    }
    ctx.beginPath();
    ctx.moveTo(x + radius.tl, y);
    ctx.lineTo(x + width - radius.tr, y);
    ctx.quadraticCurveTo(x + width, y, x + width, y + radius.tr);
    ctx.lineTo(x + width, y + height - radius.br);
    ctx.quadraticCurveTo(x + width, y + height, x + width - radius.br, y + height);
    ctx.lineTo(x + radius.bl, y + height);
    ctx.quadraticCurveTo(x, y + height, x, y + height - radius.bl);
    ctx.lineTo(x, y + radius.tl);
    ctx.quadraticCurveTo(x, y, x + radius.tl, y);
    ctx.closePath();

    return ctx;
}

$(document).ready(function () {
    // Add slideDown animation to Bootstrap dropdown when expanding.
    $('#produkty-menu').hover(function() {
        $(this).dropdown('toggle')
    }, function() {
        $('.dropdown-menu').hover(function () {

        }, function () {
            $('.open').removeClass('open');
            $('.show').removeClass('show');

        });
    });

    $('.site-loader').hide("fade");
    $( "#slider-range" ).slider({
        range: true,
        min: 0,
        max: 80,
        values: (typeof slider_values !== 'undefined')? slider_values : [0,70],
        slide: function( event, ui ) {
            $('#price_slider_controls').css('display', 'flex');
           initSlider();
        },
        start: function (event, ui) {
            initSlider();
        }
    });
    $('.loader')
        .hide()  // Hide it initially
        .ajaxStart(function() {
            $(this).show("fade", 200 );
        })
        .ajaxStop(function() {
                $('.loader').hide('fade',100);
        })
    ;
    $('.populary-product-slider').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        autoplay: true,
        arrows: false,
        autoplaySpeed: 1000,
    });
    $('#product_slider').slick({
        slidesToShow: 3,
        slidesToScroll: 1,

        autoplay: true,

        autoplaySpeed: 3000,

        responsive: [
            {
                breakpoint: 480,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            },
            {
                breakpoint: 1180,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1
                }
            }
        ]
    });

    $('#gallery_slider').slick({

        slidesToShow: 4,
        slidesToScroll: 2,

        autoplay: false,

        autoplaySpeed: 3000,

        responsive: [
            {
                breakpoint: 480,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            },
            {
                breakpoint: 1180,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }
        ]
    });


    if(typeof slider_values !== 'undefined'){
        initSlider();

    }
    if($('#search-input').length){
        $( "#search-input" ).autocomplete({
            source: function( request, response ) {
                $.ajax( {
                    url: base_url+'/search',
                    data: {
                        term: $('#search-input').val()
                    },
                    success: function( data ) {
                        response( data );
                    }
                } );
            },
            minLength: 2,
            select: function( event, ui ) {
                window.location.href = base_url+'/produkt/'+ui.item.macma_id+'/'+ui.item.label;
            }
        } ).autocomplete( "instance" )._renderItem = function( ul, item ) {
            return $( "<li class='search_div col-md-3'>" )
                .append( "<div><img class='search_img w-100' src='"+item.img+"'></div><div class='overlay'><p>" + item.label +"<strong>"+item.price +" zł </strong></p></div> " )
                .appendTo( ul );
        };
    }





// Add slideDown animation to Bootstrap dropdown when expanding.
    if($('#navigation').length){
        h = window.location.href;
        arr = h.split("/");

        if(arr[2]){
            hr = $('<a>', {
                href: base_url + '/produkty',
                class: 'divider my-link',
            }).append('produkty');
            $('#navigation').append(hr);
        }
        if(arr[3]){
            if(arr[3] == 'kategoria'){
                hr = $('<a>', {
                    href: base_url+'/kategoria/'+arr[4],
                    class: 'divider my-link',
                }).append(arr[4]);
                $('#navigation').append(hr);
            }
        }
        if(arr[5]){
            if(arr[5] == 'podkategoria'){
                hr = $('<a>', {
                    href: '#',
                    class: 'divider my-link',
                }).append(arr[6]);
                $('#navigation').append(hr);
            }
        }

    }
    $('#ig_slider').carousel({
       interval: 3500,
    });

    setTimeout(function () {
        $('.loader-container').fadeOut('slow');
    }, 1000);

    var btn = $('#button');
    setPriceInCart();
    AOS.init();
    $(window).scroll(function () {
        if ($(window).scrollTop() != 0) {
            $('.navbar').addClass(' scrolled');
        } else {
            $('.navbar').removeClass(' scrolled');
        }
    });
    $(window).scroll(function () {
        if ($(window).scrollTop() > 1200) {
            btn.addClass('show-button');
        } else {
            btn.removeClass('show-button');
        }
    });
    btn.on('click', function (e) {
        e.preventDefault();
        $('html, body').animate({scrollTop: 0}, '300');
    });
    var handle_from = $("#custom-handle-from");
    var handle_to = $("#custom-handle-to");
    $('#filters').on('show.bs.collapse', function () {
        $('#filter-toogle-text').text('Zwiń panel filtrów');
    });
    $('#filters').on('hidden.bs.collapse', function () {
        $('#filter-toogle-text').text('Otwórz panel filtrów');
    });

    var last_page = parseInt($('#next_page').attr('data-last_page'));

    $('.price-slider').slider({
        range: true,
        min: 0,
        max: 3000,
        values: [$('input[name = "price_from"]').attr('data-value'), $('input[name = "price_to"]').attr('data-value')],
        create: function () {

        },
        slide: function (event, ui) {
            $('input[name = "price_from"]').val(ui.values[0]);
            handle_from.text(ui.values[0]);
            handle_to.text(ui.values[1]);
            $('input[name = "price_to"]').val(ui.values[1]);
        }
    });

    $(window).scroll(function () { //watches scroll of the window
        didScroll = true;
    });
    closeErrors();
    $('#accordion').accordion({
        collapsible: true
    });
    var trigger = $('.hamburger'),
        overlay = $('.overlay'),
        isClosed = false;
    trigger.click(function () {
        hamburger_cross();
    });

    function hamburger_cross() {

        if (isClosed == true) {
            overlay.hide();
            trigger.removeClass('is-open');
            trigger.addClass('is-closed');
            isClosed = false;
        } else {
            overlay.show();
            trigger.removeClass('is-closed');
            trigger.addClass('is-open');
            isClosed = true;
        }
    }

    $('[data-hover = "hover"]').on('hover', function () {

    });
    $('[data-toggle="offcanvas"]').click(function () {
        $('#wrapper').toggleClass('toggled');
    });

    $('.collapsed').on('click', function () {

        var target = $(this).attr('data-target');
        if ($(target).css('display') == 'block') {
            $(target).css('display', 'none');
        } else $(target).css('display', 'block');

    });
    $('#carousel').carousel('pause');
    $('[name = "delivery_id"]').on('change', setPriceInCart);

    $('[name = "quanity"]').on('change', function () {
    });
    $('#filter-form').on('submit', function () {
        $('#duplicate_search').val($('#search-input').val());
    });


    $('.quanity_change_input').on('keyup', function () {
        var that = this;
        if (timeout !== null) {
            clearTimeout(timeout);
        }
        timeout = setTimeout(function () {
            $('#'+$(that).attr('data-target')).val($(that).val());
            $('#refresh_cart').submit();
        }, 1500, that);
    });


    $('.payment_type').on('click', function () {
        $('.payment_type').each(function (index, elem) {
            $(elem).removeClass('act');
        });
        $('#payment_type').val($(this).attr('data-payment_val'));
        $(this).addClass('act');
    });



});
setInterval(function() {
    if (didScroll){
        didScroll = false;
        if(($(document).height()-$(window).height())-$(window).scrollTop() < 600){
            pageCountUpdate();
        }
    }
}, 250);
var setPriceInCart = function () {
    var items_price = parseFloat($('[name = "delivery_id"]').attr('data-totalPrice'));
    totalPrice = items_price + parseFloat($('[name = "delivery_id"]').find(':selected').attr('data-price'));
    $('#totalPrice').text(parseFloat(totalPrice).toFixed(2));
};
function quick_view(link, e) {
    e.preventDefault();
    $.get( base_url+'/quick_view/'+$(link).attr("data-product_id")+'/'+$(link).attr('data-slug'), function( data ) {
        $('#product_view').replaceWith(data);
        $('#product_view').modal();


    });
}

function submit_form(elem, e) {
    e.preventDefault();
    var my_form = new FormData(elem);
    var form = this;
    $.ajax({
        method: 'POST',
        url: $(elem).attr('action'),
        data: my_form,
        processData: false,
        contentType: false,
    }).done(function (msg) {

        window.location.href = base_url+'/koszyk';

    })
}
function pageCountUpdate(){
    if(parseInt($('#page').val()) % 2 != 0 || check == true) {
        var page = parseInt($('#page').val());
        var max_page = parseInt($('#max_page').val());
        check = false;
        if (page < max_page) {
            $('#page').val(page + 1);
            getPosts();
            $('#end_of_page').hide();
        } else {
            $('#end_of_page').fadeIn();
        }
    } else {
    }
}
//Ajax call to get your new posts
function getPosts(){
    $.ajax({
        type: "GET",
        url: (function (data) {
            if(window.location.href.indexOf('?') != -1){
                return window.location.href+'&page='+$('#page').val();
            }else{
                return window.location.href+'?page='+$('#page').val();
            }

        })(),

        //window.location.href+'?page='+$('#page').val(),
        beforeSend: function(){ //This is your loading message ADD AN ID
        },
        complete: function(){ //remove the loading message
        },
        success: function(html) { // success! YAY!! Add HTML to content container
            $('#content').append(html);
            AOS.init();
        }
    });



} //end of getPosts function
function showOrHide(elem) {
    $( elem ).hide( "drop", { direction: "down" }, "slow" );
    if($(elem).css('display') == 'none'){
        $(elem).hide();
    } else {
        $(elem).show();
    }


}
function checkbox(elem) {
    if($(elem).prop('checked') === true){
        $(elem).parent().attr('class', 'btn-warning');
        $(elem).val(0);
        $(elem).prop('checked', false);
    } else {
        $(elem).val(1);
        $(elem).prop('checked', true);
        $(elem).parent().attr('class', 'btn-primary');
    }
}
function refresh(elem) {
    $('input[name = "brand"]').each(function (index) {
        if($(this)[0] == elem[0]){

        }else{
            $(this).prop('checked', false);
            $(this).removeAttr('checked');
        }
    })
}

/*$('select').each(function(){
   if($(this).attr('name') != 'delivery_id'){


    var $this = $(this), numberOfOptions = $(this).children('option').length;

    $this.addClass('select-hidden');
    $this.wrap('<div class="select"></div>');
    $this.after('<div class="select-styled"></div>');

    var $styledSelect = $this.next('div.select-styled');
    $styledSelect.text($this.children('option').eq(0).text());

    var $list = $('<ul />', {
        'class': 'select-options'
    }).insertAfter($styledSelect);

    for (var i = 0; i < numberOfOptions; i++) {
        $('<li />', {
            text: $this.children('option').eq(i).text(),
            rel: $this.children('option').eq(i).val()
        }).appendTo($list);
    }

    var $listItems = $list.children('li');

    $styledSelect.click(function(e) {
        e.stopPropagation();
        $('div.select-styled.active').not(this).each(function(){
            $(this).removeClass('active').next('ul.select-options').hide();
        });
        $(this).toggleClass('active').next('ul.select-options').toggle();
    });

    $listItems.click(function(e) {
        e.stopPropagation();
        $styledSelect.text($(this).text()).removeClass('active');
        $this.val($(this).attr('rel'));
        $list.hide();
        //console.log($this.val());
    });

    $(document).click(function() {
        $styledSelect.removeClass('active');
        $list.hide();
    });
    }
});*/


function get_item_Signs(item) {
    $.ajax({
        method: 'GET',
        url: base_url+'/marks_modal/'+item,
        success: function (data) {
            if(typeof(data) == "object"){
                $('.errors').addClass('active-errors');
                $('.errors').text('');
                $('.errors').append("<p>"+data['errors']+"</p>");
                closeErrors();
            }else {
                $('#marks_modal').replaceWith(data);
                $('#marks_modal').modal();
            }

        },
    });

}

function chose_mark(elem) {
    mark = $(elem).attr('data-mark_id');
    mark = $('#mark_id_input').val(mark);

    $('.single-mark').each(function (index, elem) {

        $(elem).removeClass('chosen');
    });
    $(elem).addClass('chosen');
    $('#add_file').removeClass('hide');
}

 function sendform(elem, e) {
    e.preventDefault();
    var my_form = new FormData(elem);
    var form = this;
    $.ajax({
        method: 'POST',
        url: $(elem).attr('action'),
        data: my_form,
        processData: false,
        contentType: false,
    }).done(function (msg) {
        $('#product_view').modal('hide');
        $('#thanks_modal').replaceWith(msg);
        $('#thanks_modal').modal();

    });
}

function isJson(str) {
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }
    return true;
}

function closeErrors() {
    setTimeout(function() {
        $(".errors").hide('Fade', null, 300);
        $(".errors").removeClass('active-errors');
    }, 5000);
}

function getProductColors(product_id) {
    $.ajax({
        method: 'GET',
        url: base_url+'/get_colours/'+product_id,
        success: function (data) {
            if(typeof(data) == "object"){
                $('.errors').addClass('active-errors');
                $('.errors').text('');
                $('.errors').append("<p>"+data['errors']+"</p>");
                closeErrors();
            }else {
                $('#colours_modal').replaceWith(data);
                $('#colours_modal').modal();
            }

        },
    });
}

function change_colour(elem, colour, hex) {
    $('#colour_input').val(colour);
    $('#colour_hex_input').val(hex);
    $('#change_colour_form').submit();
}

function show_more_products(elem) {
    check = true;
    $(elem).remove();
    pageCountUpdate();
    check = false;
}

function is_dark_color(c) {
    var c = c.substring(1);
    var rgb = parseInt(c, 16);
    var r = (rgb >> 16) & 0xff;
    var g = (rgb >>  8) & 0xff;
    var b = (rgb >>  0) & 0xff;

    var luma = 0.2126 * r + 0.7152 * g + 0.0722 * b;

    if (luma < 150) {
        return true;
    }
};

function change_image(elem) {
    $(elem).parent().next().show();
}

function removeParams(sParam)
{
    var url = window.location.href.split('?')[0]+'?';
    var sPageURL = decodeURIComponent(window.location.search.substring(1)),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;
    console.log(sURLVariables);
    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');
        if (sParameterName[0] != sParam) {
            url = url + sParameterName[0] + '=' + sParameterName[1] + '&'
        }
    }
    return url.substring(0,url.length-1);
}


$(document).ready(function(){
    var radius = 600;
    var fields = $('.itemDot');
    var container = $('.dotCircle');
    var width = container.width();
    radius = width/2;

    var height = container.height();
    var angle = 0, step = (2*Math.PI) / fields.length;
    fields.each(function() {
        var x = Math.round(width/2 + radius * Math.cos(angle) - $(this).width()/2);
        var y = Math.round(height/2 + radius * Math.sin(angle) - $(this).height()/2);
        if(window.console) {
        }

        $(this).css({
            left: x + 'px',
            top: y + 'px'
        });
        angle += step;
    });


    $('.itemDot').click(function(){

        var dataTab= $(this).data("tab");
        $('.itemDot').removeClass('active');
        $(this).addClass('active');
        $('.CirItem').removeClass('active');
        $( '.CirItem'+ dataTab).addClass('active');
        i=dataTab;

        $('.dotCircle').css({
            "transform":"rotate("+(360-(i-1)*36)+"deg)",
            "transition":"2s"
        });
        $('.itemDot').css({
            "transform":"rotate("+((i-1)*36)+"deg)",
            "transition":"1s"
        });


    });


});

function zoom(e){
    var zoomer = e.currentTarget;
    e.offsetX ? offsetX = e.offsetX : offsetX = e.touches[0].pageX
    e.offsetY ? offsetY = e.offsetY : offsetX = e.touches[0].pageX
    x = offsetX/zoomer.offsetWidth*100
    y = offsetY/zoomer.offsetHeight*100
    zoomer.style.backgroundPosition = x + '% ' + y + '%';
}

function initSlider() {
    $('#price_from_input').val($('#slider-range').slider('option', 'values')[0]);
    $('#price_to_input').val($('#slider-range').slider('option', 'values')[1]);
}

function updateSlider() {
    $('#slider-range').slider('option', 'values', [$('#price_from_input').val(), $('#price_to_input').val()]);

}

function deletePriceQuery() {
    var output = remove
}
function send_favourite(elem, e) {
    e.preventDefault();
    var my_form = new FormData(elem);
    var form = this;
    $.ajax({
        method: 'POST',
        url: $(elem).attr('action'),
        data: my_form,
        processData: false,
        contentType: false,
    }).done(function (msg) {
        if($('#saved').index() != -1){
            $('#saved').replaceWith(msg);
        }else{
            $('.nav').append(msg);

        }
    });
}

function copyLink(elem, event) {
    event.preventDefault();
    const el = document.createElement('textarea');
    el.value = $(elem).attr('href');
    document.body.appendChild(el);
    el.select();
    document.execCommand('copy');
    document.body.removeChild(el);
    alert('Skopiowano link');
}
function drawEllipseByCenter(ctx, cx, cy, w, h) {
    return this.drawEllipse(ctx, cx - w/2.0, cy - h/2.0, w, h);
}
function drawEllipse(ctx, x, y, w, h) {
    var kappa = .5522848,
        ox = (w / 2) * kappa, // control point offset horizontal
        oy = (h / 2) * kappa, // control point offset vertical
        xe = x + w,           // x-end
        ye = y + h,           // y-end
        xm = x + w / 2,       // x-middle
        ym = y + h / 2;       // y-middle

    ctx.beginPath();
    ctx.moveTo(x, ym);
    ctx.bezierCurveTo(x, ym - oy, xm - ox, y, xm, y);
    ctx.bezierCurveTo(xm + ox, y, xe, ym - oy, xe, ym);
    ctx.bezierCurveTo(xe, ym + oy, xm + ox, ye, xm, ye);
    ctx.bezierCurveTo(xm - ox, ye, x, ym + oy, x, ym);
    return ctx;
}
function dataURItoBlob(dataURI) {
    // convert base64/URLEncoded data component to raw binary data held in a string
    var byteString;
    if (dataURI.split(',')[0].indexOf('base64') >= 0)
        byteString = atob(dataURI.split(',')[1]);
    else
        byteString = unescape(dataURI.split(',')[1]);

    // separate out the mime component
    var mimeString = dataURI.split(',')[0].split(':')[1].split(';')[0];

    // write the bytes of the string to a typed array
    var ia = new Uint8Array(byteString.length);
    for (var i = 0; i < byteString.length; i++) {
        ia[i] = byteString.charCodeAt(i);
    }

    return new Blob([ia], {type:mimeString});
}