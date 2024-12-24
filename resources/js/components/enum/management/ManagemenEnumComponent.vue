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
        <div class="custom-form mt-5">
            <div class="custom-form-column">
                <FloatLabel>
                    <InputText
                        id="name"
                        class="inputtext-custom"
                        :class="{ 'p-invalid': errors.name }"
                        v-model="formEnum.name"
                        @input="clearError('name')"
                        style="width: 100%;"
                    />
                    <label for="name">Name</label>
                </FloatLabel>
                <small v-if="errors.name" class="p-error">{{
                    errors.name
                }}</small>
            </div>
        </div>
        <div class="custom-form mt-2">
            <div class="custom-form-column">
                <Select
                    :options="listStatus"
                    v-model="formEnum.status"
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
import FloatLabel from "primevue/floatlabel";
import InputText from "primevue/inputtext";
import Dialog from "primevue/dialog";
import Select from "primevue/select";
import Button from "primevue/button";

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
                status: null,
                value1: null,
                brother_relation_id: null,
            },
            errors: {},
            options: this.listOptions,
            listState: [],
            listStatus: [],
        };
    },
    components: {
        FloatLabel,
        Dialog,
        Select,
        InputText,
        Button
    },
    watch: {

    },
    mounted() {
        this.formEnum.parent_id = this.selectedOption;
        if (this.selectedEnum) {
            this.formEnum.name = this.selectedEnum.name;
            this.formEnum.brother_relation_id = this.selectedEnum.brother_relation_id;
            this.formEnum.status = parseInt(
                    this.selectedEnum.status
                );
        }
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
        },
        async validateForm() {
            let dinamicRules = {};
            let initialRules = {
                parent_id: Yup.string().required("Parent is required"),
                name: Yup.string().required("Name is required"),
            };
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
                        this.$alertSuccess("Opcion Añadida");
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
                        this.$alertSuccess("Opcion Actualizado");
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
