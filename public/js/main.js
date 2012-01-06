window.addEvent('domready', function() {
    new Fx.SmoothScroll({
        duration: 200
    }, window);

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
});