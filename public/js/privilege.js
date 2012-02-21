var privilegesBoxList;

window.addEvent('domready', function() {
    var resources = document.id('privileges_resources');

    privilegesBoxList = new TextboxList('privileges_list', {
        onBitBoxAdd: function(bit) {
            var idPrivilege = bit.value[0],
                newPrivilege = bit.value[1];

            if(idPrivilege != null)
                return;

            new Request.JSON({
                url: resources.getParent('form').action + '/add.json',
                data: 'resource=' + resources.get('value') + '&privileges=' + newPrivilege,

                onComplete: function(json) {
                    if(json.id) {
                        bit.value[0] = json.id;
                    } else {
                        bit.remove();
                    }

                    alert(json.message);
                }
            }).send();
        },
        onBitBoxRemove: function(bit) {
            var idPrivilege = bit.value[0],
                namePrivilege = bit.value[1];

            if(idPrivilege == null)
                return;

            new Request.JSON({
                url: resources.getParent('form').action + '/delete.json',
                data: 'resource=' + resources.get('value') + '&privileges=' + idPrivilege,

                onComplete: function(json) {
                    if(!json.ok) {
                        privilegesBoxList.add(namePrivilege, idPrivilege);
                    }

                    alert(json.message);
                }
            }).send();
        }
    });

    resources.addEvent('change', function() {
        privilegesBoxList.getBits().each(function(bit) {
            bit.value = [null, bit.value[1], null];
            bit.remove();
        });

        new Request.JSON({
            url: resources.getParent('form').action + '/get.json',
            data: 'resource=' + resources.get('value'),
            method: 'post',

            onComplete: function(json) {
                json.privileges.each(function(privilege) {
                    privilegesBoxList.add(privilege.name, privilege.id);
                });
            }
        }).send();
    });
    resources.fireEvent('change');
});