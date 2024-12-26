<template>
    <Card>
        <template #title>Incident</template>
        <template #content>
            <div class="p-d-flex p-jc-end p-mb-3">
                <Button
                    icon="pi pi-plus"
                    rounded
                    raised
                    @click="addIncident"
                    style="
                        margin-right: 10px;
                        background-color: #f76f31 !important;
                        border-color: #f76f31;
                    "
                />
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
            <DataTable
                v-model:filters="filters"
                :loading="loading"
                :value="incidents"
                :paginator="true"
                :rows="perPage"
                :sortField="sortField"
                :sortOrder="sortOrder"
                :totalRecords="totalRecords"
                :lazy="true"
                @page="onPage"
                @sort="onSort"
                @filter="onFilters"
                filterDisplay="menu"
                removableSort
                stripedRows
                scrollable
            >
                <!-- Description Column -->
                <Column
                    field="description"
                    header="Description"
                    sortable
                    style="min-width: 150px"
                >
                    <template #body="{ data }">
                        {{ data.description }}
                    </template>
                    <template #filter="{ filterModel }">
                        <InputText
                            v-model="filterModel.value"
                            type="text"
                            class="p-column-filter"
                            placeholder="Search by description"
                        />
                    </template>
                </Column>
                <!-- Report Date Column -->
                <Column
                    field="report_date"
                    header="Report Date"
                    sortable
                    style="min-width: 150px"
                >
                    <template #body="{ data }">
                        {{ data.report_date }}
                    </template>
                    <template #filter="{ filterModel }">
                        <InputText
                            v-model="filterModel.value"
                            type="text"
                            class="p-column-filter"
                            placeholder="Search by report date"
                        />
                    </template>
                </Column>
                <!-- Status Column -->
                <Column
                    field="status_name"
                    header="Status"
                    sortable
                    style="min-width: 120px"
                >
                    <template #body="{ data }">
                        {{ data.status_name }}
                    </template>
                    <template #filter="{ filterModel }">
                        <Select
                            :options="statuses"
                            v-model="filterModel.value"
                            placeholder="Select status"
                            optionLabel="value"
                            optionValue="name"
                            style="width: 100%"
                        />
                    </template>
                </Column>
                <!-- Incident Type Column -->
                <Column
                    field="incident_type_name"
                    header="Incident Type"
                    sortable
                    style="min-width: 180px"
                >
                    <template #body="{ data }">
                        {{ data.incident_type_name }}
                    </template>
                    <template #filter="{ filterModel }">
                        <InputText
                            v-model="filterModel.value"
                            type="text"
                            class="p-column-filter"
                            placeholder="Search by incident type"
                        />
                    </template>
                </Column>
                <!-- Priority Column -->
                <Column
                    field="priority_name"
                    header="Priority"
                    sortable
                    style="min-width: 150px"
                >
                    <template #body="{ data }">
                        {{ data.priority_name }}
                    </template>
                    <template #filter="{ filterModel }">
                        <InputText
                            v-model="filterModel.value"
                            type="text"
                            class="p-column-filter"
                            placeholder="Search by priority"
                        />
                    </template>
                </Column>
                <!-- Actions Column -->
                <Column
                    header="Actions"
                    style="min-width: 180px; text-align: center"
                >
                    <template #body="slotProps">
                        <div class="row">
                            <Button
                                icon="pi pi-pencil"
                                class="p-button-rounded p-button-primary"
                                style="
                                    margin: 5px;
                                    background-color: #f76f31;
                                    border-color: #f76f31;
                                "
                                @click="editIncident(slotProps.data)"
                            />
                            <Button
                                icon="pi pi-trash"
                                class="p-button-rounded p-button-danger"
                                style="
                                    margin: 5px;
                                    background-color: #db6464;
                                    border-color: #db6464;
                                "
                                @click="deleteIncident(slotProps.data.id)"
                            />
                        </div>
                    </template>
                </Column>
            </DataTable>
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

import Card from "primevue/card";
import { FilterMatchMode, FilterOperator } from "@primevue/core/api";
import ManagementIncidentComponent from "./management/ManagemenIncidentComponent.vue";
import InputText from "primevue/inputtext";
import DataTable from "primevue/datatable";
import Select from "primevue/select";
import Column from "primevue/column";
import Button from "primevue/button";

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
                { value: "Open", name: "Open" },
                { value: "Closed", name: "Closed" },
            ],
        };
    },
    components: {
        FilterMatchMode,
        FilterOperator,
        Card,
        ManagementIncidentComponent,
        DataTable,
        Column,
        Button,
        InputText,
        Select,
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
                incident_type_name: {
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
                .get("/incidents/list", {
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
                })
                .catch((error) => {
                    this.$readStatusHttp(error);
                    this.loading = false;
                });
        },
        addIncident() {
            this.selectedIncident = null;
            this.dialogVisible = true;
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
                    await axios.delete(`/incidents/${incidentId}`);
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
    },
};
</script>

<style></style>
