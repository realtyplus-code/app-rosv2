<template>
    <Dialog
        v-model:visible="visible"
        modal
        class="dialog-management"
        :style="{ width: '45rem' }"
        :draggable="false"
    >
        <template #header>
            <h3>Management Provider</h3>
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
                        id="name"
                        class="inputtext-custom"
                        :class="{ 'p-invalid': errors.name }"
                        v-model="formProvider.name"
                        @input="clearError('name')"
                    />
                    <label for="name">Name</label>
                </FloatLabel>
                <small v-if="errors.name" class="p-error">{{
                    errors.name
                }}</small>
            </div>
        </div>
        <div class="custom-form mt-4">
            <div class="custom-form-column">
                <FloatLabel>
                    <InputText
                        id="user"
                        class="inputtext-custom"
                        :class="{ 'p-invalid': errors.user }"
                        v-model="formProvider.user"
                        @input="clearError('user')"
                    />
                    <label for="user">User</label>
                </FloatLabel>
                <small v-if="errors.user" class="p-error">{{
                    errors.user
                }}</small>
            </div>
            <div class="custom-form-column">
                <InputGroup>
                    <InputGroupAddon>
                        <Button
                            icon="pi pi-key"
                            @click="generateRandomPassword"
                        />
                    </InputGroupAddon>
                    <FloatLabel>
                        <Password
                            id="password"
                            :class="{ 'p-invalid': errors.password }"
                            v-model="formProvider.password"
                            @input="clearError('password')"
                            toggleMask
                        />
                        <label for="password">Password</label>
                    </FloatLabel>
                </InputGroup>
                <small v-if="errors.password" class="p-error">{{
                    errors.password
                }}</small>
            </div>
        </div>
        <div class="custom-form" style="margin-top: 20px">
            <div class="custom-form-column">
                <FloatLabel>
                    <InputText
                        id="address"
                        class="inputtext-custom"
                        :class="{ 'p-invalid': errors.address }"
                        v-model="formProvider.address"
                        @input="clearError('address')"
                    />
                    <label for="address">Address</label>
                </FloatLabel>
                <small v-if="errors.address" class="p-error">{{
                    errors.address
                }}</small>
            </div>
        </div>
        <div class="custom-form">
            <div class="custom-form-column">
                <FloatLabel>
                    <InputText
                        id="coverage_area"
                        class="inputtext-custom"
                        :class="{ 'p-invalid': errors.coverage_area }"
                        v-model="formProvider.coverage_area"
                        @input="clearError('coverage_area')"
                    />
                    <label for="coverage_area">Coverage Area</label>
                </FloatLabel>
                <small v-if="errors.coverage_area" class="p-error">{{
                    errors.coverage_area
                }}</small>
            </div>
        </div>
        <div class="custom-form">
            <div class="custom-form-column">
                <div class="phone-wrapper">
                    <input
                        id="countryPhone"
                        type="tel"
                        class="p-inputtext p-component p-inputtext-sm p-rounded"
                        :class="{ 'p-invalid': errors.contact_phone }"
                        placeholder="Enter the ally's contact_phone number"
                        v-model="formProvider.contact_phone"
                        style="width: 320px !important; height: 42px"
                    />
                </div>
                <small v-if="errors.contact_phone" class="p-error">{{
                    errors.contact_phone
                }}</small>
            </div>
            <div class="custom-form-column">
                <FloatLabel>
                    <InputText
                        id="email"
                        class="inputtext-custom"
                        :class="{ 'p-invalid': errors.email }"
                        v-model="formProvider.email"
                        @input="clearError('email')"
                    />
                    <label for="email">Contact Email</label>
                </FloatLabel>
                <small v-if="errors.email" class="p-error">{{
                    errors.email
                }}</small>
            </div>
        </div>
        <div class="custom-form">
            <div class="custom-form-column">
                <FloatLabel>
                    <InputText
                        id="website"
                        class="inputtext-custom"
                        :class="{ 'p-invalid': errors.website }"
                        v-model="formProvider.website"
                        @input="clearError('website')"
                    />
                    <label for="website">Website</label>
                </FloatLabel>
                <small v-if="errors.website" class="p-error">{{
                    errors.website
                }}</small>
            </div>
        </div>
        <div class="custom-form">
            <div class="custom-form-column">
                <FloatLabel>
                    <InputNumber
                        id="service_cost"
                        class="inputtext-custom"
                        :class="{ 'p-invalid': errors.service_cost }"
                        v-model="formProvider.service_cost"
                        @input="clearError('service_cost')"
                        :currency="$globals.CURRENCY_TYPES.EUR"
                    />
                    <label for="service_cost">Service Cost</label>
                </FloatLabel>
                <small v-if="errors.service_cost" class="p-error">{{
                    errors.service_cost
                }}</small>
            </div>
        </div>
        <div class="custom-form">
            <div class="custom-form-column">
                <MultiSelect
                    :options="listTypeProvider"
                    v-model="formProvider.providers"
                    filter
                    placeholder="Select providers"
                    :class="{ 'p-invalid': errors.providers }"
                    :maxSelectedLabels="limitPrivders"
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
                <Select
                    filter
                    :options="listLanguages"
                    v-model="formProvider.language_id"
                    placeholder="Select language"
                    :class="{ 'p-invalid': errors.language_id }"
                    optionLabel="name"
                    optionValue="id"
                    style="width: 100%"
                />
                <small v-if="errors.language_id" class="p-error">{{
                    errors.language_id
                }}</small>
            </div>
            <div class="custom-form-column">
                <Select
                    filter
                    :options="listStatus"
                    v-model="formProvider.status"
                    placeholder="Select status"
                    :class="{ 'p-invalid': errors.status }"
                    optionLabel="name"
                    optionValue="id"
                    style="width: 100%"
                />
                <small v-if="errors.status" class="p-error">{{
                    errors.status
                }}</small>
            </div>
        </div>
        <hr />
        <template #footer>
            <div class="text-center">
                <Button
                    v-if="!selectedProvider"
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
    props: ["dialogVisible", "selectedProvider"],
    data() {
        return {
            visible: this.dialogVisible,
            formProvider: {
                id: null,
                name: null,
                address: null,
                coverage_area: null,
                contact_phone: null,
                code_number: null,
                code_country: null,
                email: null,
                service_cost: null,
                status: null,
                providers: [],
                password: null, // nuevo campo
                language_id: null, // nuevo campo
                website: null, // nuevo campo
            },
            errors: {},
            listStatus: [],
            phoneInput: null,
            isLoad: false,
            limitPrivders: 10,
            listTypeProvider: [],
            listLanguages: [], // nuevo campo
        };
    },
    components: {},
    mounted() {
        this.$nextTick(() => {
            if (this.selectedProvider) {
                const currentTypeName = this.$parseTags(
                    this.selectedProvider.providers_name
                );
                this.formProvider.id = this.selectedProvider.id;
                this.formProvider.name = this.selectedProvider.name;
                this.formProvider.address = this.selectedProvider.address;
                this.formProvider.coverage_area =
                    this.selectedProvider.coverage_area;
                this.formProvider.contact_phone =
                    this.selectedProvider.contact_phone;
                this.formProvider.email =
                    this.selectedProvider.email;
                this.formProvider.service_cost =
                    this.selectedProvider.service_cost;
                this.formProvider.status = this.selectedProvider.status;
                this.formProvider.code_number =
                    this.selectedProvider.code_number;
                this.formProvider.code_country =
                    this.selectedProvider.code_country;
                this.formProvider.providers = currentTypeName.map(
                    (type) => type.id
                );
            }
            const phoneInputField = document.querySelector("#countryPhone");
            if (!phoneInputField) {
                console.error("El elemento #phone no está definido.");
                return;
            }
            this.phoneInput = intlTelInput(phoneInputField, {
                preferredCountries: ["us", "mx", "es", "ar"],
                initialCountry: this.selectedProvider
                    ? this.selectedProvider.code_country
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
        async initServices() {
            this.listStatus = [
                { id: 1, name: "active" },
                { id: 2, name: "inactive" },
            ];
            const comboNames = ["provider_type", "language"];
            const response = await this.$getEnumsOptions(comboNames);
            const {
                provider_type: responProviderType,
                language: responLanguages,
            } = response.data;
            this.listTypeProvider = responProviderType;
            this.listLanguages = responLanguages;
        },
        async validateForm() {
            let initialRules = {
                name: Yup.string().required("Provider name is required"),
                address: Yup.string().required("Address is required"),
                coverage_area: Yup.string().required(
                    "Coverage area is required"
                ),
                contact_phone: Yup.string().required(
                    "Contact phone is required"
                ),
                email: Yup.string()
                    .email("The email format is not valid")
                    .required("Contact email is required"),
                service_cost: Yup.number().required("Service cost is required"),
                password: Yup.string().required("Password is required"), // nuevo campo
                language_id: Yup.number().required("Language is required"), // nuevo campo
                website: Yup.string()
                    .url("The website format is not valid")
                    .required("Website is required"), // nuevo campo
                status: Yup.number().required("Status is required"), // nuevo campo
            };
            const schema = Yup.object().shape({
                ...initialRules,
            });
            this.errors = {};
            try {
                await schema.validate(this.formProvider, {
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
                this.formProvider.code_number = tmpFormatNumber;
                this.formProvider.code_country = tmpFormatCountry;
                this.$axios
                    .post("/providers/store", this.formProvider, {
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
                this.formProvider.code_number = tmpFormatNumber;
                this.formProvider.code_country = tmpFormatCountry;
                this.$axios
                    .post(
                        `/providers/update/${this.selectedProvider.id}`,
                        this.formProvider,
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
        clearError(field) {
            this.errors[field] = null;
        },
        handleDialogClose() {
            this.visible = false;
            this.$emit("hidden", this.visible);
        },
        generateRandomPassword() {
            const chars =
                "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789@#$&*";
            let password = "";
            for (let i = 0; i < 12; i++) {
                password += chars.charAt(
                    Math.floor(Math.random() * chars.length)
                );
            }
            this.formProvider.password = password;
        },
    },
};
</script>
