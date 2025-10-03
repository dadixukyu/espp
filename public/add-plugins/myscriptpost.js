
$(document).ready(function () {

    // form upload
    $('.formDataMultipart').submit(function (e) {
        e.preventDefault();

        var formData = new FormData(this);
        $.ajax({
            //type: "POST",
            type: $(this).attr('method'),
            url: $(this).attr('action'),
            data: formData,
            cache: false,
            contentType: false,
            processData: false,

            beforeSend: function () {
                $('#tombolSave').prop('disabled', true);
                $('#tombolSave').html("<i class='mr-25 spinner-border spinner-border-sm'></i>");
                $('#loading-spinner').removeClass('d-none');
            },
            complete: function () {
                $('#tombolSave').prop('disabled', false);
                $('#tombolSave').html("<i class='bx bx-save mr-25'></i>SIMPAN");
                $('#loading-spinner').addClass('d-none');
            },
            success: function (response) {
                if (response.success) {
                    $('#loading-spinner').addClass('d-none');
                    Lobibox.notify('success', {
                        pauseDelayOnHover: true,
                        continueDelayOnInactiveTab: false,
                        delay: 3000,
                        position: 'top right',
                        icon: 'bx bx-check-circle',
                        msg: response.success
                    });
                    // Swal.fire('Berhasil', response.success, 'success')
                    $('#modalFormData').modal('hide');

                    if (response.myReload == 'usulandata') {
                        $('#modalFormData').modal('hide');
                        myReloadTable();

                    }

                    if (response.myReload == 'verifadpemdata') {
                        $('#modalFormData').modal('hide');
                        myReloadTable();

                    }

                    if (response.myReload == 'berita_acaradata') {
                        $('#modalFormData').modal('hide');
                        myReloadTable();

                    }

                    if (response.myReload == 'tagihan_sppdata') {
                        $('#modalFormData').modal('hide');
                        myReloadTable();

                    }




                    // Swal.fire('Berhasil', response.success, 'success').then((result) => {
                    //     //window.location.reload(); 

                    //     $('#modalFormData').modal('hide');
                    //     if(response.myReload =='slideShowData'){
                    //         slideShowData();
                    //     } else if(response.myReload =='lamanGambar'){
                    //         lamanGambar();
                    //     } else if(response.myReload =='href'){
                    //         window.location.href=response.route;
                    //     } else if(response.action=="storePengaduan"){
                    //         window.location.href=response.route;

                    //          // ===== rio ==//
                    //     } else if(response.myReload =='entridata'){ 
                    //         $('#modalFormData').modal('hide');  
                    //         myReloadTable();  

                    //     } else {
                    //         myTable.ajax.reload();
                    //     }
                    // })  
                } else if (response.error) {
                    Swal.fire('Gagal', response.error, 'error');
                    $('#loading-spinner').addClass('d-none');
                }
            },
            error: function (xhr, ajaxOptons, throwError) {

                if (xhr.status >= 500) {
                    // alert(xhr.status + '\n' + throwError);
                    Swal.fire('Error', xhr.status + '\n' + throwError, 'error');
                }
                if (xhr.status == 422) {
                    var errors = xhr.responseJSON.errors;
                    var errorList = '';
                    for (var key in errors) {
                        if (errors.hasOwnProperty(key)) {
                            errorList += '\n - ' + errors[key] + '</br>';
                        }
                    }
                    Swal.fire('Gagal', errorList, 'warning');
                }
            }
        });
        return false;
    });


    // form tanpa upload
    $('.formData').submit(function (e) {
        e.preventDefault();
        $.ajax({
            type: $(this).attr('method'),
            url: $(this).attr('action'),
            data: $(this).serialize(),
            dataType: "json",
            beforeSend: function () {
                $('#tombolSave').prop('disabled', true);
                $('#tombolSave').html("<i class='mr-25 spinner-border spinner-border-sm'></i>");

            },
            complete: function () {
                $('#tombolSave').prop('disabled', false);
                $('#tombolSave').html("<i class='bx bx-save mr-25'></i>SIMPAN");

            },
            success: function (response) {
                // alert(response.success)
                if (response.success) {

                    Lobibox.notify('success', {
                        pauseDelayOnHover: true,
                        continueDelayOnInactiveTab: false,
                        delay: 3000,
                        position: 'top right',
                        icon: 'bx bx-check-circle',
                        msg: response.success
                    });
                    // kontrak Swal.fire('Berhasil', response.success, 'success')
                    if (response.myReload == 'siswadata') {
                        $('#modalFormData').modal('hide');
                        myReloadTable();
                    }
                    if (response.myReload == 'parsppdata') {
                        $('#modalFormData').modal('hide');
                        myReloadTable();
                    }

                    if (response.myReload == 'partahundata') {
                        $('#modalFormData').modal('hide');
                        myReloadTable();
                    }


                    if (response.myReload == 'parkelasdata') {
                        $('#modalFormData').modal('hide');
                        myReloadTable();
                    }

                    if (response.myReload == 'parbiayadata') {
                        $('#modalFormData').modal('hide');
                        myReloadTable();
                    }

                    if (response.myReload == 'pendaftarandata') {
                        $('#modalFormData').modal('hide');
                        myReloadTable();
                    }

                    if (response.myReload == 'tagihanlaindata') {
                        $('#modalFormData').modal('hide');
                        myReloadTable();
                    }

                    if (response.myReload == 'tagihanlaindata') {
                        $('#modalFormData').modal('hide');
                        myReloadTable1();
                    }
                    if (response.myReload == 'tagihan_sppdata') {
                        $('#modalFormData').modal('hide');
                        myReloadTable();
                    }

                    if (response.myReload == 'userdata') {
                        $('#modalFormData').modal('hide');
                        myReloadTable();
                    }

                    if (response.myReload == 'kasdata') {
                        $('#modalFormData').modal('hide');
                        myReloadTable();
                    }

                    if (response.myReload == 'penampungdata') {
                        $('#modalFormData').modal('hide');
                        myReloadTable();
                    }

                    if (response.myReload == 'penampungdata') {
                        $('#modalFormData').modal('hide');
                        myReloadTable1();
                    }


                }

                // notif
                else if (response.error) {
                    Swal.fire('Gagal', response.error, 'error');
                    // Lobibox.notify('error', {
                    //     pauseDelayOnHover: true,
                    //     continueDelayOnInactiveTab: false,
                    //     delay: 2000,
                    //     position: 'top right',
                    //     icon: 'bx bx-x-circle',
                    //     msg: response.error
                    // });
                }

            },
            error: function (xhr, ajaxOptons, throwError) {
                if (xhr.status >= 500) {
                    // alert(xhr.status + '\n' + throwError);
                    Swal.fire('Error', xhr.status + '\n' + throwError, 'error');
                }

                if (xhr.status == 422) {
                    var errors = xhr.responseJSON.errors;
                    var errorList = '';
                    for (var key in errors) {
                        if (errors.hasOwnProperty(key)) {
                            errorList += '\n - ' + errors[key] + '</br>';
                        }
                    }
                    Swal.fire('Gagal', errorList, 'warning');
                }
            }
        });
        return false;
    });




});


