<template>
    <Card>
        <template #title>Property</template>
        <template #content>
            <div class="p-d-flex p-jc-end p-mb-3">
                <Button
                    icon="pi pi-plus"
                    rounded
                    raised
                    @click="addProperty"
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
                        margin-right: 10px;
                        background-color: #f76f31 !important;
                        border-color: #f76f31;
                    "
                />
                <Button
                    icon="pi pi-file-excel"
                    class="p-button-sm"
                    rounded
                    raised
                    @click="exportToExcel"
                    style="
                        margin-right: 10px;
                        background-color: #28a745 !important;
                        border-color: #28a745;
                    "
                />
                <Button
                    icon="pi pi-file-pdf"
                    class="p-button-sm"
                    rounded
                    raised
                    @click="exportToPDF"
                    style="
                        background-color: #dc3545 !important;
                        border-color: #dc3545;
                    "
                />
            </div>
            <DataTable
                v-model:filters="filters"
                :loading="loading"
                :value="users"
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
                <!-- Name Column -->
                <Column
                    field="name"
                    header="Name"
                    sortable
                    style="min-width: 150px"
                >
                    <template #body="{ data }">
                        {{ data.name }}
                    </template>
                    <template #filter="{ filterModel }">
                        <InputText
                            v-model="filterModel.value"
                            type="text"
                            class="p-column-filter"
                            placeholder="Search by name"
                        />
                    </template>
                </Column>
                <!-- Address Column -->
                <Column
                    field="address"
                    header="Address"
                    sortable
                    style="min-width: 200px"
                >
                    <template #body="{ data }">
                        {{ data.address }}
                    </template>
                    <template #filter="{ filterModel }">
                        <InputText
                            v-model="filterModel.value"
                            type="text"
                            class="p-column-filter"
                            placeholder="Search by address"
                        />
                    </template>
                </Column>
                <!-- Status Column -->
                <Column
                    field="status"
                    header="Status"
                    sortable
                    style="min-width: 120px"
                >
                    <template #body="{ data }">
                        {{ $formatStatus(data.status) }}
                    </template>
                    <template #filter="{ filterModel }">
                        <Select
                            :options="statuses"
                            v-model="filterModel.value"
                            placeholder="Select status"
                            optionLabel="value"
                            optionValue="id"
                            style="width: 100%"
                        />
                    </template>
                </Column>
                <Column
                    field="country"
                    header="Country"
                    sortable
                    :showClearButton="false"
                    style="min-width: 100px"
                >
                    <template #body="{ data }">
                        {{ data.country_name }}
                    </template>
                    <template #filter="{ filterModel }">
                        <InputText
                            v-model="filterModel.value"
                            type="text"
                            class="p-column-filter"
                            placeholder="Search by country"
                        />
                    </template>
                </Column>
                <Column
                    field="state"
                    header="State"
                    sortable
                    :showClearButton="false"
                    style="min-width: 100px"
                >
                    <template #body="{ data }">
                        {{ data.state_name }}
                    </template>
                    <template #filter="{ filterModel }">
                        <InputText
                            v-model="filterModel.value"
                            type="text"
                            class="p-column-filter"
                            placeholder="Search by state"
                        />
                    </template>
                </Column>
                <Column
                    field="city"
                    header="City"
                    sortable
                    :showClearButton="false"
                    style="min-width: 100px"
                >
                    <template #body="{ data }">
                        {{ data.city_name }}
                    </template>
                    <template #filter="{ filterModel }">
                        <InputText
                            v-model="filterModel.value"
                            type="text"
                            class="p-column-filter"
                            placeholder="Search by city"
                        />
                    </template>
                </Column>
                <!-- Property Type Name Column -->
                <Column
                    field="property_type_name"
                    header="Property Type"
                    sortable
                    style="min-width: 180px"
                >
                    <template #body="{ data }">
                        {{ data.property_type_name }}
                    </template>
                    <template #filter="{ filterModel }">
                        <InputText
                            v-model="filterModel.value"
                            type="text"
                            class="p-column-filter"
                            placeholder="Search by property type"
                        />
                    </template>
                </Column>
                <!-- Owner Name Column -->
                <Column
                    field="owners_name"
                    header="Owner Name"
                    sortable
                    style="min-width: 150px"
                >
                    <template #body="{ data }">
                        <div class="size-tags">
                            <Tag
                                v-for="index in $parseTags(data.owners_name)"
                                :key="index.id"
                                :value="`${index.tag}`"
                                class="size-tag"
                            />
                        </div>
                    </template>
                </Column>
                <!-- Tenant Name Column -->
                <Column
                    field="tenants_name"
                    header="Tenant Name"
                    sortable
                    style="min-width: 150px"
                >
                    <template #body="{ data }">
                        <div class="size-tags">
                            <Tag
                                v-for="index in $parseTags(data.tenants_name)"
                                :key="index.id"
                                :value="`${index.tag}`"
                                class="size-tag"
                            />
                        </div>
                    </template>
                </Column>
                <!-- Insurances Type Name Column -->
                <Column
                    field="insurances"
                    header="Insurances"
                    style="min-width: 120px"
                >
                    <template #body="{ data }">
                        <div
                            class="size-insurances"
                            style="justify-content: center"
                        >
                            <i
                                class="size-insurance"
                                :class="$parsePreview(data.insurances)"
                                :title="
                                    data.insurances > 0
                                        ? 'View insurances'
                                        : 'No insurances available'
                                "
                                :style="{
                                    fontSize: '1.5rem',
                                    color:
                                        data.insurances > 0 ? 'green' : 'red',
                                    cursor:
                                        data.insurances > 0 ? 'pointer' : '',
                                }"
                                @click="
                                    viewPanelInsurance(data.insurances, data.id)
                                "
                            ></i>
                        </div>
                    </template>
                </Column>
                <!-- incidents Type Name Column -->
                <Column
                    field="incidents"
                    header="Incidents"
                    style="min-width: 120px"
                >
                    <template #body="{ data }">
                        <div
                            class="size-incidents"
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
                                    color: data.incidents > 0 ? 'green' : 'red',
                                    cursor: data.incidents > 0 ? 'pointer' : '',
                                }"
                                @click="
                                    viewPanelIncidents(data.incidents, data.id)
                                "
                            ></i>
                        </div>
                    </template>
                </Column>
                <Column
                    field="created_at"
                    header="Crated"
                    sortable
                    :showClearButton="false"
                    style="min-width: 100px"
                >
                    <template #body="{ data }">
                        {{ data.created_at }}
                    </template>
                    <template #filter="{ filterModel }">
                        <InputText
                            v-model="filterModel.value"
                            type="text"
                            class="p-column-filter"
                            placeholder="Search by created"
                        />
                    </template>
                </Column>
                <Column
                    field="expected_end_date_ros"
                    header="Expected End"
                    sortable
                    :showClearButton="false"
                    style="min-width: 100px"
                >
                    <template #body="{ data }">
                        {{ data.expected_end_date_ros }}
                    </template>
                    <template #filter="{ filterModel }">
                        <InputText
                            v-model="filterModel.value"
                            type="text"
                            class="p-column-filter"
                            placeholder="Search by expected end"
                        />
                    </template>
                </Column>
                <!-- Photos Column -->
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
                <!-- Actions Column -->
                <Column
                    header="Edit/Delete"
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
                                @click="editProperty(slotProps.data)"
                            />
                            <Button
                                icon="pi pi-trash"
                                class="p-button-rounded p-button-danger"
                                style="
                                    margin: 5px;
                                    background-color: #db6464;
                                    border-color: #db6464;
                                "
                                @click="deleteProperty(slotProps.data.id)"
                            />
                        </div>
                    </template>
                </Column>
                <Column
                    header="Upload/Manage"
                    style="min-width: 180px; text-align: center"
                >
                    <template #body="slotProps">
                        <div class="row">
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
                            <Button
                                icon="pi pi-shield"
                                class="p-button-rounded p-button-warn"
                                style="
                                    margin: 5px;
                                    background-color: #ffc107;
                                    border-color: #ffc107;
                                "
                                @click="viewInsurance(slotProps.data.id)"
                                title="Insurances"
                            />
                            <Button
                                icon="pi pi-exclamation-circle"
                                class="p-button-rounded p-button-info"
                                style="
                                    margin: 5px;
                                    background-color: #17a2b8;
                                    border-color: #17a2b8;
                                "
                                @click="viewIncident(slotProps.data.id)"
                                title="Incidents"
                            />
                        </div>
                    </template>
                </Column>
            </DataTable>
        </template>
    </Card>
    <!-- gestion de propiedades -->
    <ManagemenPropertyComponent
        v-if="dialogVisible"
        :dialogVisible="dialogVisible"
        :selectedProperty="selectedProperty"
        @hidden="hidden"
        @reload="reload"
        @reloadTable="reloadTable"
    />
    <!-- gestion de seguros -->
    <ManagemenInsuranceComponent
        v-if="dialogVisibleInsurance"
        :dialogVisible="dialogVisibleInsurance"
        :selectedPropertyId="selectedPropertyId"
        @hidden="hiddenInsurance"
        @reload="reloadInsurance"
    />
    <!-- gestion de incidencias -->
    <ManagemenIncidentComponent
        v-if="dialogVisibleIncident"
        :dialogVisible="dialogVisibleIncident"
        :selectedPropertyId="selectedPropertyId"
        @hidden="hiddenIncident"
        @reload="reloadIncident"
    />
    <UploadPdfModalComponent
        v-if="dialogVisiblePdf"
        :dialogVisible="dialogVisiblePdf"
        :selectedRegister="selectedProperty"
        :limit="1"
        @hidden="hidden"
        @uploadFiles="uploadFiles"
        @deletePdf="deletePdf"
    />
</template>

<script>
// Importar Librerias o Modulos
import { FilterMatchMode, FilterOperator } from "@primevue/core/api";
import UploadPdfModalComponent from "../utils/UploadPdfModalComponent.vue";
import ManagemenPropertyComponent from "./management/ManagemenPropertyComponent.vue";
import ManagemenInsuranceComponent from "../insurance/management/ManagemenInsuranceComponent.vue";
import ManagemenIncidentComponent from "../incident/management/ManagemenIncidentComponent.vue";

export default {
    props: [],
    data() {
        return {
            users: [],
            perPage: 10,
            totalRecords: 0,
            page: 1,
            sortField: "",
            sortOrder: null,
            filters: null,
            filtroInfo: [],
            loading: true,
            //
            selectedProperty: null,
            selectedPropertyId: true,
            dialogVisible: false,
            dialogVisibleInsurance: false,
            dialogVisibleIncident: false,
            statuses: [
                { value: "Active", id: 1 },
                { value: "Inactive", id: 2 },
            ],
            galleryKey: 0,
            dialogVisiblePdf: false,
        };
    },
    components: {
        FilterMatchMode,
        FilterOperator,
        ManagemenPropertyComponent,
        ManagemenInsuranceComponent,
        ManagemenIncidentComponent,
        UploadPdfModalComponent,
    },
    created() {
        this.initFilters();
    },
    mounted() {
        this.fetchProperty();
    },
    methods: {
        initFilters() {
            this.filters = {
                name: {
                    clear: false,
                    constraints: [
                        { value: null, matchMode: FilterMatchMode.STARTS_WITH },
                    ],
                },
                address: {
                    clear: false,
                    constraints: [
                        { value: null, matchMode: FilterMatchMode.STARTS_WITH },
                    ],
                },
                status: {
                    clear: false,
                    filterOptions: {
                        showFilterMenu: false,
                    },
                    constraints: [
                        { value: null, matchMode: FilterMatchMode.EQUALS },
                    ],
                },
                owner_name: {
                    clear: false,
                    constraints: [
                        { value: null, matchMode: FilterMatchMode.STARTS_WITH },
                    ],
                },
                tenant_name: {
                    clear: false,
                    constraints: [
                        { value: null, matchMode: FilterMatchMode.STARTS_WITH },
                    ],
                },
                property_type_name: {
                    clear: false,
                    constraints: [
                        { value: null, matchMode: FilterMatchMode.STARTS_WITH },
                    ],
                },
                country: {
                    clear: false,
                    constraints: [
                        { value: null, matchMode: FilterMatchMode.STARTS_WITH },
                    ],
                },
                state: {
                    clear: false,
                    constraints: [
                        { value: null, matchMode: FilterMatchMode.STARTS_WITH },
                    ],
                },
                city: {
                    clear: false,
                    constraints: [
                        { value: null, matchMode: FilterMatchMode.STARTS_WITH },
                    ],
                },
                created_at: {
                    clear: false,
                    constraints: [
                        { value: null, matchMode: FilterMatchMode.STARTS_WITH },
                    ],
                },
                expected_end_date_ros: {
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
            this.fetchProperty();
        },
        onPage(event) {
            this.page = event.page + 1;
            this.perPage = event.rows;
            this.fetchProperty();
        },
        onSort(event) {
            this.page = 1;
            this.sortField = event.sortField;
            this.sortOrder = event.sortOrder;
            this.fetchProperty();
        },
        onFilters(event) {
            this.page = 1;
            this.filtroInfo = [];
            for (const [key, filter] of Object.entries(event.filters)) {
                if (filter.constraints) {
                    for (const constraint of filter.constraints) {
                        if (constraint.value) {
                            this.filtroInfo.push([
                                this.$relationTableProperty(key),
                                constraint.matchMode,
                                constraint.value,
                            ]);
                        }
                    }
                }
            }
            this.fetchProperty();
        },
        fetchProperty() {
            this.loading = true;
            this.$axios
                .get("/properties/list", {
                    params: {
                        page: this.page,
                        perPage: this.perPage,
                        sort: [this.sortField, this.sortOrder],
                        filters: this.filtroInfo,
                        select: this.filterSelect ?? null,
                    },
                })
                .then((response) => {
                    this.users = response.data.data;
                    this.totalRecords = response.data.total;
                    this.loading = false;
                })
                .catch((error) => {
                    this.$readStatusHttp(error);
                    this.loading = false;
                });
        },
        addProperty() {
            this.selectedProperty = null;
            this.dialogVisible = true;
        },
        editProperty(aliado) {
            this.selectedProperty = aliado;
            this.dialogVisible = true;
        },
        async deleteProperty(aliadoId) {
            const result = await this.$swal.fire({
                title: "You're sure?",
                text: "You are about to delete this property. Are you sure you want to continue?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete",
                cancelButtonText: "Cancelar",
            });

            if (result.isConfirmed) {
                try {
                    await axios.delete(`/properties/${aliadoId}`);
                    this.$alertSuccess("Register delete");
                    this.fetchProperty();
                } catch (error) {
                    this.$readStatusHttp(error);
                }
            }
        },
        reload() {
            this.fetchProperty();
            this.selectedProperty = null;
            this.dialogVisible = false;
        },
        reloadInsurance() {
            this.fetchProperty();
            this.selectedPropertyId = null;
            this.dialogVisibleInsurance = false;
        },
        reloadIncident() {
            this.fetchProperty();
            this.selectedPropertyId = null;
            this.dialogVisibleIncident = false;
        },
        reloadTable() {
            this.fetchProperty();
            this.resetGallery();
        },
        resetGallery() {
            this.galleryKey += 1;
        },
        hidden(status) {
            this.dialogVisible = status;
            this.dialogVisiblePdf = status;
        },
        hiddenInsurance(status) {
            this.dialogVisibleInsurance = status;
        },
        hiddenIncident(status) {
            this.dialogVisibleIncident = status;
        },
        viewInsurance(id) {
            this.selectedPropertyId = id;
            this.dialogVisibleInsurance = true;
        },
        viewIncident(id) {
            this.selectedPropertyId = id;
            this.dialogVisibleIncident = true;
        },
        viewPanelInsurance(insurances, id) {
            if (insurances == 0) return;
            window.location.href = "/insurances?property_id=" + id;
            return;
        },
        viewPanelIncidents(insurances, id) {
            if (insurances == 0) return;
            window.location.href = "/occurrences?property_id=" + id;
            return;
        },
        uploadPdfIncidentAction(item) {
            this.selectedProperty = item;
            this.dialogVisiblePdf = true;
        },
        async exportToExcel() {
            const params = {
                page: this.page,
                perPage: this.perPage,
                sort: [this.sortField, this.sortOrder],
                filters: this.filtroInfo,
                select: this.filterSelect ?? null,
            };
            this.loading = true;
            try {
                await this.$exportToExcel(
                    "properties/export",
                    params,
                    "Properties"
                );
                this.$alertSuccess("Document generated successfully");
            } catch (error) {
                this.$readStatusHttp(error);
            } finally {
                this.loading = false;
            }
        },
        async exportToPDF() {
            const params = {
                page: this.page,
                perPage: this.perPage,
                sort: [this.sortField, this.sortOrder],
                filters: this.filtroInfo,
                select: this.filterSelect ?? null,
            };
            this.loading = true;
            try {
                await this.$exportToPDF(
                    "properties/export-pdf",
                    params,
                    "Properties"
                );
                this.$alertSuccess("PDF generated successfully");
            } catch (error) {
                this.$readStatusHttp(error);
            } finally {
                this.loading = false;
            }
        },
        async uploadFiles(pdfs) {
            if (pdfs.length === 0) {
                this.$alertWarning("No files selected for upload");
                return;
            }
            const data = {
                property_id: this.selectedProperty.id,
                pdfs: pdfs,
            };
            this.$axios
                .post("/properties/document/add", data, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                })
                .then(() => {
                    this.$alertSuccess("Files uploaded successfully");
                    this.fetchProperty();
                })
                .catch((error) => {
                    this.$readStatusHttp(error);
                });
        },
        deletePdf(pdfField) {
            this.$axios
                .post(`/properties/document/delete`, {
                    property_id: this.selectedProperty.id,
                    type: pdfField,
                })
                .then(() => {
                    this.$alertSuccess("File deleted successfully");
                    this.fetchProperty();
                })
                .catch((error) => {
                    this.$readStatusHttp(error);
                });
        },
    },
};
</script>

<style></style>
