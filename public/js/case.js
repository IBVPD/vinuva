function getTotal(cIds) {
    let total = 0;
    $(cIds).each(function () {
        let value = parseInt($(this).val());
        if (!Number.isNaN(value)) {
            total = total + value;
        }
    });

    return total;
}

function setTotal(cIds, totalInput) {
    totalInput.val(getTotal(cIds));
}

function sumInputs(childIds, totalId, totalOverrideId) {
    let cIds = childIds.join(', ');
    let totalInput = $(totalId);
    let totalOverride = $(totalOverrideId);

    totalOverride.change(function () {
        if ($(this).is(':checked')) {
            totalInput.attr('readonly', false);
            return;
        }

        setTotal(cIds, totalInput);
        totalInput.attr('readonly', true);
    });

    let initialTotal = parseInt(totalInput.val());
    totalInput.attr('readonly', true);

    if (initialTotal > 0 && initialTotal !== getTotal(cIds)) {
        totalOverride.attr('checked', true).change();
    }

    $(cIds).change(function () {
        if (!totalOverride.is(':checked')) {
            setTotal(cIds, totalInput);
        }
    });
}
