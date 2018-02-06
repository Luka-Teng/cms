$(function() {
    showSubMenu();
    showMobileNav();
})
window.onscroll = function(event) {
    scrollToggleHeader(event);
}

// 滚动出发收起header
function scrollToggleHeader(_event) {
    var scrollVal = $(document).scrollTop();
    if (scrollVal > 0) {
        $('.header-wrapper').addClass('header-small');
        $('.mobile-header-wrapper').addClass('black');
    } else {
        console.log('top')
        $('.header-wrapper').removeClass('header-small');
        $('.mobile-header-wrapper').removeClass('black');
    }
}

// 悬浮nav时显示submenu
function showSubMenu() {
    $('.menu-item').mouseenter(function() {
        $(this).children('.sub-menu').show();
    })
    $('.menu-item').mouseleave(function() {
        $(this).children('.sub-menu').hide();
    })
}

function showMobileNav() {
    $('.show-nav').click(function() {
        $('.mobile-nav-container').toggleClass('mobile-nav-container-show')
    })
}