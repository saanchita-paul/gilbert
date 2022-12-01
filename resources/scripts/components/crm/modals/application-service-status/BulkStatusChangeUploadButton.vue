<template>
    <div>
        <v-btn
            class="black--text"
            depressed
            outlined
            :loading="isSelecting"
            @click="onButtonClick"
        >
            <v-icon left>
                cloud_upload
            </v-icon>
            {{ buttonText }}
        </v-btn>
        <input
            ref="uploader"
            class="d-none"
            type="file"
            accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"
            @change="onFileChanged"
        >
    </div>
</template>

<script>
import ApplicationServiceStatusChangeService from "@scripts/services/crm/ApplicationServiceStatusChangeService";

export default {
    name: "BulkStatusChangeUploadButton",
    data: () => ({
        defaultButtonText: 'Bulk Status Change',
        selectedFile: null,
        isSelecting: false
    }),
    computed: {
        buttonText() {
            return this.selectedFile ? this.selectedFile.name : this.defaultButtonText
        }
    },
    methods: {
        onButtonClick() {
            this.isSelecting = true
            window.addEventListener('focus', () => {
                this.isSelecting = false
            }, {once: true})

            this.$refs.uploader.click()
        },
        async onFileChanged(e) {
            try {
                this.selectedFile = e.target.files[0]

                const formData = new FormData();
                formData.append('file', this.selectedFile);
                const data = await ApplicationServiceStatusChangeService.updateBulkStatus(formData);
                if (data.success) {
                    this.selectedFile = null
                    this.$emit('reloadLeads');
                }
            } catch (err) {
                console.log(err.response.data);
            }
        }
    }
}
</script>

<style scoped>
.v-icon--left {
    margin-right: 8px;
}
</style>
