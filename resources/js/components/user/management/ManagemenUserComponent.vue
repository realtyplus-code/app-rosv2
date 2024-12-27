<template>
    <Dialog
        v-model:visible="visible"
        modal
        class="dialog-management"
        :style="{ width: '45rem' }"
        :draggable="false"
    >
        <template #header>
            <h3>Management Property</h3>
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
                        id="name"
                        class="inputtext-custom"
                        :class="{ 'p-invalid': errors.name }"
                        v-model="formUser.name"
                        style="width: 100%;"
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
                        style="width: 100%;"
                        @input="clearError('email')"
                    />
                    <label for="email">Email</label>
                </FloatLabel>
                <small v-if="errors.email" class="p-error">{{
                    errors.email
                }}</small>
            </div>
        </div>
        <div class="custom-form mt-4" v-if="!this.selectedUser">
            <div class="custom-form-column">
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
                <small v-if="errors.password" class="p-error">{{
                    errors.password
                }}</small>
                <Button
                    icon="pi pi-key"
                    class="mt-2"
                    @click="generateRandomPassword"
                />
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
                                :src="getImageSource(photo)"
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
                                @click="removePhoto(index, photo, formUser.id)"
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
import MultiSelect from "primevue/multiselect";
import FloatLabel from "primevue/floatlabel";
import FileUpload from "primevue/fileupload";
import InputText from "primevue/inputtext";
import Password from "primevue/password";
import Dialog from "primevue/dialog";
import Select from "primevue/select";
import Button from "primevue/button";

import "intl-tel-input/build/css/intlTelInput.css";
import intlTelInput from "intl-tel-input";

export default {
    props: ["dialogVisible", "selectedUser"],
    data() {
        return {
            visible: this.dialogVisible,
            formUser: {
                id: null,
                name: null,
                email: null,
                phone: null,
                code_number: null,
                code_country: null,
                photos: [],
                role: null,
            },
            errors: {},
            phoneInput: null,
            listRoles: [],
            isLoad: false,
        };
    },
    components: {
        FloatLabel,
        Dialog,
        Select,
        InputText,
        Button,
        MultiSelect,
        FileUpload,
        Password,
    },
    watch: {},
    mounted() {
        this.$nextTick(() => {
            if (this.selectedUser) {
                this.formUser.id = this.selectedUser.id;
                this.formUser.name = this.selectedUser.name;
                this.formUser.email = this.selectedUser.email;
                this.formUser.phone = this.selectedUser.phone;
                this.formUser.code_number = this.selectedUser.code_number;
                this.formUser.code_country = this.selectedUser.code_country;
                this.formUser.role = this.selectedUser.roles[0].id;
                this.setPhotos();
            }

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
        async initServices() {
            const rols = await this.getRoles();
            if (rols) {
                this.listRoles = rols.data;
            }
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
        async validateForm() {
            this.dynamicRules = {};
            let initialRules = {
                name: Yup.string().required("Type name is required"),
                email: Yup.string()
                    .email("The email format is not valid")
                    .required("Type email is required"),
                phone: Yup.string().required("Type phone is required"),
                photos: Yup.array()
                    .min(1, "At least 1 photo is required")
                    .max(1, "You can upload up to 1 photos")
                    .required("Photo is required"),
                role: Yup.string().required("Role is required"),
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
            console.log(this.errors);
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
        handleDialogClose() {
            this.visible = false;
            this.$emit("hidden", this.visible);
        },
        clearError(field) {
            this.errors[field] = "";
        },
        setPhotos() {
            this.formUser.photos = [null, null, null, null];
            const possiblePhotos = [this.selectedUser.photo];
            possiblePhotos.forEach((photo) => {
                for (let i = 0; i < this.formUser.photos.length; i++) {
                    if (this.formUser.photos[i] === null && photo) {
                        this.formUser.photos[i] = photo;
                        break;
                    }
                }
            });
            this.formUser.photos = this.formUser.photos.filter(
                (photo) => photo !== null
            );
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
                        this.formUser.photos[index] = response.data.data;
                        this.$emit("reloadTable", true);
                    })
                    .catch((error) => {
                        this.$readStatusHttp(error);
                    });
            }
            event.target.value = "";
        },
        removePhoto(index, name, id) {
            this.$axios
                .post("/users/photo/delete", {
                    user_id: id,
                    photo: name,
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

.phone-wrapper>.iti {
    width: 100% !important;
}
</style>
