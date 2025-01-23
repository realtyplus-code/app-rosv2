<template>
    <Dialog
        v-model:visible="visible"
        modal
        class="dialog-management"
        :style="{ width: '40rem' }"
        :draggable="false"
    >
        <template #header>
            <h3>Upload PDF Files</h3>
        </template>
        <div class="custom-form">
            <div
                v-for="index in limitDouments"
                :key="index"
                class="custom-form-column custom-pdf-column"
            >
                 <div v-if="selectedRegister && selectedRegister[`document${index === 1 ? '' : index}`]">
                    <h4>PDF {{ index }}</h4>
                    <Button
                        icon="pi pi-eye"
                        @click="viewPdf(selectedRegister[`document${index === 1 ? '' : index}`])"
                        style="margin: 20px"
                    />
                    <Button
                        icon="pi pi-trash"
                        severity="danger"
                        @click="confirmDelete(`document${index === 1 ? '' : index}`)"
                    />
                </div>
                <div v-else>
                    <FileUpload
                        :id="`uploadPdf${index === 1 ? '' : index}`"
                        :ref="`fileUpload${index === 1 ? '' : index}`"
                        accept="application/pdf"
                        :class="{ 'p-invalid': errors[`document${index === 1 ? '' : index}`] }"
                        @change="onFileUpload(index === 1 ? '' : index)"
                        @remove="onFileRemove(index === 1 ? '' : index)"
                    >
                        <template #empty>
                            <p>Select PDF file {{ index }}</p>
                        </template>
                    </FileUpload>
                    <small v-if="errors[`document${index === 1 ? '' : index}`]" class="p-error">{{
                        errors[`document${index === 1 ? '' : index}`]
                    }}</small>
                </div>
            </div>
        </div>
        <template #footer>
            <div class="text-center">
                <Button
                    label="Upload"
                    severity="success"
                    style="margin-right: 10px"
                    @click="uploadFiles"
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
    props: ["dialogVisible", "selectedRegister", "limit"],
    data() {
        return {
            visible: this.dialogVisible,
            files: {},
            errors: {},
            limitDouments: this.limit ?? 0,
        };
    },
    methods: {
        onFileUpload(fileNumber) {
            const fileUpload = this.$refs[`fileUpload${fileNumber}`][0];
            if (fileUpload && fileUpload.files.length > 0) {
                const file = fileUpload.files[0];
                if (file.type === "application/pdf") {
                    this.files[`document${fileNumber}`] = file;
                    this.clearError(`document${fileNumber}`);
                } else {
                    this.errors[`document${fileNumber}`] =
                        "Only PDF files are allowed.";
                }
            }
        },
        onFileRemove(fileNumber) {
            this.files[`document${fileNumber}`] = null;
            const fileUpload = this.$refs[`fileUpload${fileNumber}`];
            if (fileUpload) {
                fileUpload.value = "";
            }
        },
        async validateForm() {
            const schema = Yup.object().shape({
                // ...existing code...
            });
            this.errors = {};
            try {
                await schema.validate(this.files, { abortEarly: false });
                return true;
            } catch (err) {
                err.inner.forEach((error) => {
                    this.errors[error.path] = error.message;
                });
                return false;
            }
        },
        async uploadFiles() {
            const isValid = await this.validateForm();
            if (isValid) {
                const pdfFiles = [];
                for (let i = 1; i <= this.limitDouments; i++) {
                    pdfFiles.push(this.files[`document${i === 1 ? '' : i}`] || null);
                }
                this.$emit("uploadFiles", pdfFiles);
                setTimeout(() => {
                    this.handleDialogClose();
                }, 1000);
            }
        },
        handleDialogClose() {
            this.visible = false;
            this.$emit("hidden", this.visible);
        },
        clearError(field) {
            this.errors[field] = "";
        },
        viewPdf(pdfUrl) {
            window.open(pdfUrl, "_blank");
        },
        async confirmDelete(pdfField) {
            const result = await this.$swal.fire({
                title: "You're sure?",
                text: "You are about to delete this PDF. Are you sure you want to continue?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete",
                cancelButtonText: "Cancelar",
            });

            if (result.isConfirmed) {
                this.$emit("deletePdf", pdfField);
                this.handleDialogClose();
            }
        },
    },
};
</script>

<style>
.custom-pdf-column {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-direction: column;
}
</style>
