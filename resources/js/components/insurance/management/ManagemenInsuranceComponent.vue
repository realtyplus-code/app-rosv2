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
        <div class="custom-form-column mt-4" v-if="isViewProperty">
            <Select
                :options="listProperty"
                v-model="formInsurance.properties"
                filter
                placeholder="Select property"
                :class="{ 'p-invalid': errors.properties }"
                optionLabel="name"
                optionValue="id"
                style="width: 100%"
            />
            <small v-if="errors.properties" class="p-error">{{
                errors.properties
            }}</small>
        </div>
        <div class="custom-form">
            <div class="custom-form-column">
                <FloatLabel>
                    <InputText
                        id="policy_number"
                        class="inputtext-custom"
                        :class="{ 'p-invalid': errors.policy_number }"
                        v-model="formInsurance.policy_number"
                        @input="clearError('policy_number')"
                    />
                    <label for="policy_number">Policy number</label>
                </FloatLabel>
                <small v-if="errors.policy_number" class="p-error">{{
                    errors.policy_number
                }}</small>
            </div>
        </div>
        <div class="custom-form">
            <div class="custom-form-column">
                <FloatLabel>
                    <InputNumber
                        id="policy_amount"
                        class="inputtext-custom"
                        :class="{ 'p-invalid': errors.policy_amount }"
                        v-model="formInsurance.policy_amount"
                        @input="clearError('policy_amount')"
                        :min="0"
                    />
                    <label for="policy_amount">Policy amount</label>
                </FloatLabel>
                <small v-if="errors.policy_amount" class="p-error">{{
                    errors.policy_amount
                }}</small>
            </div>
            <div class="custom-form-column">
                <FloatLabel>
                    <InputNumber
                        id="renewal_months"
                        class="inputtext-custom"
                        :class="{ 'p-invalid': errors.renewal_months }"
                        v-model="formInsurance.renewal_months"
                        @input="clearError('renewal_months')"
                        :min="0"
                    />
                    <label for="renewal_months">Renewal months</label>
                </FloatLabel>
                <small v-if="errors.renewal_months" class="p-error">{{
                    errors.renewal_months
                }}</small>
            </div>
        </div>
        <div class="custom-form">
            <div class="custom-form-column">
                <FloatLabel>
                    <DatePicker
                        showIcon
                        fluid
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
                        showIcon
                        fluid
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
                <Select
                    filter
                    :options="listInsuranceType"
                    v-model="formInsurance.insurance_type_id"
                    placeholder="Select insurance"
                    :class="{ 'p-invalid': errors.insurance_type_id }"
                    optionLabel="valor1"
                    optionValue="id"
                    style="width: 100%"
                />
                <small v-if="errors.insurance_type_id" class="p-error">{{
                    errors.insurance_type_id
                }}</small>
            </div>
        </div>
        <div class="custom-form">
            <div class="custom-form-column">
                <ToggleButton
                    v-model="formInsurance.renewal_indicator"
                    onLabel="Keep Safe"
                    offLabel="Discard Insurance"
                    onIcon="pi pi-lock"
                    offIcon="pi pi-lock-open"
                    class="w-36"
                    aria-label="Do you confirm"
                />
            </div>
        </div>
        <hr />
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
                    :options="listCountry"
                    v-model="formInsurance.country"
                    placeholder="Select country"
                    :class="{ 'p-invalid': errors.country }"
                    optionLabel="valor1"
                    optionValue="id"
                    style="width: 100%"
                />
                <small v-if="errors.country" class="p-error">{{
                    errors.country
                }}</small>
            </div>
        </div>
        <div class="custom-form">
            <div class="custom-form-column">
                <FloatLabel>
                    <InputText
                        id="position"
                        class="inputtext-custom"
                        :class="{ 'p-invalid': errors.position }"
                        v-model="formInsurance.position"
                        @input="clearError('position')"
                    />
                    <label for="position">Position</label>
                </FloatLabel>
                <small v-if="errors.position" class="p-error">{{
                    errors.position
                }}</small>
            </div>
        </div>
        <div class="custom-form mt-4">
            <div class="custom-form-column">
                <div class="phone-wrapper">
                    <input
                        id="countryPhone"
                        type="tel"
                        class="p-inputtext p-component p-inputtext-sm p-rounded"
                        :class="{ 'p-invalid': errors.phone }"
                        placeholder="Enter the ally's phone number"
                        v-model="formInsurance.phone"
                        style="width: 100% !important; height: 42px"
                    />
                </div>
                <small v-if="errors.phone" class="p-error">{{
                    errors.phone
                }}</small>
            </div>
        </div>
        <hr />
        <template #footer>
            <div class="text-center">
                <Button
                    v-if="!selectedInsurance"
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

import "intl-tel-input/build/css/intlTelInput.css";
import intlTelInput from "intl-tel-input";

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
                position: null,
                phone: null,
                code_number: null,
                code_country: null,
                property_id: null,
                insurance_type_id: null,
                country: null,
                policy_number: null,
                renewal_indicator: true,
                renewal_months: 0,
                policy_amount: null,
                properties: [],
            },
            errors: {},
            listInsuranceType: [],
            listCountry: [],
            listProperty: [],
            isLoad: false,
            isViewProperty: true,
        };
    },
    components: {},
    watch: {},
    mounted() {
        this.$nextTick(async () => {
            if (this.selectedInsurance) {
                this.formInsurance.id = this.selectedInsurance.id;
                this.formInsurance.insurance_company =
                    this.selectedInsurance.insurance_company;
                this.formInsurance.contact_person =
                    this.selectedInsurance.contact_person;
                this.formInsurance.contact_email =
                    this.selectedInsurance.contact_email;
                this.formInsurance.insurance_type_id =
                    this.selectedInsurance.insurance_id;
                this.formInsurance.start_date = new Date(
                    this.selectedInsurance.start_date
                );
                this.formInsurance.end_date = new Date(
                    this.selectedInsurance.end_date
                );
                this.formInsurance.position = this.selectedInsurance.position;
                this.formInsurance.phone = this.selectedInsurance.phone;
                this.formInsurance.country = this.selectedInsurance.country_id;
                this.formInsurance.policy_number =
                    this.selectedInsurance.policy_number;
                this.formInsurance.code_country =
                    this.selectedInsurance.code_country;
                this.formInsurance.code_number =
                    this.selectedInsurance.code_number;
                this.formInsurance.renewal_months =
                    this.selectedInsurance.renewal_months;
                this.formInsurance.policy_amount =
                    this.selectedInsurance.policy_amount;
                this.formInsurance.renewal_indicator =
                    this.selectedInsurance.renewal_indicator;
                this.formInsurance.properties = this.selectedInsurance.property_id;
            } else {
                this.setFormUrl();
            }
            await this.getProperties();
            const phoneInputField = document.querySelector("#countryPhone");
            if (!phoneInputField) {
                console.error("El elemento #phone no está definido.");
                return;
            }
            this.phoneInput = intlTelInput(phoneInputField, {
                preferredCountries: ["us", "mx", "es", "ar"],
                initialCountry: this.selectedInsurance
                    ? this.selectedInsurance.code_country
                    : null,
                utilsScript:
                    "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
            });
        });
    },
    created() {
        this.initServices();
    },
    methods: {
        async setFormUrl() {
            const property_id = $.urlParam("property_id");
            if (property_id) {
                this.isViewProperty = false;
                let response = await this.getPropertyById(property_id);
                this.formInsurance.properties = response.map(
                    (item) => item.id
                );
            }
        },
        async initServices() {
            const comboNames = ["insurance_type", "country"];
            const response = await this.$getEnumsOptions(comboNames);
            const {
                insurance_type: respInsuranceType,
                country: responsCountry,
            } = response.data;
            this.listInsuranceType = respInsuranceType;
            this.listCountry = responsCountry;
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
        async getProperties() {
            let url = `/properties/list`;
            try {
                const response = await this.$axios.get(url);
                this.listProperty = response.data.data;
            } catch (error) {
                this.$readStatusHttp(error);
            }
        },
        async getPropertyById(id) {
            let url = `/properties/byId/` + id;
            try {
                const response = await this.$axios.get(url);
                return response.data.data;
            } catch (error) {
                this.$readStatusHttp(error);
            }
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
                position: Yup.string().required("Position is required"),
                phone: Yup.string()
                    .matches(
                        /^[0-9]+$/,
                        "Phone number must contain only digits"
                    )
                    .required("Type phone is required"),
                insurance_type_id: Yup.string().required(
                    "Coverage type is required"
                ),
                policy_number: Yup.string().required(
                    "Policy number is required"
                ),
                country: Yup.string().required("Country is required"),
                renewal_months: Yup.string().required(
                    "Renewal months is required"
                ),
                policy_amount: Yup.string().required(
                    "Policy amount is required"
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
            let tmpFormatNumber = null;
            let tmpFormatCountry = null;
            const isValid = await this.validateForm();
            const countryData = this.phoneInput.getSelectedCountryData();
            if (!countryData.dialCode || countryData.dialCode == undefined) {
                this.isLoad = false;
                return this.$alertWarning("select first country phone");
            }
            tmpFormatNumber = countryData.dialCode;
            tmpFormatCountry = countryData.iso2;
            if (isValid) {
                this.formInsurance.code_number = tmpFormatNumber;
                this.formInsurance.code_country = tmpFormatCountry;
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
            let tmpFormatNumber = null;
            let tmpFormatCountry = null;
            const isValid = await this.validateForm();
            const countryData = this.phoneInput.getSelectedCountryData();
            if (!countryData.dialCode || countryData.dialCode == undefined) {
                this.isLoad = false;
                return this.$alertWarning("select first country phone");
            }
            tmpFormatNumber = countryData.dialCode;
            tmpFormatCountry = countryData.iso2;
            if (isValid) {
                this.formInsurance.code_number = tmpFormatNumber;
                this.formInsurance.code_country = tmpFormatCountry;
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
