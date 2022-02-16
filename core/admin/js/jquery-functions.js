function houndSlugify(title, slug) {
    var titleElement = document.getElementById(title),
        slugElement = document.getElementById(slug);

    titleElement = titleElement.value.trim() // Remove surrounding whitespace.
    .toLowerCase() // Lowercase.
    .replace(/[^a-z0-9]+/g,'-') // Find everything that is not a lowercase letter or number, one or more times, globally, and replace it with a dash.
    .replace(/^-+/, '') // Remove all dashes from the beginning of the string.
    .replace(/-+$/, ''); // Remove all dashes from the end of the string.

    slugElement.value = titleElement;
}


function sortTable(table, order) {
    var asc = order === 'asc',
        tbody = table.find('tbody');

    tbody.find('tr').sort(function(a, b) {
    	var a = $(a).find('td:first').text(), b = $(b).find('td:first').text();
        if (asc) {
  			return a.localeCompare(b, false, {numeric: true});
        } else {
            return b.localeCompare(a, false, {numeric: true});
        }
    }).appendTo(tbody);
}

$(document).ready(function() {
    sortTable($('.hd-sortable'), 'asc');
});
