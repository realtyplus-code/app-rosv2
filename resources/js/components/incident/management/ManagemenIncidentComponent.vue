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
                <Select
                    filter
                    :options="listProperty"
                    v-model="formIncident.property_id"
                    placeholder="Select property"
                    :class="{ 'p-invalid': errors.property_id }"
                    optionLabel="name"
                    optionValue="id"
                    style="width: 100%"
                    @clear="clearProperty('property_id')"
                />
                <small v-if="errors.property_id" class="p-error">{{
                    errors.property_id
                }}</small>
            </div>
        </div>
        <div class="custom-form">
            <div class="custom-form-column">
                <Select
                    filter
                    :options="listCurrency"
                    v-model="formIncident.currency_id"
                    placeholder="Select currency"
                    :class="{ 'p-invalid': errors.currency_id }"
                    optionLabel="name"
                    optionValue="id"
                    style="width: 100%"
                />
                <small v-if="errors.currency_id" class="p-error">{{
                    errors.currency_id
                }}</small>
            </div>
            <div class="custom-form-column">
                <FloatLabel>
                    <InputNumber
                        id="cost"
                        class="inputtext-custom"
                        :class="{ 'p-invalid': errors.cost }"
                        v-model="formIncident.cost"
                        @input="clearError('cost')"
                        :currency="$globals.CURRENCY_TYPES.EUR"
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
                        showIcon
                        fluid
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
                    placeholder="Select type"
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
        <div class="custom-form mt-4">
            <div v-if="!selectedIncident" class="custom-form-column">
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
                        v-for="(photo, index) in formIncident.photos"
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
                                v-if="formIncident.photos.length > 0"
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
                        v-if="formIncident.photos.length < 4"
                        class="gallery-item"
                    >
                        <div class="card p-card gallery-card">
                            <input
                                type="file"
                                @change="
                                    handleFileChange(
                                        $event,
                                        formIncident.photos.length,
                                        formIncident.id
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
                    v-if="!selectedIncident"
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
                currency_id: null,
                providers: [],
                cost: null,
                payer_id: null,
                photos: [],
            },
            errors: {},
            listIncidentType: [],
            listPriority: [],
            listStatus: [],
            listPayer: [],
            listOwners: [],
            listProviders: [],
            listCurrency: [],
            listProperty: [],
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
                const currentProvidersName = this.$parseTags(
                    this.selectedIncident.provider_name
                );
                this.formIncident.id = this.selectedIncident.id;
                this.formIncident.description =
                    this.selectedIncident.description;
                this.formIncident.incident_type_id =
                    this.selectedIncident.type_id;
                this.formIncident.priority_id =
                    this.selectedIncident.priority_id;
                this.formIncident.cost = this.selectedIncident.cost;
                this.formIncident.currency_id =
                    this.selectedIncident.currency_id;
                this.formIncident.payer_id = this.selectedIncident.payer_id;
                this.formIncident.property_id =
                    this.selectedIncident.property_id;
                this.formIncident.report_date = new Date(
                    this.selectedIncident.report_date
                );
                this.formIncident.status_id = this.selectedIncident.status_id;
                this.formIncident.providers = currentProvidersName.map(
                    (provider) => provider.id
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
            await this.getProperties();
            const comboNames = [
                "incident_type",
                "priority",
                "payer",
                "incident_status",
                "currency",
            ];
            const response = await this.$getEnumsOptions(comboNames);
            const {
                incident_type: responIncidentType,
                priority: responPriority,
                payer: responPayer,
                incident_status: responStatus,
                currency: responCurrency,
            } = response.data;
            this.listIncidentType = responIncidentType;
            this.listPriority = responPriority;
            this.listStatus = responStatus;
            this.listPayer = responPayer;
            this.listCurrency = responCurrency;
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
        async getProperties() {
            try {
                const response = await this.$axios.get("/properties/list");
                this.listProperty = response.data.data;
            } catch (error) {
                this.$readStatusHttp(error);
            }
        },
        async validateForm() {
            let initialRules = {
                description: Yup.string().required(
                    "Incident description is required"
                ),
                incident_type_id: Yup.string().required(
                    "Incident type is required"
                ),
                property_id: Yup.string().required("Propiedad is required"),
                priority_id: Yup.string().required("Priority is required"),
                payer_id: Yup.string().required("Payer is required"),
                report_date: Yup.string().required("Report date is required"),
                status_id: Yup.string().required("Status is required"),
                /* providers: Yup.array().min(
                    1,
                    "At least one provider is required"
                ), */
                /* photos: Yup.array()
                    .min(1, "At least 1 photo is required")
                    .max(4, "You can upload up to 4 photos")
                    .required("Photo is required"), */
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
                if (this.selectedPropertyId) {
                    this.formIncident.property_id = this.selectedPropertyId;
                }
                this.$axios
                    .post("/occurrences/store", this.formIncident, {
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
                        `/occurrences/update/${this.selectedIncident.id}`,
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
        setPhotos() {
            let images = [];
            this.selectedIncident.photos.forEach((item) => {
                images.push(item);
            });
            this.formIncident.photos = images;
        },
        onFileUpload() {
            const file_upload = this.$refs.fileUpload;
            if (file_upload && file_upload.files.length <= 4) {
                this.formIncident.photos = [];
                for (let i = 0; i < file_upload.files.length; i++) {
                    const file = file_upload.files[i];
                    if (file.type && file.type.startsWith("image/")) {
                        this.formIncident.photos.push(file);
                    }
                }
            } else {
                this.errors.photos = "You can upload up to 4 photos only.";
            }
        },
        onFileRemove(event) {
            const fileToRemove = event.file;
            this.formIncident.photos = this.formIncident.photos.filter(
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
                        "/occurrences/photo/add",
                        {
                            incident_id: id,
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
                        this.formIncident.photos.push(response.data.data);
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
                .post("/occurrences/photo/delete", {
                    attachment_id: idAttachment,
                })
                .then((response) => {
                    this.$alertSuccess("Photo deleted!");
                    this.formIncident.photos.splice(index, 1);
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
        clearProperty(field) {
            this.formIncident[field] = null;
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
