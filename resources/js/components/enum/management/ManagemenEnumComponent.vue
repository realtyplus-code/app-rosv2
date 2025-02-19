<template>
    <Dialog
        v-model:visible="visible"
        modal
        class="dialog-management-custom"
        :style="{ width: '60rem' }"
        :draggable="false"
    >
        <template #header>
            <h3>Gestionar Opcion</h3>
        </template>
        <div class="custom-form">
            <div class="custom-form-column">
                <Select
                    filter
                    :options="options"
                    v-model="formEnum.parent_id"
                    placeholder="Selecciona opcion"
                    :class="{ 'p-invalid': errors.parent_id }"
                    optionLabel="name"
                    optionValue="id"
                    style="width: 100%"
                    disabled
                />
                <small v-if="errors.parent_id" class="p-error">{{
                    errors.parent_id
                }}</small>
            </div>
        </div>
        <div v-if="isRelation == 'city'" class="custom-form mt-3">
            <div class="custom-form-column">
                <label>State</label>
                <Select
                    filter
                    :options="listState"
                    v-model="formEnum.brother_relation_id"
                    placeholder="Select state"
                    :class="{ 'p-invalid': errors.brother_relation_id }"
                    optionLabel="name"
                    optionValue="id"
                    style="width: 100%"
                />
                <small v-if="errors.brother_relation_id" class="p-error">{{
                    errors.brother_relation_id
                }}</small>
            </div>
        </div>
        <div v-if="isRelation == 'state'" class="custom-form mt-3">
            <div class="custom-form-column">
                <label>Country</label>
                <Select
                    filter
                    :options="listCountry"
                    v-model="formEnum.brother_relation_id"
                    placeholder="Select country"
                    :class="{ 'p-invalid': errors.brother_relation_id }"
                    optionLabel="name"
                    optionValue="id"
                    style="width: 100%"
                />
                <small v-if="errors.brother_relation_id" class="p-error">{{
                    errors.brother_relation_id
                }}</small>
            </div>
        </div>
        <hr />
        <div class="custom-form mt-4">
            <div class="custom-form-column">
                <FloatLabel>
                    <InputText
                        id="name"
                        class="inputtext-custom"
                        :class="{ 'p-invalid': errors.name }"
                        v-model="formEnum.name"
                        @input="clearError('name')"
                        style="width: 100%"
                        :disabled="!!selectedEnum"
                        :readonly="selectedEnum"
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
                        id="valor1"
                        class="inputtext-custom"
                        :class="{ 'p-invalid': errors.valor1 }"
                        v-model="formEnum.valor1"
                        @input="clearError('valor1')"
                        style="width: 100%"
                    />
                    <label for="valor1">Value to display</label>
                </FloatLabel>
                <small v-if="errors.valor1" class="p-error">{{
                    errors.valor1
                }}</small>
            </div>
        </div>
        <div class="custom-form mt-4" v-if="isNotSelected()">
            <div class="custom-form-column">
                <FloatLabel>
                    <Select
                        id="status"
                        filter
                        :options="listStatus"
                        v-model="formEnum.status"
                        :class="{ 'p-invalid': errors.status }"
                        optionLabel="name"
                        optionValue="id"
                        style="width: 100%"
                    />
                    <label for="status">Status</label>
                </FloatLabel>
                <small v-if="errors.status" class="p-error">{{
                    errors.status
                }}</small>
            </div>
        </div>
        <template #footer>
            <div class="text-center">
                <Button
                    v-if="!selectedEnum"
                    label="Save"
                    severity="success"
                    style="margin-right: 10px"
                    @click="saveEnum"
                />
                <Button
                    v-else
                    label="Update"
                    severity="success"
                    style="margin-right: 10px"
                    @click="updateEnum"
                />
                <Button
                    label="Close"
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
    props: [
        "dialogVisible",
        "selectedEnum",
        "listOptions",
        "selectedOption",
        "isOption",
    ],
    data() {
        return {
            visible: this.dialogVisible,
            formEnum: {
                parent_id: null,
                name: null,
                valor1: null,
                status: null,
                value1: null,
                brother_relation_id: null,
            },
            errors: {},
            options: this.listOptions,
            listStatus: [],
            isRelation: null,
            listState: [],
            listCountry: [],
        };
    },
    components: {},
    watch: {
        async isRelation(value) {
            if (value == "city") {
                const comboNames = ["state"];
                const response = await this.$getEnumsOptions(comboNames);
                const { state: responseState } = response.data;
                this.listState = responseState;
            }
            if (value == "state") {
                const comboNames = ["country"];
                const response = await this.$getEnumsOptions(comboNames);
                const { country: responseCountry } = response.data;
                this.listCountry = responseCountry;
            }
        },
    },
    mounted() {
        this.isRelation = null;
        this.formEnum.parent_id = this.selectedOption;
        if (this.selectedEnum) {
            this.formEnum.name = this.selectedEnum.name;
            this.formEnum.valor1 = this.selectedEnum.valor1;
            this.formEnum.brother_relation_id =
                this.selectedEnum.brother_relation_id;
            this.formEnum.status = parseInt(this.selectedEnum.status);
        }
        this.initEnumBrother(this.formEnum.parent_id);
    },
    created() {
        this.initServices();
    },
    methods: {
        isNotSelected() {
            if (
                this.isRelation == "city" ||
                this.isRelation == "state" ||
                this.isRelation == "country"
            ) {
                return false;
            }
            return true;
        },
        initEnumBrother(id) {
            this.$axios
                .get("/enums/get-id/" + id)
                .then((response) => {
                    const { data } = response.data;
                    this.isRelation = data.name;
                })
                .catch((error) => {
                    this.$readStatusHttp(error);
                });
        },
        async initServices() {
            this.listStatus = [
                { id: 1, name: "active" },
                { id: 2, name: "inactive" },
            ];
        },
        async validateForm() {
            let dinamicRules = {};
            let initialRules = {
                parent_id: Yup.string().required("Parent is required"),
                status: Yup.string().required("Status is required"),
                name: Yup.string().required("Name is required"),
                valor1: Yup.string().required("Descripcion is required"),
            };
            if (!this.isNotSelected()) {
                delete initialRules.status;
            }
            if (this.isRelation == "city") {
                dinamicRules.brother_relation_id =
                    Yup.string().required("State is required");
            }
            if (this.isRelation == "state") {
                dinamicRules.brother_relation_id = Yup.string().required(
                    "Country is required"
                );
            }
            const schema = Yup.object().shape({
                ...initialRules,
                ...dinamicRules,
            });
            this.errors = {};
            try {
                await schema.validate(this.formEnum, {
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
        async saveEnum() {
            const isValid = await this.validateForm();
            if (isValid) {
                this.$axios
                    .post("/enums/store", this.formEnum)
                    .then((response) => {
                        this.$alertSuccess("Record added");
                        this.$emit("reload", true);
                    })
                    .catch((error) => {
                        this.$readStatusHttp(error);
                    });
            }
        },
        async updateEnum() {
            const isValid = await this.validateForm();
            if (isValid) {
                this.$axios
                    .post(
                        "/enums/update/" + this.selectedEnum.id,
                        this.formEnum
                    )
                    .then((response) => {
                        this.$alertSuccess("Record updated");
                        this.$emit("reload", true);
                    })
                    .catch((error) => {
                        this.$readStatusHttp(error);
                    });
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

<style></style>
