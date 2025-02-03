<template>
    <div class="indicator-container">
        <h2>Dashboard</h2>
        <p>{{ rolMessage }}</p>
        <div class="cards">
            <div class="card">
                <div class="card-header">
                    <span class="card-title">
                        <img src="/img/indicators/casa.png" alt="Properties" /> PROPERTIES</span
                    >
                </div>
                <div class="card-body">
                    <h3>1,685</h3>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <span class="card-title"
                        ><img src="/img/indicators/incidente.png" alt="Incidents" />
                        INCIDENTS</span
                    >
                </div>
                <div class="card-body">
                    <h3>52,368</h3>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <span class="card-title"
                        ><img src="/img/indicators/seguro.png" alt="Insurances" /> INSURANCES</span
                    >
                </div>
                <div class="card-body">
                    <h3>15.8</h3>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <span class="card-title"
                        ><img src="/img/indicators/proveedor.png" alt="Providers" /> PROVIDERS</span
                    >
                </div>
                <div class="card-body">
                    <h3>2,345</h3>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ["rol"],
    components: {},
    data() {
        return {};
    },
    computed: {},
    mounted() {
        if (Array.isArray(this.rol) && this.rol.length > 0) {
            this.rolName = this.rol[0];
            this.rolMessage = this.getWelcomeMessage(this.rolName);
        }
    },
    methods: {
        getWelcomeMessage(userRole) {
            let message;
            switch (userRole) {
                case "owner":
                    message =
                        "Welcome back, Property Owner. Please review your properties.";
                    break;
                case "tenant":
                    message =
                        "Hello, Tenant. Please review your properties and reports.";
                    break;
                case "admin":
                    message =
                        "Welcome, Admin. You have full access to the system.";
                    break;
                case "providers":
                    message =
                        "Hello, Provider. Please review your assignments and tasks.";
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
.indicator-container {
    width: 100%;
    max-width: 1200px;
    margin: auto;
    text-align: left;
}
.cards {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
    justify-content: space-between;
}
.card {
    background: #f76f31;
    color: white;
    border-radius: 10px;
    padding: 10px;
    width: 100%;
    max-width: 260px;
    box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.2);
}
.card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: #f68049;
    border-radius: 10px;
    font-size: 14px;
}
.card-title {
    font-weight: bold;
}
.card-title img {
    width: 50px;
    margin-right: 10px;
}
.card-body {
    text-align: center;
    margin-top: 10px;
}
.card-body h3 {
    margin: 0;
    font-size: 24px;
}
.card-body p {
    font-size: 12px;
}

@media (max-width: 768px) {
    .cards {
        flex-direction: column;
        align-items: center;
    }
    .card {
        width: 90%;
    }
}
</style>
