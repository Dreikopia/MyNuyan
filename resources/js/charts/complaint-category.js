import Chart from 'chart.js/auto';

const canvas = document.getElementById('complaintCategoryChart');

if (canvas) {

    const categoryComplaints = JSON.parse(
        canvas.dataset.chartData
    );

    const data = {
        labels: categoryComplaints.map(item => item.category),

        datasets: [{
            label: 'Complaints',

            data: categoryComplaints.map(item => item.total),

            backgroundColor: categoryComplaints.map(() => {
                return `rgb(
                    ${Math.floor(Math.random() * 256)},
                    ${Math.floor(Math.random() * 256)},
                    ${Math.floor(Math.random() * 256)}
                )`;
            }),

            hoverOffset: 4
        }]
    };

    const config = {
        type: 'doughnut',
        data: data,
    };

    new Chart(canvas, config);
}