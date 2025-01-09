<template>
    <Card>
        <template #title>Incident</template>
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
                <Column
                    field="property"
                    header="Property"
                    sortable
                    style="min-width: 150px"
                >
                    <template #body="{ data }">
                        {{ data.property_name }}
                    </template>
                    <template #filter="{ filterModel }">
                        <InputText
                            v-model="filterModel.value"
                            type="text"
                            class="p-column-filter"
                            placeholder="Search by property"
                        />
                    </template>
                </Column>
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
                <Column
                    field="reported_by_name"
                    header="Reported by"
                    sortable
                    style="min-width: 180px"
                >
                    <template #body="{ data }">
                        {{ data.reported_by_name }}
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
                <Column
                    field="type_name"
                    header="Incident Type"
                    sortable
                    style="min-width: 180px"
                >
                    <template #body="{ data }">
                        {{ data.type_name }}
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
                <Column
                    field="payer_name"
                    header="Payer"
                    sortable
                    style="min-width: 150px"
                >
                    <template #body="{ data }">
                        {{ data.payer_name }}
                    </template>
                    <template #filter="{ filterModel }">
                        <InputText
                            v-model="filterModel.value"
                            type="text"
                            class="p-column-filter"
                            placeholder="Search by payer"
                        />
                    </template>
                </Column>
                <Column
                    field="cost"
                    header="Cost"
                    sortable
                    style="min-width: 150px"
                >
                    <template #body="{ data }">
                        {{ this.$formatCurrency(data.cost, "EUR") }}
                    </template>
                    <template #filter="{ filterModel }">
                        <InputText
                            v-model="filterModel.value"
                            type="text"
                            class="p-column-filter"
                            placeholder="Search by cost"
                        />
                    </template>
                </Column>
                <Column
                    field="provider_name"
                    header="Provider Name"
                    sortable
                    style="min-width: 150px"
                >
                    <template #body="{ data }">
                        <div class="size-tags">
                            <Tag
                                v-for="index in $parseTags(data.provider_name)"
                                :key="index.id"
                                :value="`${index.tag}`"
                                class="size-tag"
                            />
                        </div>
                    </template>
                </Column>
                <Column
                    field="incidents"
                    header="Incidents"
                    style="min-width: 120px"
                >
                    <template #body="{ data }">
                        <div
                            class="size-insurances"
                            style="justify-content: center"
                        >
                            <i
                                class="size-insurance"
                                :class="$parsePreview(data.incidents)"
                                :title="
                                    data.incidents > 0
                                        ? 'View incidents'
                                        : 'No incidents available'
                                "
                                :style="{
                                    fontSize: '1.5rem',
                                    color:
                                        data.incidents > 0 ? 'green' : 'red',
                                    cursor:
                                        data.incidents > 0 ? 'pointer' : '',
                                }"
                                @click="
                                    viewPanelIncidents(data.incidents, data.id)
                                "
                            ></i>
                        </div>
                    </template>
                </Column>
                <Column header="Photo">
                    <template #body="{ data }">
                        <div style="text-align: center">
                            <Galleria
                                :key="galleryKey"
                                :value="$getImages(data)"
                                :numVisible="4"
                                style="width: 900px"
                                containerClass="custom-galleria"
                                :showThumbnails="true"
                                showIndicators
                                circular
                                autoPlay
                                :transitionInterval="5000"
                            >
                                <template #item="{ item }">
                                    <Image
                                        :src="item"
                                        class="w-full h-full object-cover cursor-pointer"
                                        height="100px"
                                        width="150px"
                                        preview
                                    />
                                </template>
                            </Galleria>
                        </div>
                    </template>
                </Column>
                <Column
                    header="Actions"
                    style="min-width: 120px; text-align: center"
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
                            <Button
                                icon="pi pi-upload"
                                class="p-button-rounded p-button-success"
                                style="
                                    margin: 5px;
                                    background-color: #28a745;
                                    border-color: #28a745;
                                "
                                @click="uploadPdfIncident(slotProps.data)"
                            />
                            <Button
                                icon="pi pi-cog"
                                class="p-button-rounded p-button-success"
                                style="
                                    margin: 5px;
                                    background-color: #17a2b8;
                                    border-color: #17a2b8;
                                "
                                @click="managementIncident(slotProps.data.id)"
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
    <UploadPdfModalComponent
        v-if="dialogVisiblePdf"
        :dialogVisible="dialogVisiblePdf"
        :selectedRegister="selectedIncident"
        @hidden="hidden"
        @uploadFiles="uploadFiles"
        @deletePdf="deletePdf"
    />
    <ManagemenIncidentActionComponent
        v-if="dialogVisibleAction"
        :dialogVisible="dialogVisibleAction"
        :selectedIncidentId="selectedIncidentId"
        :selectedIncident="selectedIncidentAction"
        @hidden="hidden"
        @reload="reload"
        @reloadTable="reloadTable"
    />
</template>

<script>
// Importar Librerias o Modulos

import { FilterMatchMode, FilterOperator } from "@primevue/core/api";
import ManagementIncidentComponent from "./management/ManagemenIncidentComponent.vue";
import UploadPdfModalComponent from "../utils/UploadPdfModalComponent.vue";
import ManagemenIncidentActionComponent from "../incidentAction/management/ManagemenIncidentActionComponent.vue";

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
            selectedIncidentId : true,
            selectedIncidentAction: null,
            dialogVisible: false,
            dialogVisiblePdf: false,
            dialogVisibleAction: false,
            statuses: [
                { value: "Open", name: "Open" },
                { value: "Closed", name: "Closed" },
            ],
            galleryKey: 0,
        };
    },
    components: {
        FilterMatchMode,
        FilterOperator,
        ManagementIncidentComponent,
        UploadPdfModalComponent,
        ManagemenIncidentActionComponent,
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
            const property_id = $.urlParam("property_id");
            this.$axios
                .get("/occurrences/list", {
                    params: {
                        page: this.page,
                        perPage: this.perPage,
                        sort: [this.sortField, this.sortOrder],
                        filters: this.filtroInfo,
                        select: this.filterSelect ?? null,
                        property_id: property_id ?? null,
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
        editIncident(incident) {
            this.selectedIncident = incident;
            this.dialogVisible = true;
        },
        uploadPdfIncident(incident) {
            this.selectedIncident = incident;
            this.dialogVisiblePdf = true;
        },
        managementIncident(id) {
            this.selectedIncidentId = id;
            this.dialogVisibleAction = true;
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
            this.selectedIncidentId = null;
            this.dialogVisible = false;
            this.dialogVisibleAction = false;
        },
        reloadTable() {
            this.fetchIncident();
            this.resetGallery();
        },
        resetGallery() {
            this.galleryKey += 1;
        },
        hidden(status) {
            this.dialogVisible = status;
            this.dialogVisiblePdf = status;
        },
        async uploadFiles(pdfs) {
            if (pdfs.length === 0) {
                this.$alertWarning("No files selected for upload");
                return;
            }
            const data = {
                incident_id: this.selectedIncident.id,
                pdfs: pdfs,
            };
            this.$axios
                .post("/occurrences/document/add", data, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                })
                .then(() => {
                    this.$alertSuccess("Files uploaded successfully");
                    this.fetchIncident();
                })
                .catch((error) => {
                    this.$readStatusHttp(error);
                });
        },
        deletePdf(pdfField) {
            this.$axios
                .post(`/occurrences/document/delete`, {
                    incident_id: this.selectedIncident.id,
                    type: pdfField,
                })
                .then(() => {
                    this.$alertSuccess("File deleted successfully");
                    this.fetchIncident();
                })
                .catch((error) => {
                    this.$readStatusHttp(error);
                });
        },
        viewPanelIncidents(incidents, id) {
            if (incidents == 0) return;
            window.location.href = "/occurrences-action?incident_id=" + id;
            return;
        },
    },
};
</script>

<style></style>
