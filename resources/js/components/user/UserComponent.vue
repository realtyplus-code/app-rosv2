<template>
    <Card>
        <template #title>User</template>
        <template #content>
            <div class="custom-form-column col-md-3">
                <Select
                    filter
                    :options="
                        listRoles.map((role) => ({
                            ...role,
                            name: roleAlias(role.name),
                        }))
                    "
                    v-model="selectedRole"
                    placeholder="Filter for rol"
                    optionLabel="name"
                    optionValue="id"
                    style="width: 100%"
                    @change="onChangeSelect"
                    showClear
                />
            </div>
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
                            placeholder="Search by phone without code"
                        />
                    </template>
                </Column>
                <Column
                    field="roles.name"
                    header="User Type"
                    style="min-width: 150px"
                >
                    <template #body="{ data }">
                        {{ roleAlias(data.roles[0].name) }}
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
                <Column
                    field="address"
                    header="Address"
                    sortable
                    :showClearButton="false"
                    style="min-width: 100px"
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

import { FilterMatchMode, FilterOperator } from "@primevue/core/api";
import ManagemenUserComponent from "./management/ManagemenUserComponent.vue";

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
            filterSelect: {},
            //
            selectedUser: null,
            selectedRole: null,
            dialogVisible: false,
            galleryKey: 0,
            listRoles: [],
        };
    },
    components: {
        FilterMatchMode,
        FilterOperator,
        ManagemenUserComponent,
    },
    created() {
        this.initFilters();
    },
    mounted() {
        this.fetchUser();
        this.initServices();
    },
    methods: {
        async initServices() {
            const rols = await this.getRoles();
            if (rols) {
                this.listRoles = rols.data;
            }
        },
        async getRoles() {
            try {
                const response = await this.$axios.get(`/rols/list`);
                return response.data;
            } catch (error) {
                this.$readStatusHttp(error);
                throw error;
            }
        },
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
                address: {
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
            this.selectedRole = null;
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
        fetchUser(role = null) {
            this.loading = true;
            this.$axios
                .get("/users/list", {
                    params: {
                        page: this.page,
                        perPage: this.perPage,
                        sort: [this.sortField, this.sortOrder],
                        filters: this.filtroInfo,
                        select: this.filterSelect ?? null,
                        role : role ?? null
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
        onChangeSelect(item) {
            if(item.value === null) {
                this.selectedRole = null;
                this.fetchUser();
                return;
            }
            const role = this.listRoles.find((role) => role.id === item.value);
            this.fetchUser(role.name);
            this.resetGallery();
        },
        roleAlias(roleName) {
            const aliases = {
                owner: "Owner",
                tenant: "Tenant",
                provider: "Provider",
                ros_client: "Ros Client",
                ros_client_manager: "Ros Client Manager",
                global_manager: "Global Manager",
            };
            return aliases[roleName] || roleName;
        },
    },
};
</script>

<style></style>
