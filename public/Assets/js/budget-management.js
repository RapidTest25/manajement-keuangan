console.log("JS Loaded!");

// Helper function to force clean modal backdrops
function forceCleanModalBackdrops() {
  // Remove all modal backdrops
  const backdrops = document.querySelectorAll(".modal-backdrop");
  backdrops.forEach((backdrop) => backdrop.remove());

  // Reset body classes and styles
  document.body.classList.remove("modal-open");
  document.body.style.removeProperty("overflow");
  document.body.style.removeProperty("padding-right");
}

// Format number to Rupiahnsole.log("JS Loaded!");
// Format number to Rupiah
function formatRupiah(amount) {
  return new Intl.NumberFormat("id-ID").format(amount);
}

// Update budget display in modal and chart
function updateBudgetDisplay(budgetStatus) {
  if (!budgetStatus) return;

  // Get all the required elements
  const progressBar = document.getElementById("budgetProgressBar");
  const budgetUsed = document.getElementById("budgetUsed");
  const budgetRemaining = document.getElementById("budgetRemaining");
  const dailyTarget = document.getElementById("dailyTarget");
  const dailyStatsContainer = document.getElementById("dailyStatsContainer");
  const budgetTarget = document.getElementById("budgetTarget");

  // Update main dashboard progress bar
  const mainProgressBar = document.getElementById("mainBudgetProgressBar");
  const mainBudgetUsed = document.getElementById("mainBudgetUsed");
  const mainBudgetRemaining = document.getElementById("mainBudgetRemaining");
  const mainDailyTarget = document.getElementById("mainDailyTarget");
  if (budgetStatus.has_budget) {
    // Calculate percentage and get appropriate status class
    const percentage = Math.min(budgetStatus.percentage_used, 100);
    const barClass = `progress-bar ${
      percentage >= 100
        ? "bg-danger"
        : percentage >= 80
        ? "bg-warning"
        : "bg-success"
    }`;

    // Update both progress bars with animation
    [progressBar, mainProgressBar].forEach((bar) => {
      if (bar) {
        bar.classList = barClass;
        // Force a reflow to ensure animation works
        void bar.offsetWidth;
        bar.style.width = `${percentage}%`;
        bar.setAttribute("aria-valuenow", percentage);
        bar.setAttribute("aria-valuemin", "0");
        bar.setAttribute("aria-valuemax", "100");
      }
    });

    // Update budget modal details
    const budgetTarget = document.getElementById("budgetTarget");
    const budgetPercentage = document.getElementById("budgetPercentage");

    if (budgetTarget)
      budgetTarget.textContent = `Rp ${formatRupiah(
        budgetStatus.daily_budget
      )}`;
    if (budgetPercentage)
      budgetPercentage.textContent = `${Math.round(percentage)}%`;
    if (budgetUsed)
      budgetUsed.textContent = `Rp ${formatRupiah(budgetStatus.spent_today)}`;
    if (budgetRemaining)
      budgetRemaining.textContent = `Rp ${formatRupiah(
        budgetStatus.remaining
      )}`;
    if (dailyTarget)
      dailyTarget.textContent = formatRupiah(budgetStatus.daily_budget);

    // Update amounts in main dashboard
    if (mainBudgetUsed)
      mainBudgetUsed.textContent = `Rp ${formatRupiah(
        budgetStatus.spent_today
      )}`;
    if (mainBudgetRemaining)
      mainBudgetRemaining.textContent = `Rp ${formatRupiah(
        budgetStatus.remaining
      )}`;
    if (mainDailyTarget)
      mainDailyTarget.textContent = formatRupiah(budgetStatus.daily_budget); // Update chart
    const chart = Chart.getChart("budgetChart");
    if (chart) {
      chart.options.plugins.annotation.annotations.line1.yMin =
        budgetStatus.daily_budget;
      chart.options.plugins.annotation.annotations.line1.yMax =
        budgetStatus.daily_budget;
      chart.update();
    }

    // Update global dailyBudget variable if it exists
    if (typeof window.dailyBudget !== "undefined") {
      window.dailyBudget = budgetStatus.daily_budget;
    }

    // Update input field with new budget value
    const dailyBudgetInput = document.getElementById("dailyBudget");
    if (dailyBudgetInput) {
      dailyBudgetInput.value = budgetStatus.daily_budget;
    }

    // Bersihkan semua modal backdrop yang mungkin tertinggal
    forceCleanModalBackdrops(); // Update daily stats
    if (dailyStatsContainer && budgetStatus.daily_stats) {
      let html = '<div class="daily-stats-grid">';
      budgetStatus.daily_stats.forEach((stat) => {
        const date = new Date(stat.date);
        const dayName = date.toLocaleDateString("id-ID", { weekday: "short" });
        const percentage = Math.min(stat.percentage_used, 100);
        const colorClass =
          percentage >= 100
            ? "danger"
            : percentage >= 80
            ? "warning"
            : "success";

        html += `
                    <div class="daily-stat-card ${colorClass}">
                        <div class="date">${dayName}, ${date.getDate()}/${
          date.getMonth() + 1
        }</div>
                        <div class="progress">
                            <div class="progress-bar bg-${colorClass}" 
                                 role="progressbar" 
                                 style="width: ${percentage}%">
                            </div>
                        </div>
                        <div class="amounts">
                            <span>Terpakai: Rp ${formatRupiah(
                              stat.spent
                            )}</span>
                            <span>Sisa: Rp ${formatRupiah(
                              stat.remaining
                            )}</span>
                        </div>
                    </div>
                `;
      });
      html += "</div>";
      dailyStatsContainer.innerHTML = html;
    }
  }
}

// Load budget status
function loadBudgetStatus() {
  $.ajax({
    url: "/app/ajax/manageBudget",
    type: "GET",
    dataType: "json",
    success: function (response) {
      if (response.status) {
        updateBudgetDisplay(response.budgetStatus);
      }
    },
    error: function (xhr, status, error) {
      console.error("Error fetching budget data:", error);
      Swal.fire({
        icon: "error",
        title: "Error",
        text: "Gagal mengambil data budget. Silakan coba lagi.",
      });
    },
  });
}

// Initialize budget modal
document.addEventListener("DOMContentLoaded", function () {
  // Get the modal element (jika ada)
  const budgetModal = document.getElementById("budgetModal");

  // Load budget status when modal is shown (untuk dashboard)
  if (budgetModal) {
    budgetModal.addEventListener("show.bs.modal", function () {
      loadBudgetStatus();
    });

    // Event listener untuk memastikan backdrop dihapus saat modal ditutup
    budgetModal.addEventListener("hidden.bs.modal", function () {
      // Force clean using helper function
      forceCleanModalBackdrops();
    });
  }

  // Handle budget form submission (untuk halaman budgeting dan dashboard)
  const saveBudgetBtn = document.getElementById("saveBudget");
  if (saveBudgetBtn) {
    saveBudgetBtn.addEventListener("click", function () {
      console.log("[DEBUG] Tombol Update Budget diklik");
      // Ambil value langsung sebagai number
      let dailyBudget = parseInt(
        document.getElementById("dailyBudget").value,
        10
      );

      if (!dailyBudget || dailyBudget < 1000) {
        Swal.fire({
          icon: "warning",
          title: "Peringatan",
          text: "Budget harian minimal Rp 1.000",
        });
        return;
      }

      $.ajax({
        url: "/app/ajax/saveBudget",
        type: "POST",
        data: { daily_budget: dailyBudget },
        dataType: "json",
        success: function (response) {
          console.log("[BUDGET] saveBudget response:", response); // debug
          if (response.status) {
            // Jika ada modal, tutup modal dengan benar
            if (budgetModal) {
              const budgetModalInstance =
                bootstrap.Modal.getInstance(budgetModal);
              if (budgetModalInstance) {
                budgetModalInstance.hide();
              }
            }

            // Update display immediately
            if (response.budgetStatus) {
              updateBudgetDisplay(response.budgetStatus);
            }

            // Update chart dengan budget yang baru
            const chart = Chart.getChart("budgetChart");
            if (chart && response.budgetStatus) {
              chart.options.plugins.annotation.annotations.line1.yMin =
                response.budgetStatus.daily_budget;
              chart.options.plugins.annotation.annotations.line1.yMax =
                response.budgetStatus.daily_budget;
              chart.update();
            }

            loadBudgetStatus();

            // Pastikan tidak ada backdrop yang tertinggal
            setTimeout(() => {
              forceCleanModalBackdrops();

              // Tampilkan alert sukses
              Swal.fire({
                icon: "success",
                title: "Berhasil",
                text: response.message,
                showConfirmButton: true,
                confirmButtonText: "OK",
                allowOutsideClick: true,
                allowEscapeKey: true,
                customClass: {
                  container: "swal-container-high-z",
                },
              }).then(() => {
                // Double check untuk memastikan tidak ada backdrop
                forceCleanModalBackdrops();
              });
            }, 150);
          } else {
            Swal.fire({
              icon: "error",
              title: "Gagal",
              text: response.message || "Gagal menyimpan budget",
            });
          }
        },
        error: function (xhr, status, error) {
          console.error(
            "[BUDGET] Error saving budget:",
            error,
            xhr.responseText
          );
          Swal.fire({
            icon: "error",
            title: "Error",
            text: "Gagal menyimpan budget. Silakan coba lagi.",
          });
        },
      });
    });
  } else {
    console.log("[DEBUG] Tombol #saveBudget tidak ditemukan di halaman ini");
  }

  // Load initial budget status
  loadBudgetStatus();

  // Add global click handler to clean up any leftover backdrops
  document.addEventListener("click", function (e) {
    // If clicking outside modal area and there are leftover backdrops, clean them
    if (!e.target.closest(".modal") && !e.target.closest(".swal2-container")) {
      const backdrops = document.querySelectorAll(".modal-backdrop");
      if (backdrops.length > 0) {
        setTimeout(() => {
          forceCleanModalBackdrops();
        }, 100);
      }
    }
  });
});
