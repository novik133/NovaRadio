<div class="card">
    <div class="card-header">
        <h3>📈 Listener Reports</h3>
        <div class="date-range">
            <input type="date" id="start-date" value="<?= date('Y-m-d', strtotime('-7 days')) ?>">
            <span>to</span>
            <input type="date" id="end-date" value="<?= date('Y-m-d') ?>">
            <button onclick="loadReport()" class="btn btn-sm btn-primary">Load</button>
        </div>
    </div>
    <div class="card-body">
        <div id="report-stats" class="stats-row"></div>
        <canvas id="listeners-chart" height="100"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
let chart = null;

async function loadReport() {
    const start = document.getElementById('start-date').value;
    const end = document.getElementById('end-date').value;
    
    const report = await fetch(`dj.php?ajax=report&station=${STATION_ID}&start=${start}&end=${end}`).then(r=>r.json());
    
    if (report && !report.error) {
        // Stats
        document.getElementById('report-stats').innerHTML = `
            <div class="stat-box"><span class="stat-value">${report.listeners?.average || 0}</span><span class="stat-label">Avg Listeners</span></div>
            <div class="stat-box"><span class="stat-value">${report.listeners?.unique || 0}</span><span class="stat-label">Unique Listeners</span></div>
            <div class="stat-box"><span class="stat-value">${report.listeners?.peak || 0}</span><span class="stat-label">Peak Listeners</span></div>
        `;
        
        // Chart
        if (report.by_day && Array.isArray(report.by_day)) {
            const labels = report.by_day.map(d => d.date);
            const data = report.by_day.map(d => d.listeners);
            
            if (chart) chart.destroy();
            chart = new Chart(document.getElementById('listeners-chart'), {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Listeners',
                        data: data,
                        borderColor: '#6366f1',
                        backgroundColor: 'rgba(99,102,241,0.1)',
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, grid: { color: '#2a2a3a' } },
                        x: { grid: { color: '#2a2a3a' } }
                    }
                }
            });
        }
    }
}

loadReport();
</script>
