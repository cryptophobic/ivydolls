$(document).ready(function(){

    function openNav() {
        document.getElementById("mySidenav").style.width = "250px";
    }

    function closeNav() {
        document.getElementById("mySidenav").style.width = "0";
    }

    let sidebar = $('#sidebar');
    let overlay = $('#searchInput');
    let body = $('body');


    sidebar.on('show.bs.collapse', function (event) {
        if (event.target.id === 'sidebar' && !isSmallDevice()) {
            $('#mainContainer').removeClass('col-md-12').addClass('col-md-10')
        }
    });

    sidebar.on('hidden.bs.collapse', function (event) {
        if (event.target.id === 'sidebar' && !isSmallDevice()) {
            $('#mainContainer').removeClass('col-md-10').addClass('col-md-12')
        }
    });


    function isSmallDevice() {
        let mq = window.matchMedia( "(max-width: 767px)" );
        return mq.matches;
    }

    // media query event handler
    if (matchMedia) {
        const mq = window.matchMedia("(max-width:767px)");
        mq.addListener(WidthChange);
        WidthChange(mq);
    }

    // media query change
    function WidthChange(mq) {
        let sideBarInternal = $('#sidebar-internal');
        if (mq.matches) {
            let obj = sideBarInternal.detach();
            obj.appendTo('#searchInput');
            $('#sidebarToggler').hide();
            $('#mainContainer').removeClass('col-md-10').addClass('col-md-12')
        } else {
            let obj = sideBarInternal.detach();
            obj.appendTo('#sidebar');
            $('#sidebarToggler').show();
            $('#mainContainer').removeClass('col-md-12').addClass('col-md-10')

        }

    }

    overlay.on('show.bs.collapse', function (event) {
        if (event.target.id === 'searchInput')
        {
            var height = $(window).height();
            alert(height.toString() + 'px !important');
            overlay.css('max-height', height.toString() + 'px !important');
            body.toggleClass('noscroll')
        }
    });

    overlay.on('hidden.bs.collapse', function (event) {
        if (event.target.id === 'searchInput')
        {
            //overlay.attr('aria-hidden', false);
            body.toggleClass('noscroll')
            //overlay.scrollTop = 0;
        }
    });


    /*$('.sidebar-items').click(function(event){
        event.preventDefault();
        items.load($(this).attr('href'));
    })*/
});

