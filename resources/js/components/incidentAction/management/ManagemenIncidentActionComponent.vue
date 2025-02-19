<template>
    <Dialog
        v-model:visible="visible"
        modal
        class="dialog-management"
        :style="{ width: '45rem' }"
        :draggable="false"
    >
        <template #header>
            <h3>Management Incident Action</h3>
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
                        id="action_description"
                        class="inputtext-custom"
                        :class="{ 'p-invalid': errors.action_description }"
                        v-model="formIncident.action_description"
                        @input="clearError('action_description')"
                        :maxlength="1000"
                        rows="5"
                    />
                    <label for="action_description">Description</label>
                </FloatLabel>
                <small v-if="errors.action_description" class="p-error">{{
                    errors.action_description
                }}</small>
                <small>{{ characterCount }}/1000</small>
            </div>
        </div>
        <br />
        <p v-if="roleName != 'provider'">Select type responsible</p>
        <div class="custom-form" v-if="roleName != 'provider'">
            <ToggleButton
                v-model="isTypeUser"
                class="w-20"
                onLabel="Users"
                offLabel="Providers"
            />
        </div>
        <div class="custom-form" v-if="roleName != 'provider'">
            <div class="custom-form-column" v-if="isTypeUser">
                <Select
                    filter
                    :options="listOtherUsers"
                    v-model="formIncident.responsible_user_id"
                    placeholder="Select user"
                    :class="{ 'p-invalid': errors.responsible_user_id }"
                    optionLabel="name"
                    optionValue="id"
                    style="width: 100%"
                />
                <small v-if="errors.responsible_user_id" class="p-error">{{
                    errors.responsible_user_id
                }}</small>
            </div>
            <div class="custom-form-column" v-else>
                <Select
                    filter
                    :options="listProviders"
                    v-model="formIncident.responsible_user_id"
                    placeholder="Select provider"
                    :class="{ 'p-invalid': errors.responsible_user_id }"
                    optionLabel="name"
                    optionValue="id"
                    style="width: 100%"
                />
                <small v-if="errors.responsible_user_id" class="p-error">{{
                    errors.responsible_user_id
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
                    optionLabel="valor1"
                    optionValue="id"
                    style="width: 100%"
                    :disabled="isViewCurrency"
                />
                <small v-if="errors.currency_id" class="p-error">{{
                    errors.currency_id
                }}</small>
            </div>
            <div class="custom-form-column">
                <FloatLabel>
                    <InputNumber
                        id="action_cost"
                        class="inputtext-custom"
                        :class="{ 'p-invalid': errors.action_cost }"
                        v-model="formIncident.action_cost"
                        @input="clearError('action_cost')"
                        :currency="$globals.CURRENCY_TYPES.EUR"
                    />
                    <label for="action_cost">Cost</label>
                </FloatLabel>
                <small v-if="errors.action_cost" class="p-error">{{
                    errors.action_cost
                }}</small>
            </div>
        </div>
        <div class="custom-form">
            <div class="custom-form-column">
                <Select
                    filter
                    :options="listActionType"
                    v-model="formIncident.action_type_id"
                    placeholder="Select type"
                    :class="{ 'p-invalid': errors.action_type_id }"
                    optionLabel="valor1"
                    optionValue="id"
                    style="width: 100%"
                />
                <small v-if="errors.action_type_id" class="p-error">{{
                    errors.action_type_id
                }}</small>
            </div>
            <div class="custom-form-column">
                <Select
                    filter
                    :options="listActionStatus"
                    v-model="formIncident.status_id"
                    placeholder="Select status"
                    :class="{ 'p-invalid': errors.status_id }"
                    optionLabel="valor1"
                    optionValue="id"
                    style="width: 100%"
                />
                <small v-if="errors.status_id" class="p-error">{{
                    errors.status_id
                }}</small>
            </div>
            <div class="custom-form-column">
                <FloatLabel>
                    <DatePicker
                        showIcon
                        fluid
                        id="action_date"
                        class="inputtext-custom"
                        :class="{ 'p-invalid': errors.action_date }"
                        v-model="formIncident.action_date"
                        @input="clearError('action_date')"
                        :show-time="true"
                        :hourFormat="'24'"
                    />
                    <label for="action_date">Report Date</label>
                </FloatLabel>
                <small v-if="errors.action_date" class="p-error">{{
                    errors.action_date
                }}</small>
            </div>
        </div>
        <hr />
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
import moment from "moment";

export default {
    props: [
        "dialogVisible",
        "selectedIncident",
        "selectedIncidentId",
        "selectedPropertyCountry",
        "role",
    ],
    data() {
        return {
            roleName: this.role[0],
            visible: this.dialogVisible,
            formIncident: {
                id: null,
                incident_id: null,
                action_date: new Date(),
                responsible_user_id: null,
                responsible_user_type: null,
                action_description: null,
                action_cost: 0,
                photos: [],
                action_type_id: null,
                status_id: null,
                currency_id: null,
            },
            errors: {},
            listOtherUsers: [],
            listProviders: [],
            listActionType: [],
            listActionStatus: [],
            listCurrency: [],
            isLoad: false,
            isTypeUser: false, //true: providers, false: others users
            isViewCurrency: false,
        };
    },
    components: {},
    computed: {
        characterCount() {
            return this.formIncident.action_description
                ? this.formIncident.action_description.length
                : 0;
        },
    },
    watch: {
        async isTypeUser(status) {
            await this.checkTypeUser(status);
        },
        "formIncident.action_date"(newValue) {
            this.formIncident.action_date =
                moment(newValue).format("Y-MM-DD HH:mm");
        },
    },
    mounted() {
        this.$nextTick(async () => {
            this.setForm();
        });
    },
    created() {
        this.initServices();
    },
    methods: {
        async setForm() {
            if (this.selectedIncident) {
                this.formIncident.id = this.selectedIncident.id;
                this.formIncident.incident_id =
                    this.selectedIncident.incident_id;
                this.formIncident.action_date = new Date(
                    this.selectedIncident.action_date
                );
                this.formIncident.action_description =
                    this.selectedIncident.action_description;
                this.formIncident.action_cost =
                    this.selectedIncident.action_cost;
                this.formIncident.responsible_user_type =
                    this.selectedIncident.responsible_type;

                this.isTypeUser =
                    this.selectedIncident.responsible_type === "USER";
                this.formIncident.responsible_user_id = this.isTypeUser
                    ? this.selectedIncident.user_id
                    : this.selectedIncident.provider_id;

                this.formIncident.currency_id =
                    this.selectedIncident.currency_id;
                this.formIncident.action_type_id =
                    this.selectedIncident.action_type_id;
                this.formIncident.status_id = this.selectedIncident.status_id;

                await this.checkTypeUser(this.isTypeUser);
                this.setPhotos();
            } else {
                if (this.selectedPropertyCountry) {
                    const response = await this.$getCountryRelation(
                        this.selectedPropertyCountry,
                        "currency"
                    );
                    if (response.data) {
                        this.isViewCurrency = true;
                        this.formIncident.currency_id = parseInt(
                            response.data.related_id
                        );
                    }
                }
            }
        },
        async checkTypeUser(status) {
            this.listOtherUsers = [];
            this.listProviders = [];
            if (status) {
                this.formIncident.responsible_user_type = "USER";
                // obtenemos los usuarios
                const { data: owners } = await this.getUsers("owner");
                let listOwners = owners;
                const { data: tenants } = await this.getUsers("tenant");
                let listTenants = tenants;
                const combinedList = [...listOwners, ...listTenants].map(
                    (user) => ({
                        id: user.id,
                        name: user.name,
                    })
                );
                this.listOtherUsers = combinedList.sort((a, b) =>
                    a.name.localeCompare(b.name)
                );
            } else {
                this.formIncident.responsible_user_type = "PROVIDER";
                const { data: provider } = await this.getProviders();
                this.listProviders = provider;
            }
        },
        async initServices() {
            this.formIncident.responsible_user_type = "PROVIDER";
            const { data: provider } = await this.getProviders();
            this.listProviders = provider;

            const comboNames = ["action_status", "action_type", "currency"];
            const response = await this.$getEnumsOptions(comboNames);
            const {
                action_status: respActionStatus,
                action_type: respActionType,
                currency: respCurrency,
            } = response.data;
            this.listActionStatus = respActionStatus;
            this.listActionType = respActionType;
            this.listCurrency = respCurrency;
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
                action_date: Yup.string().required("Action date is required"),
                responsible_user_id: Yup.string().required(
                    "Responsible is required"
                ),
                action_description: Yup.string().required(
                    "Description is required"
                ),
                action_cost: Yup.number()
                    .nullable()
                    .transform((value, originalValue) =>
                        String(originalValue).trim() === "" ? null : value
                    )
                    .typeError("Cost must be a number"),
                status_id: Yup.string().required("Status is required"),
                action_type_id: Yup.string().required("Type is required"),
                currency_id: Yup.string().required("Currency is required"),
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
                this.formIncident.incident_id = this.selectedIncidentId;
                this.$axios
                    .post("/occurrences-action/store", this.formIncident, {
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
                        `/occurrences-action/update/${this.selectedIncident.id}`,
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
                        "/occurrences-action/photo/add",
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
                .post("/occurrences-action/photo/delete", {
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
