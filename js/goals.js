// //  Change color of active nav link
let navgoal = document.getElementById("goals");
navgoal.className = "w3-bar-item w3-padding w3-active-blue";

// // Goal Bar Chart
// const chart1 = document.getElementById("goalChart");
// const curbals = curbal.filter(function(val) {
//   return val !== 0;
// });
// const amount = amounts.filter(function(val) {
//   return val !== null;
// });
// let percentage = curbals.map(function(n, i) {
//   return n / amount[i];
// });
// let percentages = percentage.map(function(each_element){
//   return Number((each_element.toFixed(2)) * 100);
// });
// console.log(percentages);
// const goalChart = new Chart(chart1, {
//   type: "bar",
//   data: {
//     labels: goals,
//     datasets: [
//       {
//         label: "Percent",
//         data: percentages,
//         backgroundColor: [
//           "rgba(255, 99, 132, 0.2)",
//           "rgba(255, 159, 64, 0.2)",
//           "rgba(255, 205, 86, 0.2)",
//           "rgba(75, 192, 192, 0.2)",
//           "rgba(54, 162, 235, 0.2)",
//           "rgba(153, 102, 255, 0.2)",
//           "rgba(201, 203, 207, 0.2)",
//         ],
//         borderColor: [
//           "rgb(255, 99, 132)",
//           "rgb(255, 159, 64)",
//           "rgb(255, 205, 86)",
//           "rgb(75, 192, 192)",
//           "rgb(54, 162, 235)",
//           "rgb(153, 102, 255)",
//           "rgb(201, 203, 207)",
//         ],
//         borderWidth: 1,
//       },
//     ],
//   },
//   options: {
//     indexAxis: 'y',
//     responsive: true,
//     layout: {
//       padding: {
//         bottom: 5,
//         top: 30,
//         left: 5,
//         right: 5,
//       },
//     },
//     scales: {
//       y: {
//         border: {
//           display: false
//         },
//         grid: {
//           color: "rgba(128,128,128,0.4)"
//
//           }
//         },
//         x: {
//           min: 0,
//           max: 100,
//
//           border: {
//             display: false
//           },
//           grid: {
//             display: false,
//             color: ""
//
//             }
//           }
//     },
//     plugins: {
//       legend: {
//         display: false,
//       },
//     },
//   },
// });

// Onclick show/hide tables
let btn = document.getElementById("showhide");
btn.onclick = function myFunction() {
  let t = document.getElementById("show");
  -1 == t.className.indexOf("w3-show")
    ? (t.className += " w3-show")
    : (t.className = t.className.replace(" w3-show", ""));
};
// Set default date on add Goals modal popup
$("#goalAddModal").on("show.bs.modal", function() {
  $('#id').focus();
  var now = new Date();
  var day = ("0" + now.getDate()).slice(-2);
  var month = ("0" + (now.getMonth() + 1)).slice(-2);

  var today = (now.getFullYear() + '-' + month + '-' + day);
  //alert(today);
  $('#dateGoal').val(today);
})
$(document).on("submit", "#saveGoal", function(e) {
  e.preventDefault();
  let t = new FormData(this);
  t.append("save_goal", !0),
    $.ajax({
      type: "POST",
      url: "action/goalupdel.php",
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
            $("#goalTable").load(location.href + " #goalTable"))
          : 200 == t.status
            ? ($("#errorMessage").addClass("d-none"),
              $("#goalAddModal").modal("hide"),
              $("#saveGoal")[0].reset(),
              alertify.set("notifier", "position", "top-right"),
              alertify.success(t.message),
              $("#goalTable").load(location.href + " #goalTable"))
            : 500 == t.status && alertify.error(t.message);
      },
    });
}),
  $(document).on("click", ".editGoalBtn", function() {
    let e = $(this).val();
    $.ajax({
      type: "GET",
      url: "action/goalupdel.php?id=" + e,
      success: function(e) {
        let t = jQuery.parseJSON(e);
        404 == t.status
          ? alert(t.message)
          : 200 == t.status &&
          ($("#id").val(t.data.id),
            $("#date").val(t.data.date),
            $("#goal").val(t.data.goal),
            $("#descr").val(t.data.descr),
            $("#amount").val(t.data.amount),
            $("#deadline").val(t.data.deadline),
            $("#cat_name").val(t.data.cat_id),
            $("#goalEditModal").modal("show"));
      },
    });
  }),
  $(document).on("submit", "#updateGoal", function(e) {
    e.preventDefault();
    let t = new FormData(this);
    t.append("update_goal", !0),
      $.ajax({
        type: "POST",
        url: "action/goalupdel.php",
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
              $("#goalTable").load(location.href + " #goalTable"))
            : 200 == t.status
              ? ($("#errorMessageUpdate").addClass("d-none"),
                alertify.set("notifier", "position", "top-right"),
                alertify.success(t.message),
                $("#goalEditModal").modal("hide"),
                $("#updateGoal")[0].reset(),
                $("#goalTable").load(location.href + " #goalTable"))
              : 500 == t.status && alertify.error(t.message);
        },
      });
  }),
  $(document).on("click", ".deleteGoalBtn", function(e) {
    if (
      (e.preventDefault(),
        confirm("Are you sure you want to delete this data?"))
    ) {
      let e = $(this).val();
      $.ajax({
        type: "POST",
        url: "action/goalupdel.php",
        data: { delete_goal: !0, id: e },
        success: function(e) {
          let t = jQuery.parseJSON(e);
          422 == t.status
            ? (alertify.set("notifier", "position", "top-right"),
              alertify.error(t.message),
              $("#goalTable").load(location.href + " #goalTable"))
            : 200 == t.status
              ? (alertify.set("notifier", "position", "top-right"),
                alertify.success(t.message),
                $("#goalTable").load(location.href + " #goalTable"))
              : 500 == t.status && alertify.error(t.message);
        },
      });
    }
  });
