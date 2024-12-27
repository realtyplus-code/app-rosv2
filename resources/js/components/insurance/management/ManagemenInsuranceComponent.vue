<template>
    <Dialog
        v-model:visible="visible"
        modal
        class="dialog-management"
        :style="{ width: '45rem' }"
        :draggable="false"
    >
        <template #header>
            <h3>Management Insurance</h3>
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
                    <InputText
                        id="insurance_company"
                        class="inputtext-custom"
                        :class="{ 'p-invalid': errors.insurance_company }"
                        v-model="formInsurance.insurance_company"
                        @input="clearError('insurance_company')"
                    />
                    <label for="insurance_company">Company</label>
                </FloatLabel>
                <small v-if="errors.insurance_company" class="p-error">{{
                    errors.insurance_company
                }}</small>
            </div>
        </div>
        <div class="custom-form">
            <div class="custom-form-column">
                <FloatLabel>
                    <DatePicker
                        id="start_date"
                        class="inputtext-custom"
                        :class="{ 'p-invalid': errors.start_date }"
                        v-model="formInsurance.start_date"
                        :maxDate="formInsurance.end_date"
                        @input="clearError('start_date')"
                    />
                    <label for="start_date">Start Date</label>
                </FloatLabel>
                <small v-if="errors.start_date" class="p-error">{{
                    errors.start_date
                }}</small>
            </div>
            <div class="custom-form-column">
                <FloatLabel>
                    <DatePicker
                        id="end_date"
                        class="inputtext-custom"
                        :class="{ 'p-invalid': errors.end_date }"
                        v-model="formInsurance.end_date"
                        :minDate="formInsurance.start_date"
                        @input="clearError('end_date')"
                    />
                    <label for="end_date">End Date</label>
                </FloatLabel>
                <small v-if="errors.end_date" class="p-error">{{
                    errors.end_date
                }}</small>
            </div>
        </div>

        <div class="custom-form">
            <div class="custom-form-column">
                <FloatLabel>
                    <InputText
                        id="contact_person"
                        class="inputtext-custom"
                        :class="{ 'p-invalid': errors.contact_person }"
                        v-model="formInsurance.contact_person"
                        @input="clearError('contact_person')"
                    />
                    <label for="contact_person">Person</label>
                </FloatLabel>
                <small v-if="errors.contact_person" class="p-error">{{
                    errors.contact_person
                }}</small>
            </div>
        </div>
        <div class="custom-form">
            <div class="custom-form-column">
                <FloatLabel>
                    <InputText
                        id="contact_email"
                        class="inputtext-custom"
                        :class="{ 'p-invalid': errors.contact_email }"
                        v-model="formInsurance.contact_email"
                        @input="clearError('contact_email')"
                    />
                    <label for="contact_email">Email</label>
                </FloatLabel>
                <small v-if="errors.contact_email" class="p-error">{{
                    errors.contact_email
                }}</small>
            </div>
        </div>
        <div class="custom-form">
            <div class="custom-form-column">
                <Select
                    filter
                    :options="listCoverageType"
                    v-model="formInsurance.coverage_type_id"
                    placeholder="Select coverage"
                    :class="{ 'p-invalid': errors.coverage_type_id }"
                    optionLabel="name"
                    optionValue="id"
                    style="width: 100%"
                />
                <small v-if="errors.coverage_type_id" class="p-error">{{
                    errors.coverage_type_id
                }}</small>
            </div>
        </div>
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

export default {
    props: ["dialogVisible", "selectedInsurance", "selectedPropertyId"],
    data() {
        return {
            visible: this.dialogVisible,
            formInsurance: {
                id: null,
                insurance_company: null,
                start_date: null,
                end_date: null,
                contact_person: null,
                contact_email: null,
                property_id: null,
                coverage_type_id: null,
            },
            errors: {},
            listCoverageType: [],
            isLoad: false,
        };
    },
    components: {
    },
    watch: {},
    mounted() {
        this.$nextTick(() => {
            if (this.selectedInsurance) {
                this.formInsurance.id = this.selectedInsurance.id;
                this.formInsurance.insurance_company =
                    this.selectedInsurance.insurance_company;
                this.formInsurance.contact_person =
                    this.selectedInsurance.contact_person;
                this.formInsurance.contact_email =
                    this.selectedInsurance.contact_email;
                this.formInsurance.property_id =
                    this.selectedInsurance.property_id;
                this.formInsurance.coverage_type_id =
                    this.selectedInsurance.coverage_id;
                this.formInsurance.start_date = new Date(
                    this.selectedInsurance.start_date
                );
                this.formInsurance.end_date = new Date(
                    this.selectedInsurance.end_date
                );
            }
        });
    },
    created() {
        this.initServices();
    },
    methods: {
        async initServices() {
            const comboNames = ["coverage_type"];
            const response = await this.$getEnumsOptions(comboNames);
            const { coverage_type: responCoverageType } = response.data;
            this.listCoverageType = responCoverageType;
        },
        getUsers(role = null) {
            const vm = this;
            return new Promise((resolve, reject) => {
                const params = role ? { params: { role } } : {};
                this.$axios
                    .get(`/users/list`, params)
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
                insurance_company: Yup.string().required(
                    "Insurance company is required"
                ),
                start_date: Yup.string().required("Start date is required"),
                end_date: Yup.string().required("End date is required"),
                contact_person: Yup.string().required(
                    "Contact person is required"
                ),
                contact_email: Yup.string()
                    .required("Contact email is required")
                    .email("Contact email must be a valid email"),
                coverage_type_id: Yup.string().required(
                    "Coverage type is required"
                ),
            };
            const schema = Yup.object().shape({
                ...initialRules,
            });
            this.errors = {};
            try {
                await schema.validate(this.formInsurance, {
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
                this.formInsurance.property_id = this.selectedPropertyId;
                this.$axios
                    .post("/insurances/store", this.formInsurance, {
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
                        `/insurances/update/${this.selectedInsurance.id}`,
                        this.formInsurance,
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
