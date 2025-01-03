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
            <div class="custom-form-column custom-pdf-column">
                <div v-if="selectedRegister && selectedRegister.pdf">
                    <h4>PDF 1</h4>
                    <Button
                        icon="pi pi-eye"
                        @click="viewPdf(selectedRegister.pdf)"
                        style="margin: 20px"
                    />
                    <Button
                        icon="pi pi-trash"
                        severity="danger"
                        @click="confirmDelete('pdf')"
                    />
                </div>
                <div v-else>
                    <FileUpload
                        id="uploadPdf1"
                        ref="fileUpload1"
                        accept="application/pdf"
                        :class="{ 'p-invalid': errors.pdf1 }"
                        @change="onFileUpload(1)"
                    >
                        <template #empty>
                            <p>Select PDF file 1</p>
                        </template>
                    </FileUpload>
                    <small v-if="errors.pdf1" class="p-error">{{
                        errors.pdf1
                    }}</small>
                </div>
            </div>
            <div class="custom-form-column custom-pdf-column">
                <div v-if="selectedRegister && selectedRegister.pdf1">
                    <h4>PDF 2</h4>
                    <Button
                        icon="pi pi-eye"
                        @click="viewPdf(selectedRegister.pdf1)"
                        style="margin: 20px"
                    />
                    <Button
                        icon="pi pi-trash"
                        severity="danger"
                        @click="confirmDelete('pdf1')"
                    />
                </div>
                <div v-else>
                    <FileUpload
                        id="uploadPdf2"
                        ref="fileUpload2"
                        accept="application/pdf"
                        :class="{ 'p-invalid': errors.pdf2 }"
                        @change="onFileUpload(2)"
                    >
                        <template #empty>
                            <p>Select PDF file 2</p>
                        </template>
                    </FileUpload>
                    <small v-if="errors.pdf2" class="p-error">{{
                        errors.pdf2
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
    props: ["dialogVisible", "selectedRegister"],
    data() {
        return {
            visible: this.dialogVisible,
            files: {
                pdf1: null,
                pdf2: null,
            },
            errors: {},
        };
    },
    methods: {
        onFileUpload(fileNumber) {
            const fileUpload = this.$refs[`fileUpload${fileNumber}`];
            if (fileUpload && fileUpload.files.length > 0) {
                const file = fileUpload.files[0];
                if (file.type === "application/pdf") {
                    this.files[`pdf${fileNumber}`] = file;
                    this.clearError(`pdf${fileNumber}`);
                } else {
                    this.errors[`pdf${fileNumber}`] =
                        "Only PDF files are allowed.";
                }
            }
        },
        async validateForm() {
            const schema = Yup.object().shape({
                /* pdf1: Yup.mixed().required("PDF file 1 is required"),
                pdf2: Yup.mixed().required("PDF file 2 is required"), */
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
                const pdfFiles = [this.files.pdf1 || null, this.files.pdf2 || null];
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
