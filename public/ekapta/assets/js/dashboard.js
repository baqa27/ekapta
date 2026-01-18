const confirmDelete = () => {
    event.preventDefault();
    const form = event.target.form;
    Swal.fire({
        title: 'Yakin ingin dihapus ?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes'
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    })
}

const confirmAcc = () => {
    event.preventDefault();
    var form = event.target.form;
    Swal.fire({
        title: 'Yakin ingin diacc ?',
        icon: 'success',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes'
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    })
}

const confirmActive = () => {
    event.preventDefault();
    var form = event.target.form;
    Swal.fire({
        title: 'Yakin ingin diaktifkan ?',
        icon: 'success',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes'
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    })
}

const confirmDisable = () => {
    event.preventDefault();
    var form = event.target.form;
    Swal.fire({
        title: 'Yakin ingin dinonaktifkan ?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes'
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    })
}

const confirmCancel = () => {
    event.preventDefault();
    var form = event.target.form;
    Swal.fire({
        title: 'Yakin ingin dibatalkan ?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes'
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    })
}

const confirmAdd = () => {
    event.preventDefault();
    var form = event.target.form;
    Swal.fire({
        title: 'Yakin ingin ditambahkan ?',
        icon: 'success',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes'
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    })
}


 // Summernote
$(function() {
    $('#summernote').summernote()
})

// Custom file input
$(function() {
    bsCustomFileInput.init();
});

// DataTable
$(function() {
    $("#example1").DataTable({
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false,
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

});
$(function() {
    $("#example2").DataTable({
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false,
    }).buttons().container().appendTo('#example2_wrapper .col-md-6:eq(0)');

});
$(function() {
    $("#example3").DataTable({
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false,
    }).buttons().container().appendTo('#example3_wrapper .col-md-6:eq(0)');

});
$(function() {
    $("#examplebutton").DataTable({
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false,
        "buttons": ["copy", "csv", "excel", "pdf", "print"]
    }).buttons().container().appendTo('#examplebutton_wrapper .col-md-6:eq(0)');

});
$(function() {
    $("#examplebutton2").DataTable({
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false,
        "buttons": ["copy", "csv", "excel", "pdf", "print"]
    }).buttons().container().appendTo('#examplebutton2_wrapper .col-md-6:eq(0)');

});

// Calendar
// $('#calendar').datetimepicker({
//     format: 'L',
//     inline: true
// })
