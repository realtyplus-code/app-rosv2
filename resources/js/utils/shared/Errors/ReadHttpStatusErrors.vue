<script>
const readStatus = {};

// Funcion para obtener los datos del error de las respuesta http
readStatus.getDataError = (error = []) => {
    let response = {};
    try {
        response = [error][0].response;
    } catch (error) {
        response = {};
    }
    return response;
};

readStatus.validateForm = (data) => {
    const errors = data.errors;
    if (errors) {
        let messages = "";
        Object.values(errors).forEach((errorList) => {
            errorList.forEach((error) => {
                messages += error + "<br>";
            });
        });
        return messages;
    }
};

export default {
    methods: {
        $readStatusHttp(error = []) {
            const response = readStatus.getDataError(error);
            const $t = this.$t; // Alias para facilitar
            const httpErrors = "httpErrors";

            switch (response.status) {
                case 400:
                    this.$alertDanger(
                        $t(`${httpErrors}.400.title`),
                        $t(`${httpErrors}.400.message`)
                    );
                    break;
                case 401:
                    this.$alertDanger(
                        $t(`${httpErrors}.401.title`),
                        $t(`${httpErrors}.401.message`)
                    );
                    setTimeout(() => (window.location.href = "/"), 2000);
                    break;
                case 422:
                    this.$alertDanger(
                        $t(`${httpErrors}.422.title`),
                        readStatus.validateForm(response.data)
                    );
                    break;
                case 500:
                    this.$alertDanger(
                        $t(`${httpErrors}.500.title`),
                        $t(`${httpErrors}.500.message`)
                    );
                    break;
                default:
                    this.$alertDanger(
                        $t(`${httpErrors}.default.title`),
                        $t(`${httpErrors}.default.message`)
                    );
            }
        },
        textCodeMessage(code) {
            return this.$t(`textCodeMessages.${code}`) || code;
        },
    },
};
</script>
