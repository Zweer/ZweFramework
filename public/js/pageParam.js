window.addEvent('domready', function(){
    if(document.id('page-module') && document.id('page-controller') && document.id('page-action')) {
        var module = document.id('page-module'),
            controller = document.id('page-controller'),
            action = document.id('page-action');

        module.addEvent('change', function() {
            new Request.JSON({
                url: __BASEURL__LANG__ + '/admin/page/getControllers.json',
                data: 'module=' + module.get('value'),

                onComplete: function(J) {
                    controller.empty();
                    action.empty();

                    J.controllers.each(function(c) {
                        new Element('option', { text: c, value: c }).inject(controller);
                    });
                },

                onFailure: function() {
                    controller.empty();
                    action.empty();
                }
            }).send();
        });

        controller.addEvent('change', function() {
            new Request.JSON({
                url: __BASEURL__LANG__ + '/admin/page/getActions.json',
                data: 'module=' + module.get('value') + '&controller=' + controller.get('value'),

                onComplete: function(J) {
                    action.empty();

                    J.actions.each(function(a) {
                        new Element('option', { text: a, value: a }).inject(action);
                    });
                },

                onFailure: function() {
                    action.empty();
                }
            }).send();
        });
    }
});