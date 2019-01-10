try {
    window.Popper = require('popper.js').default;
    window.$ = window.jQuery = require('jquery');

    require('bootstrap');
} catch (e) {}

(function() {
    $('.submitsForm').on('click', function (ev) {
        const $target = $(ev.currentTarget);
        const $targetForm = $target.parent()
            .parent()
            .find(`form.${$target.data('target')}`);

        $targetForm.submit();
    });

    $('.makesButtonClickable').on('change', function (ev) {
        const $target = $(ev.currentTarget);
        const $targetButton = $($target.data('target'));

        $targetButton.attr('disabled', ! $target.is(':checked'));
    });
})();
