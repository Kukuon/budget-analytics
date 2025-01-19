document.addEventListener('DOMContentLoaded', function () {
    var chartDataJson = document.getElementById('chartDataJson');
    var yearChartDataJson = document.getElementById('yearChartDataJson');

    if (chartDataJson) {
        var chartData = JSON.parse(chartDataJson.value);
        var ctx = document.getElementById('myChart').getContext('2d');
        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: chartData.labels,
                datasets: chartData.datasets
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    if (yearChartDataJson) {
        var yearChartData = JSON.parse(yearChartDataJson.value);
        var yearChartCtx = document.getElementById('yearChart').getContext('2d');
        new Chart(yearChartCtx, {
            type: 'bar',
            data: {
                labels: yearChartData.labels,
                datasets: yearChartData.datasets
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }
});
