window.addEvents({
    domready: function() {
        if(document.id('login-open')) {
            document.id('login-open').addEvent('click', function(event) {
                event.preventDefault();
                new Request.HTML({
                    url: document.id('login-open').get('href'),
                    method: 'get',
                    update: document.id('layout-top-left-login'),

                    onComplete: function() {
                        $$('#layout-top-left-login input').each(function(inputElement) {
                            var label = null;
                            if(label = inputElement.getPrevious('label')) {
                                inputElement.set('title', label.get('text'));
                                label.destroy();
                            }

                            if(inputElement.get('type') == 'text' || inputElement.get('type') == 'password') {
                               inputElement.myOverText('title');
                            }
                        })
                    }
                }).send();
            })
        }

        new Fx.SmoothScroll({
            duration: 200
        }, window);

        hideGoToTop();

        // preload of the hover breadcrumbs home
        if($$('#layout-header-title-breadcrumbs a').length > 0)
            new Asset.image(__BASEURL__ + '/images/layout/breadcrumb-home-hover.png');

        // preload of the hover goto top image
        new Asset.image(__BASEURL__ + '/images/layout/go-top-arrow-hover.png');

        // search overtext
       document.id('layout-header-title-search').getElement('input').myOverText();

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

function myOverText(element, property) {
    property = property || 'value';
    element.store('myOverText', element.get(property));
    element.addEvents({
        blur: function() {
            if(this.get('value') == '')
                this.set('value', element.retrieve('myOverText', 'search'));
        },
        focus: function() {
            if(this.get('value') == element.retrieve('myOverText', 'search'))
                this.set('value', '');
        }
    });

    if(element.get('value') == '')
        element.fireEvent('blur');
}
Element.implement({
    myOverText: function(property) {
        myOverText(this, property);
    }
});