//  Change color of active nav link
let navinc = document.getElementById("income");
navinc.className = "w3-bar-item w3-padding w3-active-blue";

// Income Donut
let chrt = document.getElementById("donut").getContext("2d");
Chart.defaults.font.family = "sans-serif";
Chart.defaults.font.size = 13;
Chart.defaults.color = "white";

let chartId = new Chart(chrt, {
  type: "doughnut",
  data: {
    labels: catname,
    datasets: [
      {
        label: "",
        data: income,
        backgroundColor: [
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
        borderWidth: 0.75,
        hoverOffset: 5,
      },
    ],
  },
  options: {
    responsive: true,
    layout: {
      padding: {
        bottom: 30,
        top: 30,
        left: 10,
        right: 10,
      },
    },
    plugins: {
      legend: {
        fullSize: true,
        position: "left",
        textAlign: "left",
        bottom: 5,
        top: 5,
        left: 5,
        right: 5,
      },
      title: {
        display: false,
        text: "Monthly incomes",
        position: "top",
        align: "end",
        padding: {
          top: 5,
          bottom: 5,
        },
        fullSize: true,
        font: {
          weight: "bold",
          size: 15,
        },
      },
    },
  },
});

// Income Line Chart
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
//       ctx.shadowColor = 'black';
//       ctx.shadowBlur = 10;
//       ctx.shadowOffsetX = 0;
//       ctx.shadowOffsetY = 4;
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

const chart3 = document.getElementById("incChart");
// Set some point options
Chart.defaults.elements.point.hoverRadius = 13;
Chart.defaults.elements.point.radius = 7;

const incChart = new Chart(chart3, {
  type: "line",
  data: {
    labels: labels3,
    datasets: [
      {
        label: "Income",
        data: amount,
        backgroundColor: "rgba(25, 99, 132, 0.3)",
        borderColor: "rgba(16, 177, 7, 0.8)",
        tension: 0.3,
        fill: 'origin'
      },
    ],
  },
  options: {
    responsive: true,
    layout: {
      padding: {
        bottom: 5,
        top: 30,
        left: 5,
        right: 5,
      },
    },
    scales: {
      y: {
        position: 'right',
        border: {
          display: false
        },
        grid: {
          display: true,
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
        display: false,
      },
    },
  },
});

// Onclick show/hide tables
let btn = document.getElementById("showhide");
btn.onclick = function myFunction() {
  let t = document.getElementById("show");
  -1 == t.className.indexOf("w3-show")
    ? (t.className += " w3-show")
    : (t.className = t.className.replace(" w3-show", ""));
};
// Set default date on add APY modal popup
$("#apyAddModal").on("show.bs.modal", function() {
  $('#id').focus();
  var now = new Date();
  var day = ("0" + now.getDate()).slice(-2);
  var month = ("0" + (now.getMonth() + 1)).slice(-2);

  var today = (now.getFullYear() + '-' + month + '-' + day);
  //alert(today);
  $('#dateAPY').val(today);
})
// Set default date on add Income modal popup
$("#incomeAddModal").on("show.bs.modal", function() {
  $('#id').focus();
  var now = new Date();
  var day = ("0" + now.getDate()).slice(-2);
  var month = ("0" + (now.getMonth() + 1)).slice(-2);

  var today = (now.getFullYear() + '-' + month + '-' + day);
  //alert(today);
  $('#dateInc').val(today);
})
// Set default date on add Loan modal popup
$("#loanAddModal").on("show.bs.modal", function() {
  $('#id').focus();
  var now = new Date();
  var day = ("0" + now.getDate()).slice(-2);
  var month = ("0" + (now.getMonth() + 1)).slice(-2);

  var today = (now.getFullYear() + '-' + month + '-' + day);
  //alert(today);
  $('#dateLoan').val(today);
})
// Add APY Interest Income
$(document).on("submit", "#runAPY", function(e) {
  e.preventDefault();
  let a = new FormData(this);
  a.append("run_APY", !0),
    $.ajax({
      type: "POST",
      url: "action/incupdel.php",
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
            $("#incTable").load(location.href + " #incTable"))
          : 200 == a.status
            ? ($("#errorMessage").addClass("d-none"),
              $("#apyAddModal").modal("hide"),
              $("#runAPY")[0].reset(),
              alertify.set("notifier", "position", "top-right"),
              alertify.success(a.message),
              $("#incTable").load(location.href + " #incTable"))
            : 500 == a.status && alertify.error(a.message);
      },
    });
}),
  // Add Income
  $(document).on("submit", "#saveIncome", function(e) {
    e.preventDefault();
    let a = new FormData(this);
    a.append("save_income", !0),
      $.ajax({
        type: "POST",
        url: "action/incupdel.php",
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
              $("#incTable").load(location.href + " #incTable"))
            : 200 == a.status
              ? ($("#errorMessage").addClass("d-none"),
                $("#incomeAddModal").modal("hide"),
                $("#saveIncome")[0].reset(),
                alertify.set("notifier", "position", "top-right"),
                alertify.success(a.message),
                $("#incTable").load(location.href + " #incTable"))
              : 500 == a.status && alertify.error(a.message);
        },
      });
  }),
  // Add Loan
  $(document).on("submit", "#saveLoan", function(e) {
    e.preventDefault();
    let a = new FormData(this);
    a.append("save_loan", !0),
      $.ajax({
        type: "POST",
        url: "action/incupdel.php",
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
              $("#incTable").load(location.href + " #incTable"))
            : 200 == a.status
              ? ($("#errorMessage").addClass("d-none"),
                $("#loanAddModal").modal("hide"),
                $("#saveLoan")[0].reset(),
                alertify.set("notifier", "position", "top-right"),
                alertify.success(a.message),
                $("#incTable").load(location.href + " #incTable"))
              : 500 == a.status && alertify.error(a.message);
        },
      });
  }),
  $(document).on("click", ".editIncBtn", function() {
    let e = $(this).val();
    $.ajax({
      type: "GET",
      url: "action/incupdel.php?id=" + e,
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
            $("#incEditModal").modal("show"));
      },
    });
  }),
  $(document).on("submit", "#updateInc", function(e) {
    e.preventDefault();
    let a = new FormData(this);
    a.append("update_income", !0),
      $.ajax({
        type: "POST",
        url: "action/incupdel.php",
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
              $("#incTable").load(location.href + " #incTable"))
            : 200 == a.status
              ? ($("#errorMessageUpdate").addClass("d-none"),
                alertify.set("notifier", "position", "top-right"),
                alertify.success(a.message),
                $("#incEditModal").modal("hide"),
                $("#updateInc")[0].reset(),
                $("#incTable").load(location.href + " #incTable"))
              : 500 == a.status && alertify.error(a.message);
        },
      });
  }),
  $(document).on("click", ".deleteIncBtn", function(e) {
    if (
      (e.preventDefault(),
        confirm("Are you sure you want to delete this data?"))
    ) {
      let e = $(this).val();
      $.ajax({
        type: "POST",
        url: "action/incupdel.php",
        data: { delete_income: !0, id: e },
        success: function(e) {
          let a = jQuery.parseJSON(e);
          422 == a.status
            ? (alertify.set("notifier", "position", "top-right"),
              alertify.error(a.message),
              $("#incTable").load(location.href + " #incTable"))
            : 200 == a.status
              ? (alertify.set("notifier", "position", "top-right"),
                alertify.success(a.message),
                $("#incTable").load(location.href + " #incTable"))
              : 500 == a.status && alertify.error(a.message);
        },
      });
    }
  });
// // Income Month selector
// document.getElementById("donutExpMnth").addEventListener('change', function() {
//     console.log('Month is: ', this.value);
//     let expMnth = this.value;
//       $.ajax({
//         url:"./action/doExpMnth.php",
//         method: "POST",
//         data:{
//           mnth : expMnth
//         },
//         success:function(data){
//           $("#donutRes").html(data)
//         }
//       })
//     })

// // Income Year selector for Top 7 bar chart
// document.getElementById("bar7Expyr").addEventListener('change', function() {
//     console.log('Year is: ', this.value);
//     let expYr = this.value;
//       $.ajax({
//         url:"./action/doExpYr.php",
//         method: "POST",
//         data:{
//           yr : expYr
//         },
//         success:function(data){
//           $("#donutRes").html(data)
//         }
//       })
//     })

// Income Year selector for table
document.getElementById("tblIncYr").addEventListener('change', function() {
  // console.log('Year is: ', this.value);
  let tblYr = this.value;
  $.ajax({
    url: "./action/tblIncYr.php",
    method: "POST",
    data: {
      tblyr: tblYr
    },
    success: function(data) {
      $("#tblSrch").html(data)
    }
  })
})
// Income Year month dependent selector for table
document.getElementById("tblIncYr").addEventListener('change', function() {
  // console.log('Year is: ', this.value);
  let tblYr = this.value;
  $.ajax({
    url: "./action/tblIncYrMn.php",
    method: "POST",
    data: {
      tblyr: tblYr
    },
    success: function(data) {
      $("#tblIncMn").html(data)
    }
  })
})
// Income Month selector for table
document.getElementById("tblIncMn").addEventListener('change', function() {
  // console.log('Month is: ', this.value);
  let selectYr = document.getElementById("tblIncYr");
  let text = selectYr.options[selectYr.selectedIndex].text;
  // console.log('Year is: ', text);
  let tblMn = this.value;
  $.ajax({
    url: "./action/tblIncMn.php",
    method: "POST",
    data: {
      tblmn: tblMn,
      yr: text
    },
    success: function(data) {
      $("#tblSrch").html(data)
    }
  })
})
