window.addEvent('domready', function() {
    if(document.id('privileges_list') && document.id('tree_root')) {
        var privilegeClass = 'tree-resource-privilege',
            input = document.id('privileges_list'),
            form = input.getParent('form'),
            select = form.getElement('select'),
            action = form.get('action'),
            lis = document.id('tree_root').getElements('ul.' + privilegeClass + ' li');

        input.destroy();

        lis.each(function(li) {
            var IDPrivilege = li.get('id').substr(privilegeClass.length + 1).toInt(),
                img = li.getElement('img');

            img.addEvent('click', function() {
                new Request.JSON({
                    async: false,
                    url: action + '/toggle.json',
                    data: 'name=' + select.get('value') + '&list=' + IDPrivilege,

                    onComplete: function(J) {
                        setImageFromJSON(J, img);
                    }
                }).send();
            });

            select.addEvent('change', function() {
                new Request.JSON({
                    url: action + '/get.json',
                    data: 'name=' + select.get('value') + '&list=' + IDPrivilege,

                    onComplete: function(J) {
                        setImageFromJSON(J, img);
                    }
                }).send();
            });
        });

        select.fireEvent('change');
    }
});

function setImageFromJSON(J, image) {
    if(J.value) {
        image.set('src', __BASEURL__ + '/images/icons/check_mark_green_16x16.png');
    } else {
        image.set('src', __BASEURL__ + '/images/icons/delete_red_16x16.png');
    }
}