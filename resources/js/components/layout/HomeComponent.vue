<template>
    <div class="chart-container">
        <p class="rol-message text-center">{{ rolMessage }}</p>
        <div class="row">
            <!-- Gráfico de Properties -->
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title text-center">Properties</h5>
                        <PieChart
                            :key="propertiesChartKey"
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
                            :key="incidentsChartKey"
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
    props: ["rol"],
    name: "PieChartComponent",
    components: {
        PieChart: Pie,
    },
    data() {
        return {
            rolMessage: null,
            // Datos para el gráfico de Properties
            propertiesData: {
                labels: ["Actives", "Inactives"],
                datasets: [
                    {
                        label: "Properties Status",
                        data: [10, 0], // Inicialmente vacío
                        backgroundColor: ["#A3D8F4", "#FFB3C1"],
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
                    },
                ],
            },
            // Opciones comunes para ambos gráficos
            chartOptions: {
                responsive: true,
                animation: {
                    duration: 3000,
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
            propertiesChartKey: 0,
            incidentsChartKey: 0,
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
    mounted() {
        this.$nextTick(() => {
            setTimeout(() => {
                this.getPropertyTypeCount();
            }, 500);
        });
        if (Array.isArray(this.rol) && this.rol.length > 0) {
            this.rolName = this.rol[0];
            this.rolMessage = this.getWelcomeMessage(this.rolName);
        }
    },
    methods: {
        getPropertyTypeCount() {
            this.$axios
                .get("/properties/byTypeCount")
                .then((response) => {
                    this.updatePropertiesData(
                        response.data.data.actives,
                        response.data.data.inactives
                    );
                })
                .catch((error) => {
                    console.error("Error fetching properties data:", error);
                });
        },
        updatePropertiesData(actives, inactives) {
            this.propertiesData.datasets[0].data = [actives, inactives];

            this.animateChartUpdate();
        },
        animateChartUpdate() {
            this.chartOptions.animation.duration = 1000; // Duración de la animación en milisegundos
            this.chartOptions.animation.easing = "easeInOutQuad"; // Tipo de animación
            this.propertiesChartKey += 1;
        },
        getWelcomeMessage(userRole) {
            let message;
            switch (userRole) {
                case "owner":
                    message = "Welcome back, Property Owner. Please review your properties.";
                    break;
                case "tenant":
                    message = "Hello, Tenant. Please review your properties and reports.";
                    break;
                case "admin":
                    message = "Welcome, Admin. You have full access to the system.";
                    break;
                case "providers":
                    message = "Hello, Provider. Please review your assignments and tasks.";
                    break;
                default:
                    message = "Welcome. Please check your profile.";
                    break;
            }
            return message;
        },
    },
};
</script>
<style scoped>
.chart-container {
    padding: 20px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

.rol-message {
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 20px;
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
