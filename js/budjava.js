//  Change color of active nav link
let navbud = document.getElementById("budget");
navbud.className = "w3-bar-item w3-padding w3-active-blue";

// Donut Chart
const chrt = document.getElementById("donut").getContext("2d");
Chart.defaults.font.family = "sans-serif";
Chart.defaults.font.size = 13;
Chart.defaults.color = "white";

const chartId = new Chart(chrt, {
  type: 'doughnut',
  data: {
    labels: budname,
    datasets: [{
      label: '',
      data: budexpense,
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

// Budget bar Chart
const chart1 = document.getElementById("budChart");

const budChart = new Chart(chart1, {
  type: "bar",
  data: {
    labels: labels,
    datasets: [
      {
        label: "Budgets",
        data: amount,
        backgroundColor: [
          "rgba(255, 99, 132, 0.2)",
          "rgba(255, 159, 64, 0.2)",
          "rgba(255, 205, 86, 0.2)",
          "rgba(75, 192, 192, 0.2)",
          "rgba(54, 162, 235, 0.2)",
          "rgba(153, 102, 255, 0.2)",
          "rgba(201, 203, 207, 0.2)",
        ],
        borderColor: [
          "rgb(255, 99, 132)",
          "rgb(255, 159, 64)",
          "rgb(255, 205, 86)",
          "rgb(75, 192, 192)",
          "rgb(54, 162, 235)",
          "rgb(153, 102, 255)",
          "rgb(201, 203, 207)",
        ],
        borderWidth: 1,
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
          color: "rgba(128,128,128,0.4)"

        }
      },
      x: {
        border: {
          display: false
        },
        grid: {
          display: false,
          color: ""

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
// Set default date on add Budget modal popup
$("#budgetAddModal").on("show.bs.modal", function() {
  $('#id').focus();
  var now = new Date();
  var day = ("0" + now.getDate()).slice(-2);
  var month = ("0" + (now.getMonth() + 1)).slice(-2);

  var today = (now.getFullYear() + '-' + month + '-' + day);
  //alert(today);
  $('#dateBud').val(today);
})
$(document).on("submit", "#saveBudget", function(e) {
  e.preventDefault();
  let t = new FormData(this);
  t.append("save_budget", !0),
    $.ajax({
      type: "POST",
      url: "action/budupdel.php",
      data: t,
      processData: !1,
      contentType: !1,
      success: function(e) {
        let t = jQuery.parseJSON(e);
        422 == t.status
          ? ($("#errorMessage").removeClass("d-none"),
            $("#errorMessage").text(t.message),
            alertify.set("notifier", "position", "top-right"),
            alertify.error(t.message),
            $("#budTable").load(location.href + " #budTable"))
          : 200 == t.status
            ? ($("#errorMessage").addClass("d-none"),
              $("#budgetAddModal").modal("hide"),
              $("#saveBudget")[0].reset(),
              alertify.set("notifier", "position", "top-right"),
              alertify.success(t.message),
              $("#budTable").load(location.href + " #budTable"))
            : 500 == t.status && alertify.error(t.message);
      },
    });
}),
  $(document).on("click", ".editBudBtn", function() {
    let e = $(this).val();
    $.ajax({
      type: "GET",
      url: "action/budupdel.php?id=" + e,
      success: function(e) {
        let t = jQuery.parseJSON(e);
        404 == t.status
          ? alert(t.message)
          : 200 == t.status &&
          ($("#id").val(t.data.id),
            $("#date").val(t.data.date),
            $("#cat_name").val(t.data.cat_id),
            $("#amount").val(t.data.amount),
            $("#budEditModal").modal("show"));
      },
    });
  }),
  $(document).on("submit", "#updateBud", function(e) {
    e.preventDefault();
    let t = new FormData(this);
    t.append("update_budget", !0),
      $.ajax({
        type: "POST",
        url: "action/budupdel.php",
        data: t,
        processData: !1,
        contentType: !1,
        success: function(e) {
          let t = jQuery.parseJSON(e);
          422 == t.status
            ? ($("#errorMessageUpdate").removeClass("d-none"),
              $("#errorMessageUpdate").text(t.message),
              alertify.set("notifier", "position", "top-right"),
              alertify.error(t.message),
              $("#budTable").load(location.href + " #budTable"))
            : 200 == t.status
              ? ($("#errorMessageUpdate").addClass("d-none"),
                alertify.set("notifier", "position", "top-right"),
                alertify.success(t.message),
                $("#budEditModal").modal("hide"),
                $("#updateBud")[0].reset(),
                $("#budTable").load(location.href + " #budTable"))
              : 500 == t.status && alertify.error(t.message);
        },
      });
  }),
  $(document).on("click", ".deleteBudBtn", function(e) {
    if (
      (e.preventDefault(),
        confirm("Are you sure you want to delete this data?"))
    ) {
      let e = $(this).val();
      $.ajax({
        type: "POST",
        url: "action/budupdel.php",
        data: { delete_budget: !0, id: e },
        success: function(e) {
          let t = jQuery.parseJSON(e);
          422 == t.status
            ? (alertify.set("notifier", "position", "top-right"),
              alertify.error(t.message),
              $("#budTable").load(location.href + " #budTable"))
            : 200 == t.status
              ? (alertify.set("notifier", "position", "top-right"),
                alertify.success(t.message),
                $("#budTable").load(location.href + " #budTable"))
              : 500 == t.status && alertify.error(t.message);
        },
      });
    }
  });
