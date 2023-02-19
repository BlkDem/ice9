$("body").on("click", ".table-filters a", function () {
    var _fid = $(this).closest('table').attr('class').split( " " )[1];
    if (_fid===null) {return;}
    $('table.' + _fid).find( 'input' ).val('');
    filterTable($(this).closest('table.' + _fid));
});

$("body").on("input", ".table-filters input", function () {
    filterTable($(this).closest('table.' + $(this).closest('table').attr('class').split( " " )[1]));
});

function filterTable(_table) {
    var _filters = _table.find('.table-filters td');
    var _rows = _table.find('.table-data');
    _rows.each(function (rowIndex) {
        var valid = true;
        $(this).find('td').each(function (colIndex) {
            if (_filters.eq(colIndex).find('input').val()) {
                if ($(this).html().toLowerCase().indexOf(
                _filters.eq(colIndex).find('input').val().toLowerCase()) == -1) {
                    valid = valid && false;
                }
            }
        });
        if (valid === true) {
            $(this).css('display', '');
        } else {
            $(this).css('display', 'none');
        }
    });
}