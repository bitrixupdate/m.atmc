BX.ready(function() {
    const color_picker_1 = BX('color_picker_1');
    const color_picker_2 = BX('color_picker_2');

    BX.bind(color_picker_1, 'focus', function () {
        new BX.ColorPicker({
            bindElement: color_picker_1,
            defaultColor: '#222425',
            allowCustomColor: true,
            onColorSelected: function (item) {
                color_picker_1.value = item
            },
            popupOptions: {
                angle: true,
                autoHide: true,
                closeByEsc: true,
                events: {
                    onPopupClose: function () {
                    }
                }
            }
        }).open();
    })

    BX.bind(color_picker_2, 'focus', function () {
        new BX.ColorPicker({
            bindElement: color_picker_2,
            defaultColor: '#222425',
            allowCustomColor: true,
            onColorSelected: function (item) {
                color_picker_2.value = item
            },
            popupOptions: {
                angle: true,
                autoHide: true,
                closeByEsc: true,
                events: {
                    onPopupClose: function () {
                    }
                }
            }
        }).open();
    })
});
