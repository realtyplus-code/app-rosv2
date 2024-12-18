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
        $parseTags(tagasString) {
            if (!tagasString) return [];
            return tagasString.split(";").map((tagPair) => {
                const [id, tag] = tagPair.split(":");
                return { tag, id: parseInt(id) };
            });
        },
        $formatStatus(type) {
            switch (type) {
                case "1":
                    return "Active";
                case "2":
                    return "Inactive";
                default:
                    return "n/a";
                    break;
            }
        },
        $getImages(data) {
            let images = [];
            if (data.photo) images.push(data.photo);
            if (data.photo1) images.push(data.photo1);
            if (data.photo2) images.push(data.photo2);
            if (data.photo3) images.push(data.photo3);
            return images;
        },
    },
};
</script>
