document.addEventListener('DOMContentLoaded', function () {

  // FORM GET MODAL INPUT DATA TYPE GET
  $(document).on('click', '#tombol-form-modal', function (e) {
    e.preventDefault();
    var url = $(this).data('url');
    $.ajax({
      type: 'GET',
      url: url,
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      beforeSend: function () {
        $('#loading-spinner').removeClass('d-none');
      },
      complete: function () {
        $('#loading-spinner').addClass('d-none');
      },
      success: function (response) {
        $('.viewModal').html(response).show();
        $('#modalFormData').modal('show');
      },
      error: function (xhr, ajaxOptons, throwError) {
        alert(xhr.status + '\n' + throwError);
      }
    });
  });



  // DELETE
  $(document).on('click', '.formDelete', function (e) {
    e.preventDefault();
    var url = $(this).attr('action');

    Swal.fire({
      title: 'Apakah Anda yakin?',
      text: "Data yang sudah dihapus tidak dapat dikembalikan!",
      icon: 'success',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Ya, hapus data!'
    }).then((result) => {
      if (result.value) {
        $.ajax({
          url: url,
          type: 'POST',
          data: $(this).serialize(),
          dataType: "json",
          beforeSend: function () {
            $('#loading-spinner').removeClass('d-none');
          },
          complete: function () {
            $('#loading-spinner').addClass('d-none');
          },
          success: function (response) {
            if (response.success) {

              Lobibox.notify('success', {
                pauseDelayOnHover: true,
                continueDelayOnInactiveTab: false,
                delay: 2000,
                position: 'top right',
                icon: 'bx bx-check-circle',
                msg: response.success
              });

              if (response.myReload == 'siswadata') {
                myReloadTable();
              } else if (response.myReload == 'parsppdata') {
                myReloadTable();
              }

              else if (response.myReload == 'partahundata') {
                myReloadTable();
              }

              else if (response.myReload == 'parkelasdata') {
                myReloadTable();
              }

              else if (response.myReload == 'parbiayadata') {
                myReloadTable();
              }

              else if (response.myReload == 'pendaftarandata') {
                myReloadTable();
              }

              else if (response.myReload == 'tagihanlaindata') {

                // $('#modalFormData').modal('hidden');
                myReloadTable();
              }

              else if (response.myReload == 'tagihanlaindata2') {
                myReloadTable1();
              }

              else if (response.myReload == 'tagihan_sppdata') {

                myReloadTable();
              }

              else if (response.myReload == 'userdata') {
                myReloadTable();
              }

              else if (response.myReload == 'kasdata') {
                myReloadTable();
              }

              else if (response.myReload == 'pindahkelasdata') {
                myReloadTable();
              }

              else if (response.myReload == 'pindahkelasdata') {
                myReloadTable1();
              }

            } else if (response.error) {
              Swal.fire('Gagal', response.error, 'warning');
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
            alert(xhr.status + '\n' + throwError);
          }
        });
      }
    });
  });



  // For Modal kedua
  $(document).on('click', '#tombol-form-modal2', function (e) {
    e.preventDefault();
    var url = $(this).data('url');
    $.ajax({
      type: 'GET',
      url: url,
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      beforeSend: function () {
        $('#loading-spinner').removeClass('d-none');
      },
      complete: function () {
        $('#loading-spinner').addClass('d-none');
      },
      success: function (response) {
        $('#modalFormData').modal('hide');
        $('.viewModal2').html(response).show();
        $('#modalFormData2').modal('show');
      },
      error: function (xhr, ajaxOptons, throwError) {
        alert(xhr.status + '\n' + throwError);
      }
    });
  });

  //untuk hapus gambar di form galeri_detail
  $(document).on('click', '#hps_select', function (e) {
    e.preventDefault();
    // alert("asasa");
    var url = $(this).data('url');
    var grid = document.getElementById("tabel_detail");

    //Reference the CheckBoxes in Table.
    var checkBoxes = grid.getElementsByTagName("INPUT");
    // var message = "Id Name ";
    var arr = [];
    var arr_nmbrg = [];
    // var arr_hrg = [];
    //Loop through the CheckBoxes.
    for (var i = 0; i < checkBoxes.length; i++) {
      if (checkBoxes[i].checked) {
        var row = checkBoxes[i].parentNode.parentNode;
        arr_nmbrg.push(row.cells[2].innerHTML);
        arr.push(row.cells[4].innerHTML);
        // arr_hrg.push(row.cells[4].innerHTML);
      }
    }

    if (arr == "") {
      Swal.fire('Peringatan', 'Belum Ada Barang Yang Dipilih', 'info')
    }

    if (arr != "") {
      Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Data yang sudah dihapus tidak dapat dikembalikan!",
        icon: 'success',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, hapus data!'
      }).then((result) => {
        if (result.value) {



          // alert(JSON.stringify(arr));
          // var id_arr = JSON.stringify(arr);
          var id_arr = arr.join(",");
          // alert(arr);
          $.ajax({
            type: 'DELETE',
            url: url,
            data: {
              arr_nmbrg: arr_nmbrg,
              id_arr: id_arr
            },
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function () {
              $('#loading-spinner').removeClass('d-none');
            },
            complete: function () {
              $('#loading-spinner').addClass('d-none');
            },
            success: function (response) {
              // Swal.fire('Berhasil', 'Gambar Telah Dihapus', 'success');
              if (response.success) {
                // Swal.fire('Berhasil', response.success, 'success');
                Swal.fire('Berhasil', response.success, 'success').then((result) => {
                  $(".sub_chk_dadix:checked").each(function () {
                    $(this).parents("tr").remove();
                  });
                })
              } else if (response.error) {
                Swal.fire('Gagal', response.error, 'warning');
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
              alert(xhr.status + '\n' + throwError);
            }
          });
        }
      });
    }
  });

});


var myChart;

$('.pickadate-formatdadix').pickadate({
  // Escape any 'rule' characters with an exclamation mark (!).
  format: 'dd mmmm yyyy',
  formatSubmit: 'mm/dd/yyyy',
  hiddenPrefix: 'prefix__',
  hiddenSuffix: '__suffix',
  selectMonths: true,
  selectYears: true
});

function formatNumber(n) {
  // format number 1000000 to 1,234,567
  return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

function hanya_number(evt) {
  evt.value = evt.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');
}

function formatCurrency(input, currency, blur) {
  // appends $ to value, validates decimal side
  // and puts cursor back in right position.
  // get input value
  var input_val = input.value;
  // don't validate empty input
  if (input_val === "") {
    return;
  }

  // original length
  var original_len = input_val.length;

  // initial caret position
  var caret_pos = input.selectionStart;

  // check for decimal
  if (input_val.indexOf(".") >= 0) {
    // get position of first decimal
    // this prevents multiple decimals from
    // being entered
    var decimal_pos = input_val.indexOf(".");

    // split number by decimal point
    var left_side = input_val.substring(0, decimal_pos);
    var right_side = input_val.substring(decimal_pos);

    // add commas to left side of number
    left_side = formatNumber(left_side);

    // validate right side
    right_side = formatNumber(right_side);

    // On blur make sure 2 numbers after decimal
    if (blur === "blur") {
      right_side += "00";
    }

    // Limit decimal to only 2 digits
    right_side = right_side.substring(0, 2);

    // join number by .
    input_val = currency + left_side + "." + right_side;
  } else {
    // no decimal entered
    // add commas to number
    // remove all non-digits
    input_val = formatNumber(input_val);
    input_val = currency + input_val;

    // final formatting
    if (blur === "blur") {
      input_val += ".00";
    }
  }

  // send updated string to input
  input.value = input_val;

  // put caret back in the right position
  var updated_len = input_val.length;
  caret_pos = updated_len - original_len + caret_pos;
  input.setSelectionRange(caret_pos, caret_pos);
}


function grafik1(arr_surat1, arr_surat2) {

  var ctx = document.getElementById("chart_tmp1").getContext('2d');

  var gradientStroke1 = ctx.createLinearGradient(0, 0, 0, 300);
  gradientStroke1.addColorStop(0, '#6078ea');
  gradientStroke1.addColorStop(1, '#17c5ea');

  var gradientStroke2 = ctx.createLinearGradient(0, 0, 0, 300);
  gradientStroke2.addColorStop(0, '#ff8359');
  gradientStroke2.addColorStop(1, '#ffdf40');

  var myChart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
      datasets: [{
        label: 'Ber Agenda',
        data: arr_surat1,
        borderColor: gradientStroke1,
        backgroundColor: gradientStroke1,
        hoverBackgroundColor: gradientStroke1,
        pointRadius: 0,
        fill: false,
        borderWidth: 0
      }, {
        label: 'Non Agenda',
        data: arr_surat2,
        borderColor: gradientStroke2,
        backgroundColor: gradientStroke2,
        hoverBackgroundColor: gradientStroke2,
        pointRadius: 0,
        fill: false,
        borderWidth: 0
      }]
    },

    options: {
      maintainAspectRatio: false,
      legend: {
        position: 'bottom',
        display: false,
        labels: {
          boxWidth: 8
        }
      },
      tooltips: {
        displayColors: false,
      },
      scales: {
        xAxes: [{
          barPercentage: .5
        }]
      }
    }
  });

}

function grafik2(jmltamu0, jmltamu1) {
  // BUAT GRAFIK BULAT DI BERANDA
  var ctx = document.getElementById("chart_tmp2").getContext('2d');

  var gradientStroke5 = ctx.createLinearGradient(0, 0, 0, 300);
  gradientStroke5.addColorStop(0, '#7f00ff');
  gradientStroke5.addColorStop(1, '#e100ff');

  var gradientStroke6 = ctx.createLinearGradient(0, 0, 0, 300);
  gradientStroke6.addColorStop(0, '#fc4a1a');
  gradientStroke6.addColorStop(1, '#f7b733');


  //   var gradientStroke7 = ctx.createLinearGradient(0, 0, 0, 300);
  //   gradientStroke7.addColorStop(0, '#283c86');
  //   gradientStroke7.addColorStop(1, '#45a247');

  var myChart = new Chart(ctx, {
    type: 'pie',
    data: {
      labels: ["Ber Agenda", "Non Agenda"],
      datasets: [{
        backgroundColor: [
          gradientStroke5,
          gradientStroke6
        ],

        hoverBackgroundColor: [
          gradientStroke5,
          gradientStroke6
        ],

        data: [jmltamu1, jmltamu0]
      }]
    },
    options: {
      maintainAspectRatio: false,
      legend: {
        display: false
      },
      tooltips: {
        displayColors: false
      }
    }
  });


}

function destroy_chart1() {
  var ctx = document.getElementById("chart_tmp1").getContext('2d');

  var gradientStroke1 = ctx.createLinearGradient(0, 0, 0, 300);
  gradientStroke1.addColorStop(0, '#6078ea');
  gradientStroke1.addColorStop(1, '#17c5ea');

  var gradientStroke2 = ctx.createLinearGradient(0, 0, 0, 300);
  gradientStroke2.addColorStop(0, '#ff8359');
  gradientStroke2.addColorStop(1, '#ffdf40');

  let data = {
    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
    datasets: [{
      label: 'Ber Agenda',
      data: [],
      borderColor: gradientStroke1,
      backgroundColor: gradientStroke1,
      hoverBackgroundColor: gradientStroke1,
      pointRadius: 0,
      fill: false,
      borderWidth: 0
    }, {
      label: 'Non Agenda',
      data: [],
      borderColor: gradientStroke2,
      backgroundColor: gradientStroke2,
      hoverBackgroundColor: gradientStroke2,
      pointRadius: 0,
      fill: false,
      borderWidth: 0
    }]
  };


  // Configuration for the bar chart
  const config = {
    type: 'bar',
    data: data,
    options: {
      maintainAspectRatio: false,
      legend: {
        position: 'bottom',
        display: false,
        labels: {
          boxWidth: 8
        }
      },
      tooltips: {
        displayColors: false,
      },
      scales: {
        xAxes: [{
          barPercentage: .5
        }]
      },
      onHover: function (event, chartElement) {
        if (chartElement.length > 0) {
          // Update the data with random values when hovering
          data.datasets[0].data = arr_surat1;
          data.datasets[1].data = arr_surat2;
          myChart.update();
          // Update the chart to reflect new data
        }
      }
    }
  };
  const myChart = new Chart(ctx, config);

  // console.log("redraw");
  // $('canvas').parent().each(function() {
  $('#chart_tmp1').parent().each(function () {
    //get child canvas id
    childCanvasId = $(this).find("canvas").attr('id');
    //   childCanvasId =document.getElementById("chart_tmp1")
    // in same pages we could have <canvas> elements without id, therefore childCanvasId is undefined
    //   if (childCanvasId !== undefined) {
    // destroy first each charts, to avoid error Uncaught TypeError: Cannot read property 'currentStyle' of null
    // todo bug: errore sopra, selezionando periodi senza dati (e.g. 10/11 settembre) e poi cambiando periodo con dati (e.g. last week)!
    // if (myChart != null) {
    //     myChart.destroy();
    //   console.log("destroyed!");

    // }
    //remove canvas
    $('#' + childCanvasId).remove();
    // append new canvas to the parent again
    $(this).append('<canvas id="' + childCanvasId + '"></canvas>');
    //                    console.log("Canvas " + childCanvasId + " deleted and appended again.");
    //   }
  });
}

function destroy_chart2() {
  var ctx = document.getElementById("chart_tmp2").getContext('2d');

  var gradientStroke5 = ctx.createLinearGradient(0, 0, 0, 300);
  gradientStroke5.addColorStop(0, '#7f00ff');
  gradientStroke5.addColorStop(1, '#e100ff');

  var gradientStroke6 = ctx.createLinearGradient(0, 0, 0, 300);
  gradientStroke6.addColorStop(0, '#fc4a1a');
  gradientStroke6.addColorStop(1, '#f7b733');


  //   var gradientStroke7 = ctx.createLinearGradient(0, 0, 0, 300);
  //   gradientStroke7.addColorStop(0, '#283c86');
  //   gradientStroke7.addColorStop(1, '#45a247');

  var myChart = new Chart(ctx, {
    type: 'pie',
    data: {
      labels: ["Ber Agenda", "Non Agenda"],
      datasets: [{
        backgroundColor: [
          gradientStroke5,
          gradientStroke6
        ],

        hoverBackgroundColor: [
          gradientStroke5,
          gradientStroke6
        ],

        data: [0, 0]
      }]
    },
    options: {
      maintainAspectRatio: false,
      legend: {
        display: false
      },
      tooltips: {
        displayColors: false
      }
    }
  });

  // console.log("redraw");
  // $('canvas').parent().each(function() {
  $('#chart_tmp2').parent().each(function () {
    //get child canvas id
    childCanvasId = $(this).find("canvas").attr('id');
    //   childCanvasId =document.getElementById("chart_tmp1")
    // in same pages we could have <canvas> elements without id, therefore childCanvasId is undefined
    //   if (childCanvasId !== undefined) {
    // destroy first each charts, to avoid error Uncaught TypeError: Cannot read property 'currentStyle' of null
    // todo bug: errore sopra, selezionando periodi senza dati (e.g. 10/11 settembre) e poi cambiando periodo con dati (e.g. last week)!
    // if (myChart != null) {
    //     myChart.destroy();
    //   console.log("destroyed!");

    // }
    //remove canvas
    $('#' + childCanvasId).remove();
    // append new canvas to the parent again
    $(this).append('<canvas id="' + childCanvasId + '"></canvas>');
    //                    console.log("Canvas " + childCanvasId + " deleted and appended again.");
    //   }
  });
}