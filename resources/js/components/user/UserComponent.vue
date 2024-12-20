<template>
    <Card>
        <template #title>User Management</template>
        <template #content>
            <div class="p-d-flex p-jc-end p-mb-3">
                <Button
                    icon="pi pi-plus"
                    rounded
                    raised
                    @click="addProperty"
                    style="
                        margin-right: 10px;
                        background-color: #58b78f !important;
                    "
                />
                <Button
                    icon="pi pi-filter-slash"
                    class="p-button-sm"
                    rounded
                    raised
                    @click="clearFilters"
                    style="background-color: #58b78f !important"
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
                    style="min-width: 120px; text-align: center"
                >
                    <template #body="slotProps">
                        <div class="row">
                            <Button
                                icon="pi pi-pencil"
                                class="p-button-rounded p-button-primary"
                                style="margin: 5px"
                                @click="editProperty(slotProps.data)"
                            />
                            <Button
                                icon="pi pi-trash"
                                class="p-button-rounded p-button-danger"
                                style="margin: 5px"
                                @click="deleteProperty(slotProps.data.id)"
                            />
                        </div>
                    </template>
                </Column>
            </DataTable>
        </template>
    </Card>
    <!-- gestion de aliados -->
    <ManagemenPropertyComponent
        v-if="dialogVisible"
        :dialogVisible="dialogVisible"
        :selectedProperty="selectedProperty"
        @hidden="hidden"
        @reload="reload"
        @reloadTable="reloadTable"
    />
</template>

<script>
import Card from "primevue/card";
import { FilterMatchMode, FilterOperator } from "@primevue/core/api";
import ManagemenPropertyComponent from "./management/ManagementUserComponent.vue";
import InputText from "primevue/inputtext";
import DataTable from "primevue/datatable";
import Galleria from "primevue/galleria";
import Select from "primevue/select";
import Column from "primevue/column";
import Button from "primevue/button";
import Image from "primevue/image";
import Tag from "primevue/tag";


</script>

<style>
.p-d-flex {
    display: flex;
}
.p-jc-end {
    justify-content: flex-end;
}
.p-mb-3 {
    margin-bottom: 1rem;
}
.p-button-sm {
    font-size: 0.75rem;
    padding: 0.5rem 1rem;
}

span {
    color: #3c3c3b;
    font-weight: bold !important;
    font-size: 1.25rem;
    font-family: "Gill Sans", "Gill Sans MT", Calibri, "Trebuchet MS",
        sans-serif;
}

.p-card-title {
    font-weight: bold !important;
    font-size: 1.75rem !important;
    font-family: "Gill Sans", "Gill Sans MT", Calibri, "Trebuchet MS",
        sans-serif;
}

.p-button-icon {
    color: #ffff;
}

svg {
    color: #58b78f !important;
}

.p-menubar-item-icon {
    color: #58b78f !important;
}

.p-button-rounded {
    background-color: #58b78f;
}

.p-datatable-filter-apply-button {
    color: #ffff !important;
}

#name,
#address {
    color: #3c3c3b;
    font-weight: bold !important;
    font-size: 1.25rem;
    font-family: "Gill Sans", "Gill Sans MT", Calibri, "Trebuchet MS",
        sans-serif !important;
}

h3 {
    font-weight: bold !important;
    font-size: 1.75rem !important;
    font-family: "Gill Sans", "Gill Sans MT", Calibri, "Trebuchet MS",
        sans-serif;
}

.p-datatable-filter-buttonbar>.p-datatable-filter-clear-button {
    display: none;
}

.p-fileupload-header>.p-fileupload-upload-button {
    display: none;
}

.p-datatable-filter-buttonbar>.p-datatable-filter-apply-button>span {
    color: #ffff;
}

.p-datatable-filter-rule>.p-inputtext {
    font-family: "Gill Sans", "Gill Sans MT", Calibri, "Trebuchet MS",
    sans-serif !important;
    color: #3c3c3b;
    font-weight: bold !important;
    font-size: 1.25rem;
}

.p-fileupload-header>.p-fileupload-choose-button {
    background-color: #58b78f;

}

.p-fileupload-header>.p-fileupload-choose-button>span {
    color: #ffff !important;

}

.p-fileupload-header>.p-fileupload-choose-button>svg {
    color: #ffff !important;
}

.text-center>.p-button-success {
    background-color: #58b78f;
}

.text-center>.p-button-success>span {
    color: #ffff;
}

.text-center>.p-button-danger {
    background-color: #db6464;
    border-color: #db6464;
}

.text-center>.p-button-danger>span {
    color: #ffff;
}
</style>