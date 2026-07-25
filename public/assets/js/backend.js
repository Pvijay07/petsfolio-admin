$(document).ready(function () {
  $(document).ready(function () {

    $("#example").DataTable({
      order: [[0, "desc"]],
      // responsive: true,
      columnDefs: [
        {
          targets: 0,
          visible: false,
        },
        // { responsivePriority: 2, targets: -1 }, // Action
      ],
      paging: true,
      searching: true,
      ordering: true,
      lengthMenu: [
        [10, 25, 50, 100, 250, 500, 1000],
        ["10", "25", "50", "100", "250", "500", "1000"],
      ],

      pageLength: 20,
      language: {
        lengthMenu: "Show _MENU_ entries",
        info: "Showing _START_ to _END_ of _TOTAL_ entries",
      },
    });
  });

  $("#addonForm,#addAddonForm").on("submit", function (e) {
    e.preventDefault(); // Prevent actual form submission
    var form = $(this);
    var btntxt = $("button", form).text();
    $("button", form).text("Please wait...").attr("disabled");
    e.preventDefault();

    var url = form.attr("action");
    $.ajax({
      type: "POST",
      url: url,
      data: form.serialize(),
      success: function (data) {
        if (data.status == true) {
          // success
          Swal.fire(data.message);

          // closePopup()
          location.reload();
        } else {
          // failed
          Swal.fire(data.message);
        }
        $("button", form).text(btntxt);
      },
    });
  });

  $('a[data-type="ajax"]').on("click", function (e) {
    e.preventDefault();

    const deleteUrl = $(this).attr("href");
    const token = $(this).data("token");
    const id = $(this).data("id");

    const confirmMessage = $(this).data("confirm");

    Swal.fire({
      title: "Are you sure?",
      text: confirmMessage,
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#DD6B55",
      confirmButtonText: "Yes delete it!",
      cancelButtonText: "No",
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: deleteUrl,
          method: "POST",
          headers: {
            "X-CSRF-TOKEN": token,
          },
          data: {
            id: id,
          },
          success: function (response) {
            if (response.status == true) {
              Swal.fire("Successfully deleted!");
              location.reload();
            } else {
              Swal.fire("Failed to delete!");
            }
          },
          error: function (xhr, status, error) {
            // Handle error response
            console.error("Error deleting the item:", error);
            alert("Failed to delete the item. Please try again.");
          },
        });
      }
    });
  });

  $(".status").on("change", function () {
    var status = $(this).val();
    const id = $(this).data("id");
    const url = $(this).data("url");
    const module = $(this).data("module");

    if (status === "unverified") {
      status = "active";
    } else if (status === "verified") {
      status = "inactive";
    } else if (status === "active") {
      status = "inactive";
    } else if (status == "inactive") {
      status = "active";
    }
    $.ajax({
      type: "POST",
      url: url,
      data: {
        id: id,
        status: status,
        module: module,
      },
      success: function (data) {
        if (data.status == true) {
          // success
          Swal.fire(data.message);
          window.location.reload();
        } else {
          // failed
          Swal.fire(data.message);
        }
      },
    });
  });
  $(document).on("click", "[data-assign-module]", function () {
    const dataId = $(this).data("id"); // Get the data-id
    const moduleId = $(this).data("module"); // Get the data-assign-module

    console.log("Data ID:", dataId);
    console.log("Module ID:", moduleId);

    // Pass these values to the form
    const targetForm = $("#sendNotification");
    targetForm.data("id", dataId);
    targetForm.data("module", moduleId);
  });
  $("#sendNotification").on("submit", function (e) {
    e.preventDefault();

    const url = $(this).attr("action");
    let id = $(this).data("id") || $("#selectedProviderIds").val();

    if (id && typeof id === "string") {
      id = id.includes(",") ? id.split(",") : [id];
    }
    console.log(id);
    const title = $("#Title").val();
    const message = $("#description").val();
    let module = $(this).data("module") || $("#module").val();

    $.ajax({
      url: url,
      method: "POST",
      headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
      },
      data: {
        id: id,
        title: title,
        message: message,
        module: module,
      },
      success: function (response) {
        if (response.status) {
          Swal.fire(
            "Success!",
            "Notification sent successfully.",
            "success"
          ).then(() => {
            location.reload();
          });
        } else {
          Swal.fire("Error!", response.message, "error");
        }
      },
      error: function (xhr, status, error) {
        console.error("Error sending notification:", error);
        Swal.fire(
          "Error!",
          "An error occurred. Please try again later.",
          "error"
        );
      },
    });
  });

  $("#updateUser,#updatePassword").on("submit", function (e) {
    e.preventDefault();
    const url = $(this).attr("action");
    var form = $(this);
    $.ajax({
      url: url,
      method: "POST",
      headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
      },
      data: form.serialize(),
      success: function (response) {
        if (response.status === true) {
          Swal.fire("Success!", "Updated successfully.", "success").then(() => {
            location.reload();
          });
        } else {
          Swal.fire("Error!", response.message, "error");
        }
      },
      error: function (xhr, status, error) {
        console.error("Error sending notification:", error);
        Swal.fire(
          "Error!",
          "An error occurred. Please try again later.",
          "error"
        );
      },
    });
  });

  $(document).on("click", "[data-edit]", function () {
    const dataId = $(this).data("id");
    const moduleId = $(this).data("module");
    const url = $(this).data("url");

    $.ajax({
      url: url,
      method: "POST",
      headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
      },
      data: {
        id: dataId,
        module: moduleId,
      },
      success: function (response) {
        if (response.status === true) {
          var data = response.data;
          $("#id").val(data.id);
          $("#name").val(data.name);
          $("#email").val(data.email);
          $("#phone").val(data.phone);
          $("#dob").val(data.dob);
          $('input[name="gender"][value="' + data.gender + '"]').prop(
            "checked",
            true
          );
          $("#category-status").prop("checked", data.status);
          console.log(response);
        } else {
          Swal.fire("Error!", "Failed to get data.", "error");
        }
      },
      error: function (xhr, status, error) {
        console.error("Error calling the provider:", error);
        Swal.fire(
          "Error!",
          "An error occurred. Please try again later.",
          "error"
        );
      },
    });
  });
});
