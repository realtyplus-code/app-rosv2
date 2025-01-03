<template>
    <Card>
        <template #title>Incident Kanban</template>
        <template #content>
            <div class="p-d-flex p-jc-end p-mb-3">
                <Button
                    icon="pi pi-filter-slash"
                    class="p-button-sm"
                    rounded
                    raised
                    @click="clearFilters"
                    style="
                        background-color: #f76f31 !important;
                        border-color: #f76f31;
                    "
                />
            </div>
            <div class="kanban-board">
                <div
                    v-for="status in statuses"
                    :key="status.name"
                    class="kanban-column"
                >
                    <h3>{{ status.name }}</h3>
                    <VueDraggableNext
                        :list="status.incidents"
                        :group="{ name: 'incidents', pull: true, push: true }"
                        class="task-list"
                        @end="onDragEnd($event, status.name)"
                    >
                        <div
                            v-for="incident in status.incidents"
                            :key="incident.id"
                            class="kanban-task"
                            :data-id="incident.id"
                        >
                            <div class="incident-property">
                                <strong>Property:</strong>
                                {{ incident.property_name }}
                            </div>
                            <div class="incident-description">
                                <strong>Description:</strong>
                                {{ incident.description }}
                            </div>
                            <div class="incident-report-date">
                                <strong>Report Date:</strong>
                                {{ incident.report_date }}
                            </div>
                            <div class="incident-status">
                                <strong>Status:</strong>
                                {{ incident.status_name }}
                            </div>
                            <div class="incident-reported-by">
                                <strong>Reported By:</strong>
                                {{ incident.reported_by_name }}
                            </div>
                            <div class="incident-type">
                                <strong>Type:</strong> {{ incident.type_name }}
                            </div>
                            <div class="incident-priority">
                                <strong>Priority:</strong>
                                {{ incident.priority_name }}
                            </div>
                            <div class="incident-payer">
                                <strong>Payer:</strong>
                                {{ incident.payer_name }}
                            </div>
                            <div class="incident-cost">
                                {{ this.$formatCurrency(incident.cost, "EUR") }}
                            </div>
                            <div class="size-tags">
                                <Tag
                                    v-for="index in $parseTags(
                                        incident.provider_name
                                    )"
                                    :key="index.id"
                                    :value="`${index.tag}`"
                                    class="size-tag"
                                />
                            </div>
                            <!-- Acciones -->
                            <div class="row" style="margin-right: 10px">
                                <Button
                                    icon="pi pi-pencil"
                                    class="p-button-rounded p-button-primary"
                                    style="
                                        margin: 5px;
                                        background-color: #f76f31;
                                        border-color: #f76f31;
                                    "
                                    @click="editIncident(incident)"
                                />
                                <Button
                                    icon="pi pi-trash"
                                    class="p-button-rounded p-button-danger"
                                    style="
                                        margin: 5px;
                                        background-color: #db6464;
                                        border-color: #db6464;
                                    "
                                    @click="deleteIncident(incident.id)"
                                />
                            </div>
                        </div>
                    </VueDraggableNext>
                </div>
            </div>
        </template>
    </Card>
    <!-- gestion de incidentes -->
    <ManagementIncidentComponent
        v-if="dialogVisible"
        :dialogVisible="dialogVisible"
        :selectedIncident="selectedIncident"
        @hidden="hidden"
        @reload="reload"
        @reloadTable="reloadTable"
    />
</template>

<script>
// Importar Librerias o Modulos

import { FilterMatchMode, FilterOperator } from "@primevue/core/api";
import ManagementIncidentComponent from "./management/ManagemenIncidentComponent.vue";

export default {
    props: [],
    data() {
        return {
            incidents: [],
            perPage: 10,
            totalRecords: 0,
            page: 1,
            sortField: "",
            sortOrder: null,
            filters: null,
            filtroInfo: [],
            loading: true,
            //
            selectedIncident: null,
            dialogVisible: false,
            statuses: [
                { value: "Opened", name: "opened", incidents: [] },
                { value: "In Progress", name: "in progress", incidents: [] },
                { value: "Closed", name: "closed", incidents: [] },
            ],
        };
    },
    components: {
        FilterMatchMode,
        FilterOperator,
        ManagementIncidentComponent,
    },
    created() {
        this.initFilters();
    },
    mounted() {
        this.fetchIncident();
    },
    methods: {
        initFilters() {
            this.filters = {
                description: {
                    clear: false,
                    constraints: [
                        { value: null, matchMode: FilterMatchMode.STARTS_WITH },
                    ],
                },
                report_date: {
                    clear: false,
                    constraints: [
                        { value: null, matchMode: FilterMatchMode.STARTS_WITH },
                    ],
                },
                status_name: {
                    clear: false,
                    filterOptions: {
                        showFilterMenu: false,
                    },
                    constraints: [
                        { value: null, matchMode: FilterMatchMode.EQUALS },
                    ],
                },
                type_name: {
                    clear: false,
                    constraints: [
                        { value: null, matchMode: FilterMatchMode.STARTS_WITH },
                    ],
                },
                priority_name: {
                    clear: false,
                    constraints: [
                        { value: null, matchMode: FilterMatchMode.STARTS_WITH },
                    ],
                },
            };
        },
        clearFilters() {
            this.initFilters();
            this.filtroInfo = [];
            this.fetchIncident();
        },
        onPage(event) {
            this.page = event.page + 1;
            this.perPage = event.rows;
            this.fetchIncident();
        },
        onSort(event) {
            this.page = 1;
            this.sortField = event.sortField;
            this.sortOrder = event.sortOrder;
            this.fetchIncident();
        },
        onFilters(event) {
            this.page = 1;
            this.filtroInfo = [];
            for (const [key, filter] of Object.entries(event.filters)) {
                if (filter.constraints) {
                    console.log(filter.constraints);
                    for (const constraint of filter.constraints) {
                        if (constraint.value) {
                            this.filtroInfo.push([
                                this.$relationTableIncident(key),
                                constraint.matchMode,
                                constraint.value,
                            ]);
                        }
                    }
                }
            }
            this.fetchIncident();
        },
        fetchIncident() {
            this.loading = true;
            this.$axios
                .get("/occurrences/list", {
                    params: {
                        page: this.page,
                        perPage: this.perPage,
                        sort: [this.sortField, this.sortOrder],
                        filters: this.filtroInfo,
                        select: this.filterSelect ?? null,
                    },
                })
                .then((response) => {
                    this.incidents = response.data.data;
                    this.totalRecords = response.data.total;
                    this.loading = false;
                    this.updateStatuses();
                })
                .catch((error) => {
                    this.$readStatusHttp(error);
                    this.loading = false;
                });
        },
        updateStatuses() {
            this.statuses.forEach((status) => {
                status.incidents = this.incidents.filter(
                    (incident) => incident.status_name === status.name
                );
            });
        },
        editIncident(incident) {
            this.selectedIncident = incident;
            this.dialogVisible = true;
        },
        async deleteIncident(incidentId) {
            const result = await this.$swal.fire({
                title: "You're sure?",
                text: "You are about to delete this incident. Are you sure you want to continue?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete",
                cancelButtonText: "Cancelar",
            });

            if (result.isConfirmed) {
                try {
                    await axios.delete(`/occurrences/${incidentId}`);
                    this.$alertSuccess("Register delete");
                    this.fetchIncident();
                } catch (error) {
                    this.$readStatusHttp(error);
                }
            }
        },
        reload() {
            this.fetchIncident();
            this.selectedIncident = null;
            this.dialogVisible = false;
        },
        reloadTable() {
            this.fetchIncident();
        },
        hidden(status) {
            this.dialogVisible = status;
        },
        async onDragEnd(event, oldStatus) {
            const incidentId = event.item.getAttribute("data-id");
            if (!incidentId) return;
            const newStatus = event.to
                .closest(".kanban-column")
                .querySelector("h3")
                .innerText.toLowerCase();
            try {
                await this.$axios.post(`/occurrences/update/${incidentId}`, {
                    type: "status",
                    status: newStatus,
                });

                this.fetchIncident();
            } catch (error) {
                this.$readStatusHttp(error);
            }
        },
    },
};
</script>

<style>
.kanban-board {
    display: flex;
}
.kanban-column {
    flex: 1;
    margin: 0 1rem;
}
.task-list {
    margin-top: 1rem;
}
.kanban-task {
    padding: 0.5rem;
    background-color: #f1f1f1;
    margin-bottom: 0.5rem;
}
.incident-property,
.incident-description,
.incident-report-date,
.incident-status,
.incident-reported-by,
.incident-type,
.incident-priority,
.incident-payer,
.incident-cost {
    margin-bottom: 0.25rem;
}
.size-tags {
    display: flex;
    flex-wrap: wrap;
}
.size-tag {
    margin-right: 0.25rem;
    margin-bottom: 0.25rem;
}
.row {
    display: flex;
    justify-content: flex-end;
}
</style>
