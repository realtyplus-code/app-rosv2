<template>
    <Card>
        <template #title>Insurance</template>
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
                <Column
                    field="insurance_company"
                    header="Company"
                    sortable
                    style="min-width: 150px"
                >
                    <template #body="{ data }">
                        {{ data.insurance_company }}
                    </template>
                    <template #filter="{ filterModel }">
                        <InputText
                            v-model="filterModel.value"
                            type="text"
                            class="p-column-filter"
                            placeholder="Search by insurance_company"
                        />
                    </template>
                </Column>
                <Column
                    field="start_date"
                    header="Start Date"
                    sortable
                    style="min-width: 150px"
                >
                    <template #body="{ data }">
                        {{ data.start_date }}
                    </template>
                    <template #filter="{ filterModel }">
                        <InputText
                            v-model="filterModel.value"
                            type="text"
                            class="p-column-filter"
                            placeholder="Search by start_date"
                        />
                    </template>
                </Column>
                <Column
                    field="end_date"
                    header="End Date"
                    sortable
                    style="min-width: 150px"
                >
                    <template #body="{ data }">
                        {{ data.end_date }}
                    </template>
                    <template #filter="{ filterModel }">
                        <InputText
                            v-model="filterModel.value"
                            type="text"
                            class="p-column-filter"
                            placeholder="Search by end_date"
                        />
                    </template>
                </Column>
                <Column
                    field="contact_person"
                    header="Person"
                    sortable
                    style="min-width: 150px"
                >
                    <template #body="{ data }">
                        {{ data.contact_person }}
                    </template>
                    <template #filter="{ filterModel }">
                        <InputText
                            v-model="filterModel.value"
                            type="text"
                            class="p-column-filter"
                            placeholder="Search by contact_person"
                        />
                    </template>
                </Column>
                <Column
                    field="property_name"
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
                            placeholder="Search by property_name"
                        />
                    </template>
                </Column>
                <Column
                    field="coverage_name"
                    header="Coverage"
                    sortable
                    style="min-width: 150px"
                >
                    <template #body="{ data }">
                        {{ data.coverage_name }}
                    </template>
                    <template #filter="{ filterModel }">
                        <InputText
                            v-model="filterModel.value"
                            type="text"
                            class="p-column-filter"
                            placeholder="Search by coverage_name"
                        />
                    </template>
                </Column>
                <Column
                    field="created_at"
                    header="Created At"
                    sortable
                    style="min-width: 150px"
                >
                    <template #body="{ data }">
                        {{ data.created_at }}
                    </template>
                    <template #filter="{ filterModel }">
                        <InputText
                            v-model="filterModel.value"
                            type="text"
                            class="p-column-filter"
                            placeholder="Search by created_at"
                        />
                    </template>
                </Column>
                <Column
                    field="updated_at"
                    header="Updated At"
                    sortable
                    style="min-width: 150px"
                >
                    <template #body="{ data }">
                        {{ data.updated_at }}
                    </template>
                    <template #filter="{ filterModel }">
                        <InputText
                            v-model="filterModel.value"
                            type="text"
                            class="p-column-filter"
                            placeholder="Search by updated_at"
                        />
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
                                @click="editInsurance(slotProps.data)"
                            />
                        </div>
                    </template>
                </Column>
            </DataTable>
        </template>
    </Card>
    <!-- gestion de seguros -->
    <ManagemenInsuranceComponent
        v-if="dialogVisibleInsurance"
        :dialogVisible="dialogVisibleInsurance"
        :selectedInsurance="selectedInsurance"
        :selectedInsuranceId="selectedInsuranceId"
        @hidden="hiddenInsurance"
        @reload="reload"
    />
</template>

<script>
// Importar Librerias o Modulos

import { FilterMatchMode, FilterOperator } from "@primevue/core/api";
import ManagemenInsuranceComponent from "./management/ManagemenInsuranceComponent.vue";

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
            selectedInsurance: null,
            selectedInsuranceId: true,
            dialogVisibleInsurance: false,
            statuses: [
                { value: "Active", id: 1 },
                { value: "Inactive", id: 2 },
            ],
            galleryKey: 0,
        };
    },
    components: {
        FilterMatchMode,
        FilterOperator,
        ManagemenInsuranceComponent,
    },
    created() {
        this.initFilters();
    },
    mounted() {
        this.fetchInsurance();
    },
    methods: {
        initFilters() {
            this.filters = {
                insurance_company: {
                    clear: false,
                    constraints: [
                        { value: null, matchMode: FilterMatchMode.STARTS_WITH },
                    ],
                },
                start_date: {
                    clear: false,
                    constraints: [
                        { value: null, matchMode: FilterMatchMode.STARTS_WITH },
                    ],
                },
                end_date: {
                    clear: false,
                    constraints: [
                        { value: null, matchMode: FilterMatchMode.STARTS_WITH },
                    ],
                },
                contact_person: {
                    clear: false,
                    constraints: [
                        { value: null, matchMode: FilterMatchMode.STARTS_WITH },
                    ],
                },
                contact_person: {
                    clear: false,
                    constraints: [
                        { value: null, matchMode: FilterMatchMode.STARTS_WITH },
                    ],
                },
                property_name: {
                    clear: false,
                    constraints: [
                        { value: null, matchMode: FilterMatchMode.STARTS_WITH },
                    ],
                },
                coverage_name: {
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
                updated_at: {
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
            this.fetchInsurance();
        },
        onPage(event) {
            this.page = event.page + 1;
            this.perPage = event.rows;
            this.fetchInsurance();
        },
        onSort(event) {
            this.page = 1;
            this.sortField = event.sortField;
            this.sortOrder = event.sortOrder;
            this.fetchInsurance();
        },
        onFilters(event) {
            this.page = 1;
            this.filtroInfo = [];
            for (const [key, filter] of Object.entries(event.filters)) {
                if (filter.constraints) {
                    for (const constraint of filter.constraints) {
                        if (constraint.value) {
                            this.filtroInfo.push([
                                this.$relationTableInsurance(key),
                                constraint.matchMode,
                                constraint.value,
                            ]);
                        }
                    }
                }
            }
            this.fetchInsurance();
        },
        fetchInsurance() {
            this.loading = true;
            const property_id = $.urlParam("property_id");
            this.$axios
                .get("/insurances/list", {
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
                    this.users = response.data.data;
                    this.totalRecords = response.data.total;
                    this.loading = false;
                })
                .catch((error) => {
                    this.$readStatusHttp(error);
                    this.loading = false;
                });
        },
        editInsurance(aliado) {
            this.selectedInsurance = aliado;
            this.dialogVisibleInsurance = true;
        },
        async deleteInsurance(aliadoId) {
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
                    await axios.delete(`/insurances/${aliadoId}`);
                    this.$alertSuccess("Register delete");
                    this.fetchInsurance();
                } catch (error) {
                    this.$readStatusHttp(error);
                }
            }
        },
        reload() {
            this.fetchInsurance();
            this.selectedInsurance = null;
            this.dialogVisibleInsurance = false;
        },
        reloadTable() {
            this.fetchInsurance();
            this.resetGallery();
        },
        resetGallery() {
            this.galleryKey += 1;
        },
        hidden(status) {
            this.dialogVisibleInsurance = status;
        },
        hiddenInsurance(status) {
            this.dialogVisibleInsurance = status;
        },
        viewInsurance(id) {
            this.selectedInsuranceId = id;
            this.dialogVisibleInsurance = true;
        },
    },
};
</script>

<style></style>
