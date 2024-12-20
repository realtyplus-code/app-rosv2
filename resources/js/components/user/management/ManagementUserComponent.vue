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
                <FloatLabel>
                    <InputText
                        id="name"
                        class="inputtext-custom"
                        :class="{ 'p-invalid': errors.name }"
                        v-model="formProperty.name"
                        @input="clearError('name')"
                    />
                    <label for="name">Name</label>
                </FloatLabel>
                <small v-if="errors.name" class="p-error">{{
                    errors.name
                }}</small>
            </div>
        </div>
        <div class="custom-form">
            <div class="custom-form-column">
                <FloatLabel>
                    <InputText
                        id="address"
                        class="inputtext-custom"
                        :class="{ 'p-invalid': errors.address }"
                        v-model="formProperty.address"
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
                <Select
                    :options="listPropertyType"
                    v-model="formProperty.property_type_id"
                    placeholder="Select property type"
                    :class="{ 'p-invalid': errors.property_type_id }"
                    optionLabel="name"
                    optionValue="id"
                    style="width: 100%"
                />
                <small v-if="errors.property_type_id" class="p-error">{{
                    errors.property_type_id
                }}</small>
            </div>
        </div>
        <div class="custom-form">
            <div class="custom-form-column">
                <Select
                    :options="listStatus"
                    v-model="formProperty.status"
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
        <div class="custom-form">
            <div class="custom-form-column">
                <MultiSelect
                    :options="listOwners"
                    v-model="formProperty.owners"
                    filter
                    placeholder="Select owners"
                    :class="{ 'p-invalid': errors.owners }"
                    :maxSelectedLabels="limitOwners"
                    optionLabel="name"
                    optionValue="id"
                    style="width: 100%"
                />
                <small v-if="errors.owners" class="p-error">{{
                    errors.owners
                }}</small>
            </div>
        </div>
        <div class="custom-form">
            <div class="custom-form-column">
                <MultiSelect
                    :options="listTenants"
                    v-model="formProperty.tenants"
                    filter
                    placeholder="Select tenants"
                    :class="{ 'p-invalid': errors.tenants }"
                    :maxSelectedLabels="limitTenants"
                    optionLabel="name"
                    optionValue="id"
                    style="width: 100%"
                />
                <small v-if="errors.tenants" class="p-error">{{
                    errors.tenants
                }}</small>
            </div>
        </div>
        <hr />
        <div class="custom-form mt-4">
            <div v-if="!selectedProperty" class="custom-form-column">
                <FileUpload
                    id="attachPhoto"
                    ref="fileUpload"
                    accept="image/*"
                    :multiple="true"
                    :fileLimit="4"
                    :class="{
                        'p-invalid': errors.photos,
                    }"
                    @change="onFileUpload"
                    @remove="onFileRemove"
                >
                    <template #empty>
                        <p>Search photos (Max 4)</p>
                    </template>
                </FileUpload>
                <small
                    v-if="errors.photos"
                    style="display: block"
                    class="p-error"
                    >{{ errors.photos }}</small
                >
            </div>
            <div v-else class="gallery" style="margin-bottom: 20px">
                <div class="gallery-grid">
                    <div
                        v-for="(photo, index) in formProperty.photos"
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
                                v-if="formProperty.photos.length > 1"
                                class="p-button-danger mt-2"
                                style="
                                    width: 100%;
                                    border-radius: 0px 0px 10px 10px;
                                "
                                @click="
                                    removePhoto(index, photo, formProperty.id)
                                "
                            >
                                Delete
                            </button>
                        </div>
                    </div>
                    <div
                        v-if="formProperty.photos.length < 4"
                        class="gallery-item"
                    >
                        <div class="card p-card gallery-card">
                            <input
                                type="file"
                                @change="
                                    handleFileChange(
                                        $event,
                                        formProperty.photos.length,
                                        formProperty.id
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
                    v-if="!selectedProperty"
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
import Dialog from "primevue/dialog";
import Select from "primevue/select";
import Button from "primevue/button";

export default {
    props: ["dialogVisible", "selectedProperty"],
    data() {
        return {
            visible: this.dialogVisible,
            formProperty: {
                id: null,
                name: null,
                address: null,
                property_type_id: null,
                status: null,
                owners: [],
                tenants: [],
                photos: [],
            },
            errors: {},
            listPropertyType: [],
            listStatus: [],
            listOwners: [],
            listTenants: [],
            // limite de seleccion
            limitOwners: 10,
            limitTenants: 10,
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
    },
    watch: {},
    mounted() {
        this.$nextTick(() => {
            if (this.selectedProperty) {
                const currentOwnersName = this.$parseTags(
                    this.selectedProperty.owners_name
                );
                const currentTenantsName = this.$parseTags(
                    this.selectedProperty.tenants_name
                );
                this.formProperty.id = this.selectedProperty.id;
                this.formProperty.name = this.selectedProperty.name;
                this.formProperty.address = this.selectedProperty.address;
                this.formProperty.property_type_id =
                    this.selectedProperty.property_type_id;
                this.formProperty.status = parseInt(
                    this.selectedProperty.status
                );
                this.formProperty.owners = currentOwnersName.map(
                    (owner) => owner.id
                );
                this.formProperty.tenants = currentTenantsName.map(
                    (tenat) => tenat.id
                );
                this.setPhotos();
            }
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
            const comboNames = ["property_type"];
            const response = await this.$getEnumsOptions(comboNames);
            const { property_type: responPropertyType } = response.data;
            this.listPropertyType = responPropertyType;
            // obtenemos los usuarios
            const { data: owners } = await this.getUsers("owners");
            this.listOwners = owners;
            const { data: tenants } = await this.getUsers("tenants");
            this.listTenants = tenants;
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
                name: Yup.string().required("Type name is required"),
                address: Yup.string().required("Type address is required"),
                status: Yup.string().required("Type owners is required"),
                owners: Yup.array()
                    .of(Yup.string().required("Each owner must be a string"))
                    .min(1, "At least one owner is required")
                    .required("Owners array is required"),
                tenants: Yup.array()
                    .of(Yup.string().required("Each tenant must be a string"))
                    .min(1, "At least one tenant is required")
                    .required("Tenants array is required"),
                property_type_id: Yup.string().required(
                    "Type property is required"
                ),
                photos: Yup.array()
                    .min(1, "At least 1 photo is required")
                    .max(4, "You can upload up to 4 photos")
                    .required("Photo is required"),
            };
            const schema = Yup.object().shape({
                ...initialRules,
            });
            this.errors = {};
            try {
                await schema.validate(this.formProperty, {
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
                this.$axios
                    .post("/properties/store", this.formProperty, {
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
                        `/properties/update/${this.selectedProperty.id}`,
                        this.formProperty,
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
            this.formProperty.photos = [null, null, null, null];
            const possiblePhotos = [
                this.selectedProperty.photo,
                this.selectedProperty.photo1,
                this.selectedProperty.photo2,
                this.selectedProperty.photo3,
            ];
            possiblePhotos.forEach((photo) => {
                for (let i = 0; i < this.formProperty.photos.length; i++) {
                    if (this.formProperty.photos[i] === null && photo) {
                        this.formProperty.photos[i] = photo;
                        break;
                    }
                }
            });
            this.formProperty.photos = this.formProperty.photos.filter(
                (photo) => photo !== null
            );
        },
        onFileUpload() {
            const file_upload = this.$refs.fileUpload;
            if (file_upload && file_upload.files.length <= 4) {
                this.formProperty.photos = [];
                for (let i = 0; i < file_upload.files.length; i++) {
                    const file = file_upload.files[i];
                    if (file.type && file.type.startsWith("image/")) {
                        this.formProperty.photos.push(file);
                    }
                }
            } else {
                this.errors.photos = "You can upload up to 4 photos only.";
            }
        },
        onFileRemove(event) {
            const fileToRemove = event.file;
            this.formProperty.photos = this.formProperty.photos.filter(
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
                        "/properties/photo/add",
                        {
                            property_id: id,
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
                        this.formProperty.photos[index] = response.data.data;
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
                .post("/properties/photo/delete", {
                    property_id: id,
                    photo: name,
                })
                .then((response) => {
                    this.$alertSuccess("Photo deleted!");
                    this.formProperty.photos.splice(index, 1);
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
