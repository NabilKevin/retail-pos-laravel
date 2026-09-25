const ctx = document.getElementById('salesChart').getContext('2d');

const chartOptions = {
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: {
      labels: {
        color: '#94a3b8', // slate-400
      }
    }
  },
  scales: {
    x: {
      ticks: { color: '#94a3b8' },
      grid: { color: 'rgba(148,163,184,0.1)' }
    },
    y: {
      ticks: { color: '#94a3b8' },
      grid: { color: 'rgba(148,163,184,0.1)' }
    }
  }
};

// Line Chart (Penjualan)
new Chart(document.getElementById('salesChart'), {
  type: 'line',
  data: {
    labels: totalPenjualanLabels,
    datasets: [{
      label: 'Total Penjualan',
      data: totalPenjualanTotals,
      borderColor: '#6366f1',
      backgroundColor: 'rgba(99,102,241,0.2)',
      fill: true,
      tension: 0.3
    }]
  },
  options: chartOptions
});

// Bar Chart (Modal)
new Chart(document.getElementById('modalChart'), {
  type: 'bar',
  data: {
    labels: totalModalLabels,
    datasets: [{
      label: 'Modal',
      data: totalModalTotals,
      backgroundColor: 'rgba(16, 185, 129, 0.6)',
      borderRadius: 6
    }]
  },
  options: chartOptions
});

// Area Chart (Keuntungan)
new Chart(document.getElementById('profitChart'), {
  type: 'line',
  data: {
    labels: totalKeuntunganLabels,
    datasets: [{
      label: 'Keuntungan',
      data: totalKeuntunganTotals,
      borderColor: '#f59e0b',
      backgroundColor: 'rgba(245, 158, 11, 0.2)',
      fill: true,
      tension: 0.4
    }]
  },
  options: chartOptions
});

const totalKeuntungan = totalKeuntunganTotals.reduce((a, b) => parseInt(a) + parseInt(b), 0)
const totalModal = totalModalTotals.reduce((a, b) => parseInt(a) + parseInt(b), 0)
let profitRatio = (totalKeuntungan / totalModal) * 100
// Donut Chart (Rasio Keuntungan)
new Chart(document.getElementById('ratioChart'), {
  type: 'doughnut',
  data: {
    labels: ['Modal', 'Keuntungan'],
    datasets: [{
      data: [100-profitRatio, profitRatio],
      backgroundColor: ['#10b981', '#f59e0b'],
      hoverOffset: 6
    }]
  },
  options: {
    responsive: true,
    plugins: {
      legend: { position: 'bottom', labels: { color: '#94a3b8' } }
    }
  }
});