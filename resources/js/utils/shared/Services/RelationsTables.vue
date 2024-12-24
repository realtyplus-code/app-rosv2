<script>
export default {
    methods: {
        $relationTableProperty(selector) {
            switch (selector) {
                case "name":
                    return "properties.name";
                case "address":
                    return "properties.address";
                case "property_type_name":
                    return "eo_property_type.name";
                case "status":
                    return "properties.status";
                default:
                    return selector;
            }
        },
        $relationTableUser(selector) {
            switch (selector) {
                case "name":
                    return "users.name";
                case "email":
                    return "users.email";
                case "phone":
                    return "users.phone";
                default:
                    return selector;
            }
        },
        $relationTableEnumOption(selector) {
            switch (selector) {
                default:
                    return selector;
            }
        },
    },
};
</script>
