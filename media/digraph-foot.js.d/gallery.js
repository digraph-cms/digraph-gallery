$(()=>{
    //navigation

    //find galleries
    $('.digraph-gallery')
    .addClass('js-enabled')
    //click handlers
    .click((e)=>{
        var $icon = $(e.target).closest('.digraph-gallery-item');
        if ($icon.length > 0) {
            //prevent double-clicking
            if ($icon.is('.selected')) {
                e.preventDefault();
                return false;
            }
            //find necessary elements
            var $items = $(e.target).closest('.digraph-gallery-items');
            var $gallery = $(e.target).closest('.digraph-gallery');
            var $iframe = $(e.target).find('.digraph-gallery-viewer iframe');
            //make CSS changes
            $items.find('.digraph-gallery-item').removeClass('selected');
            $icon.addClass('selected');
            $gallery.addClass('activated');
            //save element ID in URL hash
            window.location.hash = 'gallery-item:'+$icon.attr('id');
            //ensure item is in view
            var visibleTop = $items.scrollTop();
            var visibleBottom = visibleTop+$items.height();
            var iconTop = $icon.position().top+visibleTop;
            var iconHeight = $icon.height();
            var visibleBottom = visibleBottom-iconHeight;
            if (!(iconTop >= visibleTop && iconTop <= visibleBottom)) {
                //scroll into view
                $items.scrollTop(iconTop);
            }
        }
    })
    //keypress handlers
    .on('keydown',(e)=>{
        var $gallery = $(e.target).closest('.digraph-gallery');
        console.log(e);
        switch (e.key) {
            case 'ArrowLeft':
                console.log('left');
            case 'ArrowRight':
                console.log('right');
            case 'Escape':
                console.log('escape');
                if ($gallery.is('.zoomed')) {
                    $gallery.find('.fullscreen-toggle').click();
                }
        }
    });
    //trigger click on icon if it is in location.hash
    if (/gallery-item\:[a-f0-9]{8}/.test(window.location.hash)) {
        var i = window.location.hash.split(':')[1];
        $('#'+i).click();
        setTimeout(()=>{
            $('#'+i).click();
        },500);
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