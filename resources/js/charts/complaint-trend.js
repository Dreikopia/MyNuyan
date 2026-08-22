import Chart from 'chart.js/auto';

const canvas = document.getElementById('complaintTrendChart');

if (canvas) {
    const data = JSON.parse(canvas.dataset.chartData);

    new Chart(canvas, {
        type: 'bar',

        data: {
            labels: data.map(item => item.month),

            datasets: [
                {
                    label: 'Complaints Submitted',
                    data: data.map(item => item.total),
                    tension: 0.3,
                    fill: false,
                },
            ],
        },

        options: {
            responsive: true,
            maintainAspectRatio: false,

            scales: {
                y: {
                    beginAtZero: true,

                    ticks: {
                        precision: 0,
                    },
                },
            },
        },
    });
}