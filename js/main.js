function isJson(str) {
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }
    return true;
}

(function () {
    var langType = getCookie('lang');

    var lang;

    if(langType == 'ar'){ // set lang in tables arabic

        lang = {
            quantity_instock: "الكمية الموجودة حاليا هي",
        }
    }else if(langType == 'en'){

        lang = {
            quantity_instock: "The current quantity is",
        }

    }
    window.lang = lang;
}());



var pathname = window.location.pathname.replace(/\/+$/,'');
pathname = pathname.replace(/^\/+/,'').toLowerCase();

// variables
function sound(src) {
    this.sound = document.createElement("audio");
    this.sound.src = src;
    this.sound.setAttribute("preload", "auto");
    this.sound.setAttribute("controls", "none");
    this.sound.style.display = "none";
    document.body.appendChild(this.sound);
    this.play = function(){
        this.sound.play();
    }
    this.stop = function(){
        this.sound.pause();
    }
}



function removeClass($elm,$class)
{
    if($elm.hasClass($class))
    {
        $elm.removeClass($class)
    }
}

function Q(q) {
    return document.querySelector(q);
}
var dirCssHtml = $('html').attr('dir');

// function load script

$.loadScript = function (url, callback) {
    $.ajax({
        url: url,
        dataType: 'script',
        success: callback,
        async: true
    });
}


// function cookies


function setCookie(name,value,days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days*24*60*60*1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "")  + expires + "; path=/";
}
function getCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}
function eraseCookie(name) {
    document.cookie = name+'=; Max-Age=-99999999;';
}

// end function cookies
var xhttp;
if (window.XMLHttpRequest) {
    // code for modern browsers
    xhttp = new XMLHttpRequest();
} else {
    // code for old IE browsers
    xhttp = new ActiveXObject("Microsoft.XMLHTTP");
}

xhttp.onreadystatechange = function () {
    if (this.readyState == 4 & this.status == 200) {
        var res = xhttp.responseText;
        if (isJson(res)) {
            window.Settings = JSON.parse(res);
        }else{
            window.Settings = '';
        }
    }

}
xhttp.open("GET", '/json/settings', false);
xhttp.setRequestHeader("Content-type", "application/json; charset=utf-8");
xhttp.send();

function getSettings(name) {
    if(Settings != undefined) {

        if (Settings[name] != '') {

            return Settings[name];
        }
    }

}


// functions settings



// end functions settings


$(document).ready(function () { // remove class DBIul in active set css display block in .child-mune
    var i = 0;
    var MyI = setInterval(function (){
        if(i == 1){
            $('.DBIul .child-menu').css('display','block');
            $('.DBIul').removeClass('DBIul').addClass('Her');
            clearInterval(MyI);
        }else{
            i++;
        }

    } , 10);
});


$('.menu > li > a').click(function(e) {
    if($(this).parent().hasClass('Her')){
        e.preventDefault();
    }
    $(this).parent().addClass('active').siblings().removeClass('active');
    $(this).next().slideToggle().end().parent().siblings().find('.child-menu').slideUp();
});


$('.icon-to-down').click(function (e) {
    e.preventDefault();
    $(this).parent().children('.child-menu').slideToggle();
});





// slid menu


var media = {
    max_sm : 767.98,
};

window.windowislarger = window.innerWidth > media.max_sm;

$(window).resize(function () {
    window.windowislarger = window.innerWidth > media.max_sm;
});

$('.control-menu').click(function toggleMenu() {
    var am = $(this);
    var sideMenu = $('.side-menu');
    var sideWidth = sideMenu.innerWidth();
    var upMenuBody = $('.up-menu , body');

    if(dirCssHtml == 'ltr'){
        if(am.hasClass('fa-chevron-left')){
            sideMenu.animate({left: - sideWidth},500);
            if(window.windowislarger)
            {
                upMenuBody.animate({width: '100%'},500);
            }
            am.toggleClass('fa-chevron-left fa-chevron-right');
            setCookie('sideMenu','close',7)
        }else {
            sideMenu.animate({left: 0},500);
            if(window.windowislarger) {
                upMenuBody.animate({width: '82%'}, 500);
            }
            am.toggleClass('fa-chevron-left fa-chevron-right');
            setCookie('sideMenu','open',7)
        }


    //    set lang in tables
    }else{
        if(am.hasClass('fa-chevron-left')){
            sideMenu.animate({right: - sideWidth},500);
            if(window.windowislarger) {
                upMenuBody.animate({width: '100%'},500);
            }
            am.toggleClass('fa-chevron-left fa-chevron-right');
            setCookie('sideMenu','close',7)
        }else {
            sideMenu.animate({right: 0},500);
            if(window.windowislarger) {
                upMenuBody.animate({width: '82%'},500);
            }
            am.toggleClass('fa-chevron-left fa-chevron-right');
            setCookie('sideMenu','open',7)
        }
    }


});


if(getCookie('sideMenu') == 'close'){
    $('.side-menu').addClass('close');
    $('body , .up-menu').css('width', '100%');
    // if(dirCssHtml == 'ltr'){
    $('.control-menu').toggleClass('fa-chevron-left fa-chevron-right')
    // }
}


var TableLang;
if ($('table').length > 0 && getSettings('setting-site') != ''){
    if(dirCssHtml == 'rtl'){ // set lang in tables arabic

        TableLang = {
            pageLength : Number(getSettings('setting-site').TableRows),
            "language": {
                "lengthMenu":  "عرض _MENU_ سجلات لكل صفحة",
                "zeroRecords": "لم يتم العثور على شيء - آسف",
                "info": "عرض الصفحة _PAGE_ من _PAGES_",
                "infoEmpty": "لا توجد سجلات متاحة",
                "infoFiltered": "(تم التصفية من _MAX_ مجموع السجلات)",
                "decimal": ",",
                "thousands": ".",
                "sSearch": '',
                'searchPlaceholder': "بحث ...",
                "sProcessing": "يرجى الانتظار...",
                "sInfoEmpty": "الجدول فارغ",

                "oPaginate": {
                    "sFirst":    	"أولا",
                    "sPrevious": 	"السابق",
                    "sNext":     	"التالي",
                    "sLast":     	"آخر"
                },

            }
        };
    }else{

        TableLang = {
            pageLength : Number(getSettings('setting-site').TableRows),
            "language": {
                "lengthMenu": "Display _MENU_ records per page",
                "zeroRecords": "Nothing found - sorry",
                "info": "Showing page _PAGE_ of _PAGES_",
                "infoEmpty": "No records available",
                "infoFiltered": "(filtered from _MAX_ total records)",
                "sSearch": "",
                "decimal": ",",
                "thousands": ".",
                'searchPlaceholder': "Search ...",
                "sProcessing": "Please Wait...",
                "sInfoEmpty": "Table is Empty",
                "oPaginate": {
                    "sFirst":    	"First",
                    "sPrevious": 	"Previous",
                    "sNext":     	"Next",
                    "sLast":     	"Last"
                },

            }
        };


    }
}


if(pathname == "settings/backupdatabase" && $('.dataTableEnable').length > 0)
{
    TableLang.order = [[ 2, "desc" ]];
}

if($('.dataTableEnable').length > 0)
{

    $('.dataTableEnable').DataTable(TableLang);

}



function ajaxUserauthData(element,page,column,errorData = 0,isEdite = false) {
    element.on('blur', function () {
        var am = $(this);
        if (!$(this).parent().hasClass('sign-error')) {
            var xhttp;
            if (window.XMLHttpRequest) {
                // code for modern browsers
                xhttp = new XMLHttpRequest();
            } else {
                // code for old IE browsers
                xhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }

            xhttp.onreadystatechange = function () {
                if (this.readyState < 4) {
                    am.parent().addClass('sign-spinner');
                }
                if (this.readyState == 4 & this.status == 200) {
                    var res = xhttp.responseText;
                    if (res == errorData) {
                        am.parent().addClass('sign-error');
                    }
                    am.parent().removeClass('sign-spinner');

                }
            }

            var send = column + "=" + $(this).val();

            if(isEdite == true)
            {
                send = column + "=" + $(this).val() + "&" + 'valueOld=' + $(this).attr('data-value');
            }

            xhttp.open("POST", page, false);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.send(send);
        }
    });
}

// ajaxUserauthData($(".users-edite input[name='username']"), '/Users/Exist', 'username');
//
// ajaxUserauthData($(".users-edite input[name='password']"), '/users/exist', 'password');

// Ajax pages Create

ajaxUserauthData($(".users-create input[name='email']"), '/users/exist', 'email', 1);

ajaxUserauthData($(".form-login input[name='username']"), '/users/exist', 'username');

ajaxUserauthData($(".users-create input[name='phone']"), '/users/exist', 'phone', 1);

ajaxUserauthData($(".users-create input[name='username']"), '/Users/Exist', 'username', 1);

ajaxUserauthData($(".group-create input[name='group_name']"), '/Groups/Exist', 'GroupName', 1);

ajaxUserauthData($(".permissions-create input[name='name']"), '/Permissions/Exist', 'name', 1);

ajaxUserauthData($(".permissions-create input[name='permission']"), '/Permissions/Exist', 'permission', 1);

ajaxUserauthData($(".suppliers-create input[name='email']"), '/suppliers/exist', 'email', 1);

ajaxUserauthData($(".suppliers-create input[name='phone']"), '/suppliers/exist', 'phone', 1);

ajaxUserauthData($(".products-categories-create input[name='name']"), '/ProductsCategories/Exist', 'name', 1);

/// Ajax Pages edit

ajaxUserauthData($(".users-edit input[name='email']"), '/users/exist', 'email', 1, true);

ajaxUserauthData($(".users-edit input[name='phone']"), '/users/exist', 'phone', 1, true);

ajaxUserauthData($(".users-edit input[name='username']"), '/Users/Exist', 'username', 1, true);

ajaxUserauthData($(".group-edit input[name='group_name']"), '/Groups/Exist', 'GroupName', 1,true);

ajaxUserauthData($(".permissions-edit input[name='name']"), '/Permissions/Exist', 'name', 1,true);

ajaxUserauthData($(".permissions-edit input[name='permission']"), '/Permissions/Exist', 'permission', 1,true);

ajaxUserauthData($(".products-categories-edit input[name='name']"), '/ProductsCategories/Exist', 'name', 1,true);





// inputs add attr data value

$(".products-categories-edit input[name='name'], .permissions-edit input[name='permission'],.permissions-edit input[name='name'],.users-edit input[name='email'],.users-edit input[name='phone'],.users-edit input[name='username'],.group-edit input[name='group_name']").each(function () {
    $(this).attr('data-value',$(this).val());
});


$('.users-create .input-group-s select[name="groupid"]').on('change', function () {
    var am = $(this);
    var xhttp;
    if (window.XMLHttpRequest) {
        // code for modern browsers
        xhttp = new XMLHttpRequest();
    } else {
        // code for old IE browsers
        xhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }

    xhttp.onreadystatechange = function () {
        if (this.readyState < 4) {

        }
        if (this.readyState == 4 & this.status == 200) {
            var res = xhttp.responseText;
            res = res.split('|');
            $(".users-create input[name='permission[]']").each(function () {
                if(res.indexOf($(this).val()) != -1)
                {
                    $(this).click();
                }else if($(this).is(':checked') && res.indexOf($(this).val()) == -1){
                    $(this).click();
                }
            });
        }
    }

    xhttp.open("POST", '/Users/getPermGroup', false);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send('GroupId='+$(this).val());
});

$('.users-create .input-group-s select[name="groupid"] option:selected').each(function () {
    var am = $(this);
    var xhttp;
    if (window.XMLHttpRequest) {
        // code for modern browsers
        xhttp = new XMLHttpRequest();
    } else {
        // code for old IE browsers
        xhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }

    xhttp.onreadystatechange = function () {
        if (this.readyState < 4) {

        }
        if (this.readyState == 4 & this.status == 200) {
            var res = xhttp.responseText;
            res = res.split('|');
            $(".users-create input[name='permission[]']").each(function () {
                if(res.indexOf($(this).val()) != -1 && !$(this).is(':checked'))
                {
                    $(this).click();
                }else if(res.indexOf($(this).val()) == -1 && $(this).is(':checked')){
                    $(this).click();
                }

            });
        }
    }

    xhttp.open("POST", '/Users/getPermGroup', false);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send('GroupId='+$(this).val());
});

var valueOld = $('.input-group-s select[name="groupid"] option:selected').val();

$('.users-edit .input-group-s select[name="groupid"]').on('change', function () {
    var am = $(this);
    var xhttp;
    if (window.XMLHttpRequest) {
        // code for modern browsers
        xhttp = new XMLHttpRequest();
    } else {
        // code for old IE browsers
        xhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }

    xhttp.onreadystatechange = function () {
        if (this.readyState < 4) {

        }
        if (this.readyState == 4 & this.status == 200) {
            var res = xhttp.responseText;
            res = res.split('|');
            $(".users-edit input[name='permission[]']").each(function () {
                if(res.indexOf($(this).val()) != -1 && !$(this).is(':checked'))
                {
                    $(this).click();
                }else if(res.indexOf($(this).val()) == -1 && $(this).is(':checked')){
                    $(this).click();
                }
            });

        }
    }

    var url_string = window.location.href; //window.location.href
    var url = new URL(url_string);
    var id = url.searchParams.get("id");

    if($(this).val() == valueOld)
    {
        var send = "UserId="+id;
    }else{
        var send = 'GroupId='+$(this).val();
    }


    xhttp.open("POST", '/Users/getPermGroup', false);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send(send);
});

// cards

$('.card-category').click(function () {
    var is_open_all;
    $(this).find('p').toggle();

    $('.card-category').each(function () {

        if($(this).find('p').css('display') != 'none')
        {
            is_open_all = true;
            if($('.card-control-view span:first-child').hasClass('card-active')){
                $('.card-control-view span:first-child').removeClass('card-active');
            }else{
                $('.card-control-view span:last-child').addClass('card-active');
            }
        }else{
            is_open_all = false;
            if($('.card-control-view span:last-child').hasClass('card-active')){

                $('.card-control-view span:last-child').removeClass('card-active');
            }else{
                $('.card-control-view span:first-child').addClass('card-active');
            }        }
    });
    if(is_open_all == true)
    {
        setCookie('card-view','full',30);
    }else{

        setCookie('card-view','classic',30);
    }
});

$('.card-control-view span:first-child').click(function () {
    setCookie('card-view','classic',30);
    $(this).parents('.card').find('.card-body .card-category p').hide();
    $(this).addClass('card-active').siblings().removeClass('card-active');
});

$('.card-control-view span:last-child').click(function () {
    setCookie('card-view','full',30);
    $(this).parents('.card').find('.card-body .card-category p').show();
    $(this).addClass('card-active').siblings().removeClass('card-active');
});

if(getCookie('card-view') == 'classic')
{
    $('.card-body .card-category p').each(function () {
        $(this).hide();
    });
    $('.card-control-view span:first-child').addClass('card-active');
}else{
    $('.card-body .card-category p').each(function () {
        $(this).show();
    });
    $('.card-control-view span:last-child').addClass('card-active');
}

// stop Propagation in card

$('.card-category h3, .card-category p , .card-category .card-control').click(function (e) {
    e.stopPropagation()
});

$('.card-category').each(function () {
    if($(this).find('p').text().trim().length > 150)
    {
        $(this).find('p').html($(this).find('p').text().substring(0,150) + '<span title="'+ $(this).find('p').text().substring(150,-1) +'"> ... </span>');
    }
});

// drop mune user options


$('.profile-cog').click(function (e) {
    e.stopPropagation();
    $(this).find('.U-menu').fadeToggle(100);
});

$('.notifications').click(function (e) {
    e.stopPropagation();
    $(this).find('.notification-box').fadeToggle(100);
    if (getCookie('Notifications') != '')
    {
        // setCookie('Notifications',0,0);
        // $(this).find('.count-not').remove();
    }
});


$('.notifications , .profile-cog').click(function (e) {
    e.stopPropagation();
    var $this = $(this);
    var h = $('.notifications , .profile-cog');
    $('.notifications , .profile-cog').each(function () {
        if(!$this.is($(this)))
        {

            $(this).find('.box-toggle').fadeOut(100);
        }

    });
    // $('.U-menu ,.notification-box').fadeOut(100);
    // $(this).fadeIn();
});

$('.U-menu ,.notification-box').click(function (e) {

    e.stopPropagation();
});


$('body,html').click(function () {
    $('.U-menu ,.notification-box').fadeOut(100);
});


$('.input-image-profile').click(function () {
    $('.form-image').css('display','block');
    $('.overlay').css('display','block');

});


$('.overlay').click(function () {
    $('.form-image').css('display','none');
    $('.overlay').css('display','none');
});


// croppit start

if($('.image-editor').length > 0){
    $(function() {
        $('.image-editor').cropit();
        $('.image-editor').cropit('imageSrc', $('.input-image-profile img').attr('src'));

        $('.form-image').submit(function() {
            // Move cropped image data to hidden input
            var imageData = $('.image-editor').cropit('export');
            $('.hidden-image-data').val(imageData);

            // Print HTTP request params
            return true;
        });
    });
}



// tric products

var product = function (e) {
    var product_quantity = $(this).find('option:selected').data('quantity');
    var product_quantity_name = $(this).find('option:selected').data('quantity-name');
    var product_name = $(this).find('option:selected').text();
    var product_select = $(this).find('option:selected');
    var product_id = $(this).val();
    var price_piece = $(this).find('option:selected').data('price');
    var is_disabled = $(this).find('option:selected').is(':disabled');
    var product_element = document.querySelector('.action-product');
    var error = false;

    (function () {
        var $length_input = $('.action-product').find('input[type="hidden"]').length;
        var $inputs = $('.action-product').find('input[type="hidden"]');
        for(i=0;i<$length_input;i++)
        {
            if($inputs.eq(i).val() == product_id)
            {
                error = true;
            }
        }
    }());


    if(is_disabled || error ) return;
    if($('.action-product').hasClass('d-none')) {
        $('.action-product').removeClass('d-none');
        $('.action-product .name .name-label').text(product_name);
        $('.action-product .quantity input').val('').siblings('label').removeClass('is_focus');
        removeClass($('.action-product .quantity'),'updated');
        $('.action-product .price input').val(price_piece).siblings('label').addClass('is_focus');
        $('.action-product .name input').val(product_id);
        $.loadScript('/js/validinputs.js', function () {});
    }
    else if($('.action-product .sign-error').length == 0) {
        $.loadScript('/js/validinputs.js', function () {});

        var clone_p = product_element.cloneNode(true);

        clone_p.setAttribute('id', 'action-product');
        product_element.setAttribute('id', '');
        document.querySelector('.action-product').before(clone_p);
        $('#action-product .name .name-label').text(product_name);
        $('#action-product .name input').val(product_id);
        $('#action-product .quantity-title').remove();
        removeClass($('#action-product .quantity'),'updated');
        $('#action-product .quantity input').val('').siblings('label').removeClass('is_focus');
        $('#action-product .quantity .quantity_name').remove();
        $('#action-product .price input').val(price_piece).siblings('label').addClass('is_focus');
    }
    if($('.actions-product-btn').hasClass('d-none')) {
        $('.actions-product-btn').removeClass('d-none');
    }



    (function () {

        var $length_inputs_valid = $('.action-product').find('input').length;

        var $inputs_valid = $('.action-product').find('input');

        for(i=0;i < $length_inputs_valid;i++)
        {
            var pattern_input = $inputs_valid.eq(i).attr('data-pattern');
            var regx = new RegExp(pattern_input);
            var is_valid = regx.test($inputs_valid.eq(i).val());
            if(!is_valid ){
                $inputs_valid.eq(i).parent().addClass('sign-error').removeClass('sign-success');
            }else{
                $inputs_valid.eq(i).parent().removeClass('sign-error');
            }

        }


    }());


    (function () {
        var product     = $('.sales-invoice-create .action-product:not(.d-none) input[name="product_id[]"],.sales-invoice-edit .action-product:not(.d-none) input[name="product_id[]"]');

        product.each(function () {
            var p = product_select.val();
            var obj = $(this).val();
            var q = $(this).parent().siblings('.quantity:not(.updated)');
            if(p === obj)
            {

                $(this).parents('.input-group-s').siblings('.quantity').find('label').append('<bdi class="quantity_name"> ( '+product_quantity_name+' ) </bdi>');
                $(this).parents('.input-group-s').siblings('.quantity').find('label').after('<span class="quantity-title" data-bottom-title-quantity-product="'+ window.lang.quantity_instock + " " + Number(product_quantity) + ' ' + product_quantity_name +'"></span>');

                var is_valid = false;
                var val;
                q.on('keyup',function () {
                    val = $(this).find('input').val();
                    is_valid = Number(product_quantity) >= val;
                    if(!is_valid || !$.isNumeric(val)){
                        $(this).addClass('sign-error').removeClass('sign-success');
                    }else{
                        $(this).addClass('sign-success').removeClass('sign-error');
                    }
                });
            }
            // q.each(function () {
            //     val = $(this).find('input').val();
            //     is_valid = product_quantity >= val;
            //     if(!is_valid || !$.isNumeric(val)){
            //         $(this).addClass('sign-error').removeClass('sign-success');
            //     }else{
            //         $(this).removeClass('sign-error');
            //     }
            // });

        })
    }());

    // (function () {
    //     var product_pur_crea     = $('.sales-invoice-edit .action-product:not(.d-none) input[name="product_id[]"]');
    //
    //     product_pur_crea.each(function () {
    //         var p = product_select.val();
    //         var obj = $(this).val();
    //         var q = $(this).parent().siblings('.quantity:not(.updated)');
    //         if(p === obj)
    //         {
    //             $(this).parents('.input-group-s').siblings('.quantity').find('label').after('<span class="quantity-title" data-bottom-title-quantity-product="'+ 'الكمية الموجودة حاليا هي ' + product_quantity +'"></span>');
    //         }
    //     })
    // }());


    (function () {
        var product_pur_crea     = $('.purchases-invoice-create .action-product:not(.d-none) input[name="product_id[]"],.purchases-invoice-edit .action-product:not(.d-none) input[name="product_id[]"]');

        product_pur_crea.each(function () {
            var p = product_select.val();
            var obj = $(this).val();
            var q = $(this).parent().siblings('.quantity:not(.updated)');
            if(p === obj)
            {
                $(this).parents('.input-group-s').siblings('.quantity').find('label').append('<bdi class="quantity_name"> ( '+product_quantity_name+' ) </bdi>');
                $(this).parents('.input-group-s').siblings('.quantity').find('label').after('<span class="quantity-title" data-bottom-title-quantity-product="'+ window.lang.quantity_instock + ' ' + product_quantity + ' ' + product_quantity_name +'"></span>');
            }
        })
    }());


}


var pro_ss = function (e) {

    var product_id = $(this).val();
    var products = $('select[name="product_name"] option');
    var q = $(this).parent().siblings('.quantity');


    q.keyup(function () {
        products.each(function () {
            var i = $(this);
            q.each(function () {
                var inp = $(this).find('input');
                if (i.val() == product_id) {
                    if (Number(inp.val()) - Number(inp.data('value')) <= Number(i.data('quantity'))) {

                        $(this).addClass('success-error').removeClass('sign-error');
                    } else {
                        $(this).addClass('sign-error').removeClass('success-error');
                    }
                }
            });
        });
    });

    products.each(function () {
        var i = $(this);
        q.each(function () {
            var inp = $(this).find('input');
            if (i.val() == product_id) {
                $(this).find('label').after('<span class="quantity-title" data-bottom-title-quantity-product="' + window.lang.quantity_instock + " " + i.data('quantity') + ' ' + i.data('quantity-name') +  '"></span>');
            }
        });
    });


};


$('.purchases-invoice-edit .action-product .updated').each(function () {
    var $input = $(this).find('input');
    $input.attr('data-value', $input.val());
});

$('.action-product .updated').each(function () {
    var a = $(this);
    var product_id = $(this).siblings('.name').find('input').val();


    $('select[name="product_name"] option:not(:disabled)').each(function () {
        if(product_id == $(this).val())
        {
            var input_quan = $(this).data('quantity');
            var input_quan_name = $(this).data('quantity-name');
            a.find('label').append('<bdi class="quantity_name"> ( '+input_quan_name+' ) </bdi>');
            a.find('label').after('<span class="quantity-title" data-bottom-title-quantity-product="'+ window.lang.quantity_instock + " " + input_quan + ' ' + input_quan_name +'"></span>');

        }
    });
});



$('.purchases-invoice-edit .action-product .updated').keyup(function () {
    var a = $(this);
    var $input = $(this).find('input');
    var calc = Number($input.data('value')) - Number($input.val());
    var product_id = $(this).siblings('.name').find('input').val();


    $('select[name="product_name"] option:not(:disabled)').each(function () {
        if(product_id == $(this).val())
        {
            if(Number($(this).data('quantity')) - calc < 0 && !a.hasClass('sign-error'))
            {
                a.addClass('sign-error').removeClass('sign-success');

            }
        }
    });
});



$('.input-submit-p').click(function (e) {
    var quantity    = $('.sales-invoice-create .action-product:not(.d-none) input[name="quantity[]"]');
    var quantity_edit_purchases    = $('.purchases-invoice-edit .action-product:not(.d-none) .updated input[name="quantity[]"]');
    var products_id    = $('.sales-invoice-create .action-product:not(.d-none) input[name="product_id[]"]');
    var products = $('select[name="product_name"] option:not(:disabled)');
    var a;
    var q;
    var qParent;
    products_id.each(function () {
        a = $(this);
        products.each(function () {
            if($(this).val() == a.val())
            {
                qParent = a.parents('.action-product').find('.quantity') ;
                q = qParent.find('input') ;
                if(q.val() > $(this).data('quantity') && !qParent.hasClass('sign-error'))
                {
                    e.preventDefault();
                    qParent.addClass('sign-error').removeClass('sign-success');
                }else if(q.val() > $(this).data('quantity')){
                    e.preventDefault();
                }
            }

        });
    });

    quantity_edit_purchases.each(function () {
        var $input = $(this);
        var $q = $(this).parent('.quantity');
        var calc = $input.data('value') - $input.val();
        var product_id = $q.siblings('.name').find('input').val();
        $('select[name="product_name"] option:not(:disabled)').each(function () {

            if(product_id == $(this).val() && !$q.hasClass('sign-error'))
            {
                if($(this).data('quantity') - calc < 0)
                {
                    e.preventDefault();
                    $q.addClass('sign-error').removeClass('sign-success');

                }else {
                    removeClass($q,'sign-error');
                }
            }
        });
    })

});


$('select[name="product_name"]').on('change',product);

// $('select[name="product_name"]').on('change',pro_ss);
//
//
var products_in     = $('.sales-invoice-edit .action-product:not(.d-none) input[name="product_id[]"]');
var sales_create     = $('.sales-invoice-create .action-product:not(.d-none) input[name="product_id[]"]');
var sales_create_fun = function (e) {

    var product_id = $(this).val();
    var products = $('select[name="product_name"] option');
    var q = $(this).parent().siblings('.quantity');


    q.keyup(function () {
        products.each(function () {
            var i = $(this);
            q.each(function () {
                var inp = $(this).find('input');
                if (i.val() == product_id) {
                    if (Number(inp.val()) <= Number(i.data('quantity'))) {

                        $(this).addClass('success-error').removeClass('sign-error');
                    } else {
                        $(this).addClass('sign-error').removeClass('success-error');
                    }
                }
            });
        });
    });

    products.each(function () {
        var i = $(this);
        q.each(function () {
            var inp = $(this).find('input');
            var number ;
            if (i.val() == product_id) {
                number = i.data('quantity');
                $(this).find('label').after('<span class="quantity-title" data-bottom-title-quantity-product="' + window.lang.quantity_instock + " " + number + ' ' + i.data('quantity-name') +  '"></span>');
            }
        });
    });


};
//
products_in.each(pro_ss);


sales_create.each(sales_create_fun);


// add title in quantity input


// bar code


class Barcode {
    constructor(id,product) {
        this.barcode  = document.querySelector(id);
        this.product_names   = document.querySelectorAll('[name="product_name"] option');
        this.product_select   = document.querySelector('[name="product_name"]');
        this.button_scanner   = document.querySelector('#scanner-barcode');
        this.body   = document.querySelector('body');
        this.xhttp    = this.xhttp_ajax();
        this.product = product;
        this.body = document.querySelector('body');
        this.events();
    }
    events() {
        const $this = this;
        this.barcode.addEventListener('keydown',function (e) {
            if(e.keyCode == 13){
                const barcode = this.value;
                $this.barcode.value = '';
                var pathname = window.location.pathname.replace(/\/+$/,'');
                pathname = pathname.replace(/^\/+/,'').toLowerCase();

                if(pathname == 'sales/create')
                {
                    for (var i = 0; i < $this.product_names.length; i++) {


                        if ($this.product_names[i].getAttribute('data-barcode') == barcode) {
                            $this.product_names[i].setAttribute('selected','selected');
                            $($this.product_select).each($this.product);
                        }else {
                            $this.product_names[i].removeAttribute('selected');
                        }
                    }
                }else{

                    window.location.href = "http://www.store.com/Sales/Create/?barcode=" + barcode;
                }


            }
        });

        this.button_scanner.addEventListener('click',function () {
            $this.barcode.focus();
        });
        this.body.addEventListener('keydown',function (e) {
            if(e.keyCode == 83 && $this.keycodeold == 17)
            {
                e.preventDefault();
                $this.barcode.focus();
            }
            $this.keycodeold = e.keyCode ;
        });
        this.body.addEventListener('click',function (e) {
            if(e.target == $this.body || $(e.target).hasClass('container'))
            {
                $this.barcode.focus();
            }


        })
    }

    ajax_get_product (barcode){
        const xhttp = this.xhttp;
        xhttp.onreadystatechange = function () {
            if (this.readyState == 4 & this.status == 200) {
                return xhttp.responseText;
            }

        }
        xhttp.open("POST", '/barcode/products.php', false);
        xhttp.setRequestHeader("Content-type", "application/json; charset=utf-8");
        xhttp.send('Barcode=' + barcode);
    }
    xhttp_ajax(){
        if (window.XMLHttpRequest) {
            // code for modern browsers
            return new XMLHttpRequest();
        } else {
            // code for old IE browsers
            return new ActiveXObject("Microsoft.XMLHTTP");
        }
    }

}

if (document.getElementById('barcode-input-dashboard124') != null){
    barcode = new Barcode('#barcode-input-dashboard124',product);
}









//functions







function PrintDiv(id,style,classParent) {
    var elem = document.getElementById(id);

    var win = window.open('', "newWindow", "menubar=1,resizable=1,width=" + elem.offsetWidth + ",height=" + elem.offsetHeight);
    win.onload = function () {

        var link = document.location.origin + style;
        var mainLink = document.location.origin + '/css/main.css';
        var langLink = document.location.origin + '/css/lang.css';
        if($('html').attr('dir') == 'rtl')
        {
            win.document.write("<html dir='rtl'><head><link rel='stylesheet' href='"+ link +"'><link rel='stylesheet' href='"+ mainLink +"'><link rel='stylesheet' href='"+ langLink +"'> <style> body{width: 100%; margin: 0; padding: 0;} ."+ classParent +"{ width: 100%; }  </style> </head><body><div class="+ '\'' + classParent + '\'' +">"+ elem.innerHTML +"</div></body></html>");
        }else{

            win.document.write("<html><head><link rel='stylesheet' href='"+ mainLink +"'><link rel='stylesheet' href='"+ link +"'></head><body><div class="+ classParent +">"+ elem.innerHTML +"</div></body></html>");
        }
        win.document.close();
        setTimeout("postMsg()",2000);


    }

    postMsg = function () {
        win.print()
    };


}

$('#print').click(function () {
    PrintDiv('invoice-products','/css/invoicepreview.css',"invoice-products f-row print_win");
});


$('#print_products').click(function () {
    PrintDiv('preview-products','/css/productspreview.css','preview-products')
});


$('#tab-control a').click(function (e) {
    e.preventDefault();
    $(this).addClass('b-active-icon').siblings().removeClass('b-active-icon');
    var href = $(this).attr('href');
    var cookies_name = $(this).parent('#tab-control').data('cookies');
    setCookie(cookies_name,href,30);
    $(href).removeClass('d-none').siblings().addClass('d-none');
});


$('#tab-control a').each(function () {
    var cookies_name = $(this).parent('#tab-control').data('cookies');
    var default_cookie = "#" + $("#tab-control").data('default-cos');
    var element_id = getCookie(cookies_name) == null ? default_cookie : getCookie(cookies_name);

    if(element_id == $(this).attr('href'))
   {
       $(this).addClass('b-active-icon').siblings().removeClass('b-active-icon');
   }
});

$('.tab-content > .tab-parent').each(function () {
    var cookies_name = $("#tab-control").data('cookies');
    var default_cookie =  '#' + $("#tab-control").data('default-cos');
    var element_id = getCookie(cookies_name) == null ? default_cookie : getCookie(cookies_name);
    if($(this).attr('id') != $(element_id).attr('id')) {
        $(this).addClass('d-none');
    }
});



$(window).load(function () {
    $('.progress-load-page').animate({'width':'100%'}).fadeOut();

});


$('[data-bottom-title]').each(function () {
   $(this).attr('dir','auto');
});



//
// $('.notification-box-message .notification-box-box').each(function () {
//     var width = $(this).innerWidth();
//     $(this).css('left', - width - 20).delay(1000).animate({'left': 50},500).delay(1000).animate({'left': - width - 20},500);
// });

var not_sound = new sound('/sounds/light.mp3');
window.right = 'right';
if (dirCssHtml == 'rtl') {
    window.right = 'left';
}



for (i = 1; $('.notification-box-message .notification-box-box').length >= i; i++) {
    var $this = $('.notification-box-message .notification-box-box').eq(i - 1);
    var $id = $this.attr('id');
    var width = $this.innerWidth();
    var delay = 8000 * i ;
    var delay_read = 6000;
    var right = window.right;
    if (i == 1) delay = 2000;
    if (i == 1) delay_read = 10000;
    $this.css(right, -width - 20).delay(delay).queue(function () {
        not_sound.play();
        NotShowed($id);

        $('.close-not').click(function () {
            $(this).parents('.notification-box-box').hide();
        });

        $(this).dequeue();
    }).animate({[right]: 50}, 500).delay(delay_read).animate({[right]: -width - 20}, 500);

}
// setCookie('not-okay','',60);




function NotShowed(Id) {
    var xhttp;
    if (window.XMLHttpRequest) {
        // code for modern browsers
        xhttp = new XMLHttpRequest();
    } else {
        // code for old IE browsers
        xhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }

    var send = 'Id=' + Id;

    xhttp.onreadystatechange = function () {
    }
    xhttp.open("POST", "/Notifications/ShowedNotification/", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send(send);
}



// barcode


$('#barcode').keydown(function (e) {
    if (e.keyCode == 13)
    {
        e.preventDefault();
        if($(this).val() == '')
        {
            var rand = Math.floor(Math.random() * 12356478152178 / Math.random() * 14);
            $(this).val(rand);
        }
    }

});

/// fit text


(function( $ ){

    $.fn.fitText = function( kompressor, options ) {

        // Setup options
        var compressor = kompressor || 1,
            settings = $.extend({
                'minFontSize' : Number.NEGATIVE_INFINITY,
                'maxFontSize' : Number.POSITIVE_INFINITY
            }, options);

        return this.each(function(){

            // Store the object
            var $this = $(this);

            // Resizer() resizes items based on the object width divided by the compressor * 10
            var resizer = function () {
                $this.css('font-size', Math.max(Math.min($this.width() / (compressor*10), parseFloat(settings.maxFontSize)), parseFloat(settings.minFontSize)));
            };

            // Call once to set.
            resizer();

            // Call on resize. Opera debounces their resize by default.
            $(window).on('resize.fittext orientationchange.fittext', resizer);

        });

    };

})( jQuery );

$(".side-menu").fitText(1.2, { minFontSize: '12px', maxFontSize: '16px' });



// messangers

$('.Messenger').append('<i class="fas fa-times"></i>');

$('.Messenger .fa-times').click(function () {
    $(this).parent().css('display','none');
});

$('.Messenger.fadeout').each(function () {
    var allCharacter = $('.Messenger').text().length;
    $(this).delay(allCharacter * 200).fadeOut();
});



class ajax {

    constructor() {
        var $this = this;
        this.xhttp = this.xhttp_ajax();
        this.i = 0;
        window.setInterval(function (){ $this.ajax_user() }, 300000)
    }

    ajax_user (){
        const xhttp = this.xhttp;
        xhttp.onreadystatechange = function () {
            if (this.readyState == 4 & this.status == 200) {
                return xhttp.responseText;
            }
        }

        xhttp.open("POST", '/Users/Ajax115s', false);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send('Random=lkasnjsdoihi04i9iru934uew993giusd9uiu');

    }
    xhttp_ajax(){
        if (window.XMLHttpRequest) {
            // code for modern browsers
            return new XMLHttpRequest();
        } else {
            // code for old IE browsers
            return new ActiveXObject("Microsoft.XMLHTTP");
        }
    }

}

var $v = new ajax();




if(pathname == "products/preview"){
    var barcode = $("#barcode_preview").data('barcode');
    $("#barcode_preview").JsBarcode(barcode);

}


$('input[type="disable"]').focus(function (e) {
    $(this).blur();
});








