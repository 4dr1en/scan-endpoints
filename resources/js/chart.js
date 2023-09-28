import {
  Chart,
  LineElement,
  PointElement,
  LineController,
  LinearScale,
  TimeScale,
} from 'chart.js';
import "chartjs-adapter-date-fns";

Chart.register(
  LineElement,
  PointElement,
  LineController,
  LinearScale,
  TimeScale,
);

function buildLineChart(canvas, data) {
    const formattedData = data.map((item) => ({
        x: new Date(item.date),
        y: item.ping,
    }));

    const config = {
        type: "line",
        options: {
            scales: {
                x: {
                    type: "time",
                    time: {
                        unit: "day",
                    },
                },
                y: {
                    min: 0,
                },
            },
            transitions: {
                show: {
                    animations: {
                        y: {
                            from: 0,
                        },
                    },
                    duration: 5000,
                },
            },
        },
        data: {
            datasets: [
                {
                    label: "Response time",
                    data: formattedData,
                    fill: false,
                    borderColor: "rgb(75, 192, 192)",
                },
            ],
        },
    };

    new Chart(canvas, config);
}

window.buildLineChart = buildLineChart;
