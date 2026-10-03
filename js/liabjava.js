
//  Change color of active nav link
let navlaib = document.getElementById('liability');
navlaib.className = 'w3-bar-item w3-padding w3-active-blue';


// Liability Donut Chart
let chrt = document.getElementById("liabDonut").getContext("2d");
Chart.defaults.font.family = "sans-serif";
Chart.defaults.font.size = 13;
Chart.defaults.color = "white";

let chartId = new Chart(chrt, {
  type: 'doughnut',
  data: {
    labels: catname,
    datasets: [{
      label: '',
      data: liability,
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
        display: true,
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
        text: 'Liability',
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
// Liability Payment Chart
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

let chrt2 = document.getElementById("liabPayments").getContext("2d");
Chart.defaults.font.family = "sans-serif";
Chart.defaults.font.size = 13;
Chart.defaults.elements.point.hoverRadius = 13;
Chart.defaults.elements.point.radius = 7;

let chartId2 = new Chart(chrt2, {
  type: 'line',
  data: {
    labels: mdate,
    datasets: [{
      label: '',
      data: liaexp,
      backgroundColor: "rgba(65, 116, 201, 0.2)",
      borderColor: "rgba(65, 116, 201, 1)",
      tension: 0.3,
      fill: 'origin'
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
}
// Set default date on add APY modal popup
$("#liabilityAddModal").on("show.bs.modal", function() {
  $('#id').focus();
  var now = new Date();
  var day = ("0" + now.getDate()).slice(-2);
  var month = ("0" + (now.getMonth() + 1)).slice(-2);

  var today = (now.getFullYear() + '-' + month + '-' + day);
  //alert(today);
  $('#dateLia').val(today);
})
$(document).on("submit", "#saveLiability", function(e) {
  e.preventDefault();
  let a = new FormData(this);
  a.append("save_liability", !0),
    $.ajax({
      type: "POST",
      url: "action/liaupdel.php",
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
            $("#liaTable").load(location.href + " #liaTable"))
          : 200 == a.status
            ? ($("#errorMessage").addClass("d-none"),
              $("#liabilityAddModal").modal("hide"),
              $("#saveLiability")[0].reset(),
              alertify.set("notifier", "position", "top-right"),
              alertify.success(a.message),
              $("#liaTable").load(location.href + " #liaTable"))
            : 500 == a.status && alertify.error(a.message);
      },
    });
}),
  $(document).on("click", ".editLiaBtn", function() {
    let e = $(this).val();
    $.ajax({
      type: "GET",
      url: "action/liaupdel.php?id=" + e,
      success: function(e) {
        let a = jQuery.parseJSON(e);
        404 == a.status
          ? alert(a.message)
          : 200 == a.status &&
          ($("#id").val(a.data.id),
            $("#date").val(a.data.date),
            $("#amount").val(a.data.amount),
            $("#apr").val(a.data.apr),
            $("#liab_type").val(a.data.liab_type),
            $("#cat_name").val(a.data.cat_id),
            $("#liaEditModal").modal("show"));
      },
    });
  }),
  $(document).on("submit", "#updateLia", function(e) {
    e.preventDefault();
    let a = new FormData(this);
    a.append("update_liability", !0),
      $.ajax({
        type: "POST",
        url: "action/liaupdel.php",
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
              $("#liaTable").load(location.href + " #liaTable"))
            : 200 == a.status
              ? ($("#errorMessageUpdate").addClass("d-none"),
                alertify.set("notifier", "position", "top-right"),
                alertify.success(a.message),
                $("#liaEditModal").modal("hide"),
                $("#updateLia")[0].reset(),
                $("#liaTable").load(location.href + " #liaTable"))
              : 500 == a.status && alertify.error(a.message);
        },
      });
  }),
  $(document).on("click", ".deleteLiaBtn", function(e) {
    if (
      (e.preventDefault(),
        confirm("Are you sure you want to delete this account? This action may affect the Liabilities Payment section but not any associated transactions or account balances."))
    ) {
      let e = $(this).val();
      $.ajax({
        type: "POST",
        url: "action/liaupdel.php",
        data: { delete_liability: !0, id: e },
        success: function(e) {
          let a = jQuery.parseJSON(e);
          422 == a.status
            ? (alertify.set("notifier", "position", "top-right"),
              alertify.error(a.message),
              $("#liaTable").load(location.href + " #liaTable"))
            : 200 == a.status
              ? (alertify.set("notifier", "position", "top-right"),
                alertify.success(a.message),
                $("#liaTable").load(location.href + " #liaTable"))
              : 500 == a.status && alertify.error(a.message);
        },
      });
    }
  });
    // Toggle Hide account checkbox
$(document).on("click", "#hideAcct", function() {
  if ($(this).prop('checked')) {
    let e = $(this).val();
    $.ajax({
      url: 'action/liaupdel.php',
      type: 'POST',
      data: { hide: 1, id: e },
      success: function(e) {
        let a = jQuery.parseJSON(e);
        422 == a.status
          ? (alertify.set("notifier", "position", "top-right"),
            alertify.error(a.message))
          : 200 == a.status
            ? (alertify.set("notifier", "position", "top-right"),
              alertify.success(a.message))
            : 500 == a.status && alertify.error(a.message);
      }

    });
  } else {
    let e = $(this).val();
    $.ajax({
      url: 'action/liaupdel.php',
      type: 'POST',
      data: { hide: 0, id: e },
      success: function(e) {
        let a = jQuery.parseJSON(e);
        422 == a.status
          ? (alertify.set("notifier", "position", "top-right"),
            alertify.error(a.message))
          : 200 == a.status
            ? (alertify.set("notifier", "position", "top-right"),
              alertify.success(a.message))
            : 500 == a.status && alertify.error(a.message);
      }

    });
  }
});
