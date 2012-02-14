window.addEvent('domready', function() {
    if(document.id('tree_root')) {
        new Tree('tree_root', {
            onChange: function() {
                var order = JSON.encode(this.serialize());

                new Request.JSON({
                    url: __BASEURL__LANG__ + '/admin/privilege/order.json',
                    data: "order=" + order,

                    onSuccess: function(json) {
                        alert(json.message);
                    }
                }).send();
            }
        });
    }
});