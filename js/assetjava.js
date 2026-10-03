
//  Change color of active nav link
let navasst = document.getElementById('asset');
navasst.className = 'w3-bar-item w3-padding w3-active-blue';


// Asset Donut Chart
let chrt = document.getElementById("asstDonut").getContext("2d");
Chart.defaults.font.family = "sans-serif";
Chart.defaults.font.size = 13;
Chart.defaults.color = "white";

let chartId = new Chart(chrt, {
  type: 'doughnut',
  data: {
    labels: catname,
    datasets: [{
      label: '',
      data: assetamt,
      backgroundColor: [
        'rgba(60, 157, 78, 0.2)',
        'rgba(65, 116, 201, 0.2)',
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
        'rgba(124, 221, 221, 0.2)'
      ],
      borderColor: [
        'rgb(60, 157, 78)',
        'rgb(65, 116, 201)',
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
        'rgb(124, 221, 221)'
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

// Asset Risk Donut Chart
let chrt2 = document.getElementById("asstRisk").getContext("2d");
Chart.defaults.font.family = "sans-serif";
Chart.defaults.font.size = 13;
Chart.defaults.color = "white";


// Join both arrays into one object array
let riskamts = risks.map((a, i) => ({ risk: a, amount: amounts[i] }));

// Merge values in Object array "riskamts" with array_reduce function to form new Object array riskAmts
let holder = {};

riskamts.forEach(function(d) {
  if (holder.hasOwnProperty(d.risk)) {
    holder[d.risk] = holder[d.risk] + d.amount;
  } else {
    holder[d.risk] = d.amount;
  }
});

let riskAmts = [];

for (let prop in holder) {
  riskAmts.push({ risk: prop, amount: holder[prop] });
}

// labelsort function - sort the risk labels and amounts (Low, Moderate, High)
function labelsort(a, b) {
  // Define the array structure
  let riskLabels = ["Low", "Moderate", "High"];
  let Low = riskLabels.indexOf(a.risk);
  let Moderate = riskLabels.indexOf(b.risk)
  return Low - Moderate
};

riskAmts.sort(labelsort);

// Merge values in Object array "riskamts" with array_reduce function to form new Object array riskLbls
let holder2 = {};

riskamts.forEach(function(d) {
  if (holder2.hasOwnProperty(d.risk)) {
    holder2[d.risk] = holder2[d.risk] + d.amount;
  } else {
    holder2[d.risk] = d.amount;
  }
});


// Assign riskLbls
let riskLbls = [];

for (let prop in holder2) {
  riskLbls.push({ risk: prop, amount: holder2[prop] });
}

// use labelsort function to sort labels
riskLbls.sort(labelsort);

// Remove key "risk:" from riskAmts
for (var i = 0, len = riskAmts.length; i < len; i++) {
  delete riskAmts[i].risk;
}

// Remove key "amount:" from riskAmst but leave values
// Dump object array into riskAmtsFin array and feed it to chartjs
let riskAmtsFin = [];
for (i of riskAmts) {
  riskAmtsFin.push(...Object.values(i))
}


// Remove key "amount:" from riskLbls
for (var i = 0, len = riskLbls.length; i < len; i++) {
  delete riskLbls[i].amount;
}


// Remove key "risk:" from riskLbls but leave values
// Dump object array into riskLblsFin array and feed it to chartjs
let riskLblsFin = [];
for (i of riskLbls) {
  riskLblsFin.push(...Object.values(i))
}


// // Total risks together
// let riskTotal = 0;
// for (let i = 0; i < riskAmtsFin.length; i++){
//   riskTotal += riskAmtsFin[i];
// }

// // Divide each risk by riskTotal for each percentage
// let divisor = 100;
// for(var i = 0, len = riskAmts.length; i < len; i ++){
//   riskAmts[i] = {'amount':riskAmts.amount/divisor};
// }

// console.log(riskAmts);
// console.log(riskAmts);

let chartId2 = new Chart(chrt2, {
  type: 'doughnut',
  data: {
    labels: riskLblsFin,
    datasets: [{
      label: '',
      data: riskAmtsFin,
      backgroundColor: [
        'rgba(75, 192, 192, 0.2)',
        'rgba(255, 205, 86, 0.2)',
        'rgba(255, 99, 132, 0.2)'
      ],
      borderColor: [
        'rgb(75, 192, 192)',
        'rgb(255, 205, 86)',
        'rgb(255, 99, 132)',
      ],
      borderWidth: 1,
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

// Onclick show/hide tables
let btn = document.getElementById("showhide");
btn.onclick = function myFunction() {
  let t = document.getElementById("show");
  -1 == t.className.indexOf("w3-show")
    ? (t.className += " w3-show")
    : (t.className = t.className.replace(" w3-show", ""));
}
// Set default date on add Asset modal popup
$("#assetAddModal").on("show.bs.modal", function() {
  $('#id').focus();
  var now = new Date();
  var day = ("0" + now.getDate()).slice(-2);
  var month = ("0" + (now.getMonth() + 1)).slice(-2);

  var today = (now.getFullYear() + '-' + month + '-' + day);
  //alert(today);
  $('#dateAss').val(today);
})
$(document).on("submit", "#saveAsset", function(e) {
  e.preventDefault();
  let a = new FormData(this);
  a.append("save_asset", !0),
    $.ajax({
      type: "POST",
      url: "action/assupdel.php",
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
            $("#assTable").load(location.href + " #assTable"))
          : 200 == a.status
            ? ($("#errorMessage").addClass("d-none"),
              $("#assetAddModal").modal("hide"),
              $("#saveAsset")[0].reset(),
              alertify.set("notifier", "position", "top-right"),
              alertify.success(a.message),
              $("#assTable").load(location.href + " #assTable"))
            : 500 == a.status && alertify.error(a.message);
      },
    });
}),
  $(document).on("click", ".editAssBtn", function() {
    let e = $(this).val();
    $.ajax({
      type: "GET",
      url: "action/assupdel.php?id=" + e,
      success: function(e) {
        let a = jQuery.parseJSON(e);
        404 == a.status
          ? alert(a.message)
          : 200 == a.status &&
          ($("#id").val(a.data.id),
            $("#date").val(a.data.date),
            $("#cat_name").val(a.data.cat_id),
            $("#amount").val(a.data.amount),
            $("#apy").val(a.data.apy),
            $("#risk").val(a.data.risk),
            $("#asset_type").val(a.data.asset_type),
            $("#assEditModal").modal("show"));
      },
    });
  }),
  $(document).on("submit", "#updateAss", function(e) {
    e.preventDefault();
    let a = new FormData(this);
    a.append("update_asset", !0),
      $.ajax({
        type: "POST",
        url: "action/assupdel.php",
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
              $("#assTable").load(location.href + " #assTable"))
            : 200 == a.status
              ? ($("#errorMessageUpdate").addClass("d-none"),
                alertify.set("notifier", "position", "top-right"),
                alertify.success(a.message),
                $("#assEditModal").modal("hide"),
                $("#updateAss")[0].reset(),
                $("#assTable").load(location.href + " #assTable"))
              : 500 == a.status && alertify.error(a.message);
        },
      });
  }),
  $(document).on("click", ".deleteAssBtn", function(e) {
    if (
      (e.preventDefault(),
        confirm("Are you sure you want to delete this account? This action will not affect any associated transactions or account balances."))
    ) {
      let e = $(this).val();
      $.ajax({
        type: "POST",
        url: "action/assupdel.php",
        data: { delete_asset: !0, id: e },
        success: function(e) {
          let a = jQuery.parseJSON(e);
          422 == a.status
            ? (alertify.set("notifier", "position", "top-right"),
              alertify.error(a.message),
              $("#assTable").load(location.href + " #assTable"))
            : 200 == a.status
              ? (alertify.set("notifier", "position", "top-right"),
                alertify.success(a.message),
                $("#assTable").load(location.href + " #assTable"))
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
      url: 'action/assupdel.php',
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
      url: 'action/assupdel.php',
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
