$(document).ready(function () {
    $('.slider-wrapper').slick({
        dots: true,
        infinite: true,
        speed: 300,
        slidesToShow: 1,
        adaptiveHeight: true
    })
    $('.recent-wrapper').slick({
        dots: false,
        infinite: true,
        speed: 200,
        variableWidth: true,
        cssEase: 'linear',
        slidesToShow: 1,
        slidesToScroll: 1,
        responsive: [
            {
                breakpoint: 767,
                settings: {
                    slidesToShow: 1,
                    variableWidth: false,
                    slidesToScroll:1
                }
            }
        ]
    });
    var goDate = [];
    $('.btn-choose').click(function() {
        var thisDate = $(this).data().date;
        $(this).toggleClass('btn-active');
        if($(this).hasClass('btn-active')) {
            goDate.push(thisDate);
        } else {
            goDate.splice(goDate.indexOf(thisDate), 1);
        }
    })
    $('#doRegister').click(function() {
        $.ajax({
            url: '/asdasd',
            type: 'POST',
            data: {
                name: $('#register-name').val(),
                job:  $('#register-job').val(),
                email: $('#register-email').val(),
                code: $('#register-code').val(),
                cellphone: $('register-cellphone').val(),
                date: JSON.stringify(goDate),
                purchaseWay: $("input[name='purchaseWay']:checked").val()
            },
            dataType: 'json',
            success: function(){},
            error: function(e){}
        })
    })
});

