

// inputes

var input_s = $(".input-group-s input:not([type='checkbox']):not([type='hidden']):not([type='radio'])");
var selection = $(".input-group-s select");
var input_radio = $(".radio-g");
var input_file = $("input[type='file']");
var input_date = $(".input-group-s.DOB input");
var input_quantity = $(".quantity input");

var on_focus_input = function () {
    $(this).siblings('label').addClass('is_focus');
};

var on_blur_input = function () {
    if($(this).val().trim() == ''){
        $(this).val('');
        $(this).siblings('label').removeClass('is_focus');
    }
    if($(this).parents('.input-group-s').hasClass('sign-success')){
        $(this).parents('.input-group-s').removeClass('sign-success');
    }
};

input_s.focus(on_focus_input).on('blur',on_blur_input);

$(input_s).each(function () {

    if($(this).val().trim() != ''){
        $(this).siblings('label').addClass('is_focus');
    }

});

// $('.radio-g .checkmark-p').each(function () {

if($('.radio-g .checkmark-p .checkmark-c').length == 0){

    $('<span class="checkmark-c"></span>').insertAfter('.radio-g .checkmark-p input');
}

if($('.radio-g .checkmark-p .checkbox-c').length == 0){

    $('<span class="checkbox-c"><i class="fas fa-check"></i></span>').insertAfter('.checkbox-g .checkmark-p input');
}

// });







/** valid inputs */

// input is uploaded


$('input[type="file"]').change(function () {
    if($(this).val().trim() != '')
    {
        $(this).parent().css('border-bottom-color','#e7e7e7').removeClass('E');
    }else{
        $(this).parent().css('border-bottom-color','#e56a76').addClass('E');
    }
});

// input date

input_date.keyup(function () {

    var date_val = $(this).val();

    var Y_reg = new RegExp(/^[0-9][0-9][0-9][0-9]$/);

    var Y_M_reg = new RegExp(/^([0-9][0-9][0-9][0-9])-(0:?[0-9]|1[0-2])$/);

    if( ( date_val.length == 4 && Y_reg.test(date_val) ) || (date_val.length == 7 && Y_M_reg.test(date_val)) )
    {
        $(this).val(date_val + '-');
    }



});




// regex all inputs

var regexInputs = [];

regexInputs['username'] = '^(?=[0-9]*[A-Za-z_])(?=[A-Za-z_]*[0-9]).{3,15}$';
regexInputs['email'] = '^([A-z0-9-_.+%]){2,20}@([A-z0-9_.+-]){2,20}\\.([A-z]){2,10}$';
// regexInputs['password'] = '^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).{6,18}$';
regexInputs['phone'] = '^[(]:?(\\d{3}[.) -]*\\d{3}[. -]?\\d{4})|^([\\d +-]{10,14})$';
// regexInputs['confirm_password'] = '^' + $(".input-group-s input[name='password']").val() + '(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).{6,18}$';


//
$(".input-group-s input[name='password']").keyup(function () {
    $(".input-group-s input[name='confirm_password']").attr('data-pattern','^'+ $(this).val() +'$');
});

// event key up in input
input_s.keyup(function runError() {
    var re = new RegExp($(this).attr('data-pattern'));
    var is_valid = re.test($(this).val());
    if(!is_valid){
        $(this).parent().addClass('sign-error').removeClass('sign-success');
    }else{
        $(this).parent().addClass('sign-success').removeClass('sign-error');
    }
});

// event click submit form
$(".input-submit-p input[type='submit']").click(function (e) {
    var sign_error = $('.sign-error');
    var inputs = $(this).parents('form').find(".input-group-s input:not([type='checkbox']):not([type='hidden']):not([type='radio'])");
    var radios = $(this).parents('form').find(".radio-g");
    var selects = $(this).parents('form').find(".input-group-s select");

    inputs.each(function () {

        var pattern = $(this).attr('data-pattern');
        var re = new RegExp(pattern);
        var is_valid = re.test($(this).val());
        if(!is_valid & !$(this).parent().hasClass('sign-error')){
            e.preventDefault();
            $(this).parent().addClass('sign-error').removeClass('sign-success');
        }
    });


    // valid input radio
    radios.each(function () {
        var chekf = $(this).find('.checkmark-p input').is(':checked');
        // var chekl = $(this).find('.checkmark-p input').is(':checked');
        if(!chekf){
            e.preventDefault();
            $(this).css('border-bottom-color','#e56a76').addClass('E Vivron_input').delay(300).queue(function () {
                $(this).removeClass('Vivron_input').dequeue();
            });
        }else{
            $(this).css('border-bottom-color','#e7e7e7').removeClass('E');

        }
    });


    // add vivron class and remove
    if(sign_error.length > 0){
        e.preventDefault();
        sign_error.each(function () {
            $(this).addClass('Vivron').delay(300).queue(function () {
                $(this).removeClass('Vivron').dequeue();
            });
        });
    }

//    valid selection

    selects.each(function () {
        if(!$(this).parent().hasClass('i-op'))
        {
            if($(this).val() == null){
                e.preventDefault();
                $(this).parent().css('border-bottom-color','#e56a76').addClass('E Vivron_input').delay(300).queue(function () {
                    $(this).removeClass('Vivron_input').dequeue();
                });
            }else{
                $(this).parent().css('border-bottom-color','#e7e7e7').removeClass('E');

            }
        }

    });



});

selection.change(function () {
    $(this).parent().css('border-bottom-color','#e7e7e7').removeClass('E');
});

selection.change(function () {
    if(!$(this).siblings('label').hasClass('is_focus') || !$(this).find('option:selected').is(':disabled'))
    {
        $(this).siblings('label').addClass('is_focus');
    }else if($(this).find('option:selected').is(':disabled'))
    {
        $(this).siblings('label').removeClass('is_focus');
    }
});

selection.each(function () {
    if (!$(this).find('option:selected').is(':disabled'))
    {
        $(this).siblings('label').addClass('is_focus');
    }else {
        $(this).siblings('label').removeClass('is_focus')
    }
});

$(".radio-g input").click(function () {
    if($(this).is(':checked')){
        $(this).parents('.radio-g').css('border-bottom-color','#e7e7e7').removeClass('E');
    }
});


input_quantity.keyup(function () {
    $(this).siblings('[data-bottom-title-quantity-product]').addClass('af-be-block');
}).blur(function () {
    $(this).siblings('[data-bottom-title-quantity-product]').removeClass('af-be-block');
});


$('.action-close').click(function () {
    if($('.action-product').length == 1){

        $(this).parent('.action-product').addClass('d-none');
        var $length_input = $(this).parent('.action-product').find('input').length;
        var $inputs = $(this).parent('.action-product').find('input');
        for(i=0;i<$length_input;i++)
        {
            $inputs.eq(i).val('');
        }
    }else{
        $(this).parent('.action-product').remove();
    }
});




$('[data-bottom-title-quantity-product]').each(function () {
    $(this).attr('dir','auto');
});



$("[data-cut-title]").each(function () {
    var cut_val = $(this).data('cut-title');
    var str = $(this).text();
    if (str.length > Number(cut_val)){
        $(this).attr('data-bottom-title', str);
        str = str.substring(0,cut_val) + '...';
        $(this).text(str);
    }

});












