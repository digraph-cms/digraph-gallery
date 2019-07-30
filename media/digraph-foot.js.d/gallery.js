$(()=>{
    //click listeners for icons
    $('.digraph-gallery')
    .addClass('js-enabled')
    .click((e)=>{
        var $icon = $(e.target).closest('.digraph-gallery-item');
        if ($icon.length > 0) {
            var $items = $(e.target).closest('.digraph-gallery-items');
            var $gallery = $(e.target).closest('.digraph-gallery');
            var $iframe = $(e.target).find('.digraph-gallery-viewer iframe');
            $items.find('.digraph-gallery-item').removeClass('selected');
            $icon.addClass('selected');
            $gallery.addClass('activated');
            window.location.hash = 'gallery-item:'+$icon.attr('id');
        }
    });
    //trigger click on icon if it is in location.hash
    if (/gallery-item\:[a-f0-9]{8}/.test(window.location.hash)) {
        var i = window.location.hash.split(':')[1];
        $('#'+i).click();
    }
    //add fullscreen toggler
    $('.digraph-gallery').append('<a class="fullscreen-toggle" title="toggle fullscreen"></a>');
    $('.digraph-gallery .fullscreen-toggle').click((e)=>{
        var $gallery = $(e.target).closest('.digraph-gallery');
        $gallery.toggleClass('zoomed');
        if ($gallery.is('.zoomed')) {
            $('body').css('overflow','hidden');
        }else {
            $('body').css('overflow','auto');
        }
    });
});