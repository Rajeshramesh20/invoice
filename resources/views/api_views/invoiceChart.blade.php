<!DOCTYPE html>
<html>
<head>
    <title>Invoice Dashboard</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
   <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f6f8fa;
      margin: 0;
      padding: 30px;
    }

    .tg-section-row {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
    }

    .tg-chart-container {
      flex: 1 1 45%;
      background: #ffffff;
      border-radius: 10px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.05);
      padding: 20px;
      min-width: 320px;
    }

    .tg-section-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
    }

    .tg-section-header h3 {
      margin: 0;
      font-size: 18px;
    }

    .tg-select {
      padding: 6px 10px;
      font-size: 14px;
      border: 1px solid #ccc;
      border-radius: 6px;
    }

    .tg-chart-placeholder {
      width: 100%;
      height: 300px;
    }

    canvas {
      width: 100% !important;
      height: auto !important;
    }
  </style>
</head>
<body>

  <h2>📊 Dashboard Charts</h2>

  <div class="tg-section-row">
    <!-- Revenue Overview -->
    <div class="tg-chart-container">
      <div class="tg-section-header">
        <h3>Revenue Overview</h3>
        <div class="tg-period-selector">
          <select class="tg-select">
            <option>Last 7 Days</option>
            <option selected>Last Month</option>
            <option>Last Quarter</option>
            <option>Last Year</option>
          </select>
        </div>
      </div>
      <div class="tg-chart-placeholder">
        <canvas id="revenueChart"></canvas>
      </div>
    </div>

    <!-- User Activity -->
    <div class="tg-chart-container">
      <div class="tg-section-header">
        <h3>User Activity</h3>
        <div class="tg-period-selector">
          <select class="tg-select">
            <option>Last 7 Days</option>
            <option selected>Last Month</option>
            <option>Last Quarter</option>
            <option>Last Year</option>
          </select>
        </div>
      </div>
      <div class="tg-chart-placeholder">
        <canvas id="activityChart"></canvas>
      </div>
    </div>
  </div>

 <script>
    document.addEventListener('DOMContentLoaded', function () {
        let token = localStorage.getItem('token');
        const xhr = new XMLHttpRequest();
        xhr.open('GET', 'http://127.0.0.1:8000/api/invoicechart', true);
        xhr.setRequestHeader('Accept', 'application/json');
        xhr.setRequestHeader('Authorization', 'Bearer ' + token);

        xhr.onload = function () {
            if (xhr.status === 200) {
                try {
                    const result = JSON.parse(xhr.responseText);
                    if (result.status) {
                        const data = result.data;

                        // This Month Bar Chart
                        new Chart(document.getElementById('revenueChart'), {
                            type: 'bar',
                            data: {
                                labels: ['Total', 'Paid'],
                                datasets: [{
                                    label: 'This Month',
                                    data: [data.thisMonth.total, data.thisMonth.paid],
                                    backgroundColor: ['rgba(102, 126, 234, 0.8)', 'rgba(42, 245, 152, 0.8)'],
                                    borderColor: ['rgba(102, 126, 234, 1)', 'rgba(42, 245, 152, 1)'],
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                responsive: true,
                                plugins: {
                                    legend: { display: false }
                                },
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        grid: { drawBorder: false }
                                    },
                                    x: {
                                        grid: { display: false }
                                    }
                                }
                            }
                        });

                        // Overall Pie Chart
                        new Chart(document.getElementById('activityChart'), {
                            type: 'pie',
                            data: {
                                labels: ['Total Invoices', 'Paid Amount'],
                                datasets: [{
                                    data: [data.overall.total, data.overall.paid],
                                    backgroundColor: [
                                        'rgba(102, 126, 234, 0.8)',
                                        'rgba(42, 245, 152, 0.8)'
                                    ],
                                    borderColor: [
                                        'rgba(102, 126, 234, 1)',
                                        'rgba(42, 245, 152, 1)'
                                    ],
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                responsive: true,
                                plugins: {
                                    legend: { 
                                        position: 'bottom',
                                        labels: {
                                            padding: 20
                                        }
                                    }
                                }
                            }
                        });

                        // Recent Invoices List (limit to 5)
                        const listEl = document.getElementById('recentInvoicesList');
                        if (listEl) {
                            listEl.innerHTML = '';
                            data.recent.slice(0, 5).forEach(function (inv) {
                                const li = document.createElement('li');
                                li.textContent = `#${inv.invoice_no} — ₹${inv.total_amount} (Paid: ₹${inv.paid_amount || '0'})`;
                                listEl.appendChild(li);
                            });
                        }

                    } else {
                        alert(result.message || 'Failed to load data');
                    }

                } catch (e) {
                    console.error('JSON parse error', e);
                    alert('Invalid JSON response.');
                }
            } else {
                console.error('API Error', xhr.responseText);
                alert('Failed to load dashboard data.');
            }
        };

        xhr.onerror = function () {
            console.error('Request failed');
            alert('Something went wrong fetching dashboard data.');
        };

        xhr.send();
    });
</script>
</body>
</html>
