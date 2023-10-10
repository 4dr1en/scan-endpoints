import {
    Chart,
    LineElement,
    PointElement,
    LineController,
    LinearScale,
    TimeScale,
    Tooltip,
} from 'chart.js';
import "chartjs-adapter-date-fns";

Chart.register(
    LineElement,
    PointElement,
    LineController,
    LinearScale,
    TimeScale,
    Tooltip
);

function buildLineChart(canvas, data) {
    const colors = data.map((item) => getColor(item.ping));

    const formattedData = data.map((item) => ({
        x: new Date(item.date),
        y: item.ping,
    }));

    const haveMoreThanTreeDays = 
        (new Date(data[data.length - 1].date) - new Date(data[0].date)) > 3 * 24 * 60 * 60 * 1000;

    const config = {
        type: "line",
        options: {
            scales: {
                x: {
                    type: "time",
                    time: {
                        unit: haveMoreThanTreeDays ? "day" : "hour",
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
            plugins: {
                tooltip: {
                    displayColors: false,
                    callbacks: {
                        label: function (context) {
                            let label = context.dataset.label || "";

                            if (label) {
                                label += ": ";
                            }
                            if (context.parsed.y !== null) {
                                console.log(context);
                                label += ' ' + context.parsed.y + " ms";
                            }
                            return label;
                        },
                    },
                    padding: 10,
                    titleSpacing: 6,
                    titleFont: {
                        size: 17,
                        weight: 500,
                    },
                    bodyFont: {
                        size: 17,
                        weight: 700,
                    },
                },
            }
        },
        data: {
            datasets: [
                {
                    label: "Response time",
                    data: formattedData,
                    fill: false,
                    borderColor: 'rgb(75, 192, 192)',
                    segment: {
                        borderColor: ctx => {
                            const p1 = ctx.p0.parsed.y;
                            const p2 = ctx.p1.parsed.y;
                            const color1 = getColor(p1);
                            const color2 = getColor(p2);
                            const gradient = ctx.chart.ctx.createLinearGradient(
                                ctx.p0.x,
                                ctx.p0.y,
                                ctx.p1.x,
                                ctx.p1.y
                            );
                            gradient.addColorStop(0, color1);
                            gradient.addColorStop(1, color2);

                            return gradient;
                        }
                    },
                    borderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 7,
                    pointHoverBackgroundColor: colors,
                    pointHoverBorderColor: colors,
                },
            ],
        },
    };

    new Chart(canvas, config);
}

function getColor(value) {
    if (value < 250) {
        return 'hsl(180, 48%, 52%)';  // Starting cyanish color
    } else if (value >= 700) {
        return 'hsl(0, 70%, 60%)';  // Ending red color
    } else {
        let ratio = (value - 250) / (700 - 250);
        let hue = 180 - 180 * ratio;  // Transition from the cyanish hue (180) to red (0)
        let saturation = 48 + 22 * ratio; // Linearly increase saturation from 48 to 70
        let lightness = 52 + 8 * ratio;   // Linearly increase lightness from 52 to 60
        return `hsl(${hue}, ${saturation}%, ${lightness}%)`;
    }
}

window.buildLineChart = buildLineChart;
