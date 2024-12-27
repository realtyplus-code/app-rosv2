<template>
    <Dialog
        v-model:visible="visible"
        modal
        class="dialog-management"
        :style="{ width: '45rem' }"
        :draggable="false"
    >
        <template #header>
            <h3>Management Incident</h3>
            <ProgressSpinner
                style="width: 25px; height: 25px; margin-left: 20px"
                strokeWidth="8"
                fill="transparent"
                animationDuration=".5s"
                v-show="isLoad"
            />
        </template>
        <div class="custom-form">
            <div class="custom-form-column">
                <FloatLabel>
                    <Textarea
                        id="description"
                        class="inputtext-custom"
                        :class="{ 'p-invalid': errors.description }"
                        v-model="formIncident.description"
                        @input="clearError('description')"
                        :maxlength="1000"
                        rows="5"
                    />
                    <label for="description">Description</label>
                </FloatLabel>
                <small v-if="errors.description" class="p-error">{{
                    errors.description
                }}</small>
                <small>{{ characterCount }}/1000</small>
            </div>
        </div>
        <div class="custom-form">
            <div class="custom-form-column">
                <MultiSelect
                    :options="listProviders"
                    v-model="formIncident.providers"
                    filter
                    placeholder="Select providers"
                    :class="{ 'p-invalid': errors.providers }"
                    :maxSelectedLabels="limitProviders"
                    optionLabel="name"
                    optionValue="id"
                    style="width: 100%"
                />
                <small v-if="errors.providers" class="p-error">{{
                    errors.providers
                }}</small>
            </div>
        </div>
        <div class="custom-form">
            <div class="custom-form-column">
                <FloatLabel>
                    <InputNumber
                        id="cost"
                        class="inputtext-custom"
                        :class="{ 'p-invalid': errors.cost }"
                        v-model="formIncident.cost"
                        @input="clearError('cost')"
                        :currency="$globals.CURRENCY_TYPES.USD"
                    />
                    <label for="cost">Cost</label>
                </FloatLabel>
                <small v-if="errors.cost" class="p-error">{{
                    errors.cost
                }}</small>
            </div>
            <div class="custom-form-column">
                <FloatLabel>
                    <DatePicker
                        id="report_date"
                        class="inputtext-custom"
                        :class="{ 'p-invalid': errors.report_date }"
                        v-model="formIncident.report_date"
                        @input="clearError('report_date')"
                    />
                    <label for="report_date">Report Date</label>
                </FloatLabel>
                <small v-if="errors.report_date" class="p-error">{{
                    errors.report_date
                }}</small>
            </div>
        </div>
        <hr />
        <div class="custom-form">
            <div class="custom-form-column">
                <Select
                    filter
                    :options="listIncidentType"
                    v-model="formIncident.incident_type_id"
                    placeholder="Select incident type"
                    :class="{ 'p-invalid': errors.incident_type_id }"
                    optionLabel="name"
                    optionValue="id"
                    style="width: 100%"
                />
                <small v-if="errors.incident_type_id" class="p-error">{{
                    errors.incident_type_id
                }}</small>
            </div>
            <div class="custom-form-column">
                <Select
                    filter
                    :options="listPriority"
                    v-model="formIncident.priority_id"
                    placeholder="Select priority"
                    :class="{ 'p-invalid': errors.priority_id }"
                    optionLabel="name"
                    optionValue="id"
                    style="width: 100%"
                />
                <small v-if="errors.priority_id" class="p-error">{{
                    errors.priority_id
                }}</small>
            </div>
        </div>
        <div class="custom-form">
            <div class="custom-form-column">
                <Select
                    filter
                    :options="listPayer"
                    v-model="formIncident.payer_id"
                    placeholder="Select payer"
                    :class="{ 'p-invalid': errors.payer_id }"
                    optionLabel="name"
                    optionValue="id"
                    style="width: 100%"
                />
                <small v-if="errors.payer_id" class="p-error">{{
                    errors.payer_id
                }}</small>
            </div>
            <div class="custom-form-column">
                <Select
                    filter
                    :options="listStatus"
                    v-model="formIncident.status_id"
                    placeholder="Select status"
                    :class="{ 'p-invalid': errors.status_id }"
                    optionLabel="name"
                    optionValue="id"
                    style="width: 100%"
                />
                <small v-if="errors.status_id" class="p-error">{{
                    errors.status_id
                }}</small>
            </div>
        </div>
        <div class="custom-form"></div>
        <hr />
        <template #footer>
            <div class="text-center">
                <Button
                    v-if="selectedPropertyId"
                    label="Save"
                    severity="success"
                    style="margin-right: 10px"
                    @click="save"
                />
                <Button
                    v-else
                    label="Update"
                    severity="success"
                    style="margin-right: 10px"
                    @click="update"
                />
                <Button
                    label="Cancel"
                    severity="danger"
                    @click="handleDialogClose"
                />
            </div>
        </template>
    </Dialog>
</template>

<script>
import * as Yup from "yup";
import { hide } from "@popperjs/core";

export default {
    props: ["dialogVisible", "selectedIncident", "selectedPropertyId"],
    data() {
        return {
            visible: this.dialogVisible,
            formIncident: {
                id: null,
                property_id: null,
                description: null,
                report_date: null,
                status_id: null,
                incident_type_id: null,
                priority_id: null,
                providers: [],
                cost: null,
                payer_id: null,
            },
            errors: {},
            listIncidentType: [],
            listPriority: [],
            listStatus: [],
            listPayer: [],
            listOwners: [],
            listProviders: [],
            isLoad: false,
            limitProviders: 10,
        };
    },
    components: {},
    computed: {
        characterCount() {
            return this.formIncident.description
                ? this.formIncident.description.length
                : 0;
        },
    },
    watch: {},
    mounted() {
        this.$nextTick(() => {
            if (this.selectedIncident) {
                this.formIncident.id = this.selectedIncident.id;
                this.formIncident.description =
                    this.selectedIncident.description;
                this.formIncident.incident_type_id =
                    this.selectedIncident.incident_type_id;
                this.formIncident.priority_id =
                    this.selectedIncident.priority_id;
                this.formIncident.cost = this.selectedIncident.cost;
                this.formIncident.payer_id = this.selectedIncident.payer_id;
                this.formIncident.property_id =
                    this.selectedIncident.property_id;
                this.formIncident.report_date = new Date(
                    this.selectedIncident.report_date
                );
                this.formIncident.status_id = this.selectedIncident.status_id;
            }
        });
    },
    created() {
        this.initServices();
    },
    methods: {
        async initServices() {
            const comboNames = [
                "incident_type",
                "priority",
                "payer",
                "incident_status",
            ];
            const response = await this.$getEnumsOptions(comboNames);
            const {
                incident_type: responIncidentType,
                priority: responPriority,
                payer: responPayer,
                incident_status: responStatus,
            } = response.data;
            this.listIncidentType = responIncidentType;
            this.listPriority = responPriority;
            this.listStatus = responStatus;
            this.listPayer = responPayer;
            // obtenemos los usuarios
            const { data: provider } = await this.getProviders();
            this.listProviders = provider;
        },
        getProviders(role = null) {
            const vm = this;
            return new Promise((resolve, reject) => {
                const params = role ? { params: { role } } : {};
                this.$axios
                    .get(`/providers/listByProperty`, params)
                    .then(function (response) {
                        resolve(response.data);
                    })
                    .catch((error) => {
                        vm.$readStatusHttp(error);
                        reject(error);
                    });
            });
        },
        async validateForm() {
            let initialRules = {
                description: Yup.string().required(
                    "Incident description is required"
                ),
                incident_type_id: Yup.string().required(
                    "Incident type is required"
                ),
                priority_id: Yup.string().required("Priority is required"),
                cost: Yup.number().required("Cost is required"),
                payer_id: Yup.string().required("Payer is required"),
                report_date: Yup.string().required("Report date is required"),
                status_id: Yup.string().required("Status is required"),
            };
            const schema = Yup.object().shape({
                ...initialRules,
            });
            this.errors = {};
            try {
                await schema.validate(this.formIncident, {
                    abortEarly: false,
                });
                return true;
            } catch (err) {
                err.inner.forEach((error) => {
                    this.errors[error.path] = error.message;
                });
                return false;
            }
        },
        async save() {
            this.isLoad = true;
            const isValid = await this.validateForm();
            if (isValid) {
                this.formIncident.property_id = this.selectedPropertyId;
                this.$axios
                    .post("/incidents/store", this.formIncident, {
                        headers: {
                            "Content-Type": "multipart/form-data",
                        },
                    })
                    .then((response) => {
                        this.$alertSuccess("Record Added");
                        this.$emit("reload", true);
                    })
                    .catch((error) => {
                        this.$readStatusHttp(error);
                    })
                    .finally(() => {
                        this.isLoad = false;
                    });
            } else {
                this.isLoad = false;
            }
        },
        async update() {
            this.isLoad = true;
            const isValid = await this.validateForm();
            if (isValid) {
                this.$axios
                    .post(
                        `/incidents/update/${this.selectedIncident.id}`,
                        this.formIncident,
                        {
                            headers: {
                                "Content-Type": "multipart/form-data",
                            },
                        }
                    )
                    .then((response) => {
                        this.$alertSuccess("Register Updated");
                        this.$emit("reload", true);
                    })
                    .catch((error) => {
                        this.$readStatusHttp(error);
                    })
                    .finally(() => {
                        this.isLoad = false;
                    });
            } else {
                this.isLoad = false;
            }
        },
        handleDialogClose() {
            this.visible = false;
            this.$emit("hidden", this.visible);
        },
        clearError(field) {
            this.errors[field] = "";
        },
    },
};
</script>

<style scoped>
#attachPhoto [data-pc-name="pcuploadbutton"],
#attachPhoto [data-pc-name="pccancelbutton"] {
    display: none;
}
.gallery {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 10px;
}

.gallery-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    grid-template-rows: repeat(2, 1fr);
    gap: 10px;
    width: 640px;
    height: 640px;
}

.gallery-item {
    display: flex;
    justify-content: center;
    align-items: center;
}

.gallery-card {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    width: 100%;
    height: 100%;
    position: relative;
}

.gallery-image {
    width: 100%;
    height: 100%;
    padding: 20px;
    object-fit: cover;
}

.remove-button {
    position: absolute;
    bottom: 10px;
    right: 10px;
    background-color: #d9534f;
    color: white;
    border-radius: 10px !important;
    padding: 5px 10px;
    cursor: pointer;
    font-size: 14px;
}

.remove-button:hover {
    background-color: #c9302c;
}

.file-input {
    width: 100%;
    height: 100%;
}
</style>
