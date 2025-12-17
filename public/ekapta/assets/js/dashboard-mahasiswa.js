// confirdelete
const confirmDelete = () => {
    event.preventDefault();
    var form = event.target.form;
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

// Countdown
;(function($) {
            var MERCADO_JS = {
                init: function() {
                    this.mercado_countdown();

                },
                mercado_countdown: function() {
                    if ($(".countdown").length > 0) {
                        $(".countdown").each(function(index, el) {
                            var _this = $(this),
                                _expire = _this.data('expire');
                            _this.countdown(_expire, function(event) {
                                $(this).html(event.strftime(
                                    "<span class='mr-2'><b class='bg-white text-dark rounded p-1'>%D</b> Hari</span> <span class='mr-2'><b class='bg-white text-dark rounded p-1'>%-H</b> Jam</span> <span class='mr-2'><b class='bg-white text-dark rounded p-1'>%M</b> Menit</span> <span class='mr-2'><b class='bg-white text-dark rounded p-1'>%S</b> Detik</span>"
                                ));
                            });
                        });
                    }
                },

            }

            window.onload = function() {
                MERCADO_JS.init();
            }

        })(window.Zepto || window.jQuery, window, document);
