<?php
/** @var array<string,mixed> $stats */
$title = "Statistiques des tickets";
ob_start();
?>

<h1 class="mb-4">Statistiques des tickets</h1>

<!-- Containers for charts -->
<div class="row mb-5">
  <div class="col-md-6 mb-4">
    <div class="card shadow-sm h-100">
      <div class="card-header bg-primary text-white">Répartition globale </div>
      <div class="card-body"><canvas id="statusChart"></canvas></div>
    </div>
  </div>

  <div class="col-md-6 mb-4">
    <div class="card shadow-sm h-100">
      <div class="card-header bg-primary text-white">Tickets par jour </div>
      <div class="card-body"><canvas id="dailyChart"></canvas></div>
    </div>
  </div>
</div>

<div class="row mb-5">
  <div class="col-md-6 mb-4">
    <div class="card shadow-sm h-100">
      <div class="card-header bg-primary text-white">Tickets par mois</div>
      <div class="card-body"><canvas id="monthChart"></canvas></div>
    </div>
  </div>

  <div class="col-md-6 mb-4">
    <div class="card shadow-sm h-100">
      <div class="card-header bg-primary text-white">Tickets par année</div>
      <div class="card-body"><canvas id="yearChart"></canvas></div>
    </div>
  </div>
</div>

<div class="row mb-5">
  <div class="col-md-6 mb-4">
    <div class="card shadow-sm h-100">
      <div class="card-header bg-primary text-white">Tickets par rapporteur</div>
      <div class="card-body"><canvas id="rapporteurChart"></canvas></div>
    </div>
  </div>

  <div class="col-md-6 mb-4">
    <div class="card shadow-sm h-100">
      <div class="card-header bg-primary text-white">Tickets par développeur</div>
      <div class="card-body"><canvas id="developerChart"></canvas></div>
    </div>
  </div>
</div>

<a href="/admin" class="btn btn-secondary">Retour au tableau de bord</a>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

const statusLabels = <?= json_encode(array_keys($stats["status"])) ?>;
const statusData   = <?= json_encode(array_values($stats["status"])) ?>;

const dailyLabels  = <?= json_encode(array_column($stats["day"], "date")) ?>;
const dailyData    = <?= json_encode(array_column($stats["day"], "total")) ?>;

const monthLabels  = <?= json_encode(array_column($stats["month"], "month")) ?>;
const monthData    = <?= json_encode(array_column($stats["month"], "total")) ?>;

const yearLabels   = <?= json_encode(array_column($stats["year"], "year")) ?>;
const yearData     = <?= json_encode(array_column($stats["year"], "total")) ?>;

const rapLabels    = <?= json_encode(
    array_column($stats["rapporteurs"], "username"),
) ?>;
const rapData      = <?= json_encode(
    array_column($stats["rapporteurs"], "total"),
) ?>;

const devLabels    = <?= json_encode(
    array_column($stats["developers"], "username"),
) ?>;
const devData      = <?= json_encode(
    array_column($stats["developers"], "total"),
) ?>;


new Chart(document.getElementById('statusChart'), {
  type: 'pie',
  data: {
    labels: statusLabels,
    datasets: [{
      data: statusData,
      backgroundColor: ['#6c757d','#ffc107','#198754','#0d6efd','#dc3545']
    }]
  },
  options: { plugins: { legend: { position: 'bottom' } } }
});

new Chart(document.getElementById('dailyChart'), {
  type: 'line',
  data: {
    labels: dailyLabels,
    datasets: [{
      label: 'Tickets / jour',
      data: dailyData,
      borderColor: '#0d6efd',
      tension: .3,
      fill: false
    }]
  },
  options: { responsive: true, scales: { y: { beginAtZero: true } } }
});

new Chart(document.getElementById('monthChart'), {
  type: 'bar',
  data: {
    labels: monthLabels,
    datasets: [{
      label: 'Tickets / mois',
      data: monthData,
      backgroundColor: '#17a2b8'
    }]
  },
  options: { responsive: true, scales: { y: { beginAtZero: true } } }
});

new Chart(document.getElementById('yearChart'), {
  type: 'bar',
  data: {
    labels: yearLabels,
    datasets: [{
      label: 'Tickets / année',
      data: yearData,
      backgroundColor: '#6610f2'
    }]
  },
  options: { responsive: true, scales: { y: { beginAtZero: true } } }
});

new Chart(document.getElementById('rapporteurChart'), {
  type: 'bar',
  data: {
    labels: rapLabels,
    datasets: [{
      label: 'Tickets / rapporteur',
      data: rapData,
      backgroundColor: '#20c997'
    }]
  },
  options: { responsive: true, indexAxis: 'y', scales: { x: { beginAtZero: true } } }
});

new Chart(document.getElementById('developerChart'), {
  type: 'bar',
  data: {
    labels: devLabels,
    datasets: [{
      label: 'Tickets / développeur',
      data: devData,
      backgroundColor: '#fd7e14'
    }]
  },
  options: { responsive: true, indexAxis: 'y', scales: { x: { beginAtZero: true } } }
});
</script>

<?php
$content = ob_get_clean();
include __DIR__ . "/../layout.php";

