$(document).ready(function () {
  $(document).ready(function () {
<<<<<<< HEAD
    $("#example").DataTable({
      order: [[0, "desc"]],
=======

    $("#example").DataTable({
      order: [[0, "desc"]],
      // responsive: true,
>>>>>>> origin/main
      columnDefs: [
        {
          targets: 0,
          visible: false,
        },
<<<<<<< HEAD
=======
        // { responsivePriority: 2, targets: -1 }, // Action
>>>>>>> origin/main
      ],
      paging: true,
      searching: true,
      ordering: true,
<<<<<<< HEAD
      lengthMenu: [5, 10, 15],
      pageLength: 20,
=======
      lengthMenu: [
        [10, 25, 50, 100, 250, 500, 1000],
        ["10", "25", "50", "100", "250", "500", "1000"],
      ],

      pageLength: 20,
      language: {
        lengthMenu: "Show _MENU_ entries",
        info: "Showing _START_ to _END_ of _TOTAL_ entries",
      },
>>>>>>> origin/main
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
<<<<<<< HEAD
=======
          window.location.reload();
>>>>>>> origin/main
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
<<<<<<< HEAD

function showSection(id) {
  document
    .querySelectorAll(".content > div")
    .forEach((div) => div.classList.add("hidden"));
  document.getElementById(id).classList.remove("hidden");
}

// Pet Details Modal Functions
function showPetDetails(button) {
  const row = button.closest("tr");
  const petDetails = JSON.parse(row.getAttribute("data-pet-details"));

  const detailsContent = document.getElementById("petDetailsContent");
  detailsContent.innerHTML = `
        <h3>Pet Information</h3>
        <div class="detail-row">
          <div class="detail-label">Breed:</div>
          <div class="detail-value">${petDetails.breed}</div>
        </div>
        <div class="detail-row">
          <div class="detail-label">Age:</div>
          <div class="detail-value">${petDetails.age}</div>
        </div>
        <div class="detail-row">
          <div class="detail-label">Gender:</div>
          <div class="detail-value">${petDetails.gender}</div>
        </div>
     
      `;

  document.getElementById("petDetailsModal").style.display = "block";
}

function closePetDetails() {
  document.getElementById("petDetailsModal").style.display = "none";
}

// Email Modal Functions
function showEmailForm(email, enquiryId) {
  document.getElementById("toEmail").value = email;
  document.getElementById("enquiryId").value = enquiryId;
  document.getElementById("emailModal").style.display = "block";
}

function closeEmailModal() {
  document.getElementById("emailModal").style.display = "none";
}

function sendQuotation() {
  const toEmail = document.getElementById("toEmail").value;
  const subject = document.getElementById("emailSubject").value;
  const body = document.getElementById("emailBody").value;

  // In a real application, you would send this data to a server
  // For this demo, we'll just show an alert
  alert(`Quotation email sent to ${toEmail}`);
  closeEmailModal();
}

// Policy Details Modal Functions
// function showPolicyDetails(button) {
//   const row = button.closest("tr");
//   const policyDetails = JSON.parse(row.getAttribute("data-policy-details"));

//   const detailsContent = document.getElementById("policyDetailsContent");
//   detailsContent.innerHTML = `
//         <h3>Policy Information</h3>
//         <div class="detail-row">
//           <div class="detail-label">Policy Number:</div>
//           <div class="detail-value">${policyDetails.policyNumber}</div>
//         </div>
//         <div class="detail-row">
//           <div class="detail-label">Client Name:</div>
//           <div class="detail-value">${policyDetails.clientName}</div>
//         </div>
        
//         <div class="detail-row">
//           <div class="detail-label">Contact:</div>
//           <div class="detail-value">${policyDetails.contact}</div>
//         </div>
//         <div class="detail-row">
//           <div class="detail-label">Email:</div>
//           <div class="detail-value">${policyDetails.email}</div>
//         </div>
//         <div class="detail-row">
//           <div class="detail-label">Insurance Type:</div>
//           <div class="detail-value">${policyDetails.insuranceType}</div>
//         </div>
//         <div class="detail-row">
//           <div class="detail-label">Start Date:</div>
//           <div class="detail-value">${policyDetails.startDate}</div>
//         </div>
//         <div class="detail-row">
//           <div class="detail-label">Expiry Date:</div>
//           <div class="detail-value">${policyDetails.expiryDate}</div>
//         </div>
//         <div class="detail-row">
//           <div class="detail-label">Premium:</div>
//           <div class="detail-value">₹${policyDetails.premium}</div>
//         </div>
//         <div class="detail-row">
//           <div class="detail-label">Renewal Date:</div>
//           <div class="detail-value">${policyDetails.renewalDate}</div>
//         </div>
//         <div class="detail-row">
//           <div class="detail-label">Status:</div>
//           <div class="detail-value">${policyDetails.status}</div>
//         </div>
        
//         <h3>Pet Information</h3>
//         <div class="detail-row">
//           <div class="detail-label">Breed:</div>
//           <div class="detail-value">${policyDetails.petDetails.breed}</div>
//         </div>
//         <div class="detail-row">
//           <div class="detail-label">Age:</div>
//           <div class="detail-value">${policyDetails.petDetails.age}</div>
//         </div>
//         <div class="detail-row">
//           <div class="detail-label">Gender:</div>
//           <div class="detail-value">${policyDetails.petDetails.gender}</div>
//         </div>
        
//       `;

//   document.getElementById("policyDetailsModal").style.display = "block";
// }

function closePolicyDetails() {
  document.getElementById("policyDetailsModal").style.display = "none";
}

// Edit Insurance Modal Functions
function showEditInsuranceForm(button) {
  const row = button.closest("tr");
  const policyDetails = JSON.parse(row.getAttribute("data-policy-details"));

  // Populate form with existing data
  document.getElementById("editPolicyNumber").value =
    policyDetails.policyNumber;
  document.getElementById("editClientName").value = policyDetails.clientName;
  document.getElementById("editCity").value = policyDetails.city;
  document.getElementById("editContact").value = policyDetails.contact;
  document.getElementById("editInsuranceType").value =
    policyDetails.insuranceType;
  document.getElementById("editPremium").value = policyDetails.premium;
  document.getElementById("editStartDate").value = formatDateForInput(
    policyDetails.startDate
  );
  document.getElementById("editExpiryDate").value = formatDateForInput(
    policyDetails.expiryDate
  );
  document.getElementById("editRenewalDate").value = formatDateForInput(
    policyDetails.renewalDate
  );
  document.getElementById("editStatus").value = policyDetails.status;

  // Pet details
  document.getElementById("editBreed").value = policyDetails.petDetails.breed;
  document.getElementById("editAge").value = policyDetails.petDetails.age;
  document.getElementById("editGender").value = policyDetails.petDetails.gender;
  document.getElementById("editColor").value = policyDetails.petDetails.color;
  document.getElementById("editMicrochip").value =
    policyDetails.petDetails.microchip;
  document.getElementById("editVaccination").value =
    policyDetails.petDetails.vaccination;
  document.getElementById("editMedicalHistory").value =
    policyDetails.petDetails.medicalHistory;

  document.getElementById("editInsuranceModal").style.display = "block";
}

function closeEditInsuranceModal() {
  document.getElementById("editInsuranceModal").style.display = "none";
}

// Add Insurance Modal Functions
function showAddInsuranceForm() {
  // Clear the form
  document.getElementById("addInsuranceForm").reset();
  document.getElementById("addInsuranceModal").style.display = "block";
}

function closeAddInsuranceModal() {
  document.getElementById("addInsuranceModal").style.display = "none";
}

// Quotation Details Modal Functions
function showQuotationDetails(button) {
  const row = button.closest("tr");
  const quotationDetails = JSON.parse(
    row.getAttribute("data-quotation-details")
  );

  const detailsContent = document.getElementById("quotationDetailsContent");
  detailsContent.innerHTML = `
        <div class="quotation-section">
          <h4>Client Information</h4>
          <div class="detail-row">
            <div class="detail-label">Client Name:</div>
            <div class="detail-value">${quotationDetails.clientName}</div>
          </div>
          <div class="detail-row">
            <div class="detail-label">Email:</div>
            <div class="detail-value">${quotationDetails.email}</div>
          </div>
        </div>
        
        <div class="quotation-section">
          <h4>Insurance Details</h4>
          <div class="detail-row">
            <div class="detail-label">Insurance Type:</div>
            <div class="detail-value">${quotationDetails.insuranceType}</div>
          </div>
          <div class="detail-row">
            <div class="detail-label">Coverage Type:</div>
            <div class="detail-value">${quotationDetails.coverage}</div>
          </div>
          <div class="detail-row">
            <div class="detail-label">Premium Amount:</div>
            <div class="detail-value">₹${quotationDetails.premium}</div>
          </div>
          <div class="detail-row">
            <div class="detail-label">Valid Till:</div>
            <div class="detail-value">${quotationDetails.validTill}</div>
          </div>
          <div class="detail-row">
            <div class="detail-label">Status:</div>
            <div class="detail-value"><span class="status-badge status-sent">${quotationDetails.status}</span></div>
          </div>
        </div>
        
        <div class="quotation-section">
          <h4>Policy Benefits</h4>
          <div class="detail-value">${quotationDetails.policyBenefits}</div>
        </div>
        
        <div class="quotation-section">
          <h4>Terms & Conditions</h4>
          <div class="detail-value">${quotationDetails.terms}</div>
        </div>
      `;

  document.getElementById("quotationDetailsModal").style.display = "block";
}

function closeQuotationDetails() {
  document.getElementById("quotationDetailsModal").style.display = "none";
}

// Claim Update Modal Functions
function showClaimUpdateForm(button) {
  const row = button.closest("tr");
  const claimDetails = JSON.parse(row.getAttribute("data-claim-details"));

  document.getElementById("claimId").value = claimDetails.claimId;
  document.getElementById("claimClientName").value = claimDetails.clientName;

  // Set current status as active
  const statusOptions = document.querySelectorAll(".status-option");
  statusOptions.forEach((option) => {
    option.classList.remove("active");
    if (option.textContent === claimDetails.status) {
      option.classList.add("active");
      document.getElementById("selectedStatus").value = claimDetails.status;
    }
  });

  // Set settlement date if available
  if (claimDetails.settlementDate && claimDetails.settlementDate !== "-") {
    document.getElementById("settlementDate").value = formatDateForInput(
      claimDetails.settlementDate
    );
  } else {
    document.getElementById("settlementDate").value = "";
  }

  document.getElementById("updateRemarks").value = claimDetails.remarks;

  document.getElementById("claimUpdateModal").style.display = "block";
}

function closeClaimUpdateModal() {
  document.getElementById("claimUpdateModal").style.display = "none";
}

function selectStatus(status) {
  const statusOptions = document.querySelectorAll(".status-option");
  statusOptions.forEach((option) => {
    option.classList.remove("active");
    if (option.textContent === status) {
      option.classList.add("active");
    }
  });
  document.getElementById("selectedStatus").value = status;
}

// function updateClaimStatus() {
//   const claimId = document.getElementById("claimId").value;
//   const status = document.getElementById("selectedStatus").value;
//   const settlementDate = document.getElementById("settlementDate").value;
//   const remarks = document.getElementById("updateRemarks").value;

//   if (!status) {
//     alert("Please select a status");
//     return;
//   }

//   // In a real application, you would send this data to a server
//   // For this demo, we'll just show an alert
//   alert(`Claim ${claimId} status updated to ${status}`);
//   closeClaimUpdateModal();
// }

// Utility function to format date for input fields
function formatDateForInput(dateString) {
  if (!dateString || dateString === "-") return "";

  const parts = dateString.split("-");
  if (parts.length === 3) {
    // Assuming format is DD-MM-YYYY
    return `${parts[2]}-${parts[1]}-${parts[0]}`;
  }
  return dateString;
}

// Form submission handlers
document
  .getElementById("editInsuranceForm")
  .addEventListener("submit", function (e) {
    e.preventDefault();
    alert("Insurance policy updated successfully!");
    closeEditInsuranceModal();
  });

document
  .getElementById("addInsuranceForm")
  .addEventListener("submit", function (e) {
    e.preventDefault();
    alert("New insurance policy added successfully!");
    closeAddInsuranceModal();
  });

// Close modals when clicking outside
window.onclick = function (event) {
  const modals = [
    "petDetailsModal",
    "emailModal",
    "policyDetailsModal",
    "editInsuranceModal",
    "addInsuranceModal",
    "quotationDetailsModal",
    "claimUpdateModal",
  ];

  modals.forEach((modalId) => {
    const modal = document.getElementById(modalId);
    if (event.target == modal) {
      if (modalId === "petDetailsModal") closePetDetails();
      else if (modalId === "emailModal") closeEmailModal();
      else if (modalId === "policyDetailsModal") closePolicyDetails();
      else if (modalId === "editInsuranceModal") closeEditInsuranceModal();
      else if (modalId === "addInsuranceModal") closeAddInsuranceModal();
      else if (modalId === "quotationDetailsModal") closeQuotationDetails();
      else if (modalId === "claimUpdateModal") closeClaimUpdateModal();
    }
  });
};

// Enhanced reminder system with local storage

// ==========================================
// Global Universal Dark / Light Mode Engine
// ==========================================
window.applyTheme = function(theme) {
  const isDark = theme === 'dark';
  if (isDark) {
    document.documentElement.classList.add('dark');
    if (document.body) document.body.classList.add('dark');
    document.documentElement.setAttribute('data-bs-theme', 'dark');
    document.documentElement.setAttribute('data-theme', 'dark');
  } else {
    document.documentElement.classList.remove('dark');
    if (document.body) document.body.classList.remove('dark');
    document.documentElement.setAttribute('data-bs-theme', 'light');
    document.documentElement.setAttribute('data-theme', 'light');
  }

  // Update navbar toggle icons
  document.querySelectorAll('.theme-icon-moon').forEach(el => {
    el.style.display = isDark ? 'none' : 'inline-block';
  });
  document.querySelectorAll('.theme-icon-sun').forEach(el => {
    el.style.display = isDark ? 'inline-block' : 'none';
  });

  // Update dropdown switches
  document.querySelectorAll('#themeSwitchCheckbox, .theme-switch-checkbox').forEach(cb => {
    cb.checked = isDark;
  });

  try {
    localStorage.setItem('petsfolio_theme', theme);
  } catch (e) {}
};

window.toggleTheme = function() {
  const currentTheme = localStorage.getItem('petsfolio_theme') || (document.documentElement.classList.contains('dark') ? 'dark' : 'light');
  const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
  window.applyTheme(newTheme);
};

// Initialize theme on DOM ready
if (typeof $ !== 'undefined') {
  $(document).ready(function() {
    const savedTheme = localStorage.getItem('petsfolio_theme') || (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
    window.applyTheme(savedTheme);
  });
} else {
  document.addEventListener('DOMContentLoaded', function() {
    const savedTheme = localStorage.getItem('petsfolio_theme') || (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
    window.applyTheme(savedTheme);
  });
}


=======
>>>>>>> origin/main
