<template>
    <Card>
        <template #title>User</template>
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
                <!-- Email Column -->
                <Column
                    field="email"
                    header="Email"
                    sortable
                    style="min-width: 200px"
                >
                    <template #body="{ data }">
                        {{ data.email }}
                    </template>
                    <template #filter="{ filterModel }">
                        <InputText
                            v-model="filterModel.value"
                            type="text"
                            class="p-column-filter"
                            placeholder="Search by email"
                        />
                    </template>
                </Column>
                <!-- Phone Column -->
                <Column
                    field="phone"
                    header="Phone"
                    sortable
                    :showClearButton="false"
                    style="min-width: 100px"
                >
                    <template #body="{ data }">
                        {{
                            "+" +
                            (data.code_number || "") +
                            " " +
                            (data.phone || "")
                        }}
                    </template>
                    <template #filter="{ filterModel }">
                        <InputText
                            v-model="filterModel.value"
                            type="text"
                            class="p-column-filter"
                            placeholder="Search by phone"
                        />
                    </template>
                </Column>
                <Column
                    field="property_name"
                    header="Property Name"
                    sortable
                    style="min-width: 150px"
                >
                    <template #body="{ data }">
                        <div class="size-tags">
                            <Tag
                                v-for="index in $parseTags(data.property_name)"
                                :key="index.id"
                                :value="`${index.tag}`"
                                class="size-tag"
                            />
                        </div>
                    </template>
                </Column>
                <Column
                    field="roles.name"
                    header="User Type"
                    style="min-width: 150px"
                >
                    <template #body="{ data }">
                        {{ data.roles[0].name }}
                    </template>
                </Column>
                <!-- Photos Column -->
                <Column header="Photo">
                    <template #body="{ data }">
                        <div style="text-align: center">
                            <Galleria
                                :key="galleryKey"
                                :value="$getImages(data)"
                                :numVisible="1"
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
    <ManagemenUserComponent
        v-if="dialogVisible"
        :dialogVisible="dialogVisible"
        :selectedUser="selectedUser"
        @hidden="hidden"
        @reload="reload"
        @reloadTable="reloadTable"
    />
</template>

<script>
// Importar Librerias o Modulos

import Card from "primevue/card";
import { FilterMatchMode, FilterOperator } from "@primevue/core/api";
import ManagemenUserComponent from "./management/ManagemenUserComponent.vue";
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
            perPage: 5,
            totalRecords: 0,
            page: 1,
            sortField: "",
            sortOrder: null,
            filters: null,
            filtroInfo: [],
            loading: true,
            //
            selectedUser: null,
            dialogVisible: false,
            galleryKey: 0,
        };
    },
    components: {
        FilterMatchMode,
        FilterOperator,
        Card,
        ManagemenUserComponent,
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
        this.fetchUser();
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
                email: {
                    clear: false,
                    constraints: [
                        { value: null, matchMode: FilterMatchMode.STARTS_WITH },
                    ],
                },
                phone: {
                    clear: false,
                    constraints: [
                        { value: null, matchMode: FilterMatchMode.STARTS_WITH },
                    ],
                },
                /* 'roles.name': {
                    clear: false,
                    constraints: [
                        { value: null, matchMode: FilterMatchMode.STARTS_WITH },
                    ],
                }, */
            };
        },
        clearFilters() {
            this.initFilters();
            this.filtroInfo = [];
            this.fetchUser();
        },
        onPage(event) {
            this.page = event.page + 1;
            this.perPage = event.rows;
            this.fetchUser();
        },
        onSort(event) {
            this.page = 1;
            this.sortField = event.sortField;
            this.sortOrder = event.sortOrder;
            this.fetchUser();
        },
        onFilters(event) {
            this.page = 1;
            this.filtroInfo = [];
            for (const [key, filter] of Object.entries(event.filters)) {
                if (filter.constraints) {
                    for (const constraint of filter.constraints) {
                        if (constraint.value) {
                            this.filtroInfo.push([
                                this.$relationTableUser(key),
                                constraint.matchMode,
                                constraint.value,
                            ]);
                        }
                    }
                }
            }
            this.fetchUser();
        },
        fetchUser() {
            this.loading = true;
            this.$axios
                .get("/users/list", {
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
            this.selectedUser = null;
            this.dialogVisible = true;
        },
        editProperty(aliado) {
            this.selectedUser = aliado;
            this.dialogVisible = true;
        },
        fetchUserProperty(id) {
            return new Promise((resolve, reject) => {
                this.$axios
                    .get("/user-properties/byProperties/" + id)
                    .then((response) => {
                        resolve(response.data);
                    })
                    .catch((error) => {
                        this.$readStatusHttp(error);
                        reject(error);
                    });
            });
        },
        async deleteProperty(userId) {
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
                    const properties = await this.fetchUserProperty(userId);
                    if (properties.data.length > 0) {
                        this.$alertWarning(
                            "Has associated properties for this user"
                        );
                    } else {
                        await axios.delete(`/users/${userId}`);
                        this.$alertSuccess("Register delete");
                        this.fetchUser();
                    }
                } catch (error) {
                    this.$readStatusHttp(error);
                }
            }
        },
        reload() {
            this.fetchUser();
            this.selectedUser = null;
            this.dialogVisible = false;
        },
        reloadTable() {
            this.fetchUser();
            this.resetGallery();
        },
        resetGallery() {
            this.galleryKey += 1;
        },
        hidden(status) {
            this.dialogVisible = status;
        },
    },
};
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

.p-datatable-filter-buttonbar > .p-datatable-filter-clear-button {
    display: none;
}

.p-fileupload-header > .p-fileupload-upload-button {
    display: none;
}

.p-datatable-filter-buttonbar > .p-datatable-filter-apply-button > span {
    color: #ffff;
}

.p-datatable-filter-rule > .p-inputtext {
    font-family: "Gill Sans", "Gill Sans MT", Calibri, "Trebuchet MS",
        sans-serif !important;
    color: #3c3c3b;
    font-weight: bold !important;
    font-size: 1.25rem;
}

.p-fileupload-header > .p-fileupload-choose-button {
    background-color: #58b78f;
}

.p-fileupload-header > .p-fileupload-choose-button > span {
    color: #ffff !important;
}

.p-fileupload-header > .p-fileupload-choose-button > svg {
    color: #ffff !important;
}

.text-center > .p-button-success {
    background-color: #58b78f;
}

.text-center > .p-button-success > span {
    color: #ffff;
}

.text-center > .p-button-danger {
    background-color: #db6464;
    border-color: #db6464;
}

.text-center > .p-button-danger > span {
    color: #ffff;
}
</style>
