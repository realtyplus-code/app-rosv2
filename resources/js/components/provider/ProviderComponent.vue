<template>
    <Card>
        <template #title>Provider</template>
        <template #content>
            <div class="p-d-flex p-jc-end p-mb-3">
                <Button
                    icon="pi pi-plus"
                    rounded
                    raised
                    @click="addProvider"
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
                :value="providers"
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
                    style="min-width: 150px"
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
                <!-- Coverage Area Column -->
                <Column
                    field="coverage_area"
                    header="Coverage Area"
                    sortable
                    style="min-width: 150px"
                >
                    <template #body="{ data }">
                        {{ data.coverage_area }}
                    </template>
                    <template #filter="{ filterModel }">
                        <InputText
                            v-model="filterModel.value"
                            type="text"
                            class="p-column-filter"
                            placeholder="Search by coverage area"
                        />
                    </template>
                </Column>
                <!-- contact phone Column -->
                <Column
                    field="contact_phone"
                    header="Contact phone"
                    sortable
                    style="min-width: 150px"
                >
                    <template #body="{ data }">
                        {{
                            "+" +
                            (data.code_number || "") +
                            " " +
                            (data.contact_phone || "")
                        }}
                    </template>
                    <template #filter="{ filterModel }">
                        <InputText
                            v-model="filterModel.value"
                            type="text"
                            class="p-column-filter"
                            placeholder="Search by contact phone"
                        />
                    </template>
                </Column>
                <!-- Contact Email Column -->
                <Column
                    field="contact_email"
                    header="Contact Email"
                    sortable
                    style="min-width: 150px"
                >
                    <template #body="{ data }">
                        {{ data.contact_email }}
                    </template>
                    <template #filter="{ filterModel }">
                        <InputText
                            v-model="filterModel.value"
                            type="text"
                            class="p-column-filter"
                            placeholder="Search by contact email"
                        />
                    </template>
                </Column>
                <!-- Service Cost Column -->
                <Column
                    field="service_cost"
                    header="Service Cost"
                    sortable
                    style="min-width: 150px"
                >
                    <template #body="{ data }">
                        {{ data.service_cost }}
                    </template>
                    <template #filter="{ filterModel }">
                        <InputText
                            v-model="filterModel.value"
                            type="text"
                            class="p-column-filter"
                            placeholder="Search by service cost"
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
                 <!-- Provider Name Column -->
                 <Column
                    field="providers_name"
                    header="Services offered"
                    sortable
                    style="min-width: 150px"
                >
                    <template #body="{ data }">
                        <div class="size-tags">
                            <Tag
                                v-for="index in $parseTags(data.providers_name)"
                                :key="index.id"
                                :value="`${index.tag}`"
                                class="size-tag"
                            />
                        </div>
                    </template>
                </Column>
                <!-- Actions Column -->
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
                                @click="editProvider(slotProps.data)"
                            />
                            <Button
                                icon="pi pi-trash"
                                class="p-button-rounded p-button-danger"
                                style="
                                    margin: 5px;
                                    background-color: #db6464;
                                    border-color: #db6464;
                                "
                                @click="deleteProvider(slotProps.data.id)"
                            />
                        </div>
                    </template>
                </Column>
            </DataTable>
        </template>
    </Card>
    <!-- gestion de proveedores -->
    <ManagementProviderComponent
        v-if="dialogVisible"
        :dialogVisible="dialogVisible"
        :selectedProvider="selectedProvider"
        @hidden="hidden"
        @reload="reload"
        @reloadTable="reloadTable"
    />
</template>

<script>
// Importar Librerias o Modulos

import { FilterMatchMode, FilterOperator } from "@primevue/core/api";
import ManagementProviderComponent from "./management/ManagementProviderComponent.vue";

export default {
    props: [],
    data() {
        return {
            providers: [],
            perPage: 10,
            totalRecords: 0,
            page: 1,
            sortField: "",
            sortOrder: null,
            filters: null,
            filtroInfo: [],
            loading: true,
            //
            selectedProvider: null,
            dialogVisible: false,
            statuses: [
                { value: "Active", id: 1 },
                { value: "Inactive", id: 2 },
            ],
        };
    },
    components: {
        FilterMatchMode,
        FilterOperator,
        ManagementProviderComponent,
    },
    created() {
        this.initFilters();
    },
    mounted() {
        this.fetchProvider();
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
                coverage_area: {
                    clear: false,
                    constraints: [
                        { value: null, matchMode: FilterMatchMode.STARTS_WITH },
                    ],
                },
                contact_email: {
                    clear: false,
                    constraints: [
                        { value: null, matchMode: FilterMatchMode.STARTS_WITH },
                    ],
                },
                contact_phone: {
                    clear: false,
                    constraints: [
                        { value: null, matchMode: FilterMatchMode.STARTS_WITH },
                    ],
                },
                service_cost: {
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
            };
        },
        clearFilters() {
            this.initFilters();
            this.filtroInfo = [];
            this.fetchProvider();
        },
        onPage(event) {
            this.page = event.page + 1;
            this.perPage = event.rows;
            this.fetchProvider();
        },
        onSort(event) {
            this.page = 1;
            this.sortField = event.sortField;
            this.sortOrder = event.sortOrder;
            this.fetchProvider();
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
                                this.$relationTableProvider(key),
                                constraint.matchMode,
                                constraint.value,
                            ]);
                        }
                    }
                }
            }
            this.fetchProvider();
        },
        fetchProvider() {
            this.loading = true;
            this.$axios
                .get("/providers/list", {
                    params: {
                        page: this.page,
                        perPage: this.perPage,
                        sort: [this.sortField, this.sortOrder],
                        filters: this.filtroInfo,
                        select: this.filterSelect ?? null,
                    },
                })
                .then((response) => {
                    this.providers = response.data.data;
                    this.totalRecords = response.data.total;
                    this.loading = false;
                })
                .catch((error) => {
                    this.$readStatusHttp(error);
                    this.loading = false;
                });
        },
        addProvider() {
            this.selectedProvider = null;
            this.dialogVisible = true;
        },
        editProvider(provider) {
            this.selectedProvider = provider;
            this.dialogVisible = true;
        },
        async deleteProvider(providerId) {
            const result = await this.$swal.fire({
                title: "You're sure?",
                text: "You are about to delete this provider. Are you sure you want to continue?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete",
                cancelButtonText: "Cancelar",
            });

            if (result.isConfirmed) {
                try {
                    await axios.delete(`/providers/${providerId}`);
                    this.$alertSuccess("Register delete");
                    this.fetchProvider();
                } catch (error) {
                    this.$readStatusHttp(error);
                }
            }
        },
        reload() {
            this.fetchProvider();
            this.selectedProvider = null;
            this.dialogVisible = false;
        },
        reloadTable() {
            this.fetchProvider();
        },
        hidden(status) {
            this.dialogVisible = status;
        },
    },
};
</script>

<style></style>
