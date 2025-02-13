<template>
    <div class="indicator-container">
        <div class="cards">
            <div class="card" v-if="roleName !== 'provider'">
                <div
                    class="card-header"
                    @click="getViewModule('property')"
                    style="cursor: pointer"
                >
                    <span class="card-title">
                        <img src="/img/indicators/casa.png" alt="Properties" />
                        PROPERTIES</span
                    >
                </div>
                <div class="card-body">
                    <div v-if="loadingProperties">Loading...</div>
                    <div v-else>
                        <h3>{{ totalProperties }}</h3>
                        <div
                            v-for="property in propertiesCounts"
                            :key="property.type_name"
                            :class="[
                                'tag',
                                property.type_name.replace(' ', '-') + '-tag',
                            ]"
                            :title="property.type_name"
                            style="cursor: pointer"
                            @click="getViewModuleFilter('property', property.type_name)"
                        >
                            <i :class="getIconClass(property.type_name)"></i>
                            {{ property.count }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div
                    class="card-header"
                    @click="getViewModule('incident')"
                    style="cursor: pointer"
                >
                    <span class="card-title">
                        <img
                            src="/img/indicators/incidente.png"
                            alt="Incidents"
                        />
                        INCIDENTS
                    </span>
                </div>
                <div class="card-body">
                    <div v-if="loadingIncidents">Loading...</div>
                    <div v-else>
                        <h3>{{ totalIncidents }}</h3>
                        <div
                            v-for="incident in incidentCounts"
                            :key="incident.type_name"
                            :class="[
                                'tag',
                                incident.type_name.replace(' ', '-') + '-tag',
                            ]"
                            :title="incident.type_name"
                            style="cursor: pointer"
                            @click="getViewModuleFilter('incident', incident.type_name)"
                        >
                            <i :class="getIconClass(incident.type_name)"></i>
                            {{ incident.count }}
                        </div>
                    </div>
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
        return {
            roleName: this.rol[0],
            propertiesCounts: [],
            totalProperties: 0,
            incidentCounts: [],
            totalIncidents: 0,
            loadingProperties: true,
            loadingIncidents: true,
        };
    },
    computed: {},
    mounted() {},
    created() {
        this.getPropertyTypeCount();
        this.getIncidentTypeCount();
    },
    methods: {
        getPropertyTypeCount() {
            this.$axios
                .get("/properties/byTypeCount")
                .then((response) => {
                    this.propertiesCounts = response.data.data;
                    this.totalProperties = this.propertiesCounts.reduce(
                        (sum, item) => sum + item.count,
                        0
                    );
                })
                .catch((error) => {
                    console.error("Error fetching properties data:", error);
                })
                .finally(() => {
                    this.loadingProperties = false;
                });
        },
        getIncidentTypeCount() {
            this.$axios
                .get("/occurrences/byTypeCount")
                .then((response) => {
                    this.incidentCounts = response.data.data;
                    this.totalIncidents = this.incidentCounts.reduce(
                        (sum, item) => sum + item.count,
                        0
                    );
                })
                .catch((error) => {
                    console.error("Error fetching incidents data:", error);
                })
                .finally(() => {
                    this.loadingIncidents = false;
                });
        },
        getIconClass(typeName) {
            switch (typeName.toLowerCase()) {
                case "active":
                    return "pi pi-check-circle";
                case "inactive":
                    return "pi pi-times-circle";
                case "opened":
                    return "pi pi-folder-open";
                case "closed":
                    return "pi pi-folder";
                case "in progress":
                    return "pi pi-spinner";
                case "home":
                    return "pi pi-home";
                case "business":
                    return "pi pi-briefcase";
                case "rent default":
                    return "pi pi-dollar";
                default:
                    return "pi pi-tag";
            }
        },
        getViewModule(value) {
            switch (value) {
                case "property":
                    window.location.href = "/properties";
                    break;
                case "incident":
                    window.location.href = "/occurrences";
                    break;
            }
        },
        getViewModuleFilter(value, type) {
            switch (value) {
                case "property":
                    window.location.href = "/properties?status=" + type;
                    break;
                case "incident":
                    window.location.href = "/occurrences?status=" + type;
                    break;
            }
        },
    },
};
</script>
<style scoped>
.indicator-container {
    width: 100%;
    max-width: 1400px;
    margin: auto;
    text-align: left;
}
.cards {
    display: flex;
    gap: 25px;
    flex-wrap: wrap;
    justify-content: center;
}
.card {
    background: #f76f31;
    color: white;
    border-radius: 10px;
    padding: 10px;
    width: 100%;
    max-width: 300px;
    box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.2);
}
.card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: #f68049;
    border-radius: 10px;
    font-size: 15px;
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
.card-body p {
    font-size: 12px;
}
.tag {
    display: inline-block;
    align-items: center;
    padding: 5px 10px;
    border-radius: 5px;
    color: black;
    font-size: 12px;
    margin: 5px 5px;
    font-weight: bold;
    text-transform: capitalize;
}
.tag i {
    margin-right: 5px;
}
.active-tag,
.opened-tag,
.home-tag {
    background-color: #a4f0c8;
}
.inactive-tag {
    background-color: #f6a49d;
}
.total-tag {
    background-color: #d6d6d6;
}
.closed-tag,
.business-tag {
    background-color: #a4c8f0;
}
.in-progress-tag,
.rent-default-tag {
    background-color: #f6d6a4;
}

@media (max-width: 1400px) {
    .cards {
        gap: 20px;
    }
    .card {
        max-width: 260px;
    }
}
</style>
