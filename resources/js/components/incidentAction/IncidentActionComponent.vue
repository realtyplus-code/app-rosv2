<template>
    <Card>
        <template #title>Incident Actions</template>
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
                    field="incident_name"
                    header="Incident"
                    sortable
                    style="min-width: 150px"
                >
                    <template #body="{ data }">
                        {{ data.incident_name }}
                    </template>
                    <template #filter="{ filterModel }">
                        <InputText
                            v-model="filterModel.value"
                            type="text"
                            class="p-column-filter"
                            placeholder="Search by incident"
                        />
                    </template>
                </Column>
                <Column
                    field="action_description"
                    header="Description"
                    sortable
                    style="min-width: 150px"
                >
                    <template #body="{ data }">
                        {{ data.action_description }}
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
                    field="action_date"
                    header="Action Date"
                    sortable
                    style="min-width: 150px"
                >
                    <template #body="{ data }">
                        {{ data.action_date }}
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
                    field="responsible_user_name"
                    header="Responsible by"
                    sortable
                    style="min-width: 180px"
                >
                    <template #body="{ data }">
                        {{ data.responsible_user_name }}
                    </template>
                    <template #filter="{ filterModel }">
                        <InputText
                            v-model="filterModel.value"
                            type="text"
                            class="p-column-filter"
                            placeholder="Search by responsible"
                        />
                    </template>
                </Column>
                <Column
                    field="action_cost"
                    header="Cost"
                    sortable
                    style="min-width: 150px"
                >
                    <template #body="{ data }">
                        {{ this.$formatCurrency(data.action_cost, "EUR") }}
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
                    style="min-width: 100px; text-align: center"
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
                                @click="editIncidentAction(slotProps.data)"
                            />
                            <Button
                                icon="pi pi-trash"
                                class="p-button-rounded p-button-danger"
                                style="
                                    margin: 5px;
                                    background-color: #db6464;
                                    border-color: #db6464;
                                "
                                @click="deleteIncidentAction(slotProps.data.id)"
                            />
                            <Button
                                icon="pi pi-upload"
                                class="p-button-rounded p-button-success"
                                style="
                                    margin: 5px;
                                    background-color: #28a745;
                                    border-color: #28a745;
                                "
                                @click="uploadPdfIncidentAction(slotProps.data)"
                            />
                        </div>
                    </template>
                </Column>
            </DataTable>
        </template>
    </Card>
    <!-- gestion de incidentes -->
    <UploadPdfModalComponent
        v-if="dialogVisiblePdf"
        :dialogVisible="dialogVisiblePdf"
        :selectedRegister="selectedIncident"
        @hidden="hidden"
        @uploadFiles="uploadFiles"
        @deletePdf="deletePdf"
    />
</template>

<script>
// Importar Librerias o Modulos

import { FilterMatchMode, FilterOperator } from "@primevue/core/api";
import UploadPdfModalComponent from "../utils/UploadPdfModalComponent.vue";

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
            selectedIncidentId: true,
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
        UploadPdfModalComponent,
    },
    created() {
        this.initFilters();
    },
    mounted() {
        this.fetchIncidentAction();
    },
    methods: {
        initFilters() {
            this.filters = {
                incident_name: {
                    clear: false,
                    constraints: [
                        { value: null, matchMode: FilterMatchMode.STARTS_WITH },
                    ],
                },
                action_date: {
                    clear: false,
                    constraints: [
                        { value: null, matchMode: FilterMatchMode.STARTS_WITH },
                    ],
                },
                responsible_user_name: {
                    clear: false,
                    constraints: [
                        { value: null, matchMode: FilterMatchMode.STARTS_WITH },
                    ],
                },
                action_description: {
                    clear: false,
                    constraints: [
                        { value: null, matchMode: FilterMatchMode.STARTS_WITH },
                    ],
                },
                action_cost: {
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
            this.fetchIncidentAction();
        },
        onPage(event) {
            this.page = event.page + 1;
            this.perPage = event.rows;
            this.fetchIncidentAction();
        },
        onSort(event) {
            this.page = 1;
            this.sortField = event.sortField;
            this.sortOrder = event.sortOrder;
            this.fetchIncidentAction();
        },
        onFilters(event) {
            this.page = 1;
            this.filtroInfo = [];
            for (const [key, filter] of Object.entries(event.filters)) {
                if (filter.constraints) {
                    for (const constraint of filter.constraints) {
                        if (constraint.value) {
                            this.filtroInfo.push([
                                this.$relationTableIncidentAction(key),
                                constraint.matchMode,
                                constraint.value,
                            ]);
                        }
                    }
                }
            }
            this.fetchIncidentAction();
        },
        fetchIncidentAction() {
            this.loading = true;
            const incident_id = $.urlParam("incident_id");
            this.$axios
                .get("/occurrences-action/list", {
                    params: {
                        page: this.page,
                        perPage: this.perPage,
                        sort: [this.sortField, this.sortOrder],
                        filters: this.filtroInfo,
                        select: this.filterSelect ?? null,
                        incident_id: incident_id ?? null,
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
        editIncidentAction(incident) {
            this.selectedIncident = incident;
            this.dialogVisible = true;
        },
        uploadPdfIncidentAction(incident) {
            this.selectedIncident = incident;
            this.dialogVisiblePdf = true;
        },
        managementIncidentAction(id) {
            this.selectedIncidentId = id;
            this.dialogVisibleAction = true;
        },
        async deleteIncidentAction(incidentId) {
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
                    await axios.delete(`/occurrences-action/${incidentId}`);
                    this.$alertSuccess("Register delete");
                    this.fetchIncidentAction();
                } catch (error) {
                    this.$readStatusHttp(error);
                }
            }
        },
        reload() {
            this.fetchIncidentAction();
            this.selectedIncident = null;
            this.selectedIncidentId = null;
            this.dialogVisible = false;
            this.dialogVisibleAction = false;
        },
        reloadTable() {
            this.fetchIncidentAction();
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
                .post("/occurrences-action/document/add", data, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                })
                .then(() => {
                    this.$alertSuccess("Files uploaded successfully");
                    this.fetchIncidentAction();
                })
                .catch((error) => {
                    this.$readStatusHttp(error);
                });
        },
        deletePdf(pdfField) {
            this.$axios
                .post(`/occurrences-action/document/delete`, {
                    incident_id: this.selectedIncident.id,
                    type: pdfField,
                })
                .then(() => {
                    this.$alertSuccess("File deleted successfully");
                    this.fetchIncidentAction();
                })
                .catch((error) => {
                    this.$readStatusHttp(error);
                });
        },
    },
};
</script>

<style></style>
