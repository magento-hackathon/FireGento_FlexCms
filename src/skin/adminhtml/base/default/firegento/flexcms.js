
var insertNewItem = function(object, areaCode, ajaxUrl) {
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

    hideAddContainer(areaCode);
};

var insertExistingItem = function(object, areaCode, ajaxUrl) {
    var value = object.options[object.selectedIndex].value;
    if (!value) {
        return;
    }

    new Ajax.Request(ajaxUrl + 'contentid/' + value + '/', {
        onSuccess: function(response) {
            var containerId = 'flexcms_element_container_' + areaCode;
            $(containerId).insert({top: response.responseText});
        }
    });

    hideAddContainer(areaCode);
};

var hideAddContainer = function (areaCode) {
    $('flexcms_add_container_' + areaCode).hide();
    $$('#flexcms_add_container_' + areaCode + ' option').each(function(optionItem) {
        optionItem.selected = false;
    })
};

var observeDisplayModeSelect = function() {
    $$('#category_edit_form select.select option[value=PRODUCTS]').each(function(selectItem) {
        alert('.' + selectItem.value);
    });
};

var categoryPublish = function(url, useAjax) {
    var form = $('category_edit_form');
    form.insert('<input type="hidden" name="publish" value="1" />');
    categorySubmit(url, useAjax);
};