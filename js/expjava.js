// Side nav highlight CSS
let navexp = document.getElementById('expense');
navexp.className = 'w3-bar-item w3-padding w3-active-blue';


// Donut Chart
const chrt = document.getElementById("donut").getContext("2d");
Chart.defaults.font.family = "sans-serif";
Chart.defaults.font.size = 13;
Chart.defaults.color = "white";

const chartId = new Chart(chrt, {
  type: 'doughnut',
  data: {
    labels: catname,
    datasets: [{
      label: '',
      data: expense,
      backgroundColor: [
        'rgba(246,109, 68, 0.2)',
        'rgba(254, 174, 101, 0.2)',
        'rgba(230, 246, 157, 0.2)',
        'rgba(170, 222, 167, 0.2)',
        'rgba(100, 194, 166, 0.2)',
        'rgba(45, 135, 187, 0.2)',
        'rgba(255, 236, 33, 0.2)',
        'rgba(55, 138, 255, 0.2)',
        'rgba(255, 163, 47, 0.2)',
        'rgba(245, 79, 82, 0.2)',
        'rgba(147, 240, 59, 0.2)',
        'rgba(149, 82, 234, 0.2)',
        'rgba(82, 215, 38, 0.2)',
        'rgba(255, 236, 0, 0.2)',
        'rgba(255, 115, 0, 0.2)',
        'rgba(255, 0, 0, 0.2)',
        'rgba(0, 126, 214, 0.2)',
        'rgba(124, 221, 221, 0.2)',
        'rgba(112, 49, 172, 0.2)',
        'rgba(60, 157, 78, 0.2)',
        'rgba(201, 77, 109, 0.2)',
        'rgba(65, 116, 201, 0.2)'
      ],
      borderColor: [
        'rgb(246,109, 68)',
        'rgb(254, 174, 101)',
        'rgb(230, 246, 157)',
        'rgb(170, 222, 167)',
        'rgb(100, 194, 166)',
        'rgb(45, 135, 187)',
        'rgb(255, 236, 33)',
        'rgb(55, 138, 255)',
        'rgb(255, 163, 47)',
        'rgb(245, 79, 82)',
        'rgb(147, 240, 59)',
        'rgb(149, 82, 234)',
        'rgb(82, 215, 38)',
        'rgb(255, 236, 0)',
        'rgb(255, 115, 0)',
        'rgb(255, 0, 0)',
        'rgb(0, 126, 214)',
        'rgb(124, 221, 221)',
        'rgb(112, 49, 172)',
        'rgb(60, 157, 78)',
        'rgb(201, 77, 109)',
        'rgb(65, 116, 201)'
      ],
      borderWidth: .75,
      hoverOffset: 5,
    }],
  },
  options: {
    responsive: true,
    layout: {
      padding: {
        bottom: 10,
        top: 10,
        left: 5,
        right: 5,
      }
    },
    plugins: {
      legend: {
        fullSize: true,
        position: 'left',
        textAlign: 'left',
        bottom: 5,
        top: 5,
        left: 5,
        right: 5,
      },
      title: {
        display: false,
        text: 'Monthly Expenses',
        position: 'top',
        align: 'end',
        padding: {
          top: 5,
          bottom: 5,
        },
        fullSize: true,
        font: {
          weight: 'bold',
          size: 15
        },

      }
    }
  },
});

// Expense bar Chart
// Custom ShadowLine - * problematic as it sits
// class Custom extends Chart.LineController {
//   draw() {
//     // Call the line controler method to draw points
//     super.draw(arguments);
//
//     const ctx = this.chart.ctx;
//     const _stroke = ctx.stroke;
//     ctx.stroke = function() {
//       ctx.save();
//       ctx.shadowColor = "black";
//       ctx.shadowBlur = 10;
//       ctx.shadowOffsetX = 0;
//       ctx.shadowOffsetY = 8;
//       _stroke.apply(this, arguments);
//       ctx.restore();
//     }
//   }
// };
//
// Custom.id = 'shadowLine';
// Custom.defaults = Chart.LineController.defaults;
//
// Chart.register(Custom);

const chart2 = document.getElementById('expChart');
// Set some point options
Chart.defaults.elements.point.hoverRadius = 13;
Chart.defaults.elements.point.radius = 7;

const expChart = new Chart(chart2, {
  type: 'line',
  data: {
    labels: expmonths,
    datasets: [{
      label: 'Expenses',
      data: expamount,
      backgroundColor: "rgba(25, 99, 132, 0.3)",
      borderColor: "rgba(238, 75, 58, 0.94)",
      tension: 0.3,
      fill: 'origin'
    }],
  },
  options: {
    layout: {
      padding: {
        bottom: 10,
        top: 10,
        left: 5,
        right: 5,
      }
    },
    scales: {
      y: {
        position: 'right',
        border: {
          display: false
        },
        grid: {
          color: "rgba(128,128,128,0.4)"

        }
      },
      x: {
        border: {
          display: false
        },
        grid: {
          display: true,
          color: "rgba(128,128,128,0.4)"
        }
      }
    },
    plugins: {
      legend: {
        display: false
      }
    }
  }
});

// Onclick show/hide tables
let btn = document.getElementById("showhide");
btn.onclick = function myFunction() {
  let t = document.getElementById("show");
  -1 == t.className.indexOf("w3-show")
    ? (t.className += " w3-show")
    : (t.className = t.className.replace(" w3-show", ""));
}
// Set default date on add Expense modal popup
$("#expenseAddModal").on("show.bs.modal", function() {
  $('#id').focus();
  var now = new Date();
  var day = ("0" + now.getDate()).slice(-2);
  var month = ("0" + (now.getMonth() + 1)).slice(-2);

  var today = (now.getFullYear() + '-' + month + '-' + day);
  //alert(today);
  $('#dateExp').val(today);
})
// Set default date on add Repayment modal popup
$("#repayAddModal").on("show.bs.modal", function() {
  $('#id').focus();
  var now = new Date();
  var day = ("0" + now.getDate()).slice(-2);
  var month = ("0" + (now.getMonth() + 1)).slice(-2);

  var today = (now.getFullYear() + '-' + month + '-' + day);
  //alert(today);
  $('#dateRep').val(today);
})
// Set default date on add Refund modal popup
$("#refundAddModal").on("show.bs.modal", function() {
  $('#id').focus();
  var now = new Date();
  var day = ("0" + now.getDate()).slice(-2);
  var month = ("0" + (now.getMonth() + 1)).slice(-2);

  var today = (now.getFullYear() + '-' + month + '-' + day);
  //alert(today);
  $('#dateRef').val(today);
})
// Set default date on add APR modal popup
$("#aprAddModal").on("show.bs.modal", function() {
  $('#id').focus();
  var now = new Date();
  var day = ("0" + now.getDate()).slice(-2);
  var month = ("0" + (now.getMonth() + 1)).slice(-2);

  var today = (now.getFullYear() + '-' + month + '-' + day);
  //alert(today);
  $('#dateAPR').val(today);
})
// Add APR Interest Expense
$(document).on("submit", "#aprExpense", function(e) {
  e.preventDefault();
  let a = new FormData(this);
  a.append("run_APR", !0),
    $.ajax({
      type: "POST",
      url: "action/expupdel.php",
      data: a,
      processData: !1,
      contentType: !1,
      success: function(e) {
        let a = jQuery.parseJSON(e);
        422 == a.status
          ? ($("#errorMessage").removeClass("d-none"),
            $("#errorMessage").text(a.message),
            alertify.set("notifier", "position", "top-right"),
            alertify.error(a.message),
            $("#expTable").load(location.href + " #expTable"))
          : 200 == a.status
            ? ($("#errorMessage").addClass("d-none"),
              $("#aprAddModal").modal("hide"),
              $("#aprExpense")[0].reset(),
              alertify.set("notifier", "position", "top-right"),
              alertify.success(a.message),
              $("#expTable").load(location.href + " #expTable"))
            : 500 == a.status && alertify.error(a.message);
      },
    });
}),

  $(document).on("submit", "#saveExpense", function(e) {
    e.preventDefault();
    let a = new FormData(this);
    a.append("save_expense", !0),
      $.ajax({
        type: "POST",
        url: "action/expupdel.php",
        data: a,
        processData: !1,
        contentType: !1,
        success: function(e) {
          let a = jQuery.parseJSON(e);
          422 == a.status
            ? ($("#errorMessage").removeClass("d-none"),
              $("#errorMessage").text(a.message),
              alertify.set("notifier", "position", "top-right"),
              alertify.error(a.message),
              $("#expTable").load(location.href + " #expTable"))
            : 200 == a.status
              ? ($("#errorMessage").addClass("d-none"),
                $("#expenseAddModal").modal("hide"),
                $("#saveExpense")[0].reset(),
                alertify.set("notifier", "position", "top-right"),
                alertify.success(a.message),
                $("#expTable").load(location.href + " #expTable"))
              : 500 == a.status && alertify.error(a.message);
        },
      });
  }),
  $(document).on("submit", "#saveRefund", function(e) {
    e.preventDefault();
    let a = new FormData(this);
    a.append("save_refund", !0),
      $.ajax({
        type: "POST",
        url: "action/expupdel.php",
        data: a,
        processData: !1,
        contentType: !1,
        success: function(e) {
          let a = jQuery.parseJSON(e);
          422 == a.status
            ? ($("#errorMessageRefund").removeClass("d-none"),
              $("#errorMessageRefund").text(a.message),
              alertify.set("notifier", "position", "top-right"),
              alertify.error(a.message),
              $("#expTable").load(location.href + " #expTable"))
            : 200 == a.status
              ? ($("#errorMessageRefund").addClass("d-none"),
                $("#refundAddModal").modal("hide"),
                $("#saveRefund")[0].reset(),
                alertify.set("notifier", "position", "top-right"),
                alertify.success(a.message),
                $("#expTable").load(location.href + " #expTable"))
              : 500 == a.status && alertify.error(a.message);
        },
      });
  }),
  $(document).on("submit", "#savePayment", function(e) {
    e.preventDefault();
    let a = new FormData(this);
    a.append("save_payment", !0),
      $.ajax({
        type: "POST",
        url: "action/expupdel.php",
        data: a,
        processData: !1,
        contentType: !1,
        success: function(e) {
          let a = jQuery.parseJSON(e);
          422 == a.status
            ? ($("#errorMessagePayment").removeClass("d-none"),
              $("#errorMessagePayment").text(a.message),
              alertify.set("notifier", "position", "top-right"),
              alertify.error(a.message),
              $("#expTable").load(location.href + " #expTable"))
            : 200 == a.status
              ? ($("#errorMessagePayment").addClass("d-none"),
                $("#repayAddModal").modal("hide"),
                $("#savePayment")[0].reset(),
                alertify.set("notifier", "position", "top-right"),
                alertify.success(a.message),
                $("#expTable").load(location.href + " #expTable"))
              : 500 == a.status && alertify.error(a.message);
        },
      });
  }),
  $(document).on("click", ".editExpBtn", function() {
    let e = $(this).val();
    $.ajax({
      type: "GET",
      url: "action/expupdel.php?id=" + e,
      success: function(e) {
        let a = jQuery.parseJSON(e);
        404 == a.status
          ? alert(a.message)
          : 200 == a.status &&
          ($("#id").val(a.data.id),
            $("#date").val(a.data.date),
            $("#descr").val(a.data.descr),
            $("#amount").val(a.data.amount),
            $("#cat_name").val(a.data.cat_id),
            $("#acct_name").val(a.data.acct_id),
            $("#chrg_type").val(a.data.chrg_type),
            $("#expEditModal").modal("show"));
      },
    });
  }),
  $(document).on("submit", "#updateExp", function(e) {
    e.preventDefault();
    let a = new FormData(this);
    a.append("update_expense", !0),
      $.ajax({
        type: "POST",
        url: "action/expupdel.php",
        data: a,
        processData: !1,
        contentType: !1,
        success: function(e) {
          let a = jQuery.parseJSON(e);
          422 == a.status
            ? ($("#errorMessageUpdate").removeClass("d-none"),
              $("#errorMessageUpdate").text(a.message),
              alertify.set("notifier", "position", "top-right"),
              alertify.error(a.message),
              $("#expTable").load(location.href + " #expTable"))
            : 200 == a.status
              ? ($("#errorMessageUpdate").addClass("d-none"),
                alertify.set("notifier", "position", "top-right"),
                alertify.success(a.message),
                $("#expEditModal").modal("hide"),
                $("#updateExp")[0].reset(),
                $("#expTable").load(location.href + " #expTable"))
              : 500 == a.status && alertify.error(a.message);
        },
      });
  }),
  $(document).on("click", ".deleteExpBtn", function(e) {
    if (
      (e.preventDefault(),
        confirm("Are you sure you want to delete this data?"))
    ) {
      let e = $(this).val();
      $.ajax({
        type: "POST",
        url: "action/expupdel.php",
        data: { delete_expense: !0, id: e },
        success: function(e) {
          let a = jQuery.parseJSON(e);
          422 == a.status
            ? (alertify.set("notifier", "position", "top-right"),
              alertify.error(a.message),
              $("#expTable").load(location.href + " #expTable"))
            : 200 == a.status
              ? (alertify.set("notifier", "position", "top-right"),
                alertify.success(a.message),
                $("#expTable").load(location.href + " #expTable"))
              : 500 == a.status && alertify.error(a.message);
        },
      });
    }
  });

// Expense Year selector for table
document.getElementById("tblExpYr").addEventListener('change', function() {
  // console.log('Year is: ', this.value);
  let tblYr = this.value;
  $.ajax({
    url: "./action/tblExpYr.php",
    method: "POST",
    data: {
      tblyr: tblYr
    },
    success: function(data) {
      $("#tblSrch").html(data)
    }
  })
})
// Expense Year dependant month selector for table
document.getElementById("tblExpYr").addEventListener('change', function() {
  // console.log('Year is: ', this.value);
  let tblYr = this.value;
  $.ajax({
    url: "./action/tblExpYrMn.php",
    method: "POST",
    data: {
      tblyr: tblYr
    },
    success: function(data) {
      $("#tblExpMn").html(data)
    }
  })
})
// Expense Month selector for table
document.getElementById("tblExpMn").addEventListener('change', function() {
  // console.log('Month is: ', this.value);
  let selectYr = document.getElementById("tblExpYr");
  let text = selectYr.options[selectYr.selectedIndex].text;
  // console.log('Year is: ', text);
  let tblMn = this.value;
  $.ajax({
    url: "./action/tblExpMn.php",
    method: "POST",
    data: {
      tblmn: tblMn,
      tblyr: text
    },
    success: function(data) {
      $("#tblSrch").html(data)
    }
  })
})
