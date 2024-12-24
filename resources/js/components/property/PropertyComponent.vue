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
                    style="min-width: 180px"
                >
                    <template #body="{ data }">
                        <div
                            class="size-insurances"
                            style="justify-content: center"
                        >
                            <i
                                class="size-insurance"
                                :class="$parseInsurance(data.insurances)"
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
                            <Button
                                icon="pi pi-shield"
                                class="p-button-rounded p-button-warn"
                                style="margin: 5px"
                                @click="viewInsurance(slotProps.data.id)"
                                title="Insurances"
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
    />
</template>

<script>
// Importar Librerias o Modulos

import Card from "primevue/card";
import { FilterMatchMode, FilterOperator } from "@primevue/core/api";
import ManagemenPropertyComponent from "./management/ManagemenPropertyComponent.vue";
import ManagemenInsuranceComponent from "../insurance/management/ManagemenInsuranceComponent.vue";
import InputText from "primevue/inputtext";
import DataTable from "primevue/datatable";
import Galleria from "primevue/galleria";
import Select from "primevue/select";
import Column from "primevue/column";
import Button from "primevue/button";
import Image from "primevue/image";
import Tag from "primevue/tag";

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
        Card,
        ManagemenPropertyComponent,
        ManagemenInsuranceComponent,
        DataTable,
        Column,
        Button,
        InputText,
        Tag,
        Select,
        Galleria,
        Image,
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
                    console.log(filter.constraints);
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
        reloadTable() {
            this.fetchProperty();
            this.resetGallery();
        },
        resetGallery() {
            this.galleryKey += 1;
        },
        hidden(status) {
            this.dialogVisible = status;
        },
        hiddenInsurance(status) {
            this.dialogVisibleInsurance = status;
        },
        viewInsurance(id) {
            this.selectedPropertyId = id;
            this.dialogVisibleInsurance = true;
        },
        viewPanelInsurance(insurances, id) {
            if (insurances == 0) return;
            alert(id);
        },
    },
};
</script>

<style>

</style>
