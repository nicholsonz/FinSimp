//  Change color of active nav link 
let navusr = document.getElementById('useracct');
    navusr.className = 'avatar avatar-active';


  $(document).on("click", ".undoExp", function(e) {
    e.preventDefault();        
      let a = $(this).val();
      $.ajax({
        type: "POST",
        url: "action/expupdel.php",
        data: { undo_expense: !0, id: a },
        success: function(e) {
          let a = jQuery.parseJSON(e);
          422 == a.status
            ? (alertify.set("notifier", "position", "top-right"),
              alertify.error(a.message),
              $("#expDel").load(location.href + " #expDel"))
            : 200 == a.status
              ? (alertify.set("notifier", "position", "top-right"),
                alertify.success(a.message),
                $("#expDel").load(location.href + " #expDel"))
              : 500 == a.status && alertify.error(a.message);
        },
      });
  });
  $(document).on("click", ".delUndoExp", function(e) {
    e.preventDefault();        
      let a = $(this).val();
      $.ajax({
        type: "POST",
        url: "action/expupdel.php",
        data: { delUndo_expense: !0, id: a },
        success: function(e) {
          let a = jQuery.parseJSON(e);
          422 == a.status
            ? (alertify.set("notifier", "position", "top-right"),
              alertify.error(a.message),
              $("#expDel").load(location.href + " #expDel"))
            : 200 == a.status
              ? (alertify.set("notifier", "position", "top-right"),
                alertify.success(a.message),
                $("#expDel").load(location.href + " #expDel"))
              : 500 == a.status && alertify.error(a.message);
        },
      });
  });
$(document).on("click", ".undoInc", function(e) {
    e.preventDefault();        
      let a = $(this).val();
      $.ajax({
        type: "POST",
        url: "action/incupdel.php",
        data: { undo_income: !0, id: a },
        success: function(e) {
          let a = jQuery.parseJSON(e);
          422 == a.status
            ? (alertify.set("notifier", "position", "top-right"),
              alertify.error(a.message),
              $("#incDel").load(location.href + " #incDel"))
            : 200 == a.status
              ? (alertify.set("notifier", "position", "top-right"),
                alertify.success(a.message),
                $("#incDel").load(location.href + " #incDel"))
              : 500 == a.status && alertify.error(a.message);
        },
      });
  });
  $(document).on("click", ".delUndoInc", function(e) {
    e.preventDefault();        
      let a = $(this).val();
      $.ajax({
        type: "POST",
        url: "action/incupdel.php",
        data: { delUndo_income: !0, id: a },
        success: function(e) {
          let a = jQuery.parseJSON(e);
          422 == a.status
            ? (alertify.set("notifier", "position", "top-right"),
              alertify.error(a.message),
              $("#incDel").load(location.href + " #incDel"))
            : 200 == a.status
              ? (alertify.set("notifier", "position", "top-right"),
                alertify.success(a.message),
                $("#incDel").load(location.href + " #incDel"))
              : 500 == a.status && alertify.error(a.message);
        },
      });
  });
  $(document).on("click", ".undoTra", function(e) {
    e.preventDefault();        
      let a = $(this).val();
      $.ajax({
        type: "POST",
        url: "action/traupdel.php",
        data: { undo_transfer: !0, id: a },
        success: function(e) {
          let a = jQuery.parseJSON(e);
          422 == a.status
            ? (alertify.set("notifier", "position", "top-right"),
              alertify.error(a.message),
              $("#traDel").load(location.href + " #traDel"))
            : 200 == a.status
              ? (alertify.set("notifier", "position", "top-right"),
                alertify.success(a.message),
                $("#traDel").load(location.href + " #traDel"))
              : 500 == a.status && alertify.error(a.message);
        },
      });
  });
  $(document).on("click", ".delUndoTra", function(e) {
    e.preventDefault();        
      let a = $(this).val();
      $.ajax({
        type: "POST",
        url: "action/traupdel.php",
        data: { delUndo_transfer: !0, id: a },
        success: function(e) {
          let a = jQuery.parseJSON(e);
          422 == a.status
            ? (alertify.set("notifier", "position", "top-right"),
              alertify.error(a.message),
              $("#traDel").load(location.href + " #traDel"))
            : 200 == a.status
              ? (alertify.set("notifier", "position", "top-right"),
                alertify.success(a.message),
                $("#traDel").load(location.href + " #traDel"))
              : 500 == a.status && alertify.error(a.message);
        },
      });
  });