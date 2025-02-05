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
                    filter
                    :options="listInsurances"
                    v-model="formProperty.insurances"
                    placeholder="Select insurances"
                    :class="{ 'p-invalid': errors.insurances }"
                    optionLabel="insurance_company"
                    optionValue="id"
                    style="width: 100%"
                />
                <small v-if="errors.insurances" class="p-error">{{
                    errors.insurances
                }}</small>
            </div>
        </div>
        <div class="custom-form mt-4">
            <div class="custom-form-column" style="margin-top: 10px">
                <Select
                    :options="listCountry"
                    v-model="formProperty.country"
                    placeholder="Select country"
                    :class="{ 'p-invalid': errors.country }"
                    optionLabel="name"
                    optionValue="id"
                    style="width: 100%"
                    @change="onChangeCountry(formProperty.country, true)"
                />
                <small v-if="errors.country" class="p-error">{{
                    errors.country
                }}</small>
            </div>
            <div class="custom-form-column" style="margin-top: 10px">
                <Select
                    :options="listState"
                    v-model="formProperty.state"
                    :placeholder="placeholderState"
                    :class="{ 'p-invalid': errors.state }"
                    optionLabel="name"
                    optionValue="id"
                    style="width: 100%"
                    @change="onChangeState(formProperty.state, true)"
                />
                <small v-if="errors.state" class="p-error">{{
                    errors.state
                }}</small>
            </div>
            <div class="custom-form-column" style="margin-top: 10px">
                <Select
                    :options="listCity"
                    v-model="formProperty.city"
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
        <div class="custom-form">
            <div class="custom-form-column">
                <Select
                    filter
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
                <Select
                    filter
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
        <div class="custom-form">
            <div class="custom-form-column">
                <Select
                    filter
                    showClear
                    :options="listClientRos"
                    v-model="formProperty.client_ros_id"
                    placeholder="Select client ros"
                    :class="{ 'p-invalid': errors.client_ros_id }"
                    optionLabel="name"
                    optionValue="id"
                    style="width: 100%"
                />
                <small v-if="errors.client_ros_id" class="p-error">{{
                    errors.client_ros_id
                }}</small>
            </div>
        </div>
        <div class="custom-form">
            <div class="custom-form-column">
                <FloatLabel>
                    <DatePicker
                        showIcon
                        fluid
                        id="expected_end_date_ros"
                        class="inputtext-custom"
                        :class="{ 'p-invalid': errors.expected_end_date_ros }"
                        v-model="formProperty.expected_end_date_ros"
                        @input="clearError('expected_end_date_ros')"
                    />
                    <label for="expected_end_date_ros">Expected End</label>
                </FloatLabel>
                <small v-if="errors.expected_end_date_ros" class="p-error">{{
                    errors.expected_end_date_ros
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
                                :src="getImageSource(photo.file_path)"
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
                                @click="removePhoto(index, photo.id)"
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
                    style="margin-right: 10px; border-color: #f76f31"
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
                insurances: [],
                photos: [],
                country: null,
                state: null,
                city: null,
                expected_end_date_ros: null,
                client_ros_id: null,
            },
            errors: {},
            listPropertyType: [],
            listStatus: [],
            listOwners: [],
            listTenants: [],
            listClientRos: [],
            // limite de seleccion
            limitOwners: 10,
            limitTenants: 10,
            isLoad: false,
            listCity: [],
            listCountry: [],
            listState: [],
            listInsurances: [],
            placeholderCity: "Select the state first",
            placeholderState: "Select the country first",
        };
    },
    components: {},
    watch: {},
    mounted() {
        this.$nextTick(async () => {
            await this.initForm();
        });
    },
    created() {
        this.initServices();
    },
    methods: {
        async initForm() {
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
                this.formProperty.country = parseInt(
                    this.selectedProperty.country_id
                );
                this.formProperty.state = parseInt(
                    this.selectedProperty.state_id
                );
                this.formProperty.city = parseInt(
                    this.selectedProperty.city_id
                );
                this.formProperty.property_type_id =
                    this.selectedProperty.property_type_id;
                this.formProperty.status = parseInt(
                    this.selectedProperty.status
                );
                this.formProperty.client_ros_id = parseInt(
                    this.selectedProperty.user_ros_id
                );
                this.formProperty.owners = currentOwnersName.map(
                    (owner) => owner.id
                );
                this.formProperty.tenants = currentTenantsName.map(
                    (tenat) => tenat.id
                );
                this.formProperty.insurances =
                    this.selectedProperty.insurances_id;
                this.setDate();
                this.setPhotos();
                if (this.selectedProperty.country_id) {
                    await this.onChangeCountry(
                        this.selectedProperty.country_id,
                        false
                    );
                    if (this.selectedProperty.state_id) {
                        await this.onChangeState(
                            this.selectedProperty.state_id,
                            false
                        );
                    }
                }
            }
        },
        async initServices() {
            this.listStatus = [
                { id: 1, name: "active" },
                { id: 2, name: "inactive" },
            ];
            const comboNames = ["country", "property_type"];
            const response = await this.$getEnumsOptions(comboNames);
            const {
                country: responsCountry,
                property_type: responPropertyType,
            } = response.data;
            this.listPropertyType = responPropertyType;
            this.listCountry = responsCountry;
            // obtenemos los usuarios
            this.getUsers("owner");
            this.getUsers("tenant");
            this.getUsers("ros_client");
            this.getInsurances();
        },
        getUsers(role = null) {
            const params = role ? { params: { role } } : {};
            this.$axios
                .get(`/users/list`, params)
                .then((response) => {
                    if (role === "owner") {
                        this.listOwners = response.data.data;
                    } else if (role === "tenant") {
                        this.listTenants = response.data.data;
                    } else if (role === "ros_client") {
                        this.listClientRos = response.data.data;
                    }
                })
                .catch((error) => {
                    this.$readStatusHttp(error);
                });
        },
        getInsurances() {
            this.$axios
                .get(`/insurances/list`)
                .then((response) => {
                    this.listInsurances = response.data.data;
                })
                .catch((error) => {
                    this.$readStatusHttp(error);
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
                property_type_id: Yup.string().required(
                    "Type property is required"
                ),
                photos: Yup.array()
                    .min(1, "At least 1 photo is required")
                    .max(4, "You can upload up to 4 photos")
                    .required("Photo is required"),
                country: Yup.string().required("Country is required"),
                state: Yup.string().required("State is required"),
                city: Yup.string().required("City is required"),
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
        onChangeCountry(value, change) {
            return new Promise(async (resolve, reject) => {
                try {
                    const response = await this.$getBrother(value);
                    this.listState = [];
                    this.listCity = [];
                    if (value && change) {
                        this.formProperty.state_id = null;
                        this.formProperty.city_id = null;
                    } else {
                        this.formProperty.state_id = this.selectedProperty
                            ? parseInt(this.selectedProperty.state_id)
                            : null;
                        this.formProperty.city_id = this.selectedProperty
                            ? parseInt(this.selectedProperty.city_id)
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
                        this.formProperty.city_id = null;
                    } else {
                        this.formProperty.city_id = this.selectedProperty
                            ? parseInt(this.selectedProperty.city_id)
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
        handleDialogClose() {
            this.visible = false;
            this.$emit("hidden", this.visible);
        },
        clearError(field) {
            this.errors[field] = "";
        },
        setPhotos() {
            let images = [];
            this.selectedProperty.photos.forEach((item) => {
                images.push(item);
            });
            this.formProperty.photos = images;
        },
        setDate() {
            if (this.selectedProperty.expected_end_date_ros) {
                this.formProperty.expected_end_date_ros = new Date(
                    this.selectedProperty.expected_end_date_ros
                );
                this.formProperty.expected_end_date_ros.setDate(
                    this.formProperty.expected_end_date_ros.getDate() + 1
                );
            }
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
                        this.formProperty.photos.push(response.data.data);
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
                .post("/properties/photo/delete", {
                    attachment_id: idAttachment,
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
