
//  Change color of active nav link
let navasst = document.getElementById('transfers');
navasst.className = 'w3-bar-item w3-padding w3-active-blue';

// Onclick show/hide tables
let btn = document.getElementById("showhide");
btn.onclick = function myFunction() {
  let t = document.getElementById("show");
  -1 == t.className.indexOf("w3-show")
    ? (t.className += " w3-show")
    : (t.className = t.className.replace(" w3-show", ""));
}
// Set default date on add APY modal popup
$("#transferAddModal").on("show.bs.modal", function() {
  $('#id').focus();
  var now = new Date();
  var day = ("0" + now.getDate()).slice(-2);
  var month = ("0" + (now.getMonth() + 1)).slice(-2);

  var today = (now.getFullYear() + '-' + month + '-' + day);
  //alert(today);
  $('#dateTra').val(today);
})
$(document).on("submit", "#saveTransfer", function(e) {
  e.preventDefault();
  let a = new FormData(this);
  a.append("save_transfer", !0),
    $.ajax({
      type: "POST",
      url: "action/traupdel.php",
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
            $("#traTable").load(location.href + " #traTable"))
          : 200 == a.status
            ? ($("#errorMessage").addClass("d-none"),
              $("#transferAddModal").modal("hide"),
              $("#saveTransfer")[0].reset(),
              alertify.set("notifier", "position", "top-right"),
              alertify.success(a.message),
              $("#traTable").load(location.href + " #traTable"))
            : 500 == a.status && alert(a.message);
      },
    });
}),
  $(document).on("click", ".editTraBtn", function() {
    let e = $(this).val();
    $.ajax({
      type: "GET",
      url: "action/traupdel.php?id=" + e,
      success: function(e) {
        let a = jQuery.parseJSON(e);
        404 == a.status
          ? alert(a.message)
          : 200 == a.status &&
          ($("#id").val(a.data.id),
            $("#date").val(a.data.date),
            $("#descr").val(a.data.descr),
            $("#amount").val(a.data.amount),
            $("#facct_name").val(a.data.facct_id),
            $("#tacct_name").val(a.data.tacct_id),
            $("#transferEditModal").modal("show"));
      },
    });
  }),
  $(document).on("submit", "#updateTransfer", function(e) {
    e.preventDefault();
    let a = new FormData(this);
    a.append("update_transfer", !0),
      $.ajax({
        type: "POST",
        url: "action/traupdel.php",
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
              $("#traTable").load(location.href + " #traTable"))
            : 200 == a.status
              ? ($("#errorMessageUpdate").addClass("d-none"),
                alertify.set("notifier", "position", "top-right"),
                alertify.success(a.message),
                $("#transferEditModal").modal("hide"),
                $("#updateTransfer")[0].reset(),
                $("#traTable").load(location.href + " #traTable"))
              : 500 == a.status && alertify.error(a.message);
        },
      });
  }),
  $(document).on("click", ".deleteTraBtn", function(e) {
    if (
      (e.preventDefault(),
        confirm("Are you sure you want to delete this data?"))
    ) {
      let e = $(this).val();
      $.ajax({
        type: "POST",
        url: "action/traupdel.php",
        data: { delete_transfer: !0, id: e },
        success: function(e) {
          let a = jQuery.parseJSON(e);
          422 == a.status
            ? (alertify.set("notifier", "position", "top-right"),
              alertify.error(a.message),
              $("#traTable").load(location.href + " #traTable"))
            : 200 == a.status
              ? (alertify.set("notifier", "position", "top-right"),
                alertify.success(a.message),
                $("#traTable").load(location.href + " #traTable"))
              : 500 == a.status && alertify.error(a.message);
        },
      });
    }
  });
// Income Year selector for table
document.getElementById("tblTrsYr").addEventListener('change', function() {
  // console.log('Year is: ', this.value);
  let tblYr = this.value;
  $.ajax({
    url: "./action/tblTrsYr.php",
    method: "POST",
    data: {
      tblyr: tblYr
    },
    success: function(data) {
      $("#tblSrch").html(data)
    }
  })
});
