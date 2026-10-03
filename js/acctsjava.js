
//  Change color of active nav link
let navacct = document.getElementById('account');
navacct.className = 'w3-bar-item w3-padding w3-active-blue';

$(document).on("submit", "#saveAccount", function(e) {
  e.preventDefault();
  let a = new FormData(this);
  a.append("save_account", !0),
    $.ajax({
      type: "POST",
      url: "action/accupdel.php",
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
            $("#accTable").load(location.href + " #accTable"))
          : 200 == a.status
            ? ($("#errorMessage").addClass("d-none"),
              $("#accountAddModal").modal("hide"),
              $("#saveAccount")[0].reset(),
              alertify.set("notifier", "position", "top-right"),
              alertify.success(a.message),
              $("#accTable").load(location.href + " #accTable"))
            : 500 == a.status && alertif.error(a.message);
      },
    });
}),
  $(document).on("click", ".editAccBtn", function() {
    let e = $(this).val();
    $.ajax({
      type: "GET",
      url: "action/accupdel.php?id=" + e,
      success: function(e) {
        let a = jQuery.parseJSON(e);
        404 == a.status
          ? alert(a.message)
          : 200 == a.status &&
          ($("#id").val(a.data.id),
            $("#cat_name").val(a.data.cat_name),
            $("#descr").val(a.data.descr),
            $("#cat_type").val(a.data.cat_type),
            $("#accEditModal").modal("show"));
      },
    });
  }),
  $(document).on("submit", "#updateAcc", function(e) {
    e.preventDefault();
    let a = new FormData(this);
    a.append("update_account", !0),
      $.ajax({
        type: "POST",
        url: "action/accupdel.php",
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
              $("#accTable").load(location.href + " #accTable"))
            : 200 == a.status
              ? ($("#errorMessageUpdate").addClass("d-none"),
                alertify.set("notifier", "position", "top-right"),
                alertify.success(a.message),
                $("#accEditModal").modal("hide"),
                $("#updateAcc")[0].reset(),
                $("#accTable").load(location.href + " #accTable"))
              : 500 == a.status && alertify.error(a.message);
        },
      });
  }),
  $(document).on("click", ".deleteAccBtn", function(e) {
    if (
      (e.preventDefault(),
        confirm("CAUTION: All transactions and data associated with this account will also be deleted! This is permanent and can change account balances. Hide the account instead if you need those related transactions but no longer wish to see the account.\n\nSelect OK to confirm deletion."))
    ) {
      let e = $(this).val();
      $.ajax({
        type: "POST",
        url: "action/accupdel.php",
        data: { delete_account: !0, id: e },
        success: function(e) {
          let a = jQuery.parseJSON(e);
          422 == a.status
            ? (alertify.set("notifier", "position", "top-right"),
              alertify.error(a.message),
              $("#accTable").load(location.href + " #accTable"))
            : 200 == a.status
              ? (alertify.set("notifier", "position", "top-right"),
                alertify.success(a.message),
                $("#accTable").load(location.href + " #accTable"))
              : 500 == a.status && alertify.error(a.message);
        },
      });
    }
  });

// Quick Personal Accounts option
$(document).ready(function() {
  $("#prsnlAcctsBtn").click(function() {
    if (confirm('Add a basic chart of accounts for personal finance?')) {
      $.ajax({
        url: 'action/prsnlaccts.php',
        method: 'POST',
        data: {
          user_id: user_id
        },
        success: function(data) {
          alert(data);
        }
      });
    }
  });
});

// Quick Business Accounts option
$(document).ready(function() {
  $("#busAcctsBtn").click(function() {
    if (confirm('Add a basic chart of accounts for business finance?')) {
      $.ajax({
        url: 'action/busaccts.php',
        method: 'POST',
        data: {
          user_id: user_id
        },
        success: function(data) {
          alert(data);
        }
      });
    }
  });
});

// Onclick show/hide tables
let btn = document.getElementById("showhide");
btn.onclick = function myFunction() {
  let t = document.getElementById("show");
  -1 == t.className.indexOf("w3-show")
    ? (t.className += " w3-show")
    : (t.className = t.className.replace(" w3-show", ""));
}

// Toggle Hide account checkbox
$(document).on("click", "#hideAcct", function() {
  if ($(this).prop('checked')) {
    let e = $(this).val();
    $.ajax({
      url: 'action/accupdel.php',
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
      url: 'action/accupdel.php',
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
