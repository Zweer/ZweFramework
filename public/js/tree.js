window.addEvent('domready', function() {
    if(document.id('tree_root')) {
        new Tree('tree_root', {
            onChange: function() {
                var order = JSON.encode(this.serialize());

                new Request.JSON({
                    url: document.id('tree_root').getPrevious('form').get('action') + '/order.json',
                    data: "order=" + order,

                    onSuccess: function(json) {
                        alert(json.message);
                    }
                }).send();
            }
        });
    }
});