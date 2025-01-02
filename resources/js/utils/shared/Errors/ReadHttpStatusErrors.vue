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
            console.log(response);
            switch (response.status) {
                case 400:
                    this.$alertDanger("Bad Request", this.textCodeMessage(response.data.message));
                    break;
                case 401:
                    this.$alertDanger("Your session has expired", "");
                    setTimeout(() => (window.location.href = "/"), 2000);
                    break;
                case 402:
                    this.$alertDanger("Bad Request");
                    break;
                case 403:
                    this.$alertDanger(
                        "Process Denied",
                        "You do not have permission to perform this process"
                    );
                    break;
                case 404:
                    this.$alertDanger("Bad Request");
                    break;
                case 406:
                    this.$alertDanger("Bad Request");
                    break;
                case 409:
                    this.$alertDanger(
                        "Unauthorized Process",
                        "The process was not authorized, try another type of process"
                    );
                    break;
                case 419:
                    this.$alertDanger("Your session has expired", "");
                    setTimeout(() => (window.location.href = "/"), 2000);
                    break;
                case 422:
                    this.$alertDanger(
                        "Form Error",
                        readStatus.validateForm(response.data)
                    );
                    break;
                case 423:
                    this.$alertDanger("Limit Exceeded");
                    break;
                case 500:
                    this.$alertDanger(
                        "Bad Request",
                        "An error occurred while performing the process"
                    );
                    break;
                default:
                    this.$alertDanger(
                        "Bad Request",
                        "An unexpected error occurred"
                    );
            }
        },
        textCodeMessage(code) {
            switch (code) {
                case "FALSE EMAIL":
                    return "The system did not recognize the email host domain";
                    break;
                default:
                    return code;
                    break;
            }
        },
    },
};
</script>
