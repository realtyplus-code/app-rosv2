<script>
export default {
    methods: {
        $getEnumsOptions(type) {
            const vm = this;
            return new Promise((resolve, reject) => {
                this.$axios
                    .get(`/enums/option/${type.join(",")}`)
                    .then(function (response) {
                        resolve(response.data);
                    })
                    .catch((error) => {
                        vm.$readStatusHttp(error);
                        reject(error);
                    });
            });
        },
        $getBrother(id) {
            const vm = this;
            return new Promise((resolve, reject) => {
                this.$axios
                    .get(`/enums/get-brother/${id}`)
                    .then(function (response) {
                        resolve(response.data);
                    })
                    .catch((error) => {
                        vm.$readStatusHttp(error);
                        reject(error);
                    });
            });
        },
        $parseTags(tagasString) {
            if (!tagasString) return [];
            return tagasString.split(";").map((tagPair) => {
                const [id, tag] = tagPair.split(":");
                return { tag, id: parseInt(id) };
            });
        },
        $parsePreview(cant) {
            if (cant > 0) {
                return "pi pi-eye";
            } else {
                return "pi pi-eye-slash";
            }
        },
        $formatStatus(type) {
            switch (type) {
                case 1:
                case "1":
                    return "Active";
                case 2:
                case "2":
                    return "Inactive";
                default:
                    return "n/a";
                    break;
            }
        },
        $getImages(data) {
            let images = [];
            if (Array.isArray(data.photos)) {
                data.photos.forEach(item => {
                    images.push(item.file_path);
                });
            }
            return images;
        },
        $exportToExcel(exportPath, filterData, name) {
            const vm = this;
            return new Promise((resolve, reject) => {
                vm.$axios
                    .get(exportPath, {
                        params: filterData,
                        responseType: "blob",
                    })
                    .then((response) => {
                        const url = window.URL.createObjectURL(
                            new Blob([response.data])
                        );
                        const link = document.createElement("a");
                        link.href = url;
                        const currentDate = new Date()
                            .toISOString()
                            .slice(0, 10);
                        link.setAttribute(
                            "download",
                            `${name}_${currentDate}_descarga.xlsx`
                        );
                        document.body.appendChild(link);
                        link.click();
                        resolve();
                    })
                    .catch((error) => {
                        vm.$readStatusHttp(error);
                        reject(error);
                    });
            });
        },
        $exportToPDF(exportPath, filterData, name) {
            const vm = this;
            return new Promise((resolve, reject) => {
                vm.$axios
                    .get(exportPath, {
                        params: filterData,
                        responseType: "blob",
                    })
                    .then((response) => {
                        const url = window.URL.createObjectURL(
                            new Blob([response.data], {
                                type: "application/pdf",
                            })
                        );
                        const link = document.createElement("a");
                        link.href = url;
                        const currentDate = new Date()
                            .toISOString()
                            .slice(0, 10);
                        link.setAttribute(
                            "download",
                            `${name}_${currentDate}_descarga.pdf`
                        );
                        document.body.appendChild(link);
                        link.click();
                        resolve();
                    })
                    .catch((error) => {
                        vm.$readStatusHttp(error);
                        reject(error);
                    });
            });
        },
        $formatCurrency(value, currency) {
            if (!value) return;
            return new Intl.NumberFormat("en-US", {
                style: "currency",
                currency: currency,
            }).format(value);
        },
    },
};
</script>
