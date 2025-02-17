<template>
    <Card class="card-management" :style="{ width: '90rem' }">
        <template #header>
            <h3 style="padding: 10px">Management Provider</h3>
            <ProgressSpinner
                style="width: 25px; height: 25px; margin-left: 20px"
                strokeWidth="8"
                fill="transparent"
                animationDuration=".5s"
                v-show="isLoad"
            />
        </template>
        <template #content>
            <div class="custom-form mt-4">
                <div class="custom-form-column">
                    <FloatLabel>
                        <InputText
                            id="name"
                            class="inputtext-custom"
                            :class="{ 'p-invalid': errors.name }"
                            v-model="formProvider.name"
                            style="width: 100%"
                            @input="clearError('name')"
                        />
                        <label for="name">Name</label>
                    </FloatLabel>
                    <small v-if="errors.name" class="p-error">{{
                        errors.name
                    }}</small>
                </div>
                <div class="custom-form-column">
                    <FloatLabel>
                        <InputText
                            id="user"
                            class="inputtext-custom"
                            :class="{ 'p-invalid': errors.user }"
                            v-model="formProvider.user"
                            style="width: 100%"
                            @input="clearError('user')"
                        />
                        <label for="user">User</label>
                    </FloatLabel>
                    <small v-if="errors.user" class="p-error">{{
                        errors.user
                    }}</small>
                </div>
            </div>
            <div class="custom-form mt-4">
                <div class="custom-form-column">
                    <FloatLabel>
                        <InputText
                            id="email"
                            class="inputtext-custom"
                            :class="{ 'p-invalid': errors.email }"
                            v-model="formProvider.email"
                            style="width: 100%"
                            @input="clearError('email')"
                        />
                        <label for="email">Email</label>
                    </FloatLabel>
                    <small v-if="errors.email" class="p-error">{{
                        errors.email
                    }}</small>
                </div>
            </div>
            <div class="custom-form mt-4">
                <div class="custom-form-column">
                    <FloatLabel>
                        <InputText
                            id="address"
                            class="inputtext-custom"
                            :class="{ 'p-invalid': errors.address }"
                            v-model="formProvider.address"
                            style="width: 100%"
                            @input="clearError('address')"
                        />
                        <label for="address">Address</label>
                    </FloatLabel>
                    <small v-if="errors.address" class="p-error">{{
                        errors.address
                    }}</small>
                </div>
                <div class="custom-form-column">
                    <FloatLabel>
                        <InputText
                            id="coverage_area"
                            class="inputtext-custom"
                            :class="{ 'p-invalid': errors.coverage_area }"
                            v-model="formProvider.coverage_area"
                            style="width: 100%"
                            @input="clearError('coverage_area')"
                        />
                        <label for="coverage_area">Coverage area</label>
                    </FloatLabel>
                    <small v-if="errors.coverage_area" class="p-error">{{
                        errors.coverage_area
                    }}</small>
                </div>
            </div>
            <div class="custom-form mt-4">
                <div class="custom-form-column" style="margin-top: 5px">
                    <FloatLabel>
                        <Select
                            :options="listCountry"
                            v-model="formProvider.country"
                            :class="{ 'p-invalid': errors.country }"
                            optionLabel="valor1"
                            optionValue="id"
                            style="width: 100%"
                            @change="
                                onChangeCountry(formProvider.country, true)
                            "
                        />
                        <label for="country">Country</label>
                    </FloatLabel>
                    <small v-if="errors.country" class="p-error">{{
                        errors.country
                    }}</small>
                </div>
                <div class="custom-form-column" style="margin-top: 5px">
                    <FloatLabel>
                        <Select
                            :options="listState"
                            v-model="formProvider.state"
                            :class="{ 'p-invalid': errors.state }"
                            optionLabel="valor1"
                            optionValue="id"
                            style="width: 100%"
                            @change="onChangeState(formProvider.state, true)"
                        />
                        <label for="state">State</label>
                    </FloatLabel>
                    <small v-if="errors.state" class="p-error">{{
                        errors.state
                    }}</small>
                </div>
                <div class="custom-form-column" style="margin-top: 5px">
                    <FloatLabel>
                        <Select
                            :options="listCity"
                            v-model="formProvider.city"
                            :class="{ 'p-invalid': errors.city }"
                            optionLabel="valor1"
                            optionValue="id"
                            style="width: 100%"
                        />
                        <label for="city">City</label>
                    </FloatLabel>
                    <small v-if="errors.city" class="p-error">{{
                        errors.city
                    }}</small>
                </div>
            </div>
            <div class="custom-form mt-4">
                <div class="custom-form-column">
                    <FloatLabel>
                        <InputText
                            id="service_cost"
                            class="inputtext-custom"
                            :class="{ 'p-invalid': errors.service_cost }"
                            v-model="formProvider.service_cost"
                            style="width: 100%"
                            @input="clearError('service_cost')"
                        />
                        <label for="service_cost">Service Cost</label>
                    </FloatLabel>
                    <small v-if="errors.service_cost" class="p-error">{{
                        errors.service_cost
                    }}</small>
                </div>
            </div>
            <div class="custom-form mt-4">
                <div class="custom-form-column" style="margin-top: 5px">
                    <FloatLabel>
                        <Select
                            filter
                            :options="listLanguages"
                            v-model="formProvider.language_id"
                            :class="{ 'p-invalid': errors.language_id }"
                            optionLabel="valor1"
                            optionValue="id"
                            style="width: 100%"
                        />
                        <label for="address">Language</label>
                    </FloatLabel>
                    <small v-if="errors.language_id" class="p-error">{{
                        errors.language_id
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
                            :class="{ 'p-invalid': errors.contact_phone }"
                            placeholder="Enter the provider's phone number"
                            v-model="formProvider.contact_phone"
                            style="width: 100% !important; height: 42px"
                        />
                    </div>
                    <small v-if="errors.contact_phone" class="p-error">{{
                        errors.contact_phone
                    }}</small>
                </div>
                <div class="custom-form-column">
                    <FloatLabel>
                        <InputText
                            id="website"
                            class="inputtext-custom"
                            :class="{ 'p-invalid': errors.website }"
                            v-model="formProvider.website"
                            style="width: 100%"
                            @input="clearError('website')"
                        />
                        <label for="website">Website</label>
                    </FloatLabel>
                    <small v-if="errors.website" class="p-error">{{
                        errors.website
                    }}</small>
                </div>
            </div>
            <div class="custom-form mt-4">
                <div class="custom-form-column">
                    <FloatLabel>
                        <Select
                            :options="listStatus"
                            v-model="formProvider.status"
                            :class="{ 'p-invalid': errors.status }"
                            optionLabel="valor1"
                            optionValue="id"
                            style="width: 100%"
                        />
                        <label for="status">Status</label>
                    </FloatLabel>
                    <small v-if="errors.status" class="p-error">{{
                        errors.status
                    }}</small>
                </div>
                <div class="custom-form-column">
                    <FloatLabel>
                        <MultiSelect
                            :options="listTypeProvider"
                            v-model="formProvider.providers"
                            :class="{ 'p-invalid': errors.providers }"
                            optionLabel="valor1"
                            optionValue="id"
                            style="width: 100%"
                        />
                        <label for="providers">Services</label>
                    </FloatLabel>
                    <small v-if="errors.providers" class="p-error">{{
                        errors.providers
                    }}</small>
                </div>
            </div>
            <hr />
        </template>
        <template #footer>
            <div class="text-center" style="display: flex; float: right">
                <Button
                    label="Update"
                    severity="success"
                    style="margin-right: 10px"
                    @click="update"
                />
            </div>
        </template>
    </Card>
</template>

<script>
import * as Yup from "yup";

import "intl-tel-input/build/css/intlTelInput.css";
import intlTelInput from "intl-tel-input";

export default {
    props: ["dialogVisible"],
    data() {
        return {
            selectedProvider: [],
            visible: this.dialogVisible,
            formProvider: {
                id: null,
                name: null,
                user: null,
                email: null,
                address: null,
                coverage_area: null,
                country: null,
                state: null,
                city: null,
                contact_phone: null,
                code_number: null,
                code_country: null,
                email: null,
                language_id: [],
                service_cost: null,
                status: null,
                providers: [],
                website: null,
            },
            errors: {},
            phoneInput: null,
            isLoad: false,
            listStatus: [],
            listCity: [],
            listTypeProvider: [],
            listCountry: [],
            listState: [],
            listLanguages: [],
        };
    },
    components: {},
    watch: {},
    mounted() {},
    created() {
        this.initServices();
        this.getProviderById();
    },
    methods: {
        async setFormProvider() {
            const currentTypeName = this.$parseTags(
                this.selectedProvider.providers_name
            );
            this.formProvider.id = this.selectedProvider.id;
            this.formProvider.name = this.selectedProvider.name;
            this.formProvider.address = this.selectedProvider.address;
            this.formProvider.user = this.selectedProvider.user;
            this.formProvider.website = this.selectedProvider.website;
            this.formProvider.coverage_area =
                this.selectedProvider.coverage_area;
            this.formProvider.contact_phone =
                this.selectedProvider.contact_phone;
            this.formProvider.email = this.selectedProvider.email;
            this.formProvider.service_cost = this.selectedProvider.service_cost;
            this.formProvider.status = this.selectedProvider.status;
            this.formProvider.code_number = this.selectedProvider.code_number;
            this.formProvider.code_country = this.selectedProvider.code_country;
            this.formProvider.providers = currentTypeName.map(
                (type) => type.id
            );
            this.formProvider.country = parseInt(
                this.selectedProvider.country_id
            );
            this.formProvider.state = parseInt(this.selectedProvider.state_id);
            this.formProvider.city = parseInt(this.selectedProvider.city_id);
            this.formProvider.language_id = parseInt(
                this.selectedProvider.language_id
            );
            if (this.selectedProvider.country_id) {
                await this.onChangeCountry(
                    this.selectedProvider.country_id,
                    false
                );
                if (this.selectedProvider.state_id) {
                    await this.onChangeState(
                        this.selectedProvider.state_id,
                        false
                    );
                }
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
        },
        async initServices() {
            this.listStatus = [
                { id: 1, name: "active", valor1: "activo" },
                { id: 2, name: "inactive", valor1: "inactivo" },
            ];
            const comboNames = ["country", "provider_type", "language"];
            const response = await this.$getEnumsOptions(comboNames);
            const {
                provider_type: responProviderType,
                language: responLanguages,
                country: responCountry,
            } = response.data;
            this.listTypeProvider = responProviderType;
            this.listLanguages = responLanguages;
            this.listCountry = responCountry;
        },
        getProviderById() {
            this.$axios
                .get(`/providers/byId`)
                .then((response) => {
                    this.selectedProvider = response.data.data[0];
                    this.setFormProvider();
                })
                .catch((error) => {
                    this.$readStatusHttp(error);
                });
        },
        async validateForm() {
            this.dynamicRules = {};
            let initialRules = {
                name: Yup.string().required("Provider name is required"),
                user: Yup.string().required("Provider user is required"),
                address: Yup.string().required("Address is required"),
                coverage_area: Yup.string().required(
                    "Coverage area is required"
                ),
                contact_phone: Yup.string()
                    .matches(
                        /^[0-9]+$/,
                        "Phone number must contain only digits"
                    )
                    .required("Type phone is required"),
                email: Yup.string()
                    .email("The email format is not valid")
                    .required("Contact email is required"),
                service_cost: Yup.number().required("Service cost is required"),
                language_id: Yup.number().required("Language is required"),
                website: Yup.string()
                    .url(
                        "The website format is not valid: https://www.example.com"
                    )
                    .required("Website is required: https://www.example.com"),
                status: Yup.number().required("Status is required"),
                providers: Yup.array().min(
                    1,
                    "At least one service is required"
                ),
            };
            const schema = Yup.object().shape({
                ...initialRules,
                ...this.dynamicRules,
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
        onChangeCountry(value, change) {
            return new Promise(async (resolve, reject) => {
                try {
                    const response = await this.$getBrotherCode(value, "state");
                    this.listState = [];
                    this.listCity = [];
                    if (value && change) {
                        this.formProvider.state = null;
                        this.formProvider.city = null;
                    } else {
                        this.formProvider.state = this.selectedProvider
                            ? parseInt(this.selectedProvider.state_id)
                            : null;
                        this.formProvider.city = this.selectedProvider
                            ? parseInt(this.selectedProvider.city_id)
                            : null;
                    }
                    this.listState = response.data;
                    if (change) {
                        this.formProvider.language_id = null;
                        const responseCode = await this.$getBrotherCode(value, "language");
                        if (responseCode.data && responseCode.data.length > 0) {
                            this.formProvider.language_id = parseInt(responseCode.data[0].id);
                        }
                    }
                    resolve();
                } catch (error) {
                    console.error("Error in onChangeCountry:", error);
                    reject(error);
                }
            });
        },
        onChangeState(value, change) {
            return new Promise(async (resolve, reject) => {
                try {
                    const response = await this.$getBrotherCode(value, "city");
                    this.listCity = [];
                    if (value && change) {
                        this.formProvider.city = null;
                    } else {
                        this.formProvider.city = this.selectedProvider
                            ? parseInt(this.selectedProvider.city_id)
                            : null;
                    }
                    this.listCity = response.data;
                    resolve();
                } catch (error) {
                    console.error("Error in onChangeState:", error);
                    reject(error);
                }
            });
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
    grid-template-columns: repeat(1, 1fr);
    grid-template-rows: repeat(1, 1fr);
    gap: 10px;
    max-width: 350px;
    max-height: 400px;
}

.gallery-item {
    display: flex;
    justify-content: center;
    align-items: center;
    max-width: 350px;
    max-height: 400px;
}

.gallery-card {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    width: 100%;
    height: 100%;
    position: relative;
    @media (max-width: 768px) {
        width: 100%;
        height: auto;
    }
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

.phone-wrapper > .iti {
    width: 100% !important;
}
</style>
