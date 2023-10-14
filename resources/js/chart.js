import {
    Chart,
    LineElement,
    PointElement,
    LineController,
    LinearScale,
    TimeScale,
    Tooltip,
} from "chart.js";
import "chartjs-adapter-date-fns";
import zoomPlugin from "chartjs-plugin-zoom";

Chart.register(
    LineElement,
    PointElement,
    LineController,
    LinearScale,
    TimeScale,
    Tooltip,
    zoomPlugin
);

function buildLineChart(canvas, data) {
    const colors = data.map((item) => getColor(item.ping));

    const formattedData = data.map((item) => ({
        x: new Date(item.date),
        y: item.ping,
    }));

    const haveMoreThanTreeDays =
        new Date(data[data.length - 1].date) - new Date(data[0].date) >
        3 * 24 * 60 * 60 * 1000;

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
                    suggestedMax: formattedData.reduce((max, item) => Math.max(max, item.y), 0)*1.1,
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
                                label += " " + context.parsed.y + " ms";
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
                zoom : {
                    pan: {
                        enabled: true,
                        mode: 'x',
                        speed: 10,
                        threshold: 10,
                    },
                    limits: {
                        x: {
                            min: formattedData[0].x - 1000 * 60 * 60,
                            max: formattedData[formattedData.length - 1].x + 1000 * 60 * 60,
                            minRange: 1000 * 60 * 60 * 3,
                        },
                        y: {
                            min: 0,
                            max: formattedData.reduce((max, item) => Math.max(max, item.y), 0)*1.1,
                        },
                    },
                    zoom: {
                        wheel: {
                            enabled: true,
                            speed: 0.1,
                        },
                        drag: {
                            enabled: true,
                            modifierKey: 'ctrl',
                        },
                        pinch: {
                            enabled: true
                        },
                        mode: 'x',
                        scallMode: 'xy',
                        speed: 0.1,
                        threshold: 2,
                    }
                }
            },
        },
        data: {
            datasets: [
                {
                    label: "Response time",
                    data: formattedData,
                    fill: false,
                    borderColor: colors,
                    segment: {
                        borderColor: (ctx) => {
                            // The parsed coordinates are the coordinates related to the chart not the data.
                            const x1 = ctx.p0.parsed.x;
                            const y1 = ctx.p0.parsed.y;
                            const x2 = ctx.p1.parsed.x;
                            const y2 = ctx.p1.parsed.y;

                            const color1 = getColor(y1);
                            const color2 = getColor(y2);

                            const gradient = ctx.chart.ctx.createLinearGradient(
                                ctx.p0.x,
                                ctx.p0.y,
                                ctx.p1.x,
                                ctx.p1.y
                            );
                            
                            // If one single point is above 700
                            if ((y1 < 700 && y2 > 700) || (y2 < 700 && y1 > 700)) 
                            {
                                // Compute gradient position based on both x and y coordinates
                                const gradientPosition = computeGradientPosition(x1, y1, x2, y2);
                                gradient.addColorStop(0, color1);
                                gradient.addColorStop(gradientPosition, 'rgb(255, 0, 0)'); // Red at the 700 point
                                gradient.addColorStop(1, color2);
                            } else {
                                gradient.addColorStop(0, color1);
                                gradient.addColorStop(1, color2);
                            }

                            return gradient;
                        },
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

/**
 * Get the color based on the ping value.
 * @param {number} the value of the ping
 * @returns 
 */
function getColor(value) {
    if (value < 250) {
        return "rgb(0, 255, 255)"; // Starting cyanish color
    } else if (value >= 700) {
        return "rgb(255, 0, 0)"; // Ending red color
    } else {
        let ratio = (value - 250) / (700 - 250);
        let r = Math.round(255 * ratio); // Transition from 0 (cyan) to 255 (red)
        let g = Math.round(255 - 255 * ratio); // Transition from 255 (cyan) to 0 (red)
        let b = Math.round(255 - 255 * ratio); // Transition from 255 (cyan) to 0 (red)
        return `rgb(${r}, ${g}, ${b})`;
    }
}

/**
 * Calculate the position in the line of the Y position.
 * 
 * @param {number} x1 
 * @param {number} y1 
 * @param {number} x2 
 * @param {number} y2 
 * @returns {number} The position of 700 in the line between 0 and 1
 */
function computeGradientPosition(x1, y1, x2, y2) {
    if (y1 === y2) return 0.5;  // If the y-values are the same, the gradient position is just the midpoint

    // Compute the slope of the line
    const slope = (y2 - y1) / (x2 - x1);

    // Compute the y-intercept of the line
    const yIntercept = y1 - slope * x1;

    // Compute the x-position of the 700 point
    const x700 = (700 - yIntercept) / slope;

    // Compute the position of the 700 point in the line
    return (x700 - x1) / (x2 - x1);
}

window.buildLineChart = buildLineChart;
