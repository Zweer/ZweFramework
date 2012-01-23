window.addEvents({
    domready: function() {
        new ZweBox();

        new Fx.SmoothScroll({
            duration: 200
        }, window);

        hideGoToTop();

        // preload of the hover goto top image
        new Asset.image(__BASEURL__ + '/images/layout/go-top-arrow-hover.png');

        // search overtext
        var searchInput = document.id('layout-header-title-search').getElement('input');
        searchInput.store('value', searchInput.get('value'));
        searchInput.addEvents({
            blur: function(event) {
                if(this.get('value') == '')
                    this.set('value', searchInput.retrieve('value', 'search'));
            },
            focus: function(event) {
                if(this.get('value') == searchInput.retrieve('value', 'search'))
                    this.set('value', '');
            }
        });

        // TODO: do search
    },

    resize: function() {
        hideGoToTop();
    }
});

function hideGoToTop() {
    if(window.getScrollSize().y == window.getSize().y)
        document.id('layout-footer-small-footer-gototop').setStyle('display', 'none');
    else
        document.id('layout-footer-small-footer-gototop').setStyle('display', 'block');
}