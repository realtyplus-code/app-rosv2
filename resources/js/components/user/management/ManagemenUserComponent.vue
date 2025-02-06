<template>
    <Dialog
        v-model:visible="visible"
        modal
        class="dialog-management"
        :style="{ width: '45rem' }"
        :draggable="false"
    >
        <template #header>
            <h3>Management User</h3>
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
                <Select
                    filter
                    :options="listRoles"
                    v-model="formUser.role"
                    placeholder="Select rol"
                    :class="{ 'p-invalid': errors.role }"
                    optionLabel="name"
                    optionValue="id"
                    style="width: 100%"
                    @change="onChangeRole(formUser.role, true)"
                />
                <small v-if="errors.role" class="p-error">{{
                    errors.role
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
                        v-model="formUser.user"
                        style="width: 100%"
                        @input="clearError('user')"
                    />
                    <label for="user">User</label>
                </FloatLabel>
                <small v-if="errors.user" class="p-error">{{
                    errors.user
                }}</small>
            </div>
            <div class="custom-form-column">
                <FloatLabel>
                    <InputText
                        id="name"
                        class="inputtext-custom"
                        :class="{ 'p-invalid': errors.name }"
                        v-model="formUser.name"
                        style="width: 100%"
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
                        id="email"
                        class="inputtext-custom"
                        :class="{ 'p-invalid': errors.email }"
                        v-model="formUser.email"
                        style="width: 100%"
                        @input="clearError('email')"
                    />
                    <label for="email">Email</label>
                </FloatLabel>
                <small v-if="errors.email" class="p-error">{{
                    errors.email
                }}</small>
            </div>
            <div class="custom-form-column" v-if="!this.selectedUser">
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
                            v-model="formUser.password"
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
        <div class="custom-form mt-4">
            <div class="custom-form-column" style="margin-top: 12px">
                <Select
                    :options="listCountry"
                    v-model="formUser.country"
                    placeholder="Select country"
                    :class="{ 'p-invalid': errors.country }"
                    optionLabel="name"
                    optionValue="id"
                    style="width: 100%"
                    @change="onChangeCountry(formUser.country, true)"
                />
                <small v-if="errors.country" class="p-error">{{
                    errors.country
                }}</small>
            </div>
            <div class="custom-form-column" style="margin-top: 12px">
                <Select
                    :options="listState"
                    v-model="formUser.state"
                    :placeholder="placeholderState"
                    :class="{ 'p-invalid': errors.state }"
                    optionLabel="name"
                    optionValue="id"
                    style="width: 100%"
                    @change="onChangeState(formUser.state, true)"
                />
                <small v-if="errors.state" class="p-error">{{
                    errors.state
                }}</small>
            </div>
            <div class="custom-form-column" style="margin-top: 12px">
                <Select
                    :options="listCity"
                    v-model="formUser.city"
                    :placeholder="placeholderCity"
                    :class="{ 'p-invalid': errors.city }"
                    optionLabel="name"
                    optionValue="id"
                    style="width: 100%"
                />

                <small v-if="errors.city" class="p-error">{{
                    errors.city
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
                        v-model="formUser.address"
                        style="width: 100%"
                        @input="clearError('address')"
                    />
                    <label for="address">Address</label>
                </FloatLabel>
                <small v-if="errors.address" class="p-error">{{
                    errors.address
                }}</small>
            </div>
        </div>
        <div class="custom-form mt-4">
            <div class="custom-form-column">
                <Select
                    filter
                    :options="listLenguage"
                    v-model="formUser.language_id"
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
                        v-model="formUser.phone"
                        style="width: 100% !important; height: 42px"
                    />
                </div>
                <small v-if="errors.phone" class="p-error">{{
                    errors.phone
                }}</small>
            </div>
        </div>
        <div class="custom-form mt-4" v-if="isRosClientGlobal">
            <div class="custom-form-column">
                <FloatLabel>
                    <MultiSelect
                        :options="usersRosClient"
                        v-model="formUser.ros_clients"
                        filter
                        :class="{ 'p-invalid': errors.ros_clients }"
                        optionLabel="name"
                        optionValue="id"
                        style="width: 100%"
                    />
                    <label for="ros_clients">Ros Clients</label>
                </FloatLabel>
                <small v-if="errors.ros_clients" class="p-error">{{
                    errors.ros_clients
                }}</small>
            </div>
        </div>
        <hr />
        <div class="custom-form mt-4">
            <div v-if="!selectedUser" class="custom-form-column">
                <FileUpload
                    id="attachPhoto"
                    ref="fileUpload"
                    accept="image/*"
                    :multiple="true"
                    :fileLimit="1"
                    :class="{
                        'p-invalid': errors.photos,
                    }"
                    @change="onFileUpload"
                    @remove="onFileRemove"
                >
                    <template #empty>
                        <p>Search photos (Max 1)</p>
                    </template>
                </FileUpload>
                <small
                    v-if="errors.photos"
                    style="display: block"
                    class="p-error"
                    >{{ errors.photos }}</small
                >
            </div>
            <div v-else class="gallery">
                <div class="gallery-grid">
                    <div
                        v-for="(photo, index) in formUser.photos"
                        :key="index"
                        class="gallery-item"
                    >
                        <div class="card p-card gallery-card">
                            <img
                                :src="getImageSource(photo.file_path)"
                                alt="Image"
                                class="gallery-image"
                            />
                            <button
                                v-if="(formUser.photos.length = 1)"
                                class="p-button-danger mt-2"
                                style="
                                    width: 100%;
                                    border-radius: 0px 0px 10px 10px;
                                "
                                @click="removePhoto(index, photo.id)"
                            >
                                Delete
                            </button>
                        </div>
                    </div>
                    <div v-if="formUser.photos.length < 1" class="gallery-item">
                        <div class="card p-card gallery-card">
                            <input
                                type="file"
                                @change="
                                    handleFileChange(
                                        $event,
                                        formUser.photos.length,
                                        formUser.id
                                    )
                                "
                                class="file-input"
                            />
                        </div>
                    </div>
                    <small
                        v-if="errors.photos"
                        style="display: block"
                        class="p-error"
                        >{{ errors.photos }}</small
                    >
                </div>
            </div>
        </div>
        <hr />
        <template #footer>
            <div class="text-center">
                <Button
                    v-if="!selectedUser"
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
    props: ["dialogVisible", "selectedUser"],
    data() {
        return {
            visible: this.dialogVisible,
            formUser: {
                id: null,
                user: null,
                name: null,
                email: null,
                phone: null,
                address: null,
                country: null,
                state: null,
                city: null,
                code_number: null,
                code_country: null,
                photos: [],
                language_id: [],
                role: null,
                ros_clients: [],
            },
            errors: {},
            phoneInput: null,
            listRoles: [],
            isLoad: false,
            listCity: [],
            listCountry: [],
            listState: [],
            listLenguage: [],
            placeholderCity: "Select the state first",
            placeholderState: "Select the country first",
            usersRosClient: [],
            isRosClientGlobal: false,
        };
    },
    components: {},
    watch: {},
    mounted() {
        this.$nextTick(async () => {
            this.isRosClientGlobal = false;
            if (this.selectedUser) {
                await this.setFormUser();
            }
            const rols = await this.getRoles();
            if (rols) {
                this.listRoles = rols.data;
            }
            this.onChangeRole(this.formUser.role, false);
            const phoneInputField = document.querySelector("#countryPhone");
            if (!phoneInputField) {
                console.error("El elemento #phone no está definido.");
                return;
            }
            this.phoneInput = intlTelInput(phoneInputField, {
                preferredCountries: ["us", "mx", "es", "ar"],
                initialCountry: this.selectedUser
                    ? this.selectedUser.code_country
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
        async setFormUser() {
            this.formUser.id = this.selectedUser.id;
            this.formUser.name = this.selectedUser.name;
            this.formUser.user = this.selectedUser.user;
            this.formUser.email = this.selectedUser.email;
            this.formUser.phone = this.selectedUser.phone;
            this.formUser.address = this.selectedUser.address;
            this.formUser.country = parseInt(this.selectedUser.country_id);
            this.formUser.state = parseInt(this.selectedUser.state_id);
            this.formUser.city = parseInt(this.selectedUser.city_id);
            this.formUser.language_id = parseInt(this.selectedUser.language_id);
            this.formUser.code_number = this.selectedUser.code_number;
            this.formUser.code_country = this.selectedUser.code_country;
            this.formUser.role = this.selectedUser.roles[0].id;
            this.setPhotos();
            if (this.selectedUser.country_id) {
                await this.onChangeCountry(this.selectedUser.country_id, false);
                if (this.selectedUser.state_id) {
                    await this.onChangeState(this.selectedUser.state_id, false);
                }
            }
            this.formUser.ros_clients = this.selectedUser.related_users.map(
                (user) => user.user_id
            );
        },
        async initServices() {
            const comboNames = ["country", "language"];
            const response = await this.$getEnumsOptions(comboNames);
            const { country: responsCountry, language: responsLenguaje } =
                response.data;
            this.listCountry = responsCountry;
            this.listLenguage = responsLenguaje;
        },
        getRoles() {
            const vm = this;
            return new Promise((resolve, reject) => {
                this.$axios
                    .get(`/rols/list`)
                    .then(function (response) {
                        resolve(response.data);
                    })
                    .catch((error) => {
                        vm.$readStatusHttp(error);
                        reject(error);
                    });
            });
        },
        fetchUsersByRole(roleName) {
            this.loading = true;
            this.$axios
                .get(`/users/role/${roleName}`)
                .then((response) => {
                    this.usersRosClient = response.data.data;
                    this.loading = false;
                })
                .catch((error) => {
                    this.$readStatusHttp(error);
                    this.loading = false;
                });
        },
        async validateForm() {
            this.dynamicRules = {};
            let initialRules = {
                name: Yup.string().required("Type name is required"),
                user: Yup.string().required("Type user is required"),
                email: Yup.string()
                    .email("The email format is not valid")
                    .required("Type email is required"),
                phone: Yup.string().required("Type phone is required"),
                photos: Yup.array()
                    .min(1, "At least 1 photo is required")
                    .max(1, "You can upload up to 1 photos")
                    .required("Photo is required"),
                role: Yup.string().required("Role is required"),
                country: Yup.string().required("Country is required"),
                state: Yup.string().required("State is required"),
                city: Yup.string().required("City is required"),
                address: Yup.string().required("Address is required"),
                language_id: Yup.string().required("Language is required"),
            };
            if (!this.selectedUser) {
                this.dynamicRules.password = Yup.string().required(
                    "Password is required"
                );
            }
            const schema = Yup.object().shape({
                ...initialRules,
                ...this.dynamicRules,
            });
            this.errors = {};
            try {
                await schema.validate(this.formUser, {
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
                this.formUser.code_number = tmpFormatNumber;
                this.formUser.code_country = tmpFormatCountry;
                this.$axios
                    .post("/users/store", this.formUser, {
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
                this.formUser.code_number = tmpFormatNumber;
                this.formUser.code_country = tmpFormatCountry;
                this.$axios
                    .post(
                        `/users/update/${this.selectedUser.id}`,
                        this.formUser,
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
                    const response = await this.$getBrother(value);
                    this.listState = [];
                    this.listCity = [];
                    if (value && change) {
                        this.formUser.state_id = null;
                        this.formUser.city_id = null;
                    } else {
                        this.formUser.state_id = this.selectedUser
                            ? parseInt(this.selectedUser.state_id)
                            : null;
                        this.formUser.city_id = this.selectedUser
                            ? parseInt(this.selectedUser.city_id)
                            : null;
                    }

                    this.placeholderState =
                        response.data.length > 0
                            ? "Select state"
                            : "Data empty";
                    this.listState = response.data;
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
                    const response = await this.$getBrother(value);
                    this.listCity = [];
                    if (value && change) {
                        this.formUser.city_id = null;
                    } else {
                        this.formUser.city_id = this.selectedUser
                            ? parseInt(this.selectedUser.city_id)
                            : null;
                    }

                    this.placeholderCity =
                        response.data.length > 0 ? "Select city" : "Data empty";
                    this.listCity = response.data;
                    resolve();
                } catch (error) {
                    console.error("Error in onChangeState:", error);
                    reject(error);
                }
            });
        },
        onChangeRole(value, change) {
            let response = this.listRoles.find((item) => item.id == value);
            if (response && response.name) {
                if (response.name == "ros_client_manager") {
                    this.isRosClientGlobal = true;
                    this.fetchUsersByRole("ros_client");
                } else {
                    this.formUser.ros_clients = [];
                    this.isRosClientGlobal = false;
                }
            }
        },
        handleDialogClose() {
            this.visible = false;
            this.$emit("hidden", this.visible);
        },
        clearError(field) {
            this.errors[field] = "";
        },
        setPhotos() {
            let images = [];
            this.selectedUser.photos.forEach((item) => {
                images.push(item);
            });
            this.formUser.photos = images;
        },
        onFileUpload() {
            const file_upload = this.$refs.fileUpload;
            if (file_upload && file_upload.files.length <= 1) {
                this.formUser.photos = [];
                for (let i = 0; i < file_upload.files.length; i++) {
                    const file = file_upload.files[i];
                    if (file.type && file.type.startsWith("image/")) {
                        this.formUser.photos.push(file);
                    }
                }
            } else {
                this.errors.photos = "You can upload up to 1 photos only.";
            }
        },
        onFileRemove(event) {
            const fileToRemove = event.file;
            this.formUser.photos = this.formUser.photos.filter(
                (photo) => photo.name !== fileToRemove.name
            );
        },
        handleFileChange(event, index, id) {
            const file = event.target.files[0];
            if (file && !file.type.startsWith("image/")) {
                this.$alertWarning("format not allowed!");
                return;
            }
            if (file && index !== undefined) {
                this.$axios
                    .post(
                        "/users/photo/add",
                        {
                            user_id: id,
                            photo: file,
                        },
                        {
                            headers: {
                                "Content-Type": "multipart/form-data",
                            },
                        }
                    )
                    .then((response) => {
                        this.$alertSuccess("Photo add!");
                        this.formUser.photos.push(response.data.data);
                        this.$emit("reloadTable", true);
                    })
                    .catch((error) => {
                        this.$readStatusHttp(error);
                    });
            }
            event.target.value = "";
        },
        removePhoto(index, idAttachment) {
            this.$axios
                .post("/users/photo/delete", {
                    attachment_id: idAttachment,
                })
                .then((response) => {
                    this.$alertSuccess("Photo deleted!");
                    this.formUser.photos.splice(index, 1);
                    this.$emit("reloadTable", true);
                })
                .catch((error) => {
                    this.$readStatusHttp(error);
                });
        },
        getImageSource(photo) {
            if (photo instanceof File) {
                return URL.createObjectURL(photo);
            }
            return photo;
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
            this.formUser.password = password;
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
    width: 640px;
    height: 400px;
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

.phone-wrapper > .iti {
    width: 100% !important;
}
</style>
