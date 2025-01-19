document.getElementById('apply-filters').addEventListener('click', function() {
    var categoryFilter = document.getElementById('category-filter').value;
    var tableRows = document.querySelectorAll('#records-table tbody tr');

    tableRows.forEach(function(row) {
        var categoryId = row.getAttribute('data-category-id');

        if (categoryFilter === '' || categoryId === categoryFilter) {
            row.style.display = 'table-row';
        } else {
            row.style.display = 'none';
        }
    });
});


function sortTable(n) {
    var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
    table = document.getElementById("records-table");
    switching = true;
    dir = "asc";
    while (switching) {
        switching = false;
        rows = table.rows;
        for (i = 1; i < (rows.length - 1); i++) {
            shouldSwitch = false;
            x = rows[i].getElementsByTagName("td")[n].textContent.trim();
            y = rows[i + 1].getElementsByTagName("td")[n].textContent.trim();
            var xValue, yValue;
            if (!isNaN(parseFloat(x.replace('$ ', '')))) {
                xValue = parseFloat(x.replace('$ ', ''));
                yValue = parseFloat(y.replace('$ ', ''));
            } else {
                xValue = x.toLowerCase();
                yValue = y.toLowerCase();
            }
            if (dir == "asc") {
                if (xValue > yValue) {
                    shouldSwitch = true;
                    break;
                }
            } else if (dir == "desc") {
                if (xValue < yValue) {
                    shouldSwitch = true;
                    break;
                }
            }
        }
        if (shouldSwitch) {
            rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
            switching = true;
            switchcount++;
        } else {
            if (switchcount == 0 && dir == "asc") {
                dir = "desc";
                switching = true;
            }
        }
    }
}


function toggleFilter(n) {
    var filterIcon = document.querySelectorAll('.filter-icon')[n];
    filterIcon.classList.toggle('active');
    var filterStatus = filterIcon.classList.contains('active');
    var table = document.getElementById("records-table");
    var rows = table.rows;
    var filterColumn = [];
    for (var i = 0; i < rows.length; i++) {
        var cell = rows[i].getElementsByTagName("td")[n];
        filterColumn.push(cell ? cell.textContent || cell.innerText : '');
    }
    var uniqueValues = filterColumn.filter(function (value, index, self) {
        return self.indexOf(value) === index && value !== '';
    });
    var filterContainer = document.createElement("div");
    filterContainer.classList.add("filter-container");
    uniqueValues.forEach(function (value) {
        var filterOption = document.createElement("div");
        filterOption.classList.add("filter-option");
        filterOption.textContent = value;
        filterOption.addEventListener('click', function () {
            applyFilter(n, value);
        });
        filterContainer.appendChild(filterOption);
    });
    if (filterStatus) {
        document.body.appendChild(filterContainer);
        document.addEventListener('click', closeFilter);
    } else {
        var existingFilterContainer = document.querySelector('.filter-container');
        if (existingFilterContainer) {
            existingFilterContainer.remove();
            document.removeEventListener('click', closeFilter);
        }
    }
}

function applyFilter(n, value) {
    var table, tr, td, i;
    table = document.getElementById("records-table");
    tr = table.getElementsByTagName("tr");
    for (i = 1; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td")[n];
        if (td) {
            if (td.textContent || td.innerText === value) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }
    var filterContainer = document.querySelector('.filter-container');
    if (filterContainer) {
        filterContainer.remove();
        document.removeEventListener('click', closeFilter);
    }
    var filterIcon = document.querySelectorAll('.filter-icon')[n];
    filterIcon.classList.remove('active');
}

function closeFilter(event) {
    var filterContainer = document.querySelector('.filter-container');
    var filterIcon = document.querySelector('.filter-icon.active');
    if (!filterContainer.contains(event.target) && event.target !== filterIcon) {
        filterContainer.remove();
        document.removeEventListener('click', closeFilter);
        filterIcon.classList.remove('active');
    }
}

document.getElementById("apply-filters").addEventListener("click", function () {
    var monthFilter = document.getElementById("month-filter").value;
    var yearFilter = document.getElementById("year-filter").value;
    var categoryFilter = document.getElementById("category-filter").value;
    var recordsTable = document.getElementById("records-table");
    var rows = recordsTable.getElementsByTagName("tr");
    for (var i = 1; i < rows.length; i++) {
        var row = rows[i];
        var month = row.cells[0].textContent || row.cells[0].innerText;
        var year = row.cells[1].textContent || row.cells[1].innerText;
        var category = row.cells[2].textContent || row.cells[2].innerText;
        if ((monthFilter === '' || month === monthFilter) &&
            (yearFilter === '' || year === yearFilter) &&
            (categoryFilter === '' || category === categoryFilter)) {
            row.style.display = "";
        } else {
            row.style.display = "none";
        }
    }
});