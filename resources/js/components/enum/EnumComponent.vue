<template>
    <Card class="container">
        <template #title
            >Options
            <i class="pi pi-info info" @click="infoVisible = true"></i>
        </template>
        <template #content>
            <DataTable
                v-model:filters="filters"
                :loading="loading"
                :value="enums"
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
                showGridlines
                editMode="row"
            >
                <template #header>
                    <div class="header-container mb-2">
                        <div class="header-start">
                            <Button
                                v-if="selectedOption"
                                label="Add"
                                icon="pi pi-plus"
                                rounded
                                raised
                                @click="addEnum"
                                style="margin-right: 10px"
                            />
                            <Button
                                icon="pi pi-filter-slash"
                                class="p-button-danger"
                                rounded
                                raised
                                @click="clearFilters"
                                style="
                                    background-color: #f76f31 !important;
                                    border-color: #f76f31 !important;
                                "
                            />
                        </div>
                        <div class="header-end" style="margin-top: 20px">
                            <div class="row">
                                <div class="col">
                                    <Select
                                        filter
                                        v-model="selectedOption"
                                        :options="listOptions"
                                        placeholder="Select option"
                                        optionLabel="name"
                                        optionValue="id"
                                        class="w-full md:w-56"
                                        style="width: 100%"
                                        @change="changeSelectOption"
                                        showClear
                                    />
                                </div>
                                <div class="col">
                                    <Select
                                        filter
                                        v-if="isRelation == 'city'"
                                        v-model="selectedOptionCity"
                                        :options="listState"
                                        placeholder="Select state"
                                        optionLabel="name"
                                        optionValue="id"
                                        class="w-full md:w-56"
                                        style="width: 100%"
                                        @change="changeSelectOptionCity"
                                        showClear
                                    />
                                    <Select
                                        filter
                                        v-if="isRelation == 'state'"
                                        v-model="selectedOptionState"
                                        :options="listCountry"
                                        placeholder="Select country"
                                        optionLabel="name"
                                        optionValue="id"
                                        class="w-full md:w-56"
                                        style="width: 100%"
                                        @change="changeSelectOptionState"
                                        showClear
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
                <Column
                    field="parent.name"
                    header="List"
                    :showClearButton="false"
                    style="min-width: 200px"
                >
                    <template #body="{ data }">
                        {{ data.parent.name }}
                    </template>
                </Column>
                <Column
                    field="name"
                    header="Name system"
                    :showClearButton="false"
                    style="min-width: 200px"
                    sortable
                >
                    <template #body="{ data }">
                        {{ data.name }}
                    </template>
                    <template #filter="{ filterModel }">
                        <InputText
                            v-model="filterModel.value"
                            type="text"
                            class="p-column-filter"
                            placeholder="Buscar por nombre"
                        />
                    </template>
                </Column>
                <Column
                    field="valor1"
                    header="Value to display"
                    :showClearButton="false"
                    style="min-width: 200px"
                    sortable
                >
                    <template #body="{ data }">
                        {{ data.valor1 }}
                    </template>
                    <template #filter="{ filterModel }">
                        <InputText
                            v-model="filterModel.value"
                            type="text"
                            class="p-column-filter"
                            placeholder="Buscar por nombre"
                        />
                    </template>
                </Column>
                <Column
                    v-if="isRelation == 'city'"
                    field="data.brother_relation.name"
                    header="State"
                    :showClearButton="false"
                    style="min-width: 200px"
                >
                    <template #body="{ data }">
                        {{ data.brother_relation?.name }}
                    </template>
                </Column>
                <Column
                    v-if="isRelation == 'state'"
                    field="data.brother_relation.name"
                    header="Country"
                    :showClearButton="false"
                    style="min-width: 200px"
                >
                    <template #body="{ data }">
                        {{ data.brother_relation?.name }}
                    </template>
                </Column>
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
                            filter
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
                    header="Actions"
                    style="min-width: 120px; text-align: center"
                >
                    <template #body="slotProps">
                        <Button
                            v-if="selectedOption"
                            icon="pi pi-pencil"
                            class="p-button-rounded p-button-success"
                            style="
                                margin: 5px;
                                background-color: #f76f31;
                                border-color: #f76f31;
                            "
                            @click="editEnum(slotProps.data)"
                        />
                        <Button
                            icon="pi pi-trash"
                            class="p-button-rounded p-button-danger"
                            style="
                                margin: 5px;
                                background-color: #db6464;
                                border-color: #db6464;
                            "
                            @click="deleteEnum(slotProps.data.id)"
                        />
                    </template>
                </Column>
            </DataTable>
        </template>
    </Card>

    <Dialog
        v-model:visible="infoVisible"
        maximizable
        modal
        header="Información"
        :style="{ width: '50rem' }"
        :breakpoints="{ '1199px': '75vw', '575px': '90vw' }"
        style="text-align: justify"
    >
        <p class="m-0">
            Este módulo permite crear opciones para los diferentes listados
            dentro del aplicativo. Para gestionar (crear o editar) una opción,
            primero debemos ubicarnos en el listado correspondiente,
            identificado como "Select Option", y seleccionar la opción deseada.
            Una vez seleccionada, se mostrarán las opciones de creación, edición
            y eliminación.
        </p>
        <br />
        <p class="m-0">
            Para las opciones de Country, State y City, al realizar una
            selección, se desplegará un campo adicional que representa la
            relación interna de dependencia. Por lo tanto, es necesario elegir
            la opción adecuada para establecer la relación correctamente.
        </p>
    </Dialog>

    <!-- gestion de opcion -->
    <ManagemenEnumComponent
        v-if="dialogVisible"
        :dialogVisible="dialogVisible"
        :selectedEnum="selectedEnum"
        :listOptions="listOptions"
        :selectedOption="selectedOption"
        :isOption="isOption"
        @hidden="hiddenManagementEnum"
        @reload="reloadEnums"
    />
</template>

<script>
import { FilterMatchMode, FilterOperator } from "@primevue/core/api";
import ManagemenEnumComponent from "./management/ManagemenEnumComponent.vue";

export default {
    data() {
        return {
            enums: [],
            perPage: 20,
            totalRecords: 0,
            page: 1,
            sortField: "",
            sortOrder: null,
            filters: null,
            filtroInfo: [],
            loading: true,
            filterSelect: {
                parent_id: null,
            },
            selectedOption: null,
            listOptions: [],
            selectedEnum: null,
            dialogVisible: false,
            isOption: null,
            statuses: [
                { value: "Active", id: 1 },
                { value: "Inactive", id: 2 },
            ],
            infoVisible: false,
            isRelation: null,
            selectedOptionCity: null,
            selectedOptionState: null,
            listState: [],
            listCountry: [],
        };
    },
    components: {
        FilterMatchMode,
        FilterOperator,
        ManagemenEnumComponent,
    },
    created() {
        this.initFilters();
    },
    watch: {
        async selectedOption(value) {
            if (value) {
                this.isRelation = null;
                this.initEnumBrother(value);
            }
        },
        isRelation(value) {
            if (value) {
                if (this.isRelation == "city") {
                    this.getCities();
                } else if (this.isRelation == "state") {
                    this.getState();
                }
            }
        },
    },
    mounted() {
        this.fetchEnumOptios();
        this.initServices();
    },
    methods: {
        async getCities() {
            const comboNames = ["state"];
            const response = await this.$getEnumsOptions(comboNames);
            const { state: rpState } = response.data;
            this.listState = rpState;
        },
        async getState() {
            const comboNames = ["country"];
            const response = await this.$getEnumsOptions(comboNames);
            const { country: rpCountry } = response.data;
            this.listCountry = rpCountry;
        },
        initEnumBrother(id) {
            this.$axios
                .get("/enums/get-id/" + id)
                .then((response) => {
                    const { data } = response.data;
                    this.isRelation = data.name;
                })
                .catch((error) => {
                    this.$readStatusHttp(error);
                });
        },
        initServices() {
            this.$axios
                .get("/enums/fathers")
                .then((response) => {
                    const { data } = response.data;
                    this.listOptions = data;
                })
                .catch((error) => {
                    this.$readStatusHttp(error);
                });
        },
        initFilters() {
            this.filters = {
                name: {
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
                value1: {
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
            this.fetchEnumOptios();
        },
        onPage(event) {
            this.page = event.page + 1;
            this.perPage = event.rows;
            this.fetchEnumOptios();
        },
        onSort(event) {
            this.page = 1;
            this.sortField = event.sortField;
            this.sortOrder = event.sortOrder;
            this.fetchEnumOptios();
        },
        onFilters(event) {
            this.page = 1;
            this.filtroInfo = [];
            for (const [key, filter] of Object.entries(event.filters)) {
                if (filter.constraints) {
                    for (const constraint of filter.constraints) {
                        if (constraint.value) {
                            let filterValue = constraint.value;
                            this.filtroInfo.push([
                                this.$relationTableEnumOption(key),
                                constraint.matchMode,
                                constraint.value,
                            ]);
                        }
                    }
                }
            }
            this.fetchEnumOptios();
        },
        fetchEnumOptios() {
            this.loading = true;
            this.$axios
                .get("/enums/list", {
                    params: {
                        page: this.page,
                        perPage: this.perPage,
                        sort: [this.sortField, this.sortOrder],
                        filters: this.filtroInfo,
                        select: this.filterSelect ?? null,
                    },
                })
                .then((response) => {
                    this.enums = response.data.data;
                    this.totalRecords = response.data.total;
                    this.loading = false;
                })
                .catch((error) => {
                    this.$readStatusHttp(error);
                    this.loading = false;
                });
        },
        addEnum() {
            this.selectedEnum = null;
            this.dialogVisible = true;
        },
        editEnum(register) {
            this.selectedEnum = register;
            this.dialogVisible = true;
        },
        async deleteEnum(enumId) {
            const result = await this.$swal.fire({
                title: "Are you sure?",
                text: "You are about to delete this option. Are you sure you want to proceed?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it",
                cancelButtonText: "Cancel",
            });

            if (result.isConfirmed) {
                try {
                    await axios.delete(`/enums/${enumId}`);
                    this.$alertSuccess("Register delete!");
                    this.fetchEnumOptios();
                } catch (error) {
                    this.$readStatusHttp(error);
                }
            }
        },
        reloadEnums() {
            this.fetchEnumOptios();
            this.selectedEnum = null;
            this.dialogVisible = false;
        },
        hiddenManagementEnum(status) {
            this.dialogVisible = status;
        },
        changeSelectOption(event) {
            this.isRelation = null;
            this.isOption = null;
            this.filterSelect.parent_id = event.value;
            if (event.value) {
                const option = this.listOptions.find(
                    (value) => value.id == event.value
                ).code;
                this.isOption = option;
            }
            this.fetchEnumOptios();
        },
        changeSelectOptionCity(event) {
            this.filterSelect.brother_relation_id = event.value;
            this.fetchEnumOptios();
        },
        changeSelectOptionState(event) {
            this.filterSelect.brother_relation_id = event.value;
            this.fetchEnumOptios();
        },
    },
};
</script>

<style scoped>
.info {
    font-size: 1rem;
    color: rgb(255, 255, 255);
    cursor: pointer;
    background-color: #010101;
    border-radius: 50%;
    padding: 2px;
}
</style>
