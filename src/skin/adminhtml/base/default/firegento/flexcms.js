
var insertItem = function(object, areaCode, ajaxUrl) {
    var value = object.options[object.selectedIndex].value;
    if (!value) {
        return;
    }

    new Ajax.Request(ajaxUrl + 'elementtype/' + value + '/', {
        onSuccess: function(response) {
            var containerId = 'flexcms_element_container_' + areaCode;
            $(containerId).insert({top: response.responseText});
        }
    });

    $('flexcms_add_container_' + areaCode).hide();
};

var observeDisplayModeSelect = function() {
    $$('#category_edit_form select.select option[value=PRODUCTS]').each(function(selectItem) {
        alert('.' + selectItem.value);
    });
};