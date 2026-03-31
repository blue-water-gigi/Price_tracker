const dataSource = window.statsChartData || { labels: [], prices: [] };
const labels = Array.isArray(dataSource.labels) ? dataSource.labels : [];
const prices = Array.isArray(dataSource.prices) ? dataSource.prices : [];

const trendCanvas = document.getElementById("priceTrendChart");
const distributionCanvas = document.getElementById("priceDistributionChart");
const ChartCtor = window.Chart;

if (!ChartCtor || !trendCanvas || !distributionCanvas) {
  // Page was opened without chart runtime or target nodes.
} else if (prices.length === 0) {
  renderNoData(trendCanvas, "Недостаточно данных для графика динамики");
  renderNoData(
    distributionCanvas,
    "Недостаточно данных для графика распределения",
  );
} else {
  initTrendChart(ChartCtor, trendCanvas, labels, prices);
  initDistributionChart(ChartCtor, distributionCanvas, prices);
}

function initTrendChart(ChartClass, canvas, xLabels, yPrices) {
  new ChartClass(canvas, {
    type: "line",
    data: {
      labels: xLabels,
      datasets: [
        {
          label: "Цена, RUB",
          data: yPrices,
          borderColor: "#00ff41",
          backgroundColor: "rgba(0, 255, 65, 0.15)",
          pointBackgroundColor: "#00ff41",
          pointBorderColor: "#041108",
          pointRadius: 3,
          pointHoverRadius: 5,
          tension: 0.25,
          fill: true,
        },
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          labels: { color: "#6aff8a" },
        },
      },
      scales: {
        x: {
          ticks: { color: "#2e6640", maxRotation: 0, autoSkip: true },
          grid: { color: "rgba(0, 255, 65, 0.08)" },
        },
        y: {
          ticks: {
            color: "#2e6640",
            callback(value) {
              return `${Number(value).toLocaleString("ru-RU")} ₽`;
            },
          },
          grid: { color: "rgba(0, 255, 65, 0.08)" },
        },
      },
    },
  });
}

function initDistributionChart(ChartClass, canvas, yPrices) {
  const min = Math.min(...yPrices);
  const avg = yPrices.reduce((sum, p) => sum + p, 0) / yPrices.length;
  const max = Math.max(...yPrices);

  new ChartClass(canvas, {
    type: "bar",
    data: {
      labels: ["MIN", "AVG", "MAX"],
      datasets: [
        {
          label: "Сводка цен",
          data: [min, avg, max],
          backgroundColor: [
            "rgba(0, 212, 255, 0.45)",
            "rgba(255, 215, 0, 0.45)",
            "rgba(0, 255, 65, 0.45)",
          ],
          borderColor: ["#00d4ff", "#ffd700", "#00ff41"],
          borderWidth: 1,
        },
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: {
          labels: { color: "#6aff8a" },
        },
      },
      scales: {
        x: {
          ticks: { color: "#2e6640" },
          grid: { color: "rgba(0, 255, 65, 0.08)" },
        },
        y: {
          ticks: {
            color: "#2e6640",
            callback(value) {
              return `${Number(value).toLocaleString("ru-RU")} ₽`;
            },
          },
          grid: { color: "rgba(0, 255, 65, 0.08)" },
        },
      },
    },
  });
}

function renderNoData(canvas, message) {
  const holder = canvas.parentElement;
  if (!holder) return;
  canvas.remove();
  const noData = document.createElement("div");
  noData.style.minHeight = "180px";
  noData.style.display = "flex";
  noData.style.alignItems = "center";
  noData.style.justifyContent = "center";
  noData.style.color = "var(--text-muted)";
  noData.style.border = "1px dashed var(--border)";
  noData.style.fontSize = "0.85rem";
  noData.textContent = message;
  holder.appendChild(noData);
}
