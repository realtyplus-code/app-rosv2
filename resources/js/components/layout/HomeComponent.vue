<template>
    <div class="chart-container">
        <div class="row">
            <!-- Gráfico de Properties -->
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title text-center">Properties</h5>
                        <PieChart
                            :data="propertiesData"
                            :options="chartOptions"
                            class="chart-element"
                        />
                        <!-- Total de Properties -->
                        <p class="total-count text-center mt-3">
                            Total: {{ totalPropertiesCount }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Gráfico de Incidents -->
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title text-center">Incidents</h5>
                        <PieChart
                            :data="incidentsData"
                            :options="chartOptions"
                            class="chart-element"
                        />
                        <!-- Total de Incidents -->
                        <p class="total-count text-center mt-3">
                            Total: {{ totalIncidentsCount }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { Pie } from "vue-chartjs";
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    ArcElement,
    CategoryScale,
    LinearScale,
} from "chart.js";

ChartJS.register(
    Title,
    Tooltip,
    Legend,
    ArcElement,
    CategoryScale,
    LinearScale
);

export default {
    name: "PieChartComponent",
    components: {
        PieChart: Pie,
    },
    data() {
        return {
            // Datos para el gráfico de Properties
            propertiesData: {
                labels: ["Actives", "Inactives"],
                datasets: [
                    {
                        label: "Properties Status",
                        data: [40, 30],
                        backgroundColor: ["#A3D8F4", "#FFB3C1"],
                        hoverOffset: 4,
                    },
                ],
            },
            // Datos para el gráfico de Incidents
            incidentsData: {
                labels: ["Electric", "Plumbing", "Structural"],
                datasets: [
                    {
                        label: "Incident Types",
                        data: [50, 20, 30],
                        backgroundColor: ["#FFB3C1", "#FFE0B3", "#C1E1FF"],
                        hoverOffset: 4,
                    },
                ],
            },
            // Opciones comunes para ambos gráficos
            chartOptions: {
                responsive: true,
                animation: {
                    duration: 1500,
                    easing: "easeInOutQuad",
                    animateRotate: true,
                    animateScale: true,
                },
                plugins: {
                    legend: {
                        position: "top",
                    },
                    tooltip: {
                        callbacks: {
                            label: (tooltipItem) =>
                                `${tooltipItem.label}: ${tooltipItem.raw}`,
                        },
                    },
                },
            },
        };
    },
    computed: {
        totalPropertiesCount() {
            return this.propertiesData.datasets[0].data.reduce(
                (total, num) => total + num,
                0
            );
        },
        totalIncidentsCount() {
            return this.incidentsData.datasets[0].data.reduce(
                (total, num) => total + num,
                0
            );
        },
    },
};
</script>

<style scoped>
.chart-container {
    padding: 20px;
    display: flex;
    justify-content: center;
    align-items: center;
}

.card {
    border-radius: 10px;
    overflow: hidden;
}

.card-body {
    padding: 15px;
}

.card-title {
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 15px;
}

.chart-element {
    width: 100%;
    height: 250px; /* Ajusté el tamaño del gráfico */
}

.total-count {
    font-size: 1rem;
    font-weight: 500;
    color: #333;
    margin-top: 10px;
}

@media (max-width: 768px) {
    .chart-element {
        height: 200px; /* Ajuste para pantallas pequeñas */
    }
}
</style>
