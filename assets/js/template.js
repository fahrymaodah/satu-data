var url			= window.location.href.split("/");
var base_url	= window.location.protocol+'//'+window.location.hostname+'/'+url[3]+'/';

function swal(pesan)
{
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: pesan.toLowerCase().includes('berhasil') ? 'success' : 'error',
        title: '&nbsp;&nbsp;'+pesan,
        showConfirmButton: false,
        timer: 5000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    })
}

$('.pop-up').on('click', function() {
    var action = $(this).data("act");
    var table = $(this).data("tab");

    var title = action.charAt(0).toUpperCase() + action.slice(1) + ' Data';

    $('.modal-title').html(title);

    if (action == 'ubah')
    {
        $('#form').prepend('<input type="hidden" name="id_'+table+'">');
        $('.action').removeClass('tambah');
        $('.action').addClass('ubah');
    }
    else
    {
        $('#form input[name=id_'+table+']').remove();
        $('.action').removeClass('ubah');
        $('.action').addClass('tambah');

        $('#form').trigger("reset");
    }
})

function swal(pesan)
{
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: pesan.toLowerCase().includes('berhasil') ? 'success' : 'error',
        title: '&nbsp;&nbsp;'+pesan,
        showConfirmButton: false,
        timer: 5000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    })

}

function notifikasi(validasi) {
    if (validasi != '')
    {
        time = 0;
        $.each(Object.values(validasi), function (key, value) {
            setTimeout(function () {
                if (value !== '' && value !== undefined)
                {
                    $.notify({ icon: 'fas fa-exclamation-triangle', message: '&nbsp;&nbsp;'+value },
                    {
                        type: 'warning',
                        allow_dismiss: true,
                        placement: { from: 'top', align: 'right' },
                        z_index: 1051,
                        timer: 500+time,
                        animate: { enter: 'animate__animated animate__fadeInDown', exit: 'animate__animated animate__fadeOutUp' },
                    });
                }
            }, key * 500);
            time += 500;
        });
    }
}

function formulir(controller, method, form='form') {
    $.ajax({
        type:'POST',
        enctype:'multipart/form-data',
        url:base_url+controller+'/'+method,
        data: new FormData($('#'+form)[0]),
        processData:false,
        contentType: false,
        cache: false,
        dataType:'json',
        beforeSend: function() { $(".bg").show(); },
        complete: function() { $(".bg").hide(); },
        success:function(valid) { valid.status == "sukses" ? location.reload() : notifikasi(valid.validasi); }
    });
}

function hapus(href)
{
    Swal.fire({
        title: 'Apakah Anda Yakin?',
        text: "Data akan dihapus!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonClass: 'btn btn-primary mr-1',
        cancelButtonClass: 'btn btn-danger',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Tidak',
        buttonsStyling: false,
    }).then((result) => { if (result.isConfirmed) { document.location.href = href; } })
}